<?php
namespace ZorgFinder\Reviews\API;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use ZorgFinder\Reviews\Services\ReviewService;

class ReviewsController
{
    protected string $namespace = 'zorg/v1';

    public function register_routes(): void
    {
        register_rest_route($this->namespace, '/reviews', [
            [
                'methods'  => 'GET',
                'callback' => [$this, 'get_reviews'],
                'permission_callback' => '__return_true',
            ],
            [
                'methods'  => 'POST',
                'callback' => [$this, 'create_review'],
                'permission_callback' => [$this, 'require_auth'],
            ],
        ]);

        register_rest_route($this->namespace, '/reviews/(?P<id>\d+)', [
            [
                'methods'  => 'GET',
                'callback' => [$this, 'get_review_by_id'],
                'permission_callback' => '__return_true',
            ],
            [
                'methods'  => 'PATCH',
                'callback' => [$this, 'update_review'],
                'permission_callback' => [$this, 'require_admin'],
            ],
            [
                'methods'  => 'DELETE',
                'callback' => [$this, 'delete_review'],
                'permission_callback' => [$this, 'require_admin'],
            ],
        ]);

        register_rest_route($this->namespace, '/reviews/(?P<id>\d+)/restore', [
                'methods' => 'PATCH',
                'callback' => [$this, 'restore_review'],
                'permission_callback' => [$this, 'require_admin'],
        ]);

    }

    /**
     * Get reviews by provider_id or all (admin view)
     */
    public function get_reviews(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $provider_id = (int) $request->get_param('provider_id');
        $approved = $request->get_param('approved');

        $service = new ReviewService();

        if ($provider_id) {
            // Public-facing: only show approved reviews
            $approved = $approved === null ? 1 : (int)$approved;
            $reviews = $service->get_reviews_for_provider($provider_id, $approved);
            $avg = $service->get_average_rating($provider_id);
        } else {
            // Admin: get all reviews
            if (! current_user_can('manage_options')) {
                return new WP_Error('rest_forbidden', 'You are not allowed to view all reviews.', ['status' => 403]);
            }
            $reviews = $service->get_all_reviews();
            $avg = null;
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'average' => $avg ? round((float)$avg, 2) : null,
                'count'   => count($reviews),
                'reviews' => $reviews,
            ]
        ], 200);
    }

    /**
     * Get a single review by ID
     */
    public function get_review_by_id(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $id = (int) $request['id'];
        $service = new ReviewService();

        $review = $service->get_review($id);
        if (! $review) {
            return new WP_Error('not_found', 'Review not found.', ['status' => 404]);
        }

        // Public can only view approved reviews
        if ($review['approved'] == 0 && ! current_user_can('manage_options')) {
            return new WP_Error('rest_forbidden', 'You cannot view unapproved reviews.', ['status' => 403]);
        }

        return new WP_REST_Response(['success' => true, 'data' => $review], 200);
    }

    /**
     * Create a new review
     */
    public function create_review(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        if (! is_user_logged_in()) {
            return new WP_Error('rest_forbidden', 'You must be logged in to submit reviews', ['status' => 401]);
        }

        $nonce = $request->get_header('x-wp-nonce') ?: $request->get_param('_wpnonce');
        if (! $nonce || ! wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('invalid_nonce', 'Invalid or missing nonce', ['status' => 403]);
        }

        $user_id = get_current_user_id();
        $provider_id = (int) $request->get_param('provider_id');
        $rating = (int) $request->get_param('rating');
        $comment = sanitize_textarea_field($request->get_param('comment'));

        if (! $provider_id || $rating < 1 || $rating > 5) {
            return new WP_Error('invalid_input', 'provider_id and rating (1-5) are required', ['status' => 400]);
        }

        $data = [
            'provider_id' => $provider_id,
            'user_id' => $user_id,
            'rating' => $rating,
            'comment' => $comment,
            'approved' => 0,
            'created_at' => current_time('mysql'),
        ];

        $service = new ReviewService();
        $insert_id = $service->add_review($data);

        if (! $insert_id) {
            return new WP_Error('insert_failed', 'Could not save review', ['status' => 500]);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'message' => 'Review submitted; pending approval',
                'review_id' => (int)$insert_id
            ]
        ], 201);
    }

    /**
     * Update review (admin only)
     */
    public function update_review(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $id = (int) $request['id'];
        $service = new ReviewService();

        $review = $service->get_review($id);
        if (! $review) {
            return new WP_Error('not_found', 'Review not found', ['status' => 404]);
        }

        $fields = [];
        $rating = $request->get_param('rating');
        $comment = $request->get_param('comment');
        $approved = $request->get_param('approved');

        if ($rating !== null) {
            $rating = (int)$rating;
            if ($rating < 1 || $rating > 5) {
                return new WP_Error('invalid_rating', 'Rating must be between 1 and 5', ['status' => 400]);
            }
            $fields['rating'] = $rating;
        }

        if ($comment !== null) {
            $fields['comment'] = sanitize_textarea_field($comment);
        }

        if ($approved !== null) {
            $fields['approved'] = (int)$approved;
        }

        if (empty($fields)) {
            return new WP_Error('no_changes', 'No valid fields provided', ['status' => 400]);
        }

        $updated = $service->update_review($id, $fields);

        if (! $updated) {
            return new WP_Error('update_failed', 'Failed to update review', ['status' => 500]);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'message' => 'Review updated successfully',
                'review_id' => $id,
                'fields' => $fields,
            ]
        ], 200);
    }

    /**
     * Delete review (admin only)
     */
    public function delete_review(WP_REST_Request $request): WP_REST_Response|WP_Error
    {
        $id = (int) $request['id'];
        $service = new ReviewService();

        $deleted = $service->delete_review($id);
        if (! $deleted) {
            return new WP_Error('delete_failed', 'Failed to delete review', ['status' => 500]);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => [
                'message' => "Review #$id deleted successfully",
            ]
        ], 200);
    }

    public function restore_review(WP_REST_Request $request)
    {
        $id = (int)$request['id'];
        $service = new ReviewService();

        $restored = $service->restore_review($id);
        if (! $restored) {
            return new WP_Error('restore_failed', 'Failed to restore review', ['status' => 500]);
        }

        return new WP_REST_Response([
            'success' => true,
            'data' => ['message' => "Review #$id restored successfully"]
        ], 200);
    }

    /**
     * Auth helper for public POST routes
     */
    public function require_auth(): bool|WP_Error
    {
        if (! is_user_logged_in()) {
            return new WP_Error('rest_forbidden', 'You must be logged in.', ['status' => 401]);
        }
        return true;
    }

    /**
     * Auth helper for admin routes
     */
    public function require_admin(): bool|WP_Error
    {
        if (! current_user_can('manage_options')) {
            return new WP_Error('rest_forbidden', 'Only administrators can perform this action.', ['status' => 403]);
        }
        return true;
    }
}

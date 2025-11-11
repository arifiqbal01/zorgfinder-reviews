import { registerBlockType } from '@wordpress/blocks';
import { useState, useEffect } from '@wordpress/element';
import { Spinner, TextareaControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './style.scss';

registerBlockType('zorgfinder/reviews', {
	title: __('ZorgFinder Reviews', 'zorgfinder'),
	edit: () => {
		const [reviews, setReviews] = useState([]);
		const [comment, setComment] = useState('');
		const [rating, setRating] = useState(5);
		const [loading, setLoading] = useState(false);

		useEffect(() => {
			fetch(`${wpApiSettings.root}zorg/v1/reviews?provider_id=1`)
				.then((res) => res.json())
				.then((data) => setReviews(data.data?.reviews || []));
		}, []);

		const submitReview = async () => {
			setLoading(true);
			await fetch(`${wpApiSettings.root}zorg/v1/reviews`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': wpApiSettings.nonce,
				},
				body: JSON.stringify({ provider_id: 1, rating, comment }),
			});
			setLoading(false);
			setComment('');
			alert(__('Review submitted (pending approval)', 'zorgfinder'));
		};

		return (
			<div className="zorgfinder-reviews">
				<h3>{__('Reviews', 'zorgfinder')}</h3>
				{reviews.length ? (
					reviews.map((r) => (
						<p key={r.id}>
							<strong>⭐ {r.rating}</strong> – {r.comment}
						</p>
					))
				) : (
					<p>{__('No reviews yet.', 'zorgfinder')}</p>
				)}

				<h4>{__('Leave a review', 'zorgfinder')}</h4>
				<TextareaControl
					label={__('Your Comment', 'zorgfinder')}
					value={comment}
					onChange={(v) => setComment(v)}
				/>
				<Button isPrimary onClick={submitReview} disabled={loading}>
					{loading ? __('Submitting...', 'zorgfinder') : __('Submit Review', 'zorgfinder')}
				</Button>
			</div>
		);
	},
	save: () => null, // handled via REST API
});

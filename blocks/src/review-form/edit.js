import { useState } from '@wordpress/element';
import { TextControl, TextareaControl, Button, Notice } from '@wordpress/components';

const Edit = () => {
	const [rating, setRating] = useState(5);
	const [comment, setComment] = useState('');
	const [message, setMessage] = useState('');
	const [error, setError] = useState('');

	const submitReview = async () => {
		try {
			const res = await fetch(`${wpApiSettings.root}zorg/v1/reviews`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce': wpApiSettings.nonce
				},
				body: JSON.stringify({
					provider_id: 1,
					rating,
					comment
				})
			});
			const data = await res.json();
			if (data.success) {
				setMessage('Review submitted! Pending admin approval.');
				setComment('');
			} else {
				setError(data.message || 'Failed to submit review.');
			}
		} catch (err) {
			setError('Network error.');
		}
	};

	return (
		<div className="zorgfinder-review-form">
			{message && <Notice status="success">{message}</Notice>}
			{error && <Notice status="error">{error}</Notice>}

			<TextControl
				label="Rating (1–5)"
				value={rating}
				onChange={(v) => setRating(parseInt(v))}
				type="number"
				min={1}
				max={5}
			/>
			<TextareaControl
				label="Your Review"
				value={comment}
				onChange={setComment}
			/>
			<Button isPrimary onClick={submitReview}>Submit Review</Button>
		</div>
	);
};

export default Edit;

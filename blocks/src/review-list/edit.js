import { useState, useEffect } from '@wordpress/element';
import { Spinner, Notice } from '@wordpress/components';

const Edit = () => {
	const [reviews, setReviews] = useState([]);
	const [loading, setLoading] = useState(true);
	const [error, setError] = useState('');

	useEffect(() => {
		fetch(`${wpApiSettings.root}zorg/v1/reviews?provider_id=1`)
			.then(res => res.json())
			.then(data => {
				setReviews(data.data?.reviews || []);
				setLoading(false);
			})
			.catch(() => {
				setError('Failed to load reviews');
				setLoading(false);
			});
	}, []);

	if (loading) return <Spinner />;
	if (error) return <Notice status="error">{error}</Notice>;
	if (!reviews.length) return <p>No reviews yet.</p>;

	return (
		<div className="zorgfinder-review-list">
			{reviews.map(r => (
				<div key={r.id} className="review-item">
					<strong>⭐ {r.rating}/5</strong>
					<p>{r.comment}</p>
				</div>
			))}
		</div>
	);
};

export default Edit;

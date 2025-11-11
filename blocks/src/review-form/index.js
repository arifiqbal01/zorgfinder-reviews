import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

registerBlockType('zorgfinder/review-form', {
	title: 'Review Form',
	description: 'Allows users to submit a new review.',
	icon: 'feedback',
	category: 'widgets',
	edit: Edit,
	save: () => null, // dynamic REST API submission
});

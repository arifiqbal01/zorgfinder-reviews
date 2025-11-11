import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import './style.scss';

registerBlockType('zorgfinder/review-list', {
	title: 'Review List',
	description: 'Displays reviews for a provider.',
	icon: 'list-view',
	category: 'widgets',
	edit: Edit,
	save: () => null, // dynamic via PHP
});

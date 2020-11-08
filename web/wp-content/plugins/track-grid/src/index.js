import { registerBlockType, createBlock } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import './style.scss';
import Edit from './edit';
import save from './save';

// import helper functions.
import { range } from './utils.js';
import { parse } from 'node-html-parser';

export const schema = {
	columns: {
		type: 'number',
		default: 3,
	},
	height: {
		type: 'number',
		default: 250,
	},
	tracks: {
		type: 'number',
		default: 12,
	},
	color1: {
		type: 'string',
		default: 'rgb(20, 143, 133)',
	},
	color2: {
		type: 'string',
		default: 'rgb(3, 58, 103)',
	},
	textColor: {
		type: 'string',
		default: '#FFFFFF',
	},
	ctaIcon: {
		type: 'string',
	},
	className: {
		type: 'string',
	},
};

// now loop over the following to make the full schema.
{
	range( 1, 30 ).forEach( ( i ) => {
		schema[ `title${ i }` ] = {
			source: 'html',
			selector: `.track-wrapper > *:nth-child(${ i }) h4`,
			default: '',
		};
		schema[ `link${ i }` ] = {
			type: 'string',
			source: 'attribute',
			selector: `.track-wrapper > *:nth-child(${ i }) a`,
			attribute: 'href',
			default: '',
		};
	} );
}

registerBlockType( 'lf/track-grid', {
	title: __( 'Track Grid' ),
	description: __(
		'A linkable grid of tracks for an event. Currently set to max 30 tracks.'
	),
	category: 'common',
	icon: 'grid-view',
	keywords: [ __( 'track' ), __( 'grid' ), __( 'channels' ), __( 'event' ) ],
	supports: {
		align: [ 'wide', 'full' ],
	},
	attributes: schema,
	styles: [
		{
			name: 'default',
			label: __( 'Custom Colours' ),
			isDefault: true,
		},
		{
			name: 'event-gradient-color',
			label: __( 'Event Gradient' ),
		},
	],
	transforms: {
		from: [
			{
				type: 'block',
				blocks: [ 'core/list' ],
				transform: function ( attributes ) {
					// parse the list block.
					let liChildren = parse( attributes.values );
					// setup empty object.
					let transformSchema = {};
					// count the tracks.
					let tracks = liChildren.childNodes.length;
					// loop over each and setup an object.
					liChildren.childNodes.forEach( ( item, i ) => {
						let text = item.firstChild.childNodes[ 0 ].text;
						let link = item.firstChild.attributes.href;

						transformSchema[ `title${ i + 1 }` ] = text;
						transformSchema[ `link${ i + 1 }` ] = link;
					} );
					// sigh, there must be a better way to dump this in but I couldn't find it.
					return createBlock( 'lf/track-grid', {
						tracks,
						title1: transformSchema.title1,
						title2: transformSchema.title2,
						title3: transformSchema.title3,
						title4: transformSchema.title4,
						title5: transformSchema.title5,
						title6: transformSchema.title6,
						title7: transformSchema.title7,
						title8: transformSchema.title8,
						title9: transformSchema.title9,
						title10: transformSchema.title10,
						title11: transformSchema.title11,
						title12: transformSchema.title12,
						title13: transformSchema.title13,
						title14: transformSchema.title14,
						title15: transformSchema.title15,
						title16: transformSchema.title16,
						title17: transformSchema.title17,
						title18: transformSchema.title18,
						title19: transformSchema.title19,
						title20: transformSchema.title20,
						title21: transformSchema.title21,
						title22: transformSchema.title22,
						title23: transformSchema.title23,
						title24: transformSchema.title24,
						title25: transformSchema.title25,
						title26: transformSchema.title26,
						title27: transformSchema.title27,
						title28: transformSchema.title28,
						title29: transformSchema.title29,
						title30: transformSchema.title30,
						link1: transformSchema.link1,
						link2: transformSchema.link2,
						link3: transformSchema.link3,
						link4: transformSchema.link4,
						link5: transformSchema.link5,
						link6: transformSchema.link6,
						link7: transformSchema.link7,
						link8: transformSchema.link8,
						link9: transformSchema.link9,
						link10: transformSchema.link10,
						link11: transformSchema.link11,
						link12: transformSchema.link12,
						link13: transformSchema.link13,
						link14: transformSchema.link14,
						link15: transformSchema.link15,
						link16: transformSchema.link16,
						link17: transformSchema.link17,
						link18: transformSchema.link18,
						link19: transformSchema.link19,
						link20: transformSchema.link20,
						link21: transformSchema.link21,
						link22: transformSchema.link22,
						link23: transformSchema.link23,
						link24: transformSchema.link24,
						link25: transformSchema.link25,
						link26: transformSchema.link26,
						link27: transformSchema.link27,
						link28: transformSchema.link28,
						link29: transformSchema.link29,
						link30: transformSchema.link30,
					} );
				},
			},
		],
	},
	edit: Edit,
	save,
} );
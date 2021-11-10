( function (blocks, editor, components, i18n, element ) {

	var el = wp.element.createElement
	var registerBlockType = wp.blocks.registerBlockType
	var BlockControls = wp.editor.BlockControls
	var AlignmentToolbar = wp.editor.AlignmentToolbar
	var MediaUpload = wp.editor.MediaUpload
	var InspectorControls = wp.blockEditor.InspectorControls
	var TextControl = components.TextControl
	var ServerSideRender = wp.serverSideRender
	var withState = wp.compose.withState
    //this svg was "borrowed" from an unknown source, use at own risk, will credit/link/remove when I re-find it
	var twitch_icon = 
		el( 'svg' , {
				'width': '20',
				'height': '20',
				'viewBox': '0 0 18 18',
			},
			el( 'g' , {},
				el( 'path', {
					'fill': '#6441a5',
					'd': 'M3.857 0 1 2.857v10.286h3.429V16l2.857-2.857H9.57L14.714 8V0H3.857zm9.714 7.429-2.285 2.285H9l-2 2v-2H4.429V1.143h9.142v6.286z'	
				}),
				el( 'path', {
					'fill': '#6441a5',
					'd': 'M11.857 3.143h-1.143V6.57h1.143V3.143zm-3.143 0H7.571V6.57h1.143V3.143z'	
				})  
			)
		);
	registerBlockType( 'embed-block-for-twitch/video', {
		title: i18n.__( 'Twitch Stream' ),
		description: i18n.__( 'A block to embed your Twitch Stream.' ),
		icon: twitch_icon,
		keywords: [ i18n.__( 'twitch' ), i18n.__( 'video' ), i18n.__( 'embed' ) ],
		category: 'embed',
		attributes: {
			twitch_channel: {
	    		type: 'string',
			},
		},
		edit: function ( props ) {
			var attributes = props.attributes
			var twitch_channel = props.attributes.twitch_channel

			return [
				el( 'div', { className: 'components-block-description' },
					el( ServerSideRender, {
						block: 'embed-block-for-twitch/video',
						attributes: props.attributes
					} )
				),
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						components.PanelBody, {
							title: i18n.__( 'Twitch Stream' ),
							className: 'block-twitch-video',
							initialOpen: true
						},
						el(
							TextControl, {
								type: 'text',
								label: i18n.__( 'Enter the Channel Name' ),
								value: twitch_channel,
								onChange: function ( new_channel ) {
									props.setAttributes( { twitch_channel: new_channel } )
								}
							}
						)
					)
				)		
			]
		},
		save: () => {
			return null
		}
	})
})(
	window.wp.blocks,
	window.wp.editor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element
)
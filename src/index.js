const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { 

	RichText,
	InspectorControls,
	MediaUpload,
	ColorPalette,
	AlignmentToolbar,
    BlockControls

 } = wp.editor;

 const { PanelBody, 
 	    IconButton, 
 	    RangeControl,
 	    Dashicon
 } = wp.components;

registerBlockType('blockevent/custom-cta', {

	title: 'call to Action',
	description: 'block to generate a custom call to Action',
	icon: {
		   background: 'rgba(102, 102, 255)',
		  src:'format-image'
		},
	category: 'layout',

	attributes: {

		title: {
			type: 'string',
			source: 'html',
			selector: 'h2'
		},

		textAlinment: {
			type: 'string'
		},

		titleColor: {
			type: 'string',
			default: 'black'
		},

		backgroundImage: {
                type: 'string',
                default: null

		},

		body: {
			type: 'string',
			source: 'html',
			selector: 'p'

		},

		overlayColor: {
			type: 'string',
			default: 'black'
		},
		overlayOpacity: {
			type: 'number',
			default: 1
		}
	},

	edit({ attributes, setAttributes }){

		const {
			title,
			body,
			titleColor,
			backgroundImage,
			overlayColor,
			overlayOpacity,
			textAlignment,
			blockAlignment
		} = attributes;
		
			function onChangeTitle(newTitle){
					
					setAttributes({ title: newTitle } );

			}

			function onChangeBody(newBody){
					
					setAttributes({ body: newBody } );

			}

			function onTitleColorChange(newColor) {

				setAttributes( { titleColor: newColor } );
			}

			function onSelectImage(newImage){

				setAttributes( { backgroundImage: newImage.sizes.full.url });
			}

			function overlayColorChange(newColor){
				setAttributes({ overlayColor: newColor } );
			}
			function overlayOpacityChange(newColor){
				setAttributes( { overlayOpacity: newColor } );
			}


			return ([
				<InspectorControls style={{ marginBottom:'40px' } }>

				<PanelBody title={ 'Font Color Setting' } >
					<p><strong>Select a Tittle Color: </strong></p>
					<ColorPalette value={ titleColor}
								  onChange={ onTitleColorChange } />
				</PanelBody>
				<PanelBody title={ 'background Image Settings' }>
					<p><strong>Select a background Image:</strong></p>

					<MediaUpload
					    	onSelect={ onSelectImage }
					    	type="image"
					    	value={ 'background Image' }
					    	render={ ( { open } )  => (
					    		<IconButton
					    			className= "editor-media-placeholder__button is-button is-default is-large"
					    			icon="upload"
					    			onClick={ open } >
					    			Background Image
					    		</IconButton>
					    )	}
					    	/>

				<div style={{ marginTop: '20px', marginBottom:'40px'}}>
					<p><strong>Overlay Color: </strong></p>
					<ColorPalette value={ overlayColor}
								  onChange={overlayColorChange }
					/>
					</div>

					<RangeControl 
							label={'Overlay Opacity'}
							value={overlayOpacity}
							onChange={overlayOpacityChange}
							min={ 0 }
							max={ 1 }
							step={ 0.05 } />
				</PanelBody>
				</InspectorControls>,
				<div class="cta-container" style={{ 

					backgroundImage: `url(${backgroundImage})`,
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					backgroundRepeat: 'no-repeat',
					opacity: overlayOpacity
				}}>
				<BlockControls>
					
					<AlignmentToolbar
						value={textAlignment}
						onChange={ textAlignment => setAttributes( { textAlignment } )}
					/>
                    
					</BlockControls>

					<RichText key="editable"
							  tagName="h2"
							  placeholder="You CTA Title"
							  value={ title }

							  onChange={ onChangeTitle }
							  style={ { color: titleColor, textAlign: textAlignment}} />
				<RichText key="editable"
							  tagName="p"
							  placeholder="You CTA Description"
							  value={ body }
							  onChange={ onChangeBody } />
				</div>
			]);

	},

	save({ attributes }){
		const {
			title,
			body,
			titleColor,
			backgroundImage,
			overlayColor,
			overlayOpacity,
			textAlignment,
			highContrast, 
			alignment
				
		} = attributes;
		
		return (
				<div class="cta-container" style={{ 

					backgroundImage: `url(${backgroundImage})`,
					backgroundSize: 'cover',
					backgroundPosition: 'center',
					backgroundRepeat: 'no-repeat',
					opacity: overlayOpacity
				}} >
					<h2 style={{ textAlign: textAlignment}}>{ title }</h2>

					<RichText.Content tagName="p"
										value={ body} />
					</div>
					);
				}
});
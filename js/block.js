( function( blocks, editor, i18n, element, components, data ) {

	var el = element.createElement;
	var __ = i18n.__;

  var source = blocks.source;

  var withSelect = data.withSelect;

  var InspectorControls = editor.InspectorControls;
  var PanelBody = components.PanelBody;
  var PanelRow = components.PanelRow;
  var TextControl = components.TextControl;
  var TextareaControl = components.TextareaControl;
  var SelectControl = components.SelectControl;
  var ColorPicker   = components.ColorPicker;
  var FontSizePicker = components.FontSizePicker;

	blocks.registerBlockType( 'ask-expert-block/ask-expert-block', {

    title: __( 'Ask The Expert', 'ask-expert-block' ),
    description: __( 'Encourage conversation in your blog comments with the easy-to-use block.', 'ask-expert-block'),
    
    icon: 'admin-users',
    
    category: 'common',

    example: {
        attributes: {
        }
    },

    attributes: {
      // Data
      user_id: {
        type: 'number',
        default: 0
      },
      text: {
        type: 'string',
        default: ''
      },
      text_badge: {
        type: 'string',
        default: __( 'Ask me', 'ask-expert-block' )
      },
      text_button: {
        type: 'string',
        default: __( 'Ask a Question', 'ask-expert-block' )
      },
      // Base
      text_size: {
        type: 'number',
        default: 14
      },
      color_background: {
        type: 'string',
        default: '#E4EAF6'
      },
      color_text: {
        type: 'string',
        default: '#000000'      
      },
      // User
      user_image_size: {
        type: 'number',
        default: 75
      },
      user_text_size_name: {
        type: 'number',
        default: 16
      },
      user_text_size_description: {
        type: 'number',
        default: 14
      },
      user_color_name: {
        type: 'string',
        default: '#000000'
      },
      user_color_description: {
        type: 'string',
        default: '#7C6E6E'
      },
      // Badge
      badge_text_size: {
        type: 'number',
        default: 12
      },
      badge_color_background: {
        type: 'string',
        default: '#FF3164'
      },
      badge_color_text: {
        type: 'string',
        default: '#FFFFFF'
      },
      // Button
      button_text_size: {
        type: 'number',
        default: 16
      },
      button_color_background: {
        type: 'string',
        default: '#86B802'
      },
      button_color_text: {
        type: 'string',
        default: '#FFFFFF'
      },
    },

		edit: withSelect( function( select, props ) {

      return {
        users: select( 'core' ).getEntityRecords( 'root', 'user', { per_page: 100, groupby: 'id' } )
      };

    }) ( function( props ) {

      if (!props.users) {
        return i18n.__('Loading...', 'ask-expert-block');
      }

      let options = [{
        value: 0,
        label: __('None')
      }];

      for(let i = 0; i < props.users.length; i++) {
        options.push({
          value: props.users[i].id,
          label: props.users[i].name
        });
      }

      return [
        el('div', { className: 'ask-expert-block'},
          el(SelectControl, {
            label: __('User', 'ask-expert-block'),
            options: options,
            value: props.attributes.user_id,
            className: 'ask-expert-block__user',
            onChange: function ( value ) {
              props.setAttributes({
                user_id: parseInt( value )
              });
            }
          }),
          el(TextControl, {
            label: __('Text Badge', 'ask-expert-block'),
            value: props.attributes.text_badge,    
            onChange: function ( text ) {
              props.setAttributes({
                text_badge: text
              });
            }
          }),
          el(TextControl, {
            label: __('Text Button', 'ask-expert-block'),
            value: props.attributes.text_button,  
            onChange: function ( text ) {
              props.setAttributes({
                text_button: text
              });
            }
          }),
          el(TextareaControl, {
            label: __('Text', 'ask-expert-block'),
            value: props.attributes.text,
            rows: 8,
            onChange: function ( text ) {
              props.setAttributes({
                text: text
              });
            }
          })
        ),
        el(InspectorControls, {},
          el(PanelBody, { title: __('Text size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(FontSizePicker, {
                value: props.attributes.text_size,
                onChange: function ( font_size ) {
                  props.setAttributes({
                    text_size: parseInt(font_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('Background color', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(ColorPicker, { 
                disableAlpha: true,
                color: props.attributes.color_background,
                onChangeComplete: function ( color ) {
                  props.setAttributes({
                    color_background: color.hex
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('Text color', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.color_text,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  color_text: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('User image size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(TextControl, {
                value: props.attributes.user_image_size,
                type: 'number',
                onChange: function ( image_size ) {
                  props.setAttributes({
                    user_image_size: parseInt(image_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('User name text size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(FontSizePicker, {
                value: props.attributes.user_text_size_name,
                onChange: function ( font_size ) {
                  props.setAttributes({
                    user_text_size_name: parseInt(font_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('User description text size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(FontSizePicker, {
                value: props.attributes.user_text_size_description,
                onChange: function ( font_size ) {
                  props.setAttributes({
                    user_text_size_description: parseInt(font_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('User name color', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.user_color_name,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  user_color_name: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('User description color', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.user_color_description,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  user_color_description: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('Badge text size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(FontSizePicker, {
                value: props.attributes.badge_text_size,
                onChange: function ( font_size ) {
                  props.setAttributes({
                    badge_text_size: parseInt(font_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('Badge color background', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.badge_color_background,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  badge_color_background: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('Badge color text', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.badge_color_text,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  badge_color_text: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('Button text size', 'ask-expert-block'), initialOpen: false },
            el(PanelRow, {},
              el(FontSizePicker, {
                value: props.attributes.button_text_size,
                onChange: function ( font_size ) {
                  props.setAttributes({
                    button_text_size: parseInt(font_size)
                  });
                }
              })
            )
          ),
          el(PanelBody, { title: __('Button color background', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.button_color_background,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  button_color_background: color.hex
                });
              }
            })
          ),
          el(PanelBody, { title: __('Button color text', 'ask-expert-block'), initialOpen: false },
            el(ColorPicker, { 
              disableAlpha: true,
              color: props.attributes.button_color_text,
              onChangeComplete: function ( color ) {
                props.setAttributes({
                  button_color_text: color.hex
                });
              }
            })
          ),
        )
      ];
    }),
    
		save: function( props ) {

    }
    
  } );

} )( window.wp.blocks, window.wp.blockEditor, window.wp.i18n, window.wp.element, window.wp.components, window.wp.data );
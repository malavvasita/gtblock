import { SelectControl, PanelBody } from '@wordpress/components'
import { InspectorControls } from "@wordpress/block-editor";
import { Fragment } from "@wordpress/element";

wp.blocks.registerBlockType(
    'gtblock/get-posts',
    {
        title: "Get Posts",
        icon: "archive",
        category: "common",
        attributes: {
            postType: {
                type: 'string',
                default: 'posts',
            }
        },
        edit: function( props ) {

            return (
                <Fragment>
                    <InspectorControls>
                        <PanelBody title="Settings" initialOpen={true}>
                            <SelectControl
                                label="Select Visual Component"
                                value={ props.attributes.postType }
                                options={ [
                                    { label: 'Posts', value: 'posts' },
                                    { label: 'Products', value: 'products' },
                                ] }
                                onChange={ (value) => props.setAttributes({ postType: value }) }
                            />
                        </PanelBody>
                    </InspectorControls>
                    <div>
                        <p className='gtblock-editor-message'>
                            Latest 5 {props.attributes.postType} will be shown here!
                        </p>
                    </div>
                </Fragment>
            )
        },
        save: function( props ) {
            return null
        }
    }
);

import React from 'react';

import { more } from '@wordpress/icons';
import {
	Flex,
	FlexItem,
	Panel,
	PanelBody,
	PanelRow,
	__experimentalText as Text,
} from '@wordpress/components';

import { SERVER_VARIABLES } from 'plugin-settings';

const variables = Object.entries( JSON.parse( SERVER_VARIABLES ) )

const ServerPanel = () => (
	<Panel
		className='custom-development-plugin-panel'
	>
		<PanelBody
			title='Server Variables'
			initialOpen={ true }
			className='custom-development-plugin-panel__body'
		>
			{ 
				variables.map( ( [ name, value ], index ) => {
					return (
						<PanelRow
							key={ index }
							className='custom-development-plugin-panel__row'
						>
							<Flex>
								<FlexItem>{ name }</FlexItem>
								<FlexItem>{ value }</FlexItem>
							</Flex>
						</PanelRow>
					);
				} )
			}
		</PanelBody>
	</Panel>
);

export default ServerPanel;

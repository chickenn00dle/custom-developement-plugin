import React from 'react';
import { Flex, FlexItem, PanelRow } from '@wordpress/components';

const ServerList = ( { variables } ) => {
	return (
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
	);
}

export default ServerList;

import React from 'react';
import { Button, Flex, FlexBlock, FlexItem, PanelRow } from '@wordpress/components';
import { Icon, close } from '@wordpress/icons';

const ConstantsList = ( { constants, onClick } ) => {
	return (
		constants.map( ( { name, value }, index ) => {
			return (
				<PanelRow
					key={ index }
					className='custom-development-plugin-panel__row'
				>
					<Flex justify='start'>
						<FlexItem>
							<Button
								isSmall
								isTertiary
								onClick={ () => onClick( name ) }
							>
								<Icon icon={ close } size={ 10 } />
							</Button>
						</FlexItem>
						<FlexItem>{ name }</FlexItem>
						<FlexBlock />
						<FlexItem>{ value }</FlexItem>
					</Flex>
				</PanelRow>
			);
		} )
	);
}

export default ConstantsList;

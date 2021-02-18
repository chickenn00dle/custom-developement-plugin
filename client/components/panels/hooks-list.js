import React from 'react';
import {
	Button,
	Flex,
	FlexBlock,
	FlexItem,
	PanelRow,
} from '@wordpress/components';
import { Icon, close } from '@wordpress/icons';

const HooksList = ( { hooks, onClick } ) => {
	return (
		hooks.map( ( { id, title, name, cb, args }, index ) => {
			return (
				<PanelRow
					key={ index }
					className='custom-development-plugin-panel__row'
				>
					<Flex justify='start'>
						<FlexItem className='custom-development-plugin-panel__column custom-development-plugin-panel__column--title'>
							<Button
								className='custom-development-plugin-panel__column--button'
								isSmall
								isTertiary
								onClick={ () => onClick( id ) }
							>
								<Icon icon={ close } size={ 10 } />
							</Button><strong>{ title }</strong>
						</FlexItem>
						<FlexItem className='custom-development-plugin-panel__column custom-development-plugin-panel__column--name'>{ name }</FlexItem>
						<FlexItem className='custom-development-plugin-panel__column custom-development-plugin-panel__column--priority'>{ args.priority }</FlexItem>
						<FlexItem className='custom-development-plugin-panel__column custom-development-plugin-panel__column--accepted'>{ args.accepted }</FlexItem>
						<FlexBlock />
						<FlexItem className='custom-development-plugin-panel__column custom-development-plugin-panel__column--code-block'>{ cb }</FlexItem>
					</Flex>
				</PanelRow>
			);
		} )
	);
}

export default HooksList;

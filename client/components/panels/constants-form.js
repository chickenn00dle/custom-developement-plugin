import React from 'react';
import { useState } from '@wordpress/element';
import {
	Button,
	Flex,
	FlexItem,
	FlexBlock,
	PanelRow,
	SelectControl,
	TextControl
} from '@wordpress/components';

const ConstantsForm = ( { onSubmit } ) => {
	const [ name, setName ] = useState( '' );
	const [ val, setVal ] = useState( '' );
	const [ type, setType ] = useState( 'string' );
	return (
		<>
			<PanelRow className='custom-development-plugin-panel__row custom-development-plugin-panel__row--form'>
				<Flex justify='start'>
					<FlexItem>
						<TextControl
							label='Constant Name'
							value={ name }
							onChange={ value => setName( value ) }
						/>
					</FlexItem>
					<FlexItem>
						<TextControl
							label='Constant Value'
							value={ val }
							onChange={ value => setVal( value ) }
						/>
					</FlexItem>
					<FlexItem>
						<SelectControl
							label='Type'
							value={ type }
							options={ [
								{ label: 'String', value: 'string' },
								{ label: 'Boolean', value: 'bool' },
								{ label: 'Number', value: 'number' },
							] }
							onChange={ value => setType( value ) }
						/>
					</FlexItem>
					<FlexBlock />
					<FlexItem>
						<Button
							isPrimary
							onClick={ () => onSubmit( {
								name,
								type,
								value: val
							} ) }
						>
							Add Constant
						</Button>
					</FlexItem>
				</Flex>
			</PanelRow>
		</>
	);
}

export default ConstantsForm;

import React from 'react';
import { useState } from '@wordpress/element';
import {
	Button,
	Flex,
	FlexItem,
	FlexBlock,
	PanelRow,
	SelectControl,
	TextareaControl,
	TextControl
} from '@wordpress/components';

const HooksForm = ( { onSubmit } ) => {
	const [ title, setTitle ] = useState( '' );
	const [ cb, setCb ] = useState( '' );
	const [ name, setName ] = useState( '' );
	const [ type, setType ] = useState( 'action' );
	const [ priority, setPriority ] = useState( 10 );
	const [ accepted, setAccepted ] = useState( 1 );
	return (
		<>
			<PanelRow className='custom-development-plugin-panel__row custom-development-plugin-panel__row--form'>
				<Flex
					align='start'
					justify='start'
					className='custom-development-plugin-form__container'
				>
					<FlexItem className='custom-development-plugin-panel__column'>
						<TextControl
							className='custom-development-plugin-form__input'
							placeholder='Hook Title'
							value={ title }
							onChange={ value => setTitle( value ) }
						/>
					</FlexItem>
					<FlexItem className='custom-development-plugin-panel__column'>
						<TextControl
							className='custom-development-plugin-form__input'
							placeholder='Hook'
							value={ name }
							onChange={ value => setName( value ) }
						/>
					</FlexItem>
					<FlexItem className='custom-development-plugin-panel__column'>
						<SelectControl
							className='custom-development-plugin-form__input custom-development-plugin-form__input--small'
							value={ type }
							options={ [
								{ label: 'Action', value: 'action' },
								{ label: 'Filter', value: 'filter' },
							] }
							onChange={ value => setType( value ) }
						/>
					</FlexItem>
					<FlexItem className='custom-development-plugin-panel__column'>
						<TextControl
							className='custom-development-plugin-form__input custom-development-plugin-form__input--small'
							value={ priority }
							onChange={ value => setPriority( Number( value ) ) }
							type='number'
						/>
					</FlexItem>
					<FlexItem className='custom-development-plugin-panel__column'>
						<TextControl
							className='custom-development-plugin-form__input custom-development-plugin-form__input--small'
							value={ accepted }
							onChange={ value => setAccepted( Number( value ) ) }
							type='number'
						/>
					</FlexItem>
					<FlexItem className='custom-development-plugin-panel__column'>
						<TextareaControl
							className='custom-development-plugin-form__input custom-development-plugin-form__input--large'
							placeholder='Callback'
							value={ cb }
							onChange={ value => setCb( value ) }
						/>
					</FlexItem>
					<FlexBlock />
					<FlexItem className='custom-development-plugin-panel__column'>
						<Button
							className='custom-development-plugin-form__submit'
							isPrimary
							onClick={ () => {
								onSubmit( {
									title,
									name,
									cb,
									args: {
										accepted,
										priority,
										type
									}
								} );
								setTitle( '' );
								setName( '' );
								setCb( '' );
								setAccepted( 1 );
								setPriority( 10 );
								setType( 'action' );
							} }
						>
							Add Hook
						</Button>
					</FlexItem>
				</Flex>
			</PanelRow>
		</>
	);
}

export default HooksForm;

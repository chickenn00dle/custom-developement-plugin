import React from 'react';
import { useSelect, useDispatch } from '@wordpress/data';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

import ConstantsForm from './constants-form.js';
import ConstantsList from './constants-list';
import { CONSTANT_STORE_KEY } from 'plugin-stores';

import './style.scss';

const ConstantsPanel = () => {
	const constants = useSelect( select => select( CONSTANT_STORE_KEY ).getConstants() );
	const { createConstant, deleteConstant } = useDispatch( CONSTANT_STORE_KEY );

	return (
		<>
			<Panel className='custom-development-plugin-panel'>
				<PanelBody
					title='Defined Constants'
					initialOpen={ true }
					className='custom-development-plugin-panel__body'
				>
					<ConstantsForm onSubmit={ createConstant } />
					<ConstantsList constants={ constants } onClick={ deleteConstant } />
				</PanelBody>
			</Panel>
		</>
	);
};

export default ConstantsPanel;

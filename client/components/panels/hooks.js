import React from 'react';
import { useSelect, useDispatch } from '@wordpress/data';
import { Panel, PanelBody, PanelRow } from '@wordpress/components';

import HooksForm from './hooks-form';
import HooksList from './hooks-list';
import { HOOK_STORE_KEY } from 'plugin-stores';

import './style.scss';

const HooksPanel = () => {
	const { createHook, deleteHook } = useDispatch( HOOK_STORE_KEY );
	const hooks = useSelect( select => select( HOOK_STORE_KEY ).getHooks() );
	const error = useSelect( select => select( HOOK_STORE_KEY ).getError() );

	return (
		<>
			<Panel className='custom-development-plugin-panel'>
				<PanelBody
					title='Custom Hooks'
					initialOpen={ true }
					className='custom-development-plugin-panel__body'
				>
					<HooksForm onSubmit={ createHook } />
					<HooksList hooks={ hooks } onClick={ deleteHook } />
					{ error &&
						<p>{ error }</p>
					}
				</PanelBody>
			</Panel>
		</>
	);
};

export default HooksPanel;

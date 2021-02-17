import React from 'react';
import {
	Flex,
	FlexItem,
	Panel,
	PanelBody,
	PanelRow,
	__experimentalText as Text,
} from '@wordpress/components';

import ServerList from './server-list';
import { SERVER_VARIABLES } from 'plugin-settings';
import './style.scss';

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
			<ServerList variables={ variables } />
		</PanelBody>
	</Panel>
);

export default ServerPanel;

import React from 'react';
import { render } from 'react-dom';
import { more } from '@wordpress/icons';

import { PLUGIN_TITLE, SELECTOR } from 'plugin-settings';
import { Layout } from './components/layout';
import { HooksPanel, ServerPanel } from './components/panels';

const App = () => (
	<Layout>
		<HooksPanel />
		<ServerPanel />
	</Layout>
);

render( <App/>, document.querySelector( SELECTOR ) );

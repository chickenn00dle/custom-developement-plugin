import React from 'react';
import { render } from 'react-dom';
import { more } from '@wordpress/icons';

import { PLUGIN_TITLE, SELECTOR } from 'plugin-settings';
import { Layout } from './components/layout';
import { ConstantsPanel, ServerPanel } from './components/panels';

const App = () => {
	return (
		<Layout>
			<ConstantsPanel />
			<ServerPanel />
		</Layout>
	);
}

render( <App/>, document.querySelector( SELECTOR ) );

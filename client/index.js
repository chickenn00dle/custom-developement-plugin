import React from 'react';
import { render } from 'react-dom';
import { more } from '@wordpress/icons';

import { PLUGIN_TITLE, SELECTOR } from 'plugin-settings';
import { Layout } from './layout';
import { ServerPanel } from './panels';

const App = () => {
	return (
		<Layout>
			<ServerPanel />
		</Layout>
	);
}

render( <App/>, document.querySelector( SELECTOR ) );

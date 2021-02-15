import React from 'react';
import { __experimentalText as Text } from '@wordpress/components';

import { PLUGIN_TITLE } from 'plugin-settings';

import './style.scss';

const Header = () => {
	return (
		<div className='custom-development-plugin-header'>
			<Text variant='title.small'>
				{ PLUGIN_TITLE }
			</Text>
		</div>
	);
}

export default Header;

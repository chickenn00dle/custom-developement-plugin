import React from 'react';

import Header from './header';

const Layout = ( { children } ) => {
	return(
		<>
			<div className='custom-development-plugin-container'>
				<Header />
				<div className='custom-development-plugin-body'>
					{ children }
				</div>
			</div>
		</>
	);
}

export default Layout;

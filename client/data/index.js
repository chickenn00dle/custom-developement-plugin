import { createReduxStore, register } from '@wordpress/data';

import { CONSTANT_STORE_KEY, CONSTANT_STORE_CONFIG } from './constants';

const store = createReduxStore( CONSTANT_STORE_KEY, CONSTANT_STORE_CONFIG );

register( store );

export { CONSTANT_STORE_KEY };

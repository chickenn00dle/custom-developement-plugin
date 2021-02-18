import { createReduxStore, register } from '@wordpress/data';

import { HOOK_STORE_KEY, HOOK_STORE_CONFIG } from './hooks';

const store = createReduxStore( HOOK_STORE_KEY, HOOK_STORE_CONFIG );

register( store );

export { HOOK_STORE_KEY };

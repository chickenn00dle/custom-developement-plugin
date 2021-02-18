import { apiFetch } from '@wordpress/data-controls';

import { NAMESPACE, REST_BASE } from 'plugin-settings';
import { hydrateHooks, setError } from './actions';

const url = NAMESPACE + REST_BASE;

export function* getHooks() {
	try {
		const hooks = yield apiFetch( {
			method: 'GET',
			path: url,
		} );

		yield hydrateHooks( hooks );
	} catch {
		yield setError( 'Error fetching hooks.' );
	}
}

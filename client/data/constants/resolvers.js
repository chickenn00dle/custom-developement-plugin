import { apiFetch } from '@wordpress/data-controls';

import { NAMESPACE, REST_BASE } from 'plugin-settings';
import { hydrateConstants } from './actions';

export function* getConstants() {
	try {
		const url = NAMESPACE + REST_BASE;
		const constants = yield apiFetch( {
			method: 'GET',
			path: url,
		} );

		yield hydrateConstants( constants );
	} catch {
		yield setError( 'Error fetching constants.' );
	}
}

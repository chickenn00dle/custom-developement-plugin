import { apiFetch } from '@wordpress/data-controls';

import TYPES from './action-types';
import { NAMESPACE, REST_BASE } from 'plugin-settings';

const url = NAMESPACE + REST_BASE;
const {
	CREATE,
	UPDATE,
	DELETE,
	HYDRATE,
	ERROR
} = TYPES;

export function* createHook( hook ) {
	try {
		const result = yield apiFetch( {
			method: 'POST',
			path: url,
			data: hook
		} );

		yield {
			type: CREATE,
			hook
		}
	} catch( { message } ) {
		yield setError( message );
	}
}

export function* updateHook( hook ) {
	try {
		const result = yield apiFetch( {
			method: 'POST',
			path: url,
			data: hook
		} );

		yield {
			type: UPDATE,
			hook
		}
	} catch( { message } ) {
		yield setError( message );
	}
}

export function* deleteHook( hookID ) {
	try {
		const result = yield apiFetch( {
			method: 'DELETE',
			path: url,
			data: {
				id: hookID,
			}
		} );

		yield {
			type: DELETE,
			hookID
		}
	} catch( { message } ) {
		yield setError( message );
	}
}

export const hydrateHooks = hooks => ( {
	type: HYDRATE,
	hooks
} );

export const setError = error => ( {
	type: ERROR,
	error
} );

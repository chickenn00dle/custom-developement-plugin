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

export function* createConstant( constant ) {
	try {
		const result = yield apiFetch( {
			method: 'POST',
			path: url,
			data: constant
		} );

		yield {
			type: CREATE,
			constant
		}
	} catch {
		yield setError( 'Error creating constant.' );
	}
}

export function* updateConstant( constant ) {
	try {
		const result = yield apiFetch( {
			method: 'POST',
			path: url,
			data: constant
		} );

		yield {
			type: UPDATE,
			constant
		}
	} catch {
		yield setError( 'Error updating constant.' );
	}
}

export function* deleteConstant( constantName ) {
	try {
		const result = yield apiFetch( {
			method: 'DELETE',
			path: url,
			data: {
				name: constantName,
			}
		} );

		yield {
			type: DELETE,
			constantName
		}
	} catch {
		yield {
			type: ERROR,
			error: 'Error deleting constant.'
		}
	}
}

export const hydrateConstants = constants => ( {
	type: HYDRATE,
	constants
} );

export const setError = error => ( {
	type: ERROR,
	error
} );

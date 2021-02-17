import TYPES from './action-types';

const {
	CREATE,
	UPDATE,
	DELETE,
	HYDRATE,
	ERROR
} = TYPES;

const reducer = (
	state = { constants: [], error: '' },
	{ constants: incomingConstants, constant, constantName, error, type }
) => {
	switch( type ) {
		case CREATE:
			return {
				...state,
				constants: [ ...state.constants, constant ]
			}
		case UPDATE:
			return {
				...state,
				constants: state.constants
					.filter( current => current.name !== constant.name )
					.concat( [ constant ] )
			}
		case DELETE:
			return {
				...state,
				constants: state.constants.filter( current => current.name !== constantName )
			}
		case HYDRATE:
			return {
				...state,
				constants: incomingConstants
			}
		case ERROR:
			return {
				...state,
				error
			}
		default:
			return state;
	}
}

export default reducer;

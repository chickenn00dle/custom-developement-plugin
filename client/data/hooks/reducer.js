import TYPES from './action-types';

const {
	CREATE,
	UPDATE,
	DELETE,
	HYDRATE,
	ERROR
} = TYPES;

const reducer = (
	state = { hooks: [], error: null },
	{ hooks, hook, hookID, error, type }
) => {
	switch( type ) {
		case CREATE:
			return {
				...state,
				hooks: [ ...state.hooks, hook ]
			}
		case UPDATE:
			return {
				...state,
				hooks: state.hooks
					.filter( current => current.id !== hook.id )
					.concat( [ hook ] )
			}
		case DELETE:
			return {
				...state,
				hooks: state.hooks.filter( current => current.id !== hookID )
			}
		case HYDRATE:
			return {
				...state,
				hooks
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

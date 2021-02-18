export const getHooks = state => state.hooks || [];

export const getHook = ( state, name ) => {
	return getHooks( state )
		.find( hook => hook.name === name );
}

export const getError = state => state.error || null;

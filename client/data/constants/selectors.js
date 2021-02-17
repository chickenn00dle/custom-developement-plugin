export const getConstants = state => state.constants || [];

export const getConstant = ( state, name ) => {
	return getConstants( state )
		.find( constant => constant.name === name );
}

import { controls } from '@wordpress/data-controls'

import * as actions from './actions';
import * as resolvers from './resolvers';
import * as selectors from './selectors';
import reducer from './reducer';

export const CONSTANT_STORE_KEY = 'data/constants';

export const CONSTANT_STORE_CONFIG = {
	reducer,
	actions,
	selectors,
	controls,
	resolvers
}

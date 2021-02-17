const { namespace, rest_base, selector, server_vars } = window.customDevelopmentPlugin || {};

export const NAMESPACE = `/wp-json${ namespace }`;
export const PLUGIN_TITLE = 'Custom Development Plugin';
export const REST_BASE = rest_base;
export const SELECTOR = selector;
export const SERVER_VARIABLES = server_vars;

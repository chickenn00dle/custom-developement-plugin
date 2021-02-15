const { selector, server_vars } = window.customDevelopmentPlugin || {};
const {
	server_software,
	home,
	server_host,
	server_name,
	https,
	server_port,
	server_addr,
	remote_port,
	remote_addr
} = server_vars;

export const PLUGIN_TITLE = 'Custom Development Plugin';
export const SELECTOR = selector;
export const SERVER_VARIABLES = server_vars;

<?php
/**
 * Force https in Pantheon for all environments, add primary domain redirect for live environment
 *
 * Reference: https://pantheon.io/docs/http-to-https/
 */
if (isset($_ENV['PANTHEON_ENVIRONMENT']) && php_sapi_name() !== 'cli') {
	// Redirect to https://$primary_domain in the Live environment
	if ($_ENV['PANTHEON_ENVIRONMENT'] === 'live') {
    // Update and uncomment this line when site is launched
		// $primary_domain = 'www.sitename.com';
	} else {
		// Redirect to HTTPS on every Pantheon environment.
		$primary_domain = $_SERVER['HTTP_HOST'];
	}

	if (
		$_SERVER['HTTP_HOST'] !== $primary_domain ||
		!isset($_SERVER['HTTP_USER_AGENT_HTTPS']) ||
		'ON' !== $_SERVER['HTTP_USER_AGENT_HTTPS']
	) {
		# Name transaction "redirect" in New Relic for improved reporting (optional)
		if (extension_loaded('newrelic')) {
			newrelic_name_transaction("redirect");
		}

		header('HTTP/1.0 301 Moved Permanently');
		header('Location: https://'. $primary_domain . $_SERVER['REQUEST_URI']);
		exit();
	}
}

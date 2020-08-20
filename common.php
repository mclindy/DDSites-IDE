<?php

//////////////////////////////////////////////////////////////////////////////80
// Common
//////////////////////////////////////////////////////////////////////////////80
// Copyright (c) Atheos & Liam Siira (Atheos.io), distributed as-is and without
// warranty under the MIT License. See [root]/LICENSE.md for more.
// This information must remain intact.
//////////////////////////////////////////////////////////////////////////////80
// Authors: Codiad Team, @Fluidbyte, Atheos Team, @hlsiira
//////////////////////////////////////////////////////////////////////////////80

//////////////////////////////////////////////////////////////////
// Common Class
//////////////////////////////////////////////////////////////////

require_once("traits/checks.php");
require_once("traits/database.php");
require_once("traits/helpers.php");
require_once("traits/json.php");
require_once("traits/path.php");
require_once("traits/reply.php");
require_once("traits/session.php");

class Common {

	use Check;
	use Database;
	use Helpers;
	use JSON;
	use Path;
	use Reply;
	use Session;

	//////////////////////////////////////////////////////////////////////////80
	// PROPERTIES
	//////////////////////////////////////////////////////////////////////////80

	public static $debugStack = array();

	private static $data = array(
		"session" => array(),
		"post" => array(),
		"get" => array(),
	);

	private static $database = null;

	//////////////////////////////////////////////////////////////////////////80
	// METHODS
	//////////////////////////////////////////////////////////////////////////80

	// -----------------------------||----------------------------- //

	//////////////////////////////////////////////////////////////////////////80
	// Construct
	//////////////////////////////////////////////////////////////////////////80
	public static function initialize() {
		$path = __DIR__;

		if (file_exists($path.'/config.php')) require_once($path.'/config.php');
		
		if (defined("LIFETIME") && LIFETIME !== "") {
			ini_set("session.cookie_lifetime", LIFETIME);
		}


		if (!defined("BASE_PATH")) define("BASE_PATH", $path);
		if (!defined("COMPONENTS")) define('COMPONENTS', BASE_PATH . "/components");
		if (!defined("PLUGINS")) define('PLUGINS', BASE_PATH . "/plugins");
		if (!defined("DATA")) define('DATA', BASE_PATH . "/data");
		if (!defined("THEMES")) define("THEMES", BASE_PATH . "/themes");
		if (!defined("THEME")) define("THEME", "atheos");
		if (!defined("LANGUAGE")) define("LANGUAGE", "en");
		if (!defined("DEVELOPMENT")) define("DEVELOPMENT", false);

		if (file_exists(BASE_PATH ."/components/i18n/class.i18n.php")) {
			require_once(BASE_PATH ."/components/i18n/class.i18n.php");
		}

		// Set up language translation
		global $i18n;
		$i18n = new i18n(LANGUAGE);
		$i18n->init();

		//Check for external authentification
		if (defined("AUTH_PATH") && file_exists(AUTH_PATH)) require_once(AUTH_PATH);

		global $components; global $plugins; global $themes;
		// Read Components, Plugins, Themes
		$components = Common::readDirectory(COMPONENTS);
		$plugins = Common::readDirectory(PLUGINS);
		$themes = Common::readDirectory(THEMES);

		// Add data to global variables
		if ($_POST && !empty($_POST)) {
			foreach ($_POST as $key => $value) {
				Common::$data["post"][$key] = $value;
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////80
	// Read Post/Get/Server/Session Data
	//////////////////////////////////////////////////////////////////////////80
	public static function data($key, $type = false) {
		$value = false;

		if (array_key_exists($key, Common::$data["post"])) {
			$value = Common::$data["post"][$key];
		} elseif (array_key_exists($key, Common::$data["get"])) {
			$value = Common::$data["get"][$key];
		}

		if ($type) {
			if ($type === "server") {
				$value = array_key_exists($key, $_SERVER) ? $_SERVER[$key] : false;
			} elseif ($type === "session") {
				$value = array_key_exists($key, $_SESSION) ? $_SESSION[$key] : false;
			} else {
				$value = array_key_exists($key, Common::$data[$type]) ? Common::$data[$type][$key] : false;
			}
		}
		return $value;
	}

	//////////////////////////////////////////////////////////////////////////80////////80
	// Execute Command
	//////////////////////////////////////////////////////////////////////////80////////80
	public function execute($cmd = false) {
		$output = false;
		if (!$cmd) return "No command provided";

		if (function_exists('system')) {
			ob_start();
			system($cmd);
			$output = ob_get_contents();
			ob_end_clean();
		} elseif (function_exists('exec')) {
			exec($cmd, $output);
			$output = implode("\n", $output);
		} elseif (function_exists('shell_exec')) {
			$output = shell_exec($cmd);
		} else {
			$output = 'Command execution not possible on this system';
		}
		return $output;
	}
}

Common::initialize();
Common::startSession();

function i18n($string, $args = false) {
	global $i18n;
	return $i18n->translate($string, $args);
}


function debug($val) {
	Common::$debugStack[] = $val;
}

?>
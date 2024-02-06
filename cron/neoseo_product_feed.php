<?php

$sapi = php_sapi_name();
// Version
define('VERSION', '3.0.3.2');

function log1($message)
{
	file_put_contents(DIR_LOGS . "neoseo_product_feed.log", date("Y-m-d H:i:s - ") . $message . "\r\n", FILE_APPEND);
}

// Configuration
if (is_file(dirname(__FILE__) . "/../config_local.php")) {
	require_once(dirname(__FILE__) . "/../config_local.php");
} elseif (is_file(dirname(__FILE__) . "/../config.php")) {
	require_once(dirname(__FILE__) . "/../config.php");
} else {
	log1("Отсутствует файл конфигурации");
	exit();
}

log1("Начинаем формирование экспорта по расписанию");

log1("Подключаем движок опенкарт");
// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();


// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response);

// Config
$config = new Config();
$config->load('default');
$config->load('catalog');
$registry->set('config', $config);

// Event
$event = new Event($registry);
$registry->set('event', $event);

// Event Register
if ($config->has('action_event')) {
	foreach ($config->get('action_event') as $key => $value) {
		$event->register($key, new Action($value));
	}
}

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

function error_handler($errno, $errstr, $errfile, $errline)
{
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	log1('Произошла ошибка PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	return true;
}

// Error Handler
set_error_handler('error_handler');

// Store
// todo: сделать параметром
$config->set('config_store_id', 0);
$query = $db->query("SELECT * FROM " . DB_PREFIX . "store");
$stories[] = array('store_id' => 0, 'url' => HTTPS_SERVER,);
foreach ($query->rows as $result) {
	$stories[] = array('store_id' => $result['store_id'], 'url' => $result['url'],);
}

foreach ($stories as $store) {
	$config->set('config_store_id', $store['store_id']);


	// Settings
	$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int) $config->get('config_store_id') . "' ORDER BY store_id ASC");
	foreach ($query->rows as $result) {
		if (!$result['serialized']) {
			$config->set($result['key'], $result['value']);
		} else {
			$config->set($result['key'], json_decode($result['value'], true));
		}
	}

	$config->set('config_url', $store['url']);
	$config->set('config_ssl', $store['url']);

	// Cache
	$cache = new Cache('file');
	$registry->set('cache', $cache);

	// Session
	//$session = new Session();
	//$registry->set('session', $session);
	// Language Detection
	$languages = array();

	$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");
	foreach ($query->rows as $result) {
		$languages[$result['code']] = $result;
	}

	$code = $config->get('config_language');
	$config->set('config_language_id', $languages[$code]['language_id']);
	$config->set('config_language', $languages[$code]['code']);

	// Language
	$language = new Language($languages[$code]['directory']);
	$language->load('default');
	$registry->set('language', $language);

	// Url
	$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));
	$registry->set('url', $url);

	// Log
	$log = new Log($config->get('config_error_filename'));
	$registry->set('log', $log);

	// Currency
	$registry->set('currency', new Cart\Currency($registry));

	// Tax
	$registry->set('tax', new Cart\Tax($registry));

	// Weight
	$registry->set('weight', new Cart\Weight($registry));

	// Length
	$registry->set('length', new Cart\Length($registry));


	log1("Инициализируем сео-компонент");

	if (!$seo_type = $config->get('config_seo_url_type')) {
		$seo_type = 'Seo_Url';
	}
	$seoFile = DIR_APPLICATION . 'controller/startup/' . str_replace(array('../', '..', '..'), '', mb_strtolower($seo_type)) . '.php';

	if (file_exists($seoFile)) {
		require_once($seoFile);
		$seoClass = 'ControllerStartup' . preg_replace('/[^a-zA-Z0-9]/', '', $seo_type);
		$seoController = new $seoClass($registry);
		$url->addRewrite($seoController);
	}

	putenv("SERVER_NAME=localhost"); // это чтобы почта работала
	log1("Загружаем модуль мультиэкспорта");
	$loader->model('feed/neoseo_product_feed');
	log1("Запускаем формирование экспорта");
	$registry->get("model_feed_neoseo_product_feed")->saveFeeds();
}



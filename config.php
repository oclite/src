<?php
// HTTP  
define('HTTP_SERVER', 'http://demo.oclite.ru/');

// HTTPS
define('HTTPS_SERVER', 'http://demo.oclite.ru/');

// DIR
define('DIR_APPLICATION', '/var/www/demo.oclite.ru/public_html/catalog/');
define('DIR_SYSTEM', '/var/www/demo.oclite.ru/public_html/system/');
define('DIR_LANGUAGE', DIR_APPLICATION.'language/');
define('DIR_TEMPLATE', DIR_APPLICATION.'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM.'config/');
define('DIR_CONTENT', '/var/www/demo.oclite.ru/public_html/content/');
define('DIR_CACHE', DIR_SYSTEM.'cache/');
define('DIR_DOWNLOAD', DIR_SYSTEM.'download/');
define('DIR_MODIFICATION', DIR_SYSTEM.'modification/');
define('DIR_LOGS', DIR_SYSTEM.'logs/');
define('URL_CONTENT', HTTP_SERVER.'content/');
define('ITEMS_URL', HTTP_SERVER.'content/catalog/items/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'demooclite');
define('DB_PREFIX', 'oc_');

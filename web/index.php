<?php

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);

// Entorno automatico: local (dominios *.loc / localhost) = dev; resto = prod.
$serverName = $_SERVER['SERVER_NAME'] ?? php_uname('n');
$isLocal = (strpos($serverName, '.loc') !== false) || $serverName === 'localhost';
defined('YII_DEBUG') or define('YII_DEBUG', $isLocal);
defined('YII_ENV') or define('YII_ENV', $isLocal ? 'dev' : 'prod');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();

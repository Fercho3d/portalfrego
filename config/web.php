<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// El portal comparte el almacen de archivos y la BD con el sistema Frego.
// @webfolder = ruta en disco a la carpeta del sistema (uploads compartidos).
// @sysUrl    = URL publica del sistema (para enlaces de descarga).
// Se detecta el entorno por el nombre de servidor (mismo criterio que Frego).
$serverName = $_SERVER['SERVER_NAME'] ?? php_uname('n');
$isLocal = (strpos($serverName, '.loc') !== false) || $serverName === 'localhost';
if ($isLocal) {
    $sysWebfolder = '/opt/homebrew/var/www/frego';
    $sysUrl       = 'http://frego.dev.loc';
} else {
    // Produccion (AWS). Ajustar en el servidor si cambia la ruta/URL del sistema.
    $sysWebfolder = '/opt/bitnami/apache/htdocs/frego';
    $sysUrl       = 'http://52.12.171.198';
}

$config = [
    'id' => 'portal',
    'name' => 'Sistema Frego',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@webfolder' => $sysWebfolder,
        '@sysUrl' => $sysUrl,
    ],
    'components' => [
        'formatter' => [
                
            'dateFormat' => 'php:d/m/Y',
            
            'datetimeFormat' => 'php:d/m/Y H:i:s',

            'decimalSeparator' => '.',

            'thousandSeparator' => ',',

            'currencyCode' => '$',

        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'MA6HBYACdlB91uqYEfXg2Ov-VAC9LyUM',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
              'on afterLogin' => function($event){
                Yii::$app->user->identity->afterLogin($event);
            },
            'idParam'=>'user',
            //'authTimeout' => 60*90,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => 'User', 'httpOnly' => true]
        ],
        'client' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Client',
            'enableAutoLogin' => true,
            'idParam'=>'userClient',
            //'authTimeout' => 60*90,
            'loginUrl' => ['site/login-provider'],
            'identityCookie' => ['name' => 'Client', 'httpOnly' => true]
        ],      
        
        'provider' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Provider',
            'enableAutoLogin' => true,
            'idParam'=>'userProv',
            //'authTimeout' => 60*90,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => 'Provider', 'httpOnly' => true]
        ],
    
  
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
            //set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function

            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.frego.com.mx',
                'username' => 'webmaster@frego.com.mx',
                'password' => ')4?DIsK-EZBd',
                'port' => '465',
                'encryption' => 'ssl',
            ],*/
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'forceCopy' => YII_ENV_DEV ? true : false,   
            'linkAssets' => true,   
             'bundles' => [
             'yii\web\JqueryAsset' => [
                'js' => [YII_DEBUG ? 'https://code.jquery.com/jquery-3.2.1.js' : 'https://code.jquery.com/jquery-3.2.1.min.js'],
                'jsOptions' => ['type' => 'text/javascript', 'position' => \yii\web\View::POS_HEAD ],
                ],
            ],
        ],
    ],
    'params' => $params, 
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
         'allowedIPs' => ['187.190.0.38', '187.190.0.38', '187.233.102.226', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['187.190.0.38', '187.190.0.38',  '187.233.102.226', '187.233.112.226',  '::1'],
    ];
}

return $config;

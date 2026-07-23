<?php
/**
 * Plantilla de conexion a BD. Copiar a config/db.php y poner los valores reales
 * de cada entorno. El portal comparte la MISMA base de datos que el sistema Frego.
 * config/db.php esta en .gitignore y NO se versiona.
 */
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.0.1;dbname=frego',
    'username' => 'CHANGE_ME',
    'password' => 'CHANGE_ME',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

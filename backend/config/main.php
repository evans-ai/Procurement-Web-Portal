<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-bbackend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
         'navision' => [
            'class' => 'backend\library\Navision',
        ],
         'navhelper'=>[
            'class' => 'backend\library\Navhelper',
        ],
        /*'errorcatcher' => [
            'class' => 'app\library\Errorcatcher',
        ],*/
        'recruitment'=>[
            'class' => 'backend\library\Recruitment',
        ],
        'db_auth'=>[//Authenticating external Hr users
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlsrv:Server=FRC;Database=FRC-ERP-DEV', // Global Fund TNT_LIVE
            'username' => 'Administrator',
            'password' => 'Attain@01234#',
            'charset' => 'utf8',
        ],
        
    ],
    'params' => $params,
];

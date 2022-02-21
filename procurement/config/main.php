<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-procurement',
    'basePath' => dirname(__DIR__),

    'controllerNamespace' => 'procurement\controllers',
    'bootstrap' => ['log'],
    'homeUrl'=>array('site/index'),    
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'procurement\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-procurement',
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
            'class' => 'procurement\library\Navision',
        ],
        /*'errorcatcher' => [
            'class' => 'app\library\Errorcatcher',
        ],*/
        'recruitment'=>[
            'class' => 'procurement\library\Recruitment',
        ],
        'procurement'=>[
            'class' => 'procurement\library\Recruitment',
        ],
        'navhelper'=>[
            'class' => 'procurement\library\Navhelper',
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

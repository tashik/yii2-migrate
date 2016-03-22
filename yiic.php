<?php
/**
 * Created by PhpStorm.
 * User: tashik
 * Date: 22.03.16
 * Time: 10:48
 */

$currentpath = dirname(__FILE__);
$basepath = $currentpath;

$loader = null;
$i = 4;
while (!file_exists($basepath.'/vendor/autoload.php') && $i-->0) {
  $basepath = $basepath.'/..';
}
if ($i) {
  $basepath = realpath($basepath);
  $loader = include($basepath . '/vendor/autoload.php');
}

if($loader) {
  $loader->set('migrator', $basepath . '/vendor/yii2-migrate/app');
}
defined('BASE_PATH') || define('BASE_PATH', $basepath);

$env = getenv('YIIMIGRATE_ENV');

if ($env) {
  $env = "-{$env}";
} else {
  $env = '';
}

$ap_config = include($currentpath.'/config/console.php');

$db_config = @include("{$basepath}/app/config/migration{$env}.php");
if (!$db_config) {
  throw new RuntimeException("Migration config in {$basepath}/config/migration{$env}.php not found, please use {$currentpath}/config/migration.php-default to create one");
}

$config = array_merge($ap_config, $db_config);

$config['basePath'] = $basepath;

defined('YII_DEBUG') || define('YII_DEBUG',true);

// include Yii class file
require($basepath . '/vendor/yiisoft/yii2/Yii.php');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
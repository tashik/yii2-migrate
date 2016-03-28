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
  $basepath_relative = $basepath;
  $basepath = realpath($basepath);
  $loader = include($basepath . '/vendor/autoload.php');
}

if($loader) {
  $loader->set('app', $basepath . '/vendor/tashik/yii2-migrate');
}
defined('BASE_PATH') || define('BASE_PATH', $basepath);

if (file_exists($basepath.'/set_env.php')) {
  require_once ($basepath.'/set_env.php');
}

defined('RUNTIME_ENV') || define('RUNTIME_ENV', 'global');
defined('YII_DEBUG') || define('YII_DEBUG',true);

$ap_config = include($currentpath.'/config/console.php');

if(RUNTIME_ENV) {
  $env = '-'.RUNTIME_ENV;
}

if (!isset($db_config)) {
  $db_config = @include("{$basepath}/config/autoload/migration".$env.".php");
} else {
  $db_config = array(
    'components' => [
      'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => $db_config['dsn'],
        'user' => $db_config['user'],
        'password' => $db_config['password'],
        'charset' => isset($db_config['charset']) ? $db_config['charset'] : 'utf8',
      ]
    ]
  );
}


if (!$db_config) {
  throw new RuntimeException("Migration config in {$basepath}/config/autoload/migration".$env.".php not found, please use {$currentpath}/config/migration.php-default to create one");
}

$config = array_merge($ap_config, $db_config);

$config['basePath'] = $basepath;

// include Yii class file
require($basepath . '/vendor/yiisoft/yii2/Yii.php');

if (is_dir($basepath.'/module')) {
  Yii::setAlias('@modules', $basepath.'/module');
  $modules_list = array_diff(scandir($basepath.'/module'), array('..', '.'));

  if (count($modules_list)) {
    foreach($modules_list as $module) {
      if(is_dir($basepath.'/'.$module.'/migrations')) {
        $config['controllerMap']['migrate']['migrationLookup'][] = '@modules/'.$module.'/migrations';
      }
    }
  }
}

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
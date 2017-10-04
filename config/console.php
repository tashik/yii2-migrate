<?php
/**
 * Created by PhpStorm.
 * User: tashik
 * Date: 22.03.16
 * Time: 14:06
 */

return [
  'id' => 'dbmigrator',
  'enableCoreCommands' => false,
  'controllerMap' => [
    'migrate'=>[
      'class'=>'app\commands\MigrateController',
      'interactive' => false,
      'templateFile' => '@app/migrations/template.php',
      'migrationLookup'=>[
          '@app/migrations/db/documentizer'
      ] // all paths to migrations shall be configured here manually or dynamically
    ],
    'mongodb-migrate' => [
      'class'=>'app\commands\MongoMigrateController',
      'interactive' => false,
      'migrationLookup'=>[] // all paths to migrations shall be configured here manually or dynamically
    ]
  ]
];
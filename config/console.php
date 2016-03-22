<?php
/**
 * Created by PhpStorm.
 * User: tashik
 * Date: 22.03.16
 * Time: 14:06
 */

return [
  'id' => 'dbmigrator',
  'controllerMap' => [
    'migrate'=>[
      'class'=>'app\commands\MigrateController',
      'migrationLookup'=>[
        '@migrations',
        // add other migration path here
      ]
    ]
  ]
];
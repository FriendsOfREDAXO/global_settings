<?php


$curDir = __DIR__;
//require_once $curDir . '/extensions/extension_cleanup.php';

//rex_global_settings_cleanup(['force' => true]);

rex_global_settings::deleteCache();

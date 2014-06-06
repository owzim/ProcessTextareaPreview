<?php

spl_autoload_register(function ($className) {

	$classNameArray = explode("\\", $className);
	$className      = $classNameArray[count($classNameArray)-1];
	$dirName        = dirname(__FILE__);
	$file           = "{$dirName}/../classes/{$className}.php";

    if(file_exists($file)) {
        include $file;
    }
});
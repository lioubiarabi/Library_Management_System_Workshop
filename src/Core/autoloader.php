<?php

spl_autoload_register(function ($class) {
    $directories = [
        'src/Core/',
        'src/Entities/',
        'src/Models/',
        'src/Services/',
        'src/Controllers/',
    ];

    foreach ($directories as $dir) {
        $file = __DIR__ . '/' . $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
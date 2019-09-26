<?php

spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    autoload(end($parts), __DIR__.'/src/');
});

function autoload($class, $dir) {
    if (file_exists($dir . $class . '.php')) {
        include $dir . $class . '.php';
        return true;
    }else{
        if(is_array(scandir($dir))) {
            foreach (scandir($dir) as $item) {
                if (is_dir($dir.$item) && !in_array($item, ['.', '..'])) {
                    if(autoload($class, $dir.$item.'/')){
                        break;
                    }
                }
            }
        }
    }
}

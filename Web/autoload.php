<?php

/**
 * @param $classname
 */
function autoload($classname)
{
    if (file_exists($file = __DIR__ . '/' . $classname . '.php'))
    {
        require $file;
    }
    else if (file_exists($file = __DIR__ . '/../Models/' . $classname . '.php'))
    {
        require $file;
    }
    else if (file_exists($file = __DIR__ . '/../Controller/' . $classname . '.php'))
    {
        require $file;
    }
    else if (file_exists($file = __DIR__ . '/../Vues/' . $classname . '.php'))
    {
        require $file;
    }
}

spl_autoload_register('autoload');
<?php

namespace App;

use ReflectionClass;

class App
{
    /** @var array */
    private static $bindings = [];

    
    public static function set($abstract, callable $binding) 
    {
        self::$bindings[$abstract] = $binding;
    }
    
    public static function get($abstract) {
        if(isset(self::$bindings[$abstract])) {
            return self::$bindings[$abstract](self::class);
        }

        $reflection = new ReflectionClass($abstract);
        $arguments = self::getReflectionParameters($reflection);
        return $reflection->newInstanceArgs($arguments);
    }

    private static function getReflectionParameters(ReflectionClass $reflection) {
        if(!$constructor = $reflection->getConstructor()) {
            return [];
        }
        $parameters = $constructor->getParameters();

        return array_map(function($param) {
            
            return self::get($param->getType()->getName());
        }, $parameters);
    }
}
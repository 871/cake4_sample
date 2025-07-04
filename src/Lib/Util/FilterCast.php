<?php

declare(strict_types=1);

namespace App\Lib\Util;

class FilterCast
{
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toString(mixed $val) : ?string
    {
        if (is_string($val) || is_int($val) || is_float($val)) {
            
            return (string) $val;
        }

        if (is_object($val) && method_exists($val, '__toString')) {

            return (string) $val;
        }

        return null;
    }
    
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toLikeString(mixed $val) : ?string
    {
        if (is_string($val) || is_int($val) || is_float($val)) {

            return '%' . preg_replace('/(?=(%|_))/', '\\', (string) $val) . '%';
        }

        if (is_object($val) && method_exists($val, '__toString')) {
            
            return '%' . preg_replace('/(?=(%|_))/', '\\', (string) $val) . '%';
        }

        return null;
    }
    
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toForwardLikeString(mixed $val) : ?string
    {
        if (is_string($val) || is_int($val) || is_float($val)) {

            return '%' . preg_replace('/(?=(%|_))/', '\\', (string) $val);
        }

        if (is_object($val) && method_exists($val, '__toString')) {
            
            return '%' . preg_replace('/(?=(%|_))/', '\\', (string) $val);
        }

        return null;
    }
    
    
    
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toBackwardLikeString(mixed $val) : ?string
    {
        if (is_string($val) || is_int($val) || is_float($val)) {

            return preg_replace('/(?=(%|_))/', '\\', (string) $val) . '%';
        }

        if (is_object($val) && method_exists($val, '__toString')) {
            
            return preg_replace('/(?=(%|_))/', '\\', (string) $val) . '%';
        }

        return null;
    }

    /**
     * 
     * @param mixed $val
     * @return int|null
     */
    public static function toInt(mixed $val) : ?int
    {
        if (is_int($val)) {
            
            return $val;
        }

        if (is_float($val)) {
            
            return (int) $val;
        }

        if (is_string($val) && preg_match('/^-?\d+$/', $val)) {
            
            return (int) $val;
        }

        return null;
    }
}

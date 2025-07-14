<?php

declare(strict_types=1);

namespace App\Lib\Util;

use Carbon\Carbon;
use Cake\I18n\FrozenDate;

class FilterCast
{
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toString(mixed $val) : ?string
    {
        if ($val === '' || $val === null) {
            
            return null;
        }

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
        if ($val === '' || $val === null) {
            
            return null;
        }
        
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
        if ($val === '' || $val === null) {
            
            return null;
        }
        
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
        if ($val === '' || $val === null) {
            
            return null;
        }
        
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
    
    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toArray(mixed $val) : ?array
    {
        if ($val === '' || $val === null) {
            
            return null;
        }

        return (array) $val;
    }

    /**
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function toDatetimeString(mixed $val) : ?string
    {
        if ($val === '' || $val === null) {
            
            return null;
        }
        
        if ($val instanceof Carbon) {
            
            return $val->toDateTimeString();
        }
        
        if ($val instanceof FrozenDate) {
            
            return $val->toDateTimeString();
        }

        if (is_string($val) && preg_match('/\A(19|20)\d\d[\-\/](0[1-9]|1[0-2])[\-\/](0[1-9]|[12]\d|3[01])[\sT]([01]\d|2[0-3]):([0-5]\d):([0-5]\d)\z/', $val)) {
            
            return $val;
        }

        return null;
    }
    
}

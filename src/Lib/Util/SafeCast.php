<?php

declare(strict_types=1);

namespace App\Lib\Util;

class SafeCast
{
    /**
     * 
     * @param mixed $val
     * @return string
     */
    public static function toString(mixed $val) : string
    {
        if (is_string($val) || is_int($val) || is_float($val)) {
            
            return (string) $val;
        }

        if (is_object($val) && method_exists($val, '__toString')) {

            return (string) $val;
        }

        self::throwException($val);
    }
    
    /**
     *
     * 
     * @param mixed $val
     * @return int
     */
    public static function toInt(mixed $val) : int
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

        self::throwException($val);
    }

    /**
     *
     * 
     * @param mixed $val
     * @return array
     */
    public static function toArray(mixed $val) : array
    {
        if (is_array($val)) {
            
            return $val;
        }

        self::throwException($val);
    }
    
    
    /**
     * 
     * 
     * @param mixed $val
     */
    private static function throwException(mixed $val)
    {
        $trace = debug_backtrace();
        $method = $trace[1]['function']; // クラス外から呼び出されたpublic method名
        
        $file = $trace[2]['file']; // public メソッドの呼び出元のファイル名
        $line = $trace[2]['line']; // public メソッドの呼び出元の行数
        
        throw new Exception(
            'SafeCast::' . $method . '() Error'
            . '[File Path: ' . $file . ']'
            . '[Line: ' . (string) $line . ']'
            . '[' . print_r($val, true) . ']'
        );
    }
}

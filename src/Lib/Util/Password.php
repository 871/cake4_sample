<?php

declare(strict_types=1);

namespace App\Lib\Util;


class Password
{
    /**
     * 
     * @param int $length
     * @return self
     */
    public static function create(int $length = 12) : self
    {
        $list = array_merge(range('0', '9'), range('a', 'z'), range('A', 'Z'), [
            '!', '#', '$', '%', '&', '-', '@', ';', ':', '.', '~', '*', '?'
        ]);
        $max = count($list) - 1;
        
        $result = join('', array_map(fn() => $list[rand(0, $max)], range(1, $length)));

        return self::checkPassword($result) ? $result : self::createPassword($length);
    }
    
    /**
     * 
     * @param string $password
     * @return bool
     */
    public static function checkFormat(string $password) : bool
    {
        return mb_strlen($password) >= 8
            && preg_match('/[0-9]/', $password)
            && preg_match('/[a-z]/', $password)
            && preg_match('/[A-Z]/', $password)
            && preg_match('/[!#$%&\-@;:.~*?]/', $password)
            ;
    }
    
    /**
     * 
     * @param string|null $label
     * @return string
     */
    public static function formatErrorMsg(?string $label = null) : string
    {
        return __('{0}は8文字以上、大文字英字、小文字英字、数字、記号[!#$%&-@;:.~*?]を含む文字列で設定してください。', $label ?? __('パスワード'));
    }
}

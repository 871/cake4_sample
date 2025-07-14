<?php

declare(strict_types=1);

namespace App\Lib\Util\QueryUtil;

use App\Lib\Util\FilterCast;


class Where
{
    /**
     * 
     * @param string $keyword
     * @param array $fields
     * @param string $separator
     * @return array
     */
    public static function createLikeList(string $keyword, array $fields, string $separator = ' ') : array
    {
        $keywords = array_filter(explode($separator, $keyword), fn($val) => $val !== '');

        $results = [];            
        foreach ($keywords as $value) {

            foreach ($fields as $field) {

                $results[$field . ' LIKE'] = FilterCast::toLikeString($value);
            }
        }

        return $results;
    }
}

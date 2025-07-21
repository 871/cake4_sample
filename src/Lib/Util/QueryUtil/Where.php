<?php

declare(strict_types=1);

namespace App\Lib\Util\QueryUtil;

use App\Lib\Util\FilterCast;


class Where
{
    /**
     * $keyword
     *    'xxx yyy zzz'
     * 
     * $fields
     *    [
     *        'HogeTable.aaa',
     *        'HogeTable.bbb',
     *    ]
     * 
     * return 
     *    [
     *         [
     *             'HogeTable.aaa LIKE' => '%xxx%'
     *         ],
     *         [
     *             'HogeTable.aaa LIKE' => '%yyy%'
     *         ],
     *         [
     *             'HogeTable.aaa LIKE' => '%zzz%'
     *         ],
     *         [
     *             'HogeTable.bbb LIKE' => '%xxx%'
     *         ],
     *         [
     *             'HogeTable.bbb LIKE' => '%yyy%'
     *         ],
     *         [
     *             'HogeTable.bbb LIKE' => '%zzz%'
     *         ],
     *    ]
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
        
        foreach ($fields as $field) {
            
            foreach ($keywords as $value) {

                $results[] = [
                    $field . ' LIKE' => FilterCast::toLikeString($value),
                ];
            }
        }

        return $results;
    }
}

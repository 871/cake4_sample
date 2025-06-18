<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;


use Cake\ORM\Query;


trait AdminActionSearchTrait
{
    
    /**
     * 
     * @return Query
     */
    public abstract function getSearchQuery() : Query;

    /**
     * 
     * @return array
     */
    public function getPaginateSetting() : array
    {
        return [
            'limit' => 50,
            'maxLimit' => 100,
            'page' => 1,
            'order' => [
                'modified' => 'desc'
            ], 
        ];
    }
}
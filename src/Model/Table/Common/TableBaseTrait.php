<?php
declare(strict_types=1);

namespace App\Model\Table\Common;

use Cake\ORM\TableRegistry;

trait TableBaseTrait
{
    /**
     * 
     * @return static
     */
    public static function getInstance() : static 
    {
        return TableRegistry::getTableLocator()->get(static::class);
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Table\Common;

use Cake\ORM\TableRegistry;
use Cake\Event\EventInterface;
use ArrayObject;

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
    
    /**
     * saveメソッドで空文字をNULLとして保存ずるための処理
     * 
     * @param EventInterface $event
     * @param ArrayObject $data
     * @param ArrayObject $options
     */
    public function beforeMarshal(EventInterface $event, ArrayObject $data, ArrayObject $options)
    {
        foreach ($data as $key => $value) {
            
            if (is_string($value)) {
                
                $data[$key] = trim($value) === '' ? null : trim($value);
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use App\Action\Admin\Common\AdminExclusiveControl;
use App\Action\Admin\Common\AdminInput;
use App\Action\Admin\Common\AdminInitInput;
use App\Action\Admin\Common\AdminError;
use Cake\Utility\Hash;


trait AdminActionInputTrait
{
    use AdminActionBaseTrait;
        
    /**
     * 
     * @return self
     */
    public function lockInput() : self
    {
        AdminExclusiveControl::getInstance(self::class, $this->serverRequest)->lock();

        return $this;
    }

    /**
     * 
     * @return self
     */
    public function unlockInput() : self
    {
        AdminExclusiveControl::getInstance(self::class, $this->serverRequest)->unlock();

        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public abstract function initializeInput(string $input_id) : self;

    /**
     * 
     * @return self
     */
    public abstract function updateInput() : self;
    
    
    /**
     * 
     * @return bool
     */
    public function checkInput() : bool
    {
        return $this->serverRequest->getParam('input_id')
            && AdminInput::getInstance(self::class, $this->serverRequest)->check();
    }

    /**
     * 
     * @return array
     */
    public function getInput() : array
    {
        return AdminInput::getInstance(self::class, $this->serverRequest)->read();
    }
    
    /**
     * 
     * @return self
     */
    public function deleteInput() : self
    {
        AdminInput::getInstance(self::class, $this->serverRequest)->delete();

        AdminInitInput::getInstance(self::class, $this->serverRequest)->delete();

        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function resetErrors() : self
    {
        AdminError::getInstance(self::class, $this->serverRequest)
            ->delete();

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getErrorMessages() : array
    {
        $errors = (array) AdminError::getInstance(self::class, $this->serverRequest)
            ->read();
        
        return Hash::flatten($errors);
    }

    /**
     * 
     * @return array
     */
    public function getErrorClasses() : array
    {
        $errors = (array) AdminError::getInstance(self::class, $this->serverRequest)
            ->read();
        
        return array_map(function() {
            
            return 'message error';
        }, $errors);
    }
    
    /**
     * 
     * @return self
     */
    public abstract function runValidate() : self;
    
    /**
     * 
     * @return self
     */
    public abstract function save() : self;
}
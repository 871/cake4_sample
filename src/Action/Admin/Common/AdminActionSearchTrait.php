<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;


use Cake\ORM\Query;
use Cake\Controller\Controller;
use Cake\Utility\Hash;
use Cake\Validation\Validation;


trait AdminActionSearchTrait
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @var Controller
     */
    private Controller $ctl;
    
    /**
     * 
     * @var array
     */
    private array $results = [];

    /**
     * 
     * @var array
     */
    private array $errors = [];
    
    /**
     * 
     * @return array
     */
    public function initSearchQuery() : array
    {
        return [];
    }

    /**
     * 
     * @return self
     */
    public function runValidate() : self
    {
        $this->errors = $this
            ->createValidator()
            ->validate($this->serverRequest->getQuery());
        
        if ($this->errors !== []) {
    
            throw new ValidateException();
        }
        
        return $this;
    }
    
    /**
     * 
     * @param Controller $ctl
     * @return self
     */
    public function setCtl(Controller $ctl) : self
    {
        $this->ctl = $ctl;
        
        return $this;
    }

    /**
     * 
     * @return self
     */
    public function execute() : self
    {
        $query = $this->getSearchQuery();
        $setting = $this->getPaginateSetting();
        
        $this->results = $this->ctl
            ->paginate($query, $setting)
            ->toArray();
        
        return $this;
    }
    
    /**
     * 
     * @return array
     */
    public function getResults() : array
    {
        return $this->results;
    }
    
    /**
     * 
     * @return array
     */
    public function getErrorMessages() : array
    {   
        return Hash::flatten($this->errors);
    }

    /**
     * 
     * @return array
     */
    public function getErrorClasses() : array
    {
        return array_map(function() {
            
            return 'message error';
        }, $this->errors);
    }
    
    /**
     * 
     * @return Validation
     */
    private function createValidator() : Validation
    {
        return new Validation();
    }
    
    /**
     * 
     * @return Query
     */
    private abstract function getSearchQuery() : Query;

    /**
     * 
     * @return array
     */
    private function getPaginateSetting() : array
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
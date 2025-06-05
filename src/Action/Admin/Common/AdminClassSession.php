<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;

class AdminClassSession
{
    /**
     * 
     * @var ServerRequest
     */
    private ServerRequest $serverRequest;

    /**
     * 
     * @var string
     */
    private string $session_key;

    /**
     * 
     * @param string $className
     * @param ServerRequest $serverRequest
     * @return self
     */
    public static function getInstance(string $className, ServerRequest $serverRequest, ?string $tmp_id = null) : self
    {
        $session_key = join('.', array_filter([
            $serverRequest->getParam('admin_account_id', 'ad_other'),
            Inflector::camelize($className),
            $serverRequest->getParam('tmp_id', $tmp_id),
        ]));
        
        static $insList = [];
        if ($insList[$session_key] ?? null === null) {
            
            $insList[$session_key] = new self($session_key, $serverRequest);
        }

        return $insList[$session_key];
    }

    /**
     * 
     * @param string $session_key
     * @param ServerRequest $serverRequest
     * @return self
     */
    private function __construct(string $session_key, ServerRequest $serverRequest)
    {
        $this->serverRequest = $serverRequest;
        $this->session_key = $session_key;
    }

    /**
     * 
     * @param mixed $params
     * @return self
     */
    public function write(mixed $params) : self
    {
        $this->serverRequest->getSession()->write($this->session_key, $params);
        
        return $this;
    }

    /**
     * 
     * @return bool
     */
    public function check() : bool
    {   
        return $this->serverRequest->getSession()->check($this->session_key);
    }

    /**
     * 
     * @return mixed
     */
    public function read() : mixed
    {   
        return $this->serverRequest->getSession()->read($this->session_key);
    }

    /**
     * 
     * @return self
     */
    public function delete() : self
    {
        $this->serverRequest->getSession()->delete($this->session_key);
        
        return $this;
    }
}
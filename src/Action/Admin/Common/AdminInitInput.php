<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;
use Exception;

class AdminInitInput
{
    use AdminSessionTrait;
    
    private const PREFIX = 'admin_init_input';

    /**
     * 
     * @param string $className
     * @param ServerRequest $serverRequest
     * @return self
     */
    public static function getInstance(string $className, ServerRequest $serverRequest, ?string $input_id = null) : self
    {
        $session_key = join('.', array_filter([
            self::PREFIX,
            (string) $serverRequest->getParam('admin_account_id', 'ad_other'),
            Inflector::camelize($className),
            $serverRequest->getParam('input_id', $input_id),
        ]));
        
        static $insList = [];
        if ($insList[$session_key] ?? null === null) {
            
            $insList[$session_key] = new self($session_key, $serverRequest);
        }

        return $insList[$session_key];
    }

    /**
     * 
     * @param ServerRequest $serverRequest
     * @return void
     */
    public static function destroy(ServerRequest $serverRequest) : void
    {
        $session_key = join('.', array_filter([
            self::PREFIX,
            (string) $serverRequest->getParam('admin_account_id', 'ad_other'),
        ]));
        
        $serverRequest->getSession()->delete($session_key);
    }

    /**
     * 
     * @param mixed $params
     * @return self
     */
    public function write(mixed $params) : self
    {
        if ($this->check()) {
            
            throw new Exception(
                'Init Input Params Over Write Error',
                '[session_key: ' . $this->session_key . ']',
                '[Over Write Params: ' . print_r($params, true) . ']',
                '[Old Params: ' . print_r($this->read(), true) . ']',
            );
        }
        
        $this->serverRequest->getSession()->write($this->session_key, $params);
        
        return $this;
    }
}
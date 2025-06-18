<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;

class AdminInput
{
    use AdminSessionTrait;
    
    private const PREFIX = 'admin_input';

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
}
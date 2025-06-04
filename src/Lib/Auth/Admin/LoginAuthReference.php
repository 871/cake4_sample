<?php

declare(strict_types=1);

namespace App\Lib\Auth\Admin;

use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use Cake\Http\ServerRequest;


/**
 * 
 * 管理者用ログイン判定クラス
 */
class LoginAuthReference
{   
    /**
     * 
     * @var Carbon
     */
    private Carbon $currentDatetime;

    /**
     * 
     * @var ServerRequest
     */
    private ServerRequest $serverRequest;
    
    /**
     * 
     * @var AdminAuthInterface
     */
    private AdminAuthInterface $adminAuth;

    /**
     * 
     * @return self
     */
    public static function getInstance(Carbon $currentDatetime, ServerRequest $serverRequest) : self
    {
        return new self($currentDatetime, $serverRequest);
    }

    /**
     * 
     * @return self
     */
    private function __construct(Carbon $currentDatetime, ServerRequest $serverRequest)
    {
        $this->currentDatetime = $currentDatetime;
        $this->serverRequest = $serverRequest;
    }

    /**
     * 
     * @return self
     */
    public function execute() : self
    {   
        return $this
            ->loadAdminLoginAuth()
            ;
    }
    
    /**
     * 
     * @return AdminAuthInterface
     */
    public function getResult() : AdminAuthInterface
    {
        return $this->adminAuth;
    }

    /**
     * 
     * @return self
     */
    private function loadAdminLoginAuth() : self
    {
        $authSessionKey = ADMIN_AUTH_KEY . '.' . (string) $this->serverRequest->getParam('admin_account_id');

        $this->adminAuth = $this->serverRequest->getSession()->read($authSessionKey);

        return $this;
    }
}
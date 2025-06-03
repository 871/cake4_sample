<?php

declare(strict_types=1);

namespace App\Lib\Auth\Admin;

use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use Exception;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;
use App\Model\Table\Admin\AdminAccountsTable;
use App\Model\Table\Login\LoginErrorMastersTable;
use App\Lib\Util\UUID;
use App\Lib\Auth\Admin\Auth\LoginAuth as AdminLoginAuth;


/**
 * 
 * 管理者用ログイン判定クラス
 */
class LoginAuthRefresh
{
    /**
     * 
     * @var AdminAccountsTable
     */
    private AdminAccountsTable $adminAccountsTable;
    
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
     * @var array
     */
    private array $admin_account;

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

        $this->adminAccountsTable = AdminAccountsTable::getInstance();
    }

    /**
     * 
     * @return self
     */
    public function execute() : self
    {   
        return $this
            ->loadAdminAccount()
            
            ;
    }

    /**
     * 
     * @return self
     */
    private function loadAdminAccount() : self
    {
        $this->admin_account = $this->adminAccountsTable
            ->find()
            ->where([
                'id' => $this->serverRequest->getParam('admin_account_id'),
            ])
            ->first()
            ?->toArray() ?? throw new Exception(
                'adminAccountsTable data not fund'
                . '[id: ' . (string) $this->serverRequest->getParam('admin_account_id') . ']'
            );

        return $this;
    }
}
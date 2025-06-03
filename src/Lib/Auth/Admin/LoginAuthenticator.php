<?php

declare(strict_types=1);

namespace App\Lib\Auth\Admin;

use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Exception\AuthException;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;
use App\Model\Table\Admin\AdminAccountsTable;
use App\Model\Table\Login\LoginAdminFailuresLogsTable;
use App\Model\Table\Login\LoginAdminSuccessLogsTable;
use App\Model\Table\Login\LoginErrorMastersTable;
use App\Lib\Util\UUID;
use App\Lib\Auth\Admin\Auth\LoginAuth as AdminLoginAuth;


/**
 * 
 * 管理者用ログイン判定クラス
 */
class LoginAuthenticator
{
    const LIMIT_LOGIN_FAILURES_COUNT = 5;
    
    /**
     * 
     * @var AdminAccountsTable
     */
    private AdminAccountsTable $adminAccountsTable;
    
    /**
     * 
     * @var LoginAdminFailuresLogsTable
     */
    private LoginAdminFailuresLogsTable $loginAdminFailuresLogsTable;
    
    /**
     * 
     * @var LoginAdminSuccessLogsTable
     */
    private LoginAdminSuccessLogsTable $loginAdminSuccessLogsTable;
    
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
     * @var string
     */
    private string $username;
    
    /**
     * 
     * @var string
     */
    private string $password;

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
        $this->loginAdminFailuresLogsTable = LoginAdminFailuresLogsTable::getInstance();
        $this->loginAdminSuccessLogsTable = LoginAdminSuccessLogsTable::getInstance();
    }

    /**
     * 
     * @param string $username
     * @return self
     */
    public function setUsername(string $username) : self
    {
        $this->username = $username;
        
        return $this;
    }

    /**
     * 
     * @param string $password
     * @return self
     */
    public function setPassword(string $password) : self
    {
        $this->password = $password;
        
        return $this;
    }

    /**
     * 
     * @return self
     */
    public function execute() : self
    {
        try {
            $this
                ->checkNotEmpty()
                ->checkFailuresCount()
                ->loadAdminAccount()
                ->checkIsActive()
                ->checkPassword()
                ->checkExpirationDatetime()
                ->saveLoginSuccessLog()
                ;
        } catch (AuthException $ex) {

            $this
                ->saveLoginFailureLog($ex);
            
            throw $ex;
        }
        
        return $this;
    }

    /**
     * 
     * @return AdminAuthInterface
     */
    public function getResult() : AdminAuthInterface
    {
        return new AdminLoginAuth($this->admin_account);
    }

    /**
     * 
     * @return self
     */
    private function checkNotEmpty() : self
    {
        $this->username !== '' ? : throw new AuthException(__('ログインIDを入力してください。'));
        $this->password !== '' ? : throw new AuthException(__('パスワードを入力してください。'));

        return $this;
    }

    /**
     * 
     * @return self
     */
    private function checkFailuresCount() : self
    {
        $cnt = $this->loginAdminFailuresLogsTable
            ->find()
            ->where([
                'LoginAdminFailuresLogs.username' => $this->username,
                'LoginAdminFailuresLogs.login_datetime >' 
                    => $this->currentDatetime->clone()->subMinutes(30)->toDateTimeString(),
                "LoginAdminFailuresLogs.login_datetime > COALESCE(("
                . " SELECT MAX(T1.login_datetime)"
                . " FROM login_admin_success_logs AS T1"
                . " WHERE LoginAdminFailuresLogs.username = T1.username"
                . "), '1970-01-01 00:00:00')",
            ])
            ->count();

        sleep($cnt);
        
        if ($cnt > self::LIMIT_LOGIN_FAILURES_COUNT) {
            
            throw new AuthException(__('ログイン失敗回数が規定値に達したため、アカウントがロックされました。'), LoginErrorMastersTable::ID_FAILURE_COUNT_OVER);
        }

        return $this;
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
                'username' => $this->username,
            ])
            ->first()
            ?->toArray() ?? throw new AuthException(__('ログインIDもしくはパスワードが異なります。'), LoginErrorMastersTable::ID_ACCOUNT_NOT_FUND);

        return $this;
    }

    /**
     * 
     * @return self
     */
    private function checkIsActive() : self
    {
        $this->admin_account['is_active'] 
            ? : throw new AuthException(__('ログインIDもしくはパスワードが異なります。'), LoginErrorMastersTable::ID_ACCOUNT_IS_NOT_ACTIVE);
        
        return $this;
    }

    /**
     * 
     * @return self
     */
    private function checkPassword() : self
    {
        (new DefaultPasswordHasher())->check($this->password, $this->admin_account['password'])
            ? : throw new AuthException(__('ログインIDもしくはパスワードが異なります。'), LoginErrorMastersTable::ID_PASSWORD_NOT_MATCH);

        return $this;
    }

    /**
     * 
     * @return self
     */
    private function checkExpirationDatetime() : self
    {
        $exp = Carbon::parse($this->admin_account['expiration_datetime']?->toDateTimeString() ?? '1970-01-01 00:00:00');
        
        $exp->greaterThan($this->currentDatetime)
            ? : throw new AuthException(__('パスワードの有効期限が切れています。'), LoginErrorMastersTable::ID_PASSWORD_EXPIRATION_OVER);

        return $this;
    }

    /**
     * 
     * @return self
     */
    private function saveLoginSuccessLog() : self
    {
        $new = $this->loginAdminSuccessLogsTable->newEntity([
            'id' => UUID::uuid7(),
            'username' => $this->username,
            'login_datetime' => $this->currentDatetime->toDateTimeString(),
            'created' => $this->currentDatetime->toDateTimeString(),
            'created_account_id' => null,
            'created_ip' => $this->serverRequest->clientIp(),
        ]);
        
        if (!$this->loginAdminSuccessLogsTable->save($new, [
            'checkExisting' => false,
        ])) {
            
            throw new Exception(
                    'LoginAdminSuccessLogsTable Save Error'
                    . '[' . print_r($new, true) . ']'
                );
        }
        
        return $this;
    }

    /**
     * 
     * @return self
     */
    private function saveLoginFailureLog(AuthException $ex) : self
    {
        if ($ex->getCode() === 0) {
            
            return $this;
        }
        
        $new = $this->loginAdminFailuresLogsTable->newEntity([
            'id' => UUID::uuid7(),
            'username' => $this->username,
            'login_error_master_id' => $ex->getCode(),
            'login_error_message' => $ex->getMessage(),
            'login_datetime' => $this->currentDatetime->toDateTimeString(),
            'created' => $this->currentDatetime->toDateTimeString(),
            'created_account_id' => null,
            'created_ip' => $this->serverRequest->clientIp(),
        ]);
        
        if (!$this->loginAdminFailuresLogsTable->save($new, [
            'checkExisting' => false,
        ])) {
            
            throw new Exception(
                    'LoginAdminFailuresLogsTable Save Error'
                    . '[' . print_r($new, true) . ']'
                );
        }
        
        return $this;
    }
}
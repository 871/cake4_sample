<?php
declare(strict_types=1);

namespace App\Action\Admin\UserAccounts;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionSearchTrait;
use App\Model\Table\User\UserAccountsTable;
use Cake\ORM\Query;
use App\Lib\Util\SafeCast;
use Cake\Validation\Validator;

class SearchAction implements AdminActionInterface
{
    use AdminActionSearchTrait;
    
    /**
     * 
     * @var UserAccountsTable
     */
    private UserAccountsTable $userAccountsTable;
    
    /**
     * 
     */
    private function __construct()
    {
        $this->userAccountsTable = UserAccountsTable::getInstance();
    }
    
    /**
     * 
     * @return Validator
     */
    private function createValidator() : Validator
    {
        return (new Validator())
            ->addNested('user_accounts', (function() {
            
                return (new Validator())
                    ->allowEmptyString('id')
                    ->naturalNumber('id', __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    ->greaterThanOrEqual('id', UserAccountsTable::MIN_USER_ACCOUNT_ID, __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    ->lessThanOrEqual('id', UserAccountsTable::MAX_USER_ACCOUNT_ID, __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    
                        
                    
                    ;
            
            })())
            
        
            ;
    }
    
    /**
     * 
     * @return Query
     */
    private function getSearchQuery() : Query
    {
        $params = SafeCast::toArray($this->serverRequest->getQuery('user_accounts', []));
        
        return $this->userAccountsTable
            ->find()
            ->select([
                'id' => 'UserAccounts.id',
                'name' => 'UserAccounts.name',
                'username' => 'UserAccounts.username',
                'email' => 'UserAccounts.email',
                'tel' => 'UserAccounts.tel',
                'is_active' => 'UserAccounts.is_active',
                'expiration_datetime' => 'UserAccounts.expiration_datetime',
                'is_tmp_password' => 'UserAccounts.is_tmp_password',
                'created' => 'UserAccounts.created',
                'modified' => 'UserAccounts.modified',
                'created_account_id' => 'UserAccounts.created_account_id',
                'modified_account_id' => 'UserAccounts.modified_account_id',
                'created_ip' => 'UserAccounts.created_ip',
                'modified_ip' => 'UserAccounts.modified_ip',
            ])
            ->where(array_filter([
                'UserAccounts.id' =>$params['id'] ?? null,
                'UserAccounts.name LIKE' => $params['name'] ?? null,
                'UserAccounts.username LIKE' => $params['username'] ?? null,
                'UserAccounts.email LIKE' => $params['email'] ?? null,
                'UserAccounts.tel LIKE' => $params['tel'] ?? null,
                'UserAccounts.is_active IN' => $params['is_active'] ?? [],
                'UserAccounts.expiration_datetime >=' => $params['expiration_datetime_from'] ?? null,
                'UserAccounts.expiration_datetime <=' => $params['expiration_datetime_to'] ?? null,
                'UserAccounts.is_tmp_password' => $params['is_tmp_password'] ?? [],
                'UserAccounts.created >=' => $params['created_from'] ?? null,
                'UserAccounts.created <=' => $params['created_to'] ?? null,
                'UserAccounts.modified >=' => $params['modified_from'] ?? null,
                'UserAccounts.modified <=' => $params['modified_to'] ?? null,
            ], fn($v) => !in_array($v, [null, '', []], true)));
    }
    
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
            'sortableFields' => [
                'UserAccounts.id',
                'UserAccounts.name',
                'UserAccounts.username',
                'UserAccounts.email',
                'UserAccounts.tel',
                'UserAccounts.is_active',
                'UserAccounts.expiration_datetime',
                'UserAccounts.is_tmp_password',
                'UserAccounts.created',
                'UserAccounts.modified',
            ],
        ];
    }
}

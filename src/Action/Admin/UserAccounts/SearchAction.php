<?php
declare(strict_types=1);

namespace App\Action\Admin\UserAccounts;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionSearchTrait;
use App\Model\Table\User\UserAccountsTable;
use Cake\ORM\Query;
use App\Lib\Util\SafeCast;
use Cake\Validation\Validator;
use App\Lib\Util\FilterCast;
use App\Lib\Util\QueryUtil;


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
                    // ユーザアカウントID
                    ->allowEmptyString('id')
                    ->naturalNumber('id', __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    ->greaterThanOrEqual('id', UserAccountsTable::MIN_USER_ACCOUNT_ID, __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    ->lessThanOrEqual('id', UserAccountsTable::MAX_USER_ACCOUNT_ID, __('{0}は{1}-{2}の整数を入力してください。', __('ユーザアカウントID'), UserAccountsTable::MIN_USER_ACCOUNT_ID, UserAccountsTable::MAX_USER_ACCOUNT_ID))
                    // ユーザ名
                    ->allowEmptyString('name_like')
                    ->maxLength('name_like', 50, __('{0}は{1}文字以内で入力してください。', 'ユーザ名', 50))
                    // アカウント名
                    ->allowEmptyString('username_like')
                    ->maxLength('username_like', 50, __('{0}は{1}文字以内で入力してください。', 'アカウント名', 50))
                    // メールアドレス
                    ->allowEmptyString('email_like')
                    ->maxLength('email_like', 255, __('{0}は{1}文字以内で入力してください。', 'メールアドレス', 255))
                    // 電話番号
                    ->allowEmptyString('tel_like')
                    ->maxLength('tel_like', 20, __('{0}は{1}文字以内で入力してください。', '電話番号', 20))
                    // ログイン可否
                    ->allowEmptyArray('is_active')
                    ->multipleOptions('is_active', [
                        'in' => [
                            '0', '1',
                        ]
                    ], __('{0}の入力が不正です。', 'ログイン可否'))
                    // 仮PW設定
                    ->allowEmptyArray('is_tmp_password')
                    ->multipleOptions('is_tmp_password', [
                        'in' => [
                            '0', '1',
                        ]
                    ], __('{0}の入力が不正です。', '仮PW設定'))
                    // PW有効期限(From)
                    ->allowEmptyDateTime('expiration_datetime_from')
                    ->dateTime('expiration_datetime_from', ['ymd'], __('{0}は日時を入力してください。', 'PW有効期限(From)'))
                    // PW有効期限(To)
                    ->allowEmptyDateTime('expiration_datetime_to')
                    ->dateTime('expiration_datetime_to', ['ymd'], __('{0}は日時を入力してください。', 'PW有効期限(To)'))
                    // 作成日時(From)
                    ->allowEmptyDateTime('created_from')
                    ->dateTime('created_from', ['ymd'], __('{0}は日時を入力してください。', '作成日時(From)'))
                    // 作成日時(To)
                    ->allowEmptyDateTime('created_to')
                    ->dateTime('created_to', ['ymd'], __('{0}は日時を入力してください。', '作成日時(To)'))
                    // 更新日時(From)
                    ->allowEmptyDateTime('modified_from')
                    ->dateTime('modified_from', ['ymd'], __('{0}は日時を入力してください。', '更新日時(From)'))
                    // 更新日時(To)
                    ->allowEmptyDateTime('modified_to')
                    ->dateTime('modified_to', ['ymd'], __('{0}は日時を入力してください。', '更新日時(To)'))
                    // キーワード
                    ->allowEmptyString('keyword')
                    ->maxLength('keyword', 255, __('{0}は{1}文字以内で入力してください。', 'キーワード', 255))
                    
                    ;
            
            })())
            
        
            ;
    }
    
    /**
     * 
     * @return Query
     */
    private function createSearchQuery() : Query
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
                'remarks' => 'LEFT(UserAccounts.remarks, 101)',
                'created' => 'UserAccounts.created',
                'modified' => 'UserAccounts.modified',
                'created_account_id' => 'UserAccounts.created_account_id',
                'modified_account_id' => 'UserAccounts.modified_account_id',
                'created_ip' => 'UserAccounts.created_ip',
                'modified_ip' => 'UserAccounts.modified_ip',
            ])
            ->where(array_filter([
                'UserAccounts.id' => FilterCast::toInt($params['id'] ?? null),
                'UserAccounts.name LIKE' => FilterCast::toLikeString($params['name_like'] ?? null),
                'UserAccounts.username LIKE' => FilterCast::toLikeString($params['username_like'] ?? null),
                'UserAccounts.email LIKE' => FilterCast::toLikeString($params['email_like'] ?? null),
                'UserAccounts.tel LIKE' => FilterCast::toLikeString($params['tel_like'] ?? null),
                'UserAccounts.is_active IN' => FilterCast::toArray($params['is_active'] ?? []),
                'UserAccounts.expiration_datetime >=' => FilterCast::toDatetimeString($params['expiration_datetime_from'] ?? null),
                'UserAccounts.expiration_datetime <=' => FilterCast::toDatetimeString($params['expiration_datetime_to'] ?? null),
                'UserAccounts.is_tmp_password IN' => FilterCast::toArray($params['is_tmp_password'] ?? []),
                'UserAccounts.created >=' => FilterCast::toDatetimeString($params['created_from'] ?? null),
                'UserAccounts.created <=' => FilterCast::toDatetimeString($params['created_to'] ?? null),
                'UserAccounts.modified >=' => FilterCast::toDatetimeString($params['modified_from'] ?? null),
                'UserAccounts.modified <=' => FilterCast::toDatetimeString($params['modified_to'] ?? null),
                'OR' => QueryUtil\Where::createLikeList($params['keyword'] ?? '', [
                    'UserAccounts.name',
                    'UserAccounts.username',
                    'UserAccounts.email',
                    'UserAccounts.tel',
                    'UserAccounts.remarks',
                ]),
            ], fn($v) => !in_array($v, [null, '', []], true)));
    }
    
    /**
     * 
     * @return array
     */
    public function getPaginateSetting() : array
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

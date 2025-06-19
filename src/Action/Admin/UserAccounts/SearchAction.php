<?php
declare(strict_types=1);

namespace App\Action\Admin\UserAccounts;

use App\Action\Admin\Common\AdminActionInterface;
use \App\Action\Admin\Common\AdminActionSearchTrait;
use App\Model\Table\User\UserAccountsTable;
use Cake\ORM\Query;

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
     * @return Validation
     */
    private function createValidator() : Validation
    {
        return (new Validation())
        
            ;
    }
    
    /**
     * 
     * @return Query
     */
    private function getSearchQuery() : Query
    {
        $params = SafeCast::toArray($this->serverRequest->getQuery('user_accounts'));
        
        return $this->userAccountsTable
            ->find()
            ->select([
                
                /*
                1   id 主	bigint		UNSIGNED	いいえ	なし	ID	AUTO_INCREMENT	変更 変更	削除 削除	
                2	name	varchar(50)	utf8mb4_0900_ai_ci		いいえ	なし	表示名		変更 変更	削除 削除	
                3	username インデックス	varchar(50)	utf8mb4_0900_ai_ci		いいえ	なし	ログインID		変更 変更	削除 削除	
                4	password	varchar(255)	utf8mb4_0900_ai_ci		いいえ	なし	パスワード		変更 変更	削除 削除	
                5	email インデックス	varchar(255)	utf8mb4_0900_ai_ci		いいえ	なし	メールアドレス		変更 変更	削除 削除	
                6	tel	varchar(20)	utf8mb4_0900_ai_ci		はい	NULL	電話番号		変更 変更	削除 削除	
                7	is_active	int			いいえ	0	ログイン有効フラグ		変更 変更	削除 削除	
                8	expiration_datetime	datetime			いいえ	なし	パスワード有効期限		変更 変更	削除 削除	
                9	is_tmp_password	int			いいえ	0	仮PWフラグ		変更 変更	削除 削除	
                10	remarks	text	utf8mb4_0900_ai_ci		はい	NULL	備考		変更 変更	削除 削除	
                11	created	datetime			はい	NULL	作成日時		変更 変更	削除 削除	
                12	modified	datetime			はい	NULL	更新日時		変更 変更	削除 削除	
                13	created_account_id	bigint			はい	NULL	作成アカウントID		変更 変更	削除 削除	
                14	modified_account_id	bigint			はい	NULL	更新アカウントID		変更 変更	削除 削除	
                15	created_ip	varchar(100)	utf8mb4_0900_ai_ci		はい	NULL	作成IP		変更 変更	削除 削除	
                16	modified_ip
                */
            ])
            ->where(array_filter([
                'UserAccounts.id' => SafeCast::toString($params['id'] ?? null),
                
            ], fn($v) => $v !== [] || (string) $v !== ''));
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
        ];
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Table\User;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\User\UserAccount;
use App\Model\Table\Login\LoginUserSuccessLogsTable;
use App\Model\Table\Login\LoginUserFailuresLogsTable;

/**
 * 
 */
class UserAccountsTable extends Table
{
    use TableBaseTrait;
    
    public const MIN_USER_ACCOUNT_ID = 1000000001;
    public const MAX_USER_ACCOUNT_ID = 1999999999;
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('user_accounts');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(UserAccount::class);
        
        $this->addAssociations([
            'belongsTo' => [],
            'hasOne' => [],
            'hasMany' => [
                'UserAccountHistories' => [
                    'className' => UserAccountHistoriesTable::class,
                    'foreignKey' => 'user_account_id'
                ],
                'LoginUserSuccessLogs' => [
                    'className' => LoginUserSuccessLogsTable::class,
                    'foreignKey' => 'username',
                    'bindingKey' => 'username',
                ],
                'LoginUserFailuresLogs' => [
                    'className' => LoginUserFailuresLogsTable::class,
                    'foreignKey' => 'username',
                    'bindingKey' => 'username',
                ],
            ],
            'belongsToMany' => [],
        ]);
    }
}

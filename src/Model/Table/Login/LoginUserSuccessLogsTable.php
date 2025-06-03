<?php
declare(strict_types=1);

namespace App\Model\Table\Login;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\Login\LoginUserSuccessLog;
use App\Model\Table\User\UserAccountsTable;

/**
 * 
 */
class LoginUserSuccessLogsTable extends Table
{
    use TableBaseTrait;
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('login_user_success_logs');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(LoginUserSuccessLog::class);
        
        $this->addAssociations([
            'belongsTo' => [
                'AdminAccounts' => [
                    'className' => UserAccountsTable::class,
                    'foreignKey' => 'username',
                    'bindingKey' => 'username',
                ],
            ],
            'hasOne' => [],
            'hasMany' => [],
            'belongsToMany' => [],
        ]);
    }
}

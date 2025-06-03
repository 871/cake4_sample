<?php
declare(strict_types=1);

namespace App\Model\Table\Login;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\Login\LoginAdminFailuresLog;
use App\Model\Table\Admin\AdminAccountsTable;

/**
 * 
 */
class LoginAdminFailuresLogsTable extends Table
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

        $this->setTable('login_admin_failures_logs');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(LoginAdminFailuresLog::class);
        
        $this->addAssociations([
            'belongsTo' => [
                'LoginErrorMasters' => [
                    'className' => LoginErrorMastersTable::class,
                    'bindingKey' => 'login_error_master_id',
                ],
                'AdminAccounts' => [
                    'className' => AdminAccountsTable::class,
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

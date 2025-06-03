<?php
declare(strict_types=1);

namespace App\Model\Table\Admin;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\Admin\AdminAccount;
use App\Model\Table\Login\LoginAdminSuccessLogsTable;
use App\Model\Table\Login\LoginAdminFailuresLogsTable;

/**
 * 
 */
class AdminAccountsTable extends Table
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

        $this->setTable('admin_accounts');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(AdminAccount::class);
        
        $this->addAssociations([
            'belongsTo' => [],
            'hasOne' => [],
            'hasMany' => [
                'AdminAccountHistories' => [
                    'className' => AdminAccountHistoriesTable::class,
                    'foreignKey' => 'admin_account_id'
                ],
                'LoginAdminSuccessLogs' => [
                    'className' => LoginAdminSuccessLogsTable::class,
                    'foreignKey' => 'username',
                    'bindingKey' => 'username',
                ],
                'LoginAdminFailuresLogs' => [
                    'className' => LoginAdminFailuresLogsTable::class,
                    'foreignKey' => 'username',
                    'bindingKey' => 'username',
                ],
            ],
            'belongsToMany' => [],
        ]);
    }
}

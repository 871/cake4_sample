<?php
declare(strict_types=1);

namespace App\Model\Table\Admin;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\Admin\AdminAccountHistory;

/**
 * 
 */
class AdminAccountHistoriesTable extends Table
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

        $this->setTable('admin_account_histories');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(AdminAccountHistory::class);

        $this->addAssociations([
            'belongsTo' => [
                'AdminAccounts' => [
                    'className' => AdminAccountsTable::class,
                    'bindingKey' => 'admin_account_id'
                ],
            ],
            'hasOne' => [],
            'hasMany' => [
            ],
            'belongsToMany' => [],
        ]);
    }
}

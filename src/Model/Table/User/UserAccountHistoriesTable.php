<?php
declare(strict_types=1);

namespace App\Model\Table\User;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\User\UserAccountHistory;

/**
 * 
 */
class UserAccountHistoriesTable extends Table
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

        $this->setTable('user_account_histories');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
        $this->setEntityClass(AdminAccountHistory::class);

        $this->addAssociations([
            'belongsTo' => [
                'UserAccounts' => [
                    'className' => UserAccountsTable::class,
                    'bindingKey' => 'user_account_id'
                ],
            ],
            'hasOne' => [],
            'hasMany' => [
            ],
            'belongsToMany' => [],
        ]);
    }
}

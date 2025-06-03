<?php
declare(strict_types=1);

namespace App\Model\Table\Login;


use Cake\ORM\Table;
use App\Model\Table\Common\TableBaseTrait;
use App\Model\Entity\Login\LoginErrorMaster;

/**
 * 
 */
class LoginErrorMastersTable extends Table
{
    use TableBaseTrait;
    
    const ID_ACCOUNT_NOT_FUND = 1;
    const ID_FAILURE_COUNT_OVER = 2;
    const ID_ACCOUNT_IS_NOT_ACTIVE = 3;
    const ID_PASSWORD_NOT_MATCH = 4;
    const ID_PASSWORD_EXPIRATION_OVER = 5;
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('login_error_masters');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->setEntityClass(LoginErrorMaster::class);
        
        $this->addAssociations([
            'belongsTo' => [],
            'hasOne' => [],
            'hasMany' => [
                'LoginAdminFailuresLogs' => [
                    'className' => LoginAdminFailuresLogsTable::class,
                    'foreignKey' => 'login_error_master_id'
                ],
                'LoginUserFailuresLogs' => [
                    'className' => LoginUserFailuresLogsTable::class,
                    'foreignKey' => 'login_error_master_id'
                ],
            ],
            'belongsToMany' => [],
        ]);
    }
}

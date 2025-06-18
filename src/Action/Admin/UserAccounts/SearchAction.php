<?php
declare(strict_types=1);

namespace App\Action\Admin\UserAccounts;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use \App\Action\Admin\Common\AdminActionSearchTrait;
use App\Model\Table\User\UserAccountsTable;
use Cake\ORM\Query;

class SearchAction implements AdminActionInterface
{
    use AdminActionBaseTrait,
        AdminActionSearchTrait;
    
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
     * @return Query
     */
    public function getSearchQuery() : Query
    {
        
    }
}

<?php
declare(strict_types=1);

namespace App\Action\Admin\SelfInfo;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Model\Table\Admin\AdminAccountsTable;

class DetailAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @var AdminAccountsTable
     */
    private AdminAccountsTable $adminAccountsTable;
    
    
    private function __construct()
    {
        $this->adminAccountsTable = AdminAccountsTable::getInstance();
    }
    
    public function findAdminAccount() : array
    {
        return $this->adminAccountsTable
            ->find()
            ->where([
                'id' => $this->adminAuth->getId(),
            ])
            ->first()
            ?->toArray() ?? throw new Exception(
                'adminAccountsTable data not fund'
                . '[' . (string) $this->adminAuth->getId() . ']'
            );
    }
}

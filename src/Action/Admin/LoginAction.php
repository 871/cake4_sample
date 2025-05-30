<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Lib\Auth\AdminAuth;

class LoginAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @param AdminAuth $adminAuth
     * @return $this
     */
    public function setAdminAuth(AdminAuth $adminAuth) : static 
    {
        // Memo: ログイン前の機能のため認証情報は扱わない
        throw new Exception(
            'Methods prohibited in this class'
        );
    }
    
    /**
     * 
     * @param string $className
     * @return AdminActionInterface
     */
    public function convertTo(string $className) : AdminActionInterface 
    {
        // Memo: 認証情報を扱わないため使用不可
        throw new Exception(
            'Methods prohibited in this class'
        );
    }
}

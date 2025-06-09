<?php
declare(strict_types=1);

namespace App\Action\Admin\Auth;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;


class LogoutAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    public function deleteAdminAuth() : self
    {
        $authSessionKey = ADMIN_AUTH_KEY . '.' . (string) $this->adminAuth->getId();
        
        $this->serverRequest->getSession()->delete($authSessionKey);
        $this->serverRequest->getSession()->delete('admin_input.' . (string) $this->adminAuth->getId());
        
        return $this;
    }
}

<?php
declare(strict_types=1);

namespace App\Action\Admin\Auth;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Action\Admin\Common\AdminError;
use App\Action\Admin\Common\AdminInput;
use App\Action\Admin\Common\AdminInitInput;

class LogoutAction implements AdminActionInterface
{
    use AdminActionBaseTrait;

    /**
     * 
     * @return self
     */
    public function deleteAdminAuth() : self
    {
        $authSessionKey = ADMIN_AUTH_KEY . '.' . (string) $this->adminAuth->getId();
        $this->serverRequest->getSession()->delete($authSessionKey);

        AdminError::destroy($this->serverRequest);
        AdminInput::destroy($this->serverRequest);
        AdminInitInput::destroy($this->serverRequest);

        return $this;
    }
}

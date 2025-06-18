<?php
declare(strict_types=1);

namespace App\Action\Admin\Auth;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\Common\AdminInput;
use App\Action\Admin\Common\AdminError;
use App\Exception\AuthException;
use App\Lib\Auth\Admin\LoginAuthenticator as AdminLoginAuthenticator;

class LoginAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @return self
     */
    public function initializeInput() : self
    {
        AdminInput::getInstance(self::class, $this->serverRequest)
            ->write([
                'username' => '',
                'password' => '',
            ]);

        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function updateInput() : self
    {
        AdminInput::getInstance(self::class, $this->serverRequest)
            ->write([
                'username' => $this->serverRequest->getData('username'),
                'password' => $this->serverRequest->getData('password'),
            ]);
        
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function checkInput() : bool
    {
        return AdminInput::getInstance(self::class, $this->serverRequest)->check();
    }

    /**
     * 
     * @return array
     */
    public function getInput() : array
    {
        return AdminInput::getInstance(self::class, $this->serverRequest)->read();
    }
    
    /**
     * 
     * @return self
     */
    public function deleteInput() : self
    {
        AdminInput::getInstance(self::class, $this->serverRequest)->delete();
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function resetAuthErrorMessage() : self
    {
        AdminError::getInstance(self::class, $this->serverRequest)
            ->delete();
        
        return $this;
    }
    
    /**
     * 
     * @param string $message
     * @return self
     */
    public function setAuthErrorMessage(string $message) : self
    {
        AdminError::getInstance(self::class, $this->serverRequest)
            ->write($message);
        
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getAuthErrorMessage() : string
    {
        return (string) AdminError::getInstance(self::class, $this->serverRequest)
            ->read();
    }

    /**
     * 
     * @return self
     * @throws AuthException
     */
    public function checkAuthentication() : self
    {
        try {
            $this->adminAuth = AdminLoginAuthenticator::getInstance(
                    $this->currentDatetime,
                    $this->serverRequest
                )
                ->setUsername($this->serverRequest->getData('username'))
                ->setPassword($this->serverRequest->getData('password'))
                ->execute()
                ->getResult()
                ;
        } catch (AuthException $ex) {
            // Memo: 例外のスローを明示
            throw $ex;
        }

        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getSuccessRedirectUrl() : string 
    {
        $redirect = $this->serverRequest->getQuery('redirect', '/ad/xxxxxxxxx');
        
        return preg_replace('/^\/ad\/[^\/\?]+/', '/ad/' . (string) $this->adminAuth->getId(), $redirect);
    }
    
    /**
     * 
     * @param AdminAuthInterface $adminAuth
     * @return $this
     */
    public function setAdminAuth(AdminAuthInterface $adminAuth) : static 
    {
        // Memo: ログイン前の機能のため認証情報は参照しない
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
        // Memo: 認証情報を参照しないため使用不可
        throw new Exception(
            'Methods prohibited in this class'
        );
    }
}

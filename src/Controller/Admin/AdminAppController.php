<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Exception;
use Cake\Http\Response;
use App\Lib\Auth\AdminAuthInterface;

class AdminAppController extends AppController
{
    
    
    public function initialize(): void
    {
        parent::initialize();
        
    }
    
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        $this->viewBuilder()->setLayout('after_login');
    }
    
    protected function getInstanceForAdminLoginAuth() : AdminAuthInterface
    {
        $key = ADMIN_AUTH_KEY . '.' . (string) $this->request->getParam('admin_account_id');
        
        return $this->request->getSession()->read($key);
    }

    protected function getSystemErrorResponse(Exception $ex) : Response
    {
        Log::error(
                $ex->getMessage()
                . '[File: ' . $ex->getFile() . ']' 
                . '[Line: ' . $ex->getLine() . ']' 
            );
            
        $message_id = uniqid();
        $admin_account_id = (string) $this->request->getParam('admin_account_id');
        $this->request->getSession()->write(ERROR_MESSAGES_KEY . '.' . $admin_account_id . '.' . $message_id, $ex->getMessage());

        return $this->redirect('/error/' . $admin_account_id . '/' . $message_id);
    }
}

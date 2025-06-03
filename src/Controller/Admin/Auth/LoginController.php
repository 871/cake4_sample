<?php
declare(strict_types=1);


namespace App\Controller\Admin\Auth;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Action\Admin\Auth\LoginAction as CtlAction;
use Cake\Log\Log;
use Exception;
use App\Exception\AuthException;
use Cake\Http\Response;

class LoginController extends AdminAppController
{
    /**
     * 
     * @var Carbon
     */
    private Carbon $currentDatetime;

    /**
     * 
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        $this->currentDatetime = new Carbon();

        $this->ctlAction = CtlAction::getInstance()
                ->setCurrentDatetime($this->currentDatetime)
                ->setRequest($this->getRequest())
                ;
        if (
            !$this->ctlAction->checkInput() 
            && !in_array($this->request->getParam('action'), ['index',], true)
        ) {
            return $this->redirect([
                'action' => 'index',
                '?' => $this->request->getQuery(),
            ]);
        }
    }
    
    /**
     * 
     * @param EventInterface $event
     */
    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
        
        $this->viewBuilder()->setLayout('before_login');
    }

    public function index()
    {
        try {
            $this->ctlAction
                ->initializeInput()
                ->resetAuthErrorMessage()
                ;
            
            return $this->redirect([
                'action' => 'login',
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function login()
    {
        try {
            $this->set([
                'input' => $this->ctlAction->getInput(),
                'authErrorMessage' => $this->ctlAction->getAuthErrorMessage(),
            ]);
            
            $this->render('/Admin/Auth/Login/index');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function loginPost()
    {
        try {
            $successRedirectUrl = $this->ctlAction
                ->updateInput()
                ->resetAuthErrorMessage()
                ->checkAuthentication()
                ->deleteInput()
                ->getSuccessRedirectUrl()
                ;

            return $this->redirect($successRedirectUrl);

        } catch (AuthException $ex) {
            
            $this->ctlAction
                ->setAuthErrorMessage($ex->getMessage())
                ;
            
            return $this->redirect([
                'action' => 'login',
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    protected function getSystemErrorResponse(Exception $ex) : Response
    {
        Log::error(
                $ex->getMessage()
                . '[File: ' . $ex->getFile() . ']' 
                . '[Line: ' . $ex->getLine() . ']' 
            );
            
        $message_id = uniqid();
        $this->request->getSession()->write(ERROR_MESSAGES_KEY . '.other.' . $message_id, $ex->getMessage());

        return $this->redirect('/error/' . $message_id);
    }
}
<?php
declare(strict_types=1);


namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Action\Admin\LoginAction as CtlAction;
use Cake\Log\Log;
use Exception;

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
    }

    public function index()
    {
        try {
            
            throw new Exception('Test Error Msg');
        
        } catch (Exception $ex) {
            
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
    
    public function indexPost()
    {
        $this->set([
            'errorMessages' => (array) $this->ctlAction->getErrorMessage(),
        ]);

        $this->render('Admin/Error/index');
    }
}
<?php
declare(strict_types=1);

namespace App\Controller\Admin\SelfInfo\AccountInfo;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\SelfInfo\AccountInfo\EditAction as CtlAction;
use App\Lib\Auth\Admin\LoginAuthReference;
use App\Exception\ValidateException;

class EditController extends AdminAppController
{
    /**
     * 
     * @var Carbon
     */
    private Carbon $currentDatetime;

    /**
     * 
     * @var AdminAuthInterface
     */
    private AdminAuthInterface $adminAuth;
    
    /**
     * 
     * @var CtlAction
     */
    private CtlAction $ctlAction;

    /**
     * 
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->currentDatetime = new Carbon();
        $this->adminAuth = LoginAuthReference::getInstance($this->currentDatetime, $this->getRequest())
                ->execute()
                ->getResult();
        $this->ctlAction = CtlAction::getInstance()
                ->setCurrentDatetime($this->currentDatetime)
                ->setAdminAuth($this->adminAuth)
                ->setRequest($this->getRequest());
        
        if (
            !$this->ctlAction->checkInput() 
            && !in_array($this->request->getParam('action'), ['index',], true)
        ) {
            return $this->redirect([
                'action' => 'index',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                '?' => $this->request->getQuery(),
            ]);
        }
    }
    
    public function index()
    {
        try {
            $input_id = uniqid();
            
            $this->ctlAction
                ->resetErrors()
                ->initializeInput($input_id)
                ;

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'input_id' => $input_id,
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function input()
    {
        try {
            $this->set([
                'input' => $this->ctlAction->getInput(),
                'messages' => $this->ctlAction->getErrorMessages(),
                'errors' => $this->ctlAction->getErrorClasses(),
            ]);
            
            $this->render('/Admin/SelfInfo/AccountInfo/Edit/input');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function inputPost()
    {
        try {
            $this->ctlAction
                ->lockInput()
                ->resetErrors()
                ->updateInput()
                ->runValidate()
                ->unlockInput()
                ;

            return $this->redirect([
                'action' => 'conf',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'input_id' => $this->request->getParam('input_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (ValidateException $ex) {
            
            $this->ctlAction->unlockInput();

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'input_id' => $this->request->getParam('input_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function conf()
    {
        try {
            $this->set([
                'input' => $this->ctlAction->getInput(),
            ]);
            
            $this->render('/Admin/SelfInfo/AccountInfo/Edit/conf');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function confPost()
    {
        try {
            $this->ctlAction
                ->lockInput()
                ->runValidate()
                ->save()
                ->deleteInput()
                ->authRefresh()
                ->unlockInput();

            $this->Flash->success(__('アカウント情報を更新しました。'));

            return $this->redirect([
                'prefix' => 'Admin/SelfInfo',
                'controller' => 'Detail',
                'action' => 'index',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (ValidateException $ex) {

            $this->ctlAction->unlockInput();

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'input_id' => $this->request->getParam('input_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {

            return $this->getSystemErrorResponse($ex);
        }
    }
}
<?php
declare(strict_types=1);

namespace App\Controller\Admin\SelfInfo\Password;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\SelfInfo\Password\EditAction as CtlAction;
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
            $tmp_id = uniqid();
            
            $this->ctlAction
                ->resetErrors()
                ->initializeInput($tmp_id)
                ;

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'tmp_id' => $tmp_id,
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
            
            $this->render('/Admin/SelfInfo/Password/Edit/input');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function inputPost()
    {
        try {
            $this->ctlAction
                ->resetErrors()
                ->updateInput()
                ->runValidate()
                ;

            return $this->redirect([
                'action' => 'conf',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'tmp_id' => $this->request->getParam('tmp_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (ValidateException $ex) {

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'tmp_id' => $this->request->getParam('tmp_id'),
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
            
            $this->render('/Admin/SelfInfo/Password/Edit/conf');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function confPost()
    {
        try {
            $this->ctlAction
                ->runValidate()
                ->save()
                ->deleteInput()
                ->authRefresh();
            
            $this->Flash->success(__('アカウント情報を更新しました。'));

            return $this->redirect([
                'prefix' => 'Admin/SelfInfo',
                'controller' => 'Detail',
                'action' => 'index',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (ValidateException $ex) {

            return $this->redirect([
                'action' => 'input',
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'tmp_id' => $this->request->getParam('tmp_id'),
                '?' => $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }
}
<?php
declare(strict_types=1);

namespace App\Controller\Admin\UserAccounts;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\UserAccounts\CreateAction as CtlAction;
use App\Lib\Auth\Admin\LoginAuthReference;
use App\Exception\ValidateException;

class CreateController extends AdminAppController
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
    
    /**
     * 
     * @return ?Response
     */
    public function index() : ?Response
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
    
    /**
     * 
     * @return ?Response
     */
    public function copy() : ?Response
    {
        try {
            $input_id = uniqid();
            
            $this->ctlAction
                ->resetErrors()
                ->initializeCopyInput($input_id)
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

    /**
     * 
     * @return ?Response
     */
    public function input() : ?Response
    {
        try {
            $this->set([
                'input' => $this->ctlAction->getInput(),
                'messages' => $this->ctlAction->getErrorMessages(),
                'errors' => $this->ctlAction->getErrorClasses(),
            ]);
            
            $this->render('/Admin/UserAccounts/Edit/input');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    /**
     * 
     * @return ?Response
     */
    public function inputPost() : ?Response
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

    /**
     * 
     * @return ?Response
     */
    public function conf() : ?Response
    {
        try {
            $this->set([
                'input' => $this->ctlAction->getInput(),
            ]);
            
            $this->render('/Admin/UserAccounts/Edit/conf');
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    /**
     * 
     * @return ?Response
     */
    public function confPost() : ?Response
    {
        try {
            $this->ctlAction
                ->lockInput()
                ->runValidate()
                ->save()
                ->deleteInput()
                ->unlockInput()
                ->sendTmpPasswordMail();

            $this->Flash->success(__('ユーザアカウントを作成しました。'));

            return $this->redirect([
                'prefix' => 'Admin/UserAccounts',
                'controller' => 'Search',
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
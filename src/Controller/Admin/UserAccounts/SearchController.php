<?php
declare(strict_types=1);

namespace App\Controller\Admin\UserAccounts;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\UserAccounts\SearchAction as CtlAction;
use App\Lib\Auth\Admin\LoginAuthReference;
use Exception;
use App\Exception\ValidateException;
use Cake\Http\Exception\NotFoundException;


class SearchController extends AdminAppController
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
    }
    
    public function init()
    {
        try {
            return $this->redirect([
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                'action' => 'index',
                '?' => $this->ctlAction->initSearchQuery(),
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }

    public function index()
    {
        try {
            $this->set([
                'messages' => [],
                'errors' => [],
                'results' => $this->ctlAction
                    ->runValidate()
                    ->setCtl($this)
                    ->execute()
                    ->getResults(),
            ]);
            
            return $this->render('/Admin/UserAccounts/search');
        } catch (ValidateException $ex) {

            $this->set([
                'messages' => $this->ctlAction->getErrorMessages(),
                'errors' => $this->ctlAction->getErrorClasses(),
                'results' => [],
            ]);

            return $this->render('/Admin/UserAccounts/search');
        } catch (NotFoundException $ex) {
            
            $this->Flash->error(__('指定されたページが存在しません。'));
            
            return $this->redirect([
                'admin_account_id' => $this->request->getParam('admin_account_id'),
                '?' => [
                    'page' => 1,
                ] + (array) $this->request->getQuery(),
            ]);
        } catch (Exception $ex) {

            return $this->getSystemErrorResponse($ex);
        }
    }
}

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
            
            $this->getSystemErrorResponse($ex);
        }
    }

    public function index()
    {
        try {
            $query = $this->ctlAction->getSearchQuery();
            $settings = $this->ctlAction->getPaginateSetting();
            
            $this->set([
                'results' => $this->paginate($query, $settings),
            ]);

            return $this->render('/Admin/UserAccounts/search');       
        } catch (Exception $ex) {
            
            $this->getSystemErrorResponse($ex);
        }
    }
}

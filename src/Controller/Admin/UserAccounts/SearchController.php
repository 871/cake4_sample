<?php
declare(strict_types=1);


namespace App\Controller\Admin\UserAccounts;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon;


class SearchController extends AdminAppController
{
    /**
     * 
     * @var Carbon
     */
    private Carbon $currentDatetime;
    
    /**
     * 
     * @var AdminAuth
     */
    private AdminAuth $adminAuth;
    
    /**
     * 
     * @var CategoryAction
     */
    private CategoryAction $categoryAction;
    
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
            
            $this->paginate();
            
            
        } catch (\Exception $e) {
            
            ErrorMessage::getInstance()
                ->setMessage($e->getMessage());
            
            return $this->redirect([
                'prefix' => 'Admin',
                'controller' => 'Error',
                'action' => 'index',
                '?' => [
                    'message_key' => $messageKey,
                ],
            ]);
        }
    }
    
    public function index()
    {
        
        
    }
    
    
    
}

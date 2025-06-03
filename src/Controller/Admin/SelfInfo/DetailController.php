<?php
declare(strict_types=1);

namespace App\Controller\Admin\SelfInfo;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\SelfInfo\DetailAction as CtlAction;


class DetailController extends AdminAppController
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
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->currentDatetime = new Carbon();
        $this->adminAuth = $this->getInstanceForAdminLoginAuth();
        $this->ctlAction = CtlAction::getInstance()
                ->setCurrentDatetime($this->currentDatetime)
                ->setAdminAuth($this->adminAuth)
                ->setRequest($this->getRequest())
                ;
    }

    public function index()
    {
        try {


            return $this->render('/Admin/SelfInfo/Detail/index');                    
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }
}
<?php
declare(strict_types=1);


namespace App\Controller\Admin;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\ErrorAction as CtlAction;
use App\Lib\Auth\Admin\LoginAuthReference;


class ErrorController extends AdminAppController
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
        $this->adminAuth = LoginAuthReference::getInstance($this->currentDatetime, $this->getRequest())
                ->execute()
                ->getResult();
        $this->ctlAction = CtlAction::getInstance()
                ->setCurrentDatetime($this->currentDatetime)
                ->setAdminAuth($this->adminAuth)
                ->setRequest($this->getRequest());
    }

    public function index()
    {
        $this->render('/Admin/Error/index');
    }
}
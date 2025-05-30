<?php
declare(strict_types=1);


namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\EventInterface;
use Carbon;
use App\Lib\Auth\AdminAuth;
use App\Action\Admin\ErrorAction as CtlAction;


class ErrorController extends AppController
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
     * @param EventInterface $event
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->currentDatetime = new Carbon();
        $this->adminAuth = RequestAdminAuthProvider::createInstance($this->getRequest())
                ->execute()
                ->getResult()
                ;

        $this->ctlAction = CtlAction::getInstance()
                ->setCurrentDatetime($this->currentDatetime)
                ->setAdminAuth($this->adminAuth)
                ->setRequest($this->getRequest())
                ;
    }

    public function index()
    {
        $this->set([
            'errorMessages' => (array) $this->ctlAction->getErrorMessage(),
        ]);

        $this->render('Admin/Error/index');
    }
}
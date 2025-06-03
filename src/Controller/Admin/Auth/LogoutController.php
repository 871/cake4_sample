<?php
declare(strict_types=1);

namespace App\Controller\Admin\Auth;

use App\Controller\Admin\AdminAppController;
use Cake\Event\EventInterface;
use Carbon\Carbon;
use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
use App\Action\Admin\Auth\LogoutAction as CtlAction;


class LogoutController extends AdminAppController
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
            $this->ctlAction
                ->deleteAdminAuth();

            $this->Flash->info(__('ログアウトしました。'));

            return $this->redirect([
                'controller' => 'Login',
                'action' => 'index',
            ]);
        } catch (Exception $ex) {
            
            return $this->getSystemErrorResponse($ex);
        }
    }
}
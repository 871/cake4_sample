<?php

declare(strict_types=1);


namespace App\Action\Admin\Common;

use Carbon\Carbon;
use Cake\Http\ServerRequest;
use App\Lib\Auth\AdminAuth;

interface AdminActionInterface
{
     /**
     * 
     * @return self
     */
    public static function getInstance() : static;
    
    /**
     * 
     * @param Carbon $currentDatetime
     * @return $this
     */
    public function setCurrentDatetime(Carbon $currentDatetime) : static;
    
    /**
     * 
     * @param ServerRequest $serverRequest
     * @return $this
     */
    public function setRequest(ServerRequest $serverRequest) : static;
    
    /**
     * 
     * @param AdminAuth $adminAuth
     * @return $this
     */
    public function setAdminAuth(AdminAuth $adminAuth) : static ;
    
    /**
     * 
     * @param string $className
     * @return AdminActionInterface
     */
    public function convertTo(string $className) : AdminActionInterface;
}
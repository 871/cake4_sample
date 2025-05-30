<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use Carbon\Carbon;
use Cake\Http\ServerRequest;
use App\Lib\Auth\AdminAuth;
use App\Action\Admin\Common\AdminActionInterface;


trait AdminActionBaseTrait
{
    /**
     * 
     * @var Carbon
     */
    private Carbon $currentDatetime;
    
    /**
     * 
     * @var ServerRequest
     */
    private ServerRequest $serverRequest;
    
    /**
     * 
     * @var AdminAuth
     */
    private AdminAuth $adminAuth;
    
    /**
     * 
     * @return self
     */
    public static function getInstance() : static
    {
        static $ins = null;
        
        if ($ins === null) {
            
            $ins = new static();
        }
        
        return $ins;
    }
    
    /**
     * 
     */
    private function __construct()
    {
        // 処理なし
    }
    
    /**
     * 
     * @param Carbon $currentDatetime
     * @return $this
     */
    public function setCurrentDatetime(Carbon $currentDatetime) : static
    {
        $this->currentDatetime = $currentDatetime;
        
        return $this;
    }
    
    /**
     * 
     * @param ServerRequest $serverRequest
     * @return $this
     */
    public function setRequest(ServerRequest $serverRequest) : static
    {
        $this->serverRequest = $serverRequest;
        
        return $this;
    }
    
    /**
     * 
     * @param AdminAuth $adminAuth
     * @return $this
     */
    public function setAdminAuth(AdminAuth $adminAuth) : static 
    {
        $this->$adminAuth = $adminAuth;
        
        return $this;
    }
    
    /**
     * 
     * @param string $className
     * @return AdminActionInterface
     */
    public function convertTo(string $className) : AdminActionInterface 
    {
        if (!$className instanceof AdminActionInterface) {
            
            throw new Exception(
                'Class type is not AdminActionInterface'
                . '[className: ' . $className . ']'
            );
        }
        /** @var AdminActionInterface */
        $ins = $className::getInstance();
        return $ins
                ->setCurrentDatetime($this->currentDatetime)
                ->setAdminAuth($this->adminAuth)
                ->setRequest($this->serverRequest)
                ;
    }
}
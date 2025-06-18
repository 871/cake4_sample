<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;

use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;
use Exception;

class AdminExclusiveControl
{
    /**
     * 
     * @var ServerRequest
     */
    private ServerRequest $serverRequest;

    /**
     * 
     * @var string
     */
    private string $lockFileName;

    /**
     * 
     * @var string
     */
    private string $lockFilePath;

    /**
     * 
     * @param string $className
     * @param ServerRequest $serverRequest
     * @return self
     */
    public static function getInstance(string $className, ServerRequest $serverRequest, ?string $input_id = null) : self
    {
        $lockFileName = join('_', array_filter([
            'admin_input_lock',
            (string) $serverRequest->getParam('admin_account_id', 'ad_other'),
            Inflector::camelize($className),
            $serverRequest->getParam('input_id', $input_id),
        ]));
        
        static $insList = [];
        if ($insList[$lockFileName] ?? null === null) {
            
            $insList[$lockFileName] = new self($lockFileName, $serverRequest);
        }

        return $insList[$lockFileName];
    }

    /**
     * 
     * @param string $lockFileName
     * @param ServerRequest $serverRequest
     * @return self
     */
    private function __construct(string $lockFileName, ServerRequest $serverRequest)
    {
        $this->serverRequest = $serverRequest;
        $this->lockFileName = $lockFileName;
        $this->lockFilePath = sys_get_temp_dir() . DS . $lockFileName;
    }

    /**
     * 
     * @return self
     */
    public function lock() : self
    {
        static $reTryCnt = 0;
        if ($reTryCnt > 5) {

            throw new Exception(
                'Lock File Exists Error'
                . '[Lock File Path: ' . $this->lockFilePath . ']'
            );
        }

        if (file_exists($this->lockFilePath)) {

            sleep(1);
            $reTryCnt++;

            return $this->lock();
        }

        touch($this->lockFilePath);

        return $this;
    }

    /**
     * 
     * @return self
     */
    public function unlock() : self
    {   
        if (file_exists($this->lockFilePath)) {

            unlink($this->lockFilePath);
        }

        return $this;
    }
}
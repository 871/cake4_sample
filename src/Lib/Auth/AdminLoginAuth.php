<?php

declare(strict_types=1);

namespace App\Lib\Auth;

use Carbon\Carbon;
use App\Lib\Auth\AdminAuthInterface;


class AdminLoginAuth implements AdminAuthInterface
{
    /**
     * 
     * @var array
     */
    private array $admin_account;

    public function __construct(array $admin_account)
    {
       $this->admin_account = $admin_account;
    }

    public function getId(): int
    {
       return (int) $this->admin_account['id'];
    }

    public function getIsActive(): bool
    {
       return (bool) $this->admin_account['is_active'];
    }

    public function getUserName(): string
    {
       return (string) $this->admin_account['username'];
    }

    public function getName(): string
    {
       return (string) $this->admin_account['name'];
    }

    public function getEmail(): string
    {
       return (string) $this->admin_account['email'];
    }

    public function getTel(): string
    {
       return (string) $this->admin_account['tel'];
    }            

    public function getRemarks(): string
    {
       return (string) $this->admin_account['remarks'];
    }

    public function getCreated(): ?Carbon
    {   
       return $this->admin_account['created']
           ? Carbon::parse($this->admin_account['created']?->toDateTimeString())
           : null;
    }

    public function getCreatedAccountId(): int
    {
       return (int) $this->admin_account['id'];
    }

    public function getCreatedIp(): string
    {   
       return (string) $this->admin_account['created_account_id'];
    }

    public function getModified(): ?Carbon
    {
       return $this->admin_account['modified']
           ? Carbon::parse($this->admin_account['modified']?->toDateTimeString())
           : null;
    }

    public function getModifiedAccountId(): int
    {
       return (int) $this->admin_account['modified_account_id'];
    }

    public function getModifiedIp(): string
    {   
       return (string) $this->admin_account['modified_ip'];
    }
}
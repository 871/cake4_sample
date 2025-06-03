<?php

declare(strict_types=1);

namespace App\Lib\Auth;

use Carbon\Carbon;

/**
    CREATE TABLE admin_accounts (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'ID',
        name VARCHAR(50) NOT NULL COMMENT '表示名',
        username VARCHAR(50) NOT NULL COMMENT 'ログインID',
        password VARCHAR(255) NOT NULL COMMENT 'パスワード',
        email VARCHAR(255) NOT NULL COMMENT 'メールアドレス',
        tel VARCHAR(20) DEFAULT NULL COMMENT '電話番号',
        is_active INT NOT NULL DEFAULT 0 COMMENT 'ログイン有効フラグ',
        remarks TEXT COMMENT '備考',

        created DATETIME DEFAULT NULL  COMMENT '作成日時',
        modified DATETIME DEFAULT NULL  COMMENT '更新日時',
        created_account_id BIGINT DEFAULT NULL  COMMENT '作成アカウントID',
        modified_account_id BIGINT DEFAULT NULL  COMMENT '更新アカウントID',
        created_ip VARCHAR(100) DEFAULT NULL COMMENT '作成IP',
        modified_ip VARCHAR(100) DEFAULT NULL COMMENT '更新IP',
        UNIQUE KEY user_accounts_idx01 (username),
        UNIQUE KEY user_accounts_idx02 (email)
    ) COMMENT='管理者アカウント';
 */
interface AdminAuthInterface
{
    public function getId() : int;
    
    public function getName() : string;
    
    public function getUserName() : string;
    
    public function getEmail() : string;
    
    public function getTel() : string;
    
    public function getIsActive() : bool;
    
    public function getRemarks() : string;

    public function getCreated() : ?Carbon;
    
    public function getModified() : ?Carbon;
    
    public function getCreatedAccountId() : int;
    
    public function getModifiedAccountId() : int;
    
    public function getCreatedIp() : string;
    
    public function getModifiedIp() : string;
    
}
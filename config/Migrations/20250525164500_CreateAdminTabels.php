<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateAdminTabels extends AbstractMigration
{
    public $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up()
    {
        $this->execute(<<<SQL
                
            
            DROP TABLE IF EXISTS admin_accounts;
            
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
            
            -- Memo: 各アカウントIDの重複を避けるためのダミーデータを設定
            -- 管理者アカウントのIDは900000001から開始予定
            INSERT INTO admin_accounts (
                id,
                name,
                username,
                password,
                email,
                tel,
                is_active,
                remarks,
                created,
                modified,
                created_account_id,
                modified_account_id,
                created_ip,
                modified_ip
            ) VALUES (
                900000000,
                'ダミーデータ',
                'xxxxxxxx',
                'xxxxxxxx',
                'xxxx@xxx.xxx',
                NULL,
                0,
                '管理者アカウントIDの開始値を明示的に制御するためのダミーデータ',
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            );
            
            DROP TABLE IF EXISTS admin_account_histories;
            
            CREATE TABLE admin_account_histories (
                id CHAR(36) NOT NULL PRIMARY KEY COMMENT 'ID(UUID)',
                admin_account_id BIGINT NOT NULL COMMENT '管理者アカウントID',
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
                INDEX user_account_histories_idx01(user_account_id, created)
            ) COMMENT='管理者アカウント履歴';
     
        SQL);
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down()
    {
        $this->execute(<<<SQL
                
            DROP TABLE IF EXISTS admin_accounts;
            
            
            DROP TABLE IF EXISTS admin_account_histories;
                
                
                
        SQL);
    }
}

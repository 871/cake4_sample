<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUserLoginLogTabels extends AbstractMigration
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
                
            
            DROP TABLE IF EXISTS login_user_success_logs;

            CREATE TABLE login_user_success_logs (
                id CHAR(36) NOT NULL PRIMARY KEY COMMENT 'ID(UUID)',
                username VARCHAR(50) NOT NULL COMMENT 'ログインID',
                login_datetime DATETIME NOT NULL COMMENT 'ログイン日時',
                
                created DATETIME DEFAULT NULL COMMENT '作成日時',
                created_account_id BIGINT DEFAULT NULL  COMMENT '作成アカウントID',
                created_ip VARCHAR(100) DEFAULT NULL COMMENT '作成IP',
                INDEX login_user_success_logs_idx01(username, login_datetime)
            ) COMMENT='ユーザログイン成功履歴';

            DROP TABLE IF EXISTS login_user_failures_logs;

            CREATE TABLE login_user_failures_logs (
                id CHAR(36) NOT NULL PRIMARY KEY COMMENT 'ID(UUID)',
                username VARCHAR(50) NOT NULL COMMENT 'ログインID',
                login_error_master_id INT NOT NULL COMMENT 'ログインエラーマスタID',
                login_error_message VARCHAR(255) NOT NULL COMMENT 'ログインエラーメッセージ',
                login_datetime DATETIME NOT NULL COMMENT 'ログイン日時',
                
                created DATETIME DEFAULT NULL COMMENT '作成日時',
                created_account_id BIGINT DEFAULT NULL  COMMENT '作成アカウントID',
                created_ip VARCHAR(100) DEFAULT NULL COMMENT '作成IP',
                INDEX login_user_failures_logs_idx01(username, login_datetime)
            ) COMMENT='ユーザログイン失敗履歴';
     
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
                
            DROP TABLE IF EXISTS login_user_success_logs;

            DROP TABLE IF EXISTS login_user_failures_logs;
        SQL);
    }
}

<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLoginErrorMasterTabel extends AbstractMigration
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
                
            
            DROP TABLE IF EXISTS login_error_masters;

            CREATE TABLE login_error_masters (
                id INT NOT NULL PRIMARY KEY COMMENT 'ID',
                name VARCHAR(50) NOT NULL COMMENT 'ログインID',
                remarks TEXT COMMENT '備考',
                
                created DATETIME DEFAULT NULL  COMMENT '作成日時',
                modified DATETIME DEFAULT NULL  COMMENT '更新日時',
                created_account_id BIGINT DEFAULT NULL  COMMENT '作成アカウントID',
                modified_account_id BIGINT DEFAULT NULL  COMMENT '更新アカウントID',
                created_ip VARCHAR(100) DEFAULT NULL COMMENT '作成IP',
                modified_ip VARCHAR(100) DEFAULT NULL COMMENT '更新IP'
            ) COMMENT='ログインエラーマスタ';
                
            INSERT INTO login_error_masters (
                id,
                name,
                remarks,
                created,
                modified,
                created_account_id,
                modified_account_id,
                created_ip,
                modified_ip
            ) VALUES (
                1,
                'アカウント登録なし',
                NULL,
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            ), (
                2,
                'ログイン失敗回数超過',
                NULL,
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            ), (
                3,
                'アカウント無効',
                NULL,
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            ), (
                4,
                'パスワード違い',
                NULL,
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            ), (
                5,
                '有効期限切れ',
                NULL,
                '1970-01-01 00:00:00',
                '1970-01-01 00:00:00',
                NULL,
                NULL,
                NULL,
                NULL
            );
     
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
                
            DROP TABLE IF EXISTS login_error_masters;
        SQL);
    }
}

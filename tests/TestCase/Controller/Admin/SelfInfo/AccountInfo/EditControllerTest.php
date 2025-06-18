<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Admin\SelfInfo\AccountInfo;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use App\Model\Table\AdminAccountsTable
use App\Lib\Auth\Admin\Auth\LoginAuth;
use Carbon\Carbon;
use Cake\Auth\DefaultPasswordHasher;


class EditControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected $fixtures = [

    ];

    private const ADMIN_ACCOUNT_ID = '900000001';

    public function setUp(): void
    {
        parent::setUp();

        $adminAccountsTable = AdminAccoutsTable::getInstance();
        
        $adminAccountsTable->save($adminAccountsTable->newEntity([
            'id' => self::ADMIN_ACCOUNT_ID,
            'name' => 'テスト管理者',
            'username' => 'test_admin',
            'password' => (new DefaultPasswordHasher())->hash('password123'),
            'email' => 'test@example.com',
            'tel' => '03-1234-5678',
            'is_active' => 1,
            'expiration_datetime' => '2999-12-31 23:59:59',
            'remarks' => null,
            'created' => new FrozenTime('2025-06-10 00:00:00'),
            'modified' => new FrozenTime('2025-06-10 00:00:00'),
            'created_admin_account_id' => null,
            'modified_admin_account_id' => null,
            'created_ip' => null,
            'modified_ip' => null,
        ]));

        $this->session([
            ADMIN_AUTH_KEY => [
                self::ADMIN_ACCOUNT_ID => new LoginAuth($adminAccountsTable->get(self::ADMIN_ACCOUNT_ID), new Carbon()),
            ]
        ]);
    }

    /**
     * アカウント情報編集の正常系テスト
     */
    public function testEditAccountInfo(): void
    {
        // 1. 初期表示
        $this->get('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit');

        $redirectUrl = $this->_response->getHeaderLine('Location');
        debug($redirectUrl);

        $this->assertResponseOk();
        // $this->assertRedirect($redirectUrl);
        //$this->assertResponseContains('アカウント情報編集');

        return;

        // 2. 入力画面表示
        $this->get('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/input/' . self::INPUT_ID);
        $this->assertResponseOk();
        $this->assertResponseContains('アカウント情報編集');

        // 3. バリデーションエラー（必須項目未入力）
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/input/' . self::INPUT_ID, [
            'name' => '',
            'email' => '',
            'tel' => '',
            'conf_password' => '',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('入力してください');

        // 4. バリデーションエラー（メールアドレス形式不正）
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/input/' . self::INPUT_ID, [
            'name' => 'テスト管理者',
            'email' => 'invalid-email',
            'tel' => '03-1234-5678',
            'conf_password' => 'password123',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('メールアドレスの形式が正しくありません');

        // 5. バリデーションエラー（電話番号形式不正）
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/input/' . self::INPUT_ID, [
            'name' => 'テスト管理者',
            'email' => 'test@example.com',
            'tel' => 'invalid-tel',
            'conf_password' => 'password123',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('電話番号の形式が正しくありません');

        // 6. 正常な入力
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/input/' . self::INPUT_ID, [
            'name' => 'テスト管理者',
            'email' => 'test@example.com',
            'tel' => '03-1234-5678',
            'conf_password' => 'password123',
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('確認画面');

        // 7. 確認画面表示
        $this->get('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/conf/' . self::INPUT_ID);
        $this->assertResponseOk();
        $this->assertResponseContains('確認画面');

        // 8. 更新実行
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/conf/' . self::INPUT_ID);
        $this->assertResponseSuccess();
        $this->assertFlashMessage('アカウント情報を更新しました。');
        $this->assertRedirect(['prefix' => 'Admin/SelfInfo', 'controller' => 'Detail', 'action' => 'index']);
    }

    /**
     * セッション切れのテスト
     *
    public function testSessionExpired(): void
    {
        $this->clearSession();
        
        $this->get('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit');
        $this->assertRedirect(['prefix' => 'Admin/Auth', 'controller' => 'Login', 'action' => 'index']);
    }

    /**
     * 不正なアクセスのテスト
     *
    public function testInvalidAccess(): void
    {
        // 確認画面への直接アクセス
        $this->get('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/conf/' . self::INPUT_ID);
        $this->assertRedirect(['action' => 'index']);

        // 更新処理への直接アクセス
        $this->post('/ad/' . self::ADMIN_ACCOUNT_ID . '/self_info/account_info/edit/conf/' . self::INPUT_ID);
        $this->assertRedirect(['action' => 'index']);
    }
    /* */
} 
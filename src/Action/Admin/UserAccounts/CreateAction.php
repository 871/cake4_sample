<?php
declare(strict_types=1);

namespace App\Action\Admin\UserAccounts;

use App\Action\Admin\Common\AdminActionInterface;
use Exception;
use App\Exception\ValidateException;
use App\Action\Admin\Common\AdminActionInputTrait;
use App\Action\Admin\Common\AdminInput;
use App\Action\Admin\Common\AdminError;
use Cake\Validation\Validator;
use App\Lib\Util\UUID;
use Cake\Auth\DefaultPasswordHasher;
use App\Lib\Util\Password;
use App\Model\Table\User\UserAccountsTable;
use App\Model\Entity\User\UserAccount;
use App\Model\Table\User\UserAccountHistoriesTable;

class CreateAction implements AdminActionInterface
{
    use AdminActionInputTrait;
    
    /**
     * 
     * @var UserAccountsTable
     */
    private UserAccountsTable $userAccountsTable;
    
    /**
     * 
     * @var UserAccountHistoriesTable
     */
    private UserAccountHistoriesTable $userAccountHistoriesTable;
    
    /**
     * 
     * @var UserAccount
     */
    private UserAccount $userAccount;
    
    /**
     * 
     * @var string
     */
    private string $password;

    /**
     * 
     */
    private function __construct()
    {
        $this->userAccountsTable = UserAccountsTable::getInstance();
        $this->userAccountHistoriesTable = UserAccountHistoriesTable::getInstance();
    }
    
    /**
     * 
     * @return self
     */
    public function initializeInput(string $input_id) : self
    {
        AdminInput::getInstance(self::class, $this->serverRequest, $input_id)
            ->write([
                'name' => '',
                'username' => '',
                'email' => '',
                'tel' => '',
                'is_active' => '1',
                'remarks' => '',
            ]);

        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function initializeCopyInput(string $input_id) : self
    {
        $initData = $this
            ->userAccountsTable
            ->find()
            ->where([
                'UserAccounts.id' => $this->serverRequest->getParam('user_account_id'),
            ])
            ->first()
            ?->toArray() ?? throw new Exception(
                'userAccountsTable Data not fund' 
                . '[id: ' . $this->serverRequest->getParam('user_account_id') . ']'
            );

        AdminInput::getInstance(self::class, $this->serverRequest, $input_id)
            ->write([
                'name' => $initData['name'],
                'username' => $initData['username'],
                'email' => $initData['email'],
                'tel' => $initData['tel'],
                'is_active' => $initData['is_active'],
                'remarks' => $initData['remarks'],
            ]);

        return $this;
    }

    /**
     * 
     * @return self
     */
    public function updateInput() : self
    {
        $classSession = AdminInput::getInstance(self::class, $this->serverRequest);
        
        $classSession->write(array_merge($classSession->read(), [
            'name' => $this->serverRequest->getData('name'),
            'username' => $this->serverRequest->getData('username'),
            'email' => $this->serverRequest->getData('email'),
            'tel' => $this->serverRequest->getData('tel'),
            'is_active' => $this->serverRequest->getData('is_active'),
            'remarks' => $this->serverRequest->getData('remarks'),
        ]));
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function runValidate() : self
    {
        $errors = (new Validator())
            ->notEmptyString('name', __('{0}を入力してください。', __('アカウント名')))
            ->maxLength('name', 50, __('{0}は{1}文字以内で入力してください。', __('アカウント名'), 50))
            ->notEmptyString('username', __('{0}を入力してください。', __('ログインアカウント')))
            ->maxLength('username', 50, __('{0}は{1}文字以内で入力してください。', __('ログインアカウント'), 50))
            ->notEmptyString('email', __('{0}を入力してください。', __('メールアドレス')))
            ->maxLength('email', 255, __('{0}は{1}文字以内で入力してください。', __('メールアドレス'), 255))
            ->email('email', false, __('{0}のフォーマットが不正です。', __('メールアドレス')))
            ->add('email', [
                'unique' => [
                    'rule' => function($value) {
                        
                        return !$this->userAccountsTable->exists([
                            'email' => $value,
                        ]);
                    },
                    'message' => __('入力された{0}は登録済みです。', __('メールアドレス')),
                ],
            ])    
            ->allowEmptyString('tel')
            ->maxLength('tel', 20, __('{0}は{1}文字以内で入力してください。', __('電話番号'), 20))
            ->notEmptyString('is_active', __('{0}を入力してください。', __('ログイン設定')))
            ->boolean('is_active', __('{0}の入力が不正です。', __('ログイン設定')))
            ->validate($this->getInput());
        
        if ($errors !== []) {
            
            AdminError::getInstance(self::class, $this->serverRequest)
                ->write($errors);
            
            throw new ValidateException();
        }
        
        return $this;
    }
    
    
    /**
     * 
     * @return self
     */
    public function save() : self
    {
        $conn = $this->userAccountsTable->getConnection();
            
        try {
            $conn->begin();
            
            $this
                ->insertUserAccounts()
                ->insertUserAccountHistories()
                ;
            $conn->commit();
        } catch (Exception $ex) {
            
            $conn->rollback();
            
            throw $ex;
        }
        
        return $this;
    }

    /**
     * 
     * @return self
     * @throws Exception
     */
    private function insertUserAccounts() : self
    {
        $input = $this->getInput();
        
        $this->password = Password::create(12);
        
        $this->userAccount = $this->userAccountsTable->newEntity([
            'name' => $input['name'],
            'username' => $input['username'],
            'password' => (new DefaultPasswordHasher())->hash($this->password),
            'email' => $input['email'],
            'tel' => $input['tel'],
            'is_active' => $input['is_active'],
            'expiration_datetime' => $this->currentDatetime->clone()->addDays(14)->format('Y-m-d 23:59:59'),
            'is_tmp_password' => 1,
            'remarks' => $input['remarks'],
            'created' => $this->currentDatetime->toDateTimeString(),
            'modified' => $this->currentDatetime->toDateTimeString(),
            'created_account_id' => $this->adminAuth->getId(),
            'modified_account_id' => $this->adminAuth->getId(),
            'created_ip' => $this->serverRequest->clientIp(),
            'modified_ip' => $this->serverRequest->clientIp(),
            'system_log' => '[ユーザアカウント管理-新規作成]機能で新規作成'
                . '[Uri: ' . $this->serverRequest->getUri() . ']',
        ]);
        
        if (!$this->userAccountsTable->save($this->userAccount, [
            'checkExisting' => false,
        ])) {

            throw new Exception(
                'userAccountsTable save Error'
                . '[' . print_r($this->userAccount->toArray(), true) . ']'
            );
        }

        return $this;
    }

    /**
     * 
     * @return self
     * @throws Exception
     */
    private function insertUserAccountHistories() : self
    {
        $data = $this->userAccount->toArray();
        
        $userAccountHistory = $this->userAccountHistoriesTable->newEntity(array_merge($data, [
            'id' => UUID::uuid7(),
            'user_account_id' => $data['id'],
        ]));
        
        if (!$this->userAccountHistoriesTable->save($userAccountHistory, [
            'checkExisting' => false,
        ])) {
            
            throw new Exception(
                'userAccountHistoriesTable save Error'
                . '[' . print_r($userAccountHistory->toArray(), true) . ']'
            );
        }
        
        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function sendTmpPasswordMail() : self
    {
        // TODO 未実装
        
        return $this;
    }
}
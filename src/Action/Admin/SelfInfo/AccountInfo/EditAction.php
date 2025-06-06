<?php
declare(strict_types=1);

namespace App\Action\Admin\SelfInfo\AccountInfo;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Model\Table\Admin\AdminAccountsTable;
use App\Model\Entity\Admin\AdminAccount;
use App\Model\Table\Admin\AdminAccountHistoriesTable;
use Exception;
use App\Exception\ValidateException;
use App\Action\Admin\Common\AdminClassSession as ClassSession;
use App\Lib\Auth\Admin\LoginAuthRefresh;
use Cake\Validation\Validator;
use App\Lib\Util\UUID;
use Cake\Utility\Hash;
use Cake\Auth\DefaultPasswordHasher;

class EditAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @var AdminAccountsTable
     */
    private AdminAccountsTable $adminAccountsTable;
    
    /**
     * 
     * @var AdminAccountHistoriesTable
     */
    private AdminAccountHistoriesTable $adminAccountHistoriesTable;
    
    /**
     * 
     * @var AdminAccount
     */
    private AdminAccount $adminAccount;

    private function __construct()
    {
        $this->adminAccountsTable = AdminAccountsTable::getInstance();
        $this->adminAccountHistoriesTable = AdminAccountHistoriesTable::getInstance();
    }
    
    /**
     * 
     * @return self
     */
    public function initializeInput(string $tmp_id) : self
    {
        $data = $this->findAdminAccount();

        ClassSession::getInstance(self::class, $this->serverRequest, $tmp_id)
            ->write([
                'id' => $data['id'],
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'tel' => $data['tel'],
                'modified' => $data['modified']?->format('Y-m-d H:i:s'),
                'show_created' => $data['created']?->format('Y/m/d H:i:s'),
                'show_modified' => $data['modified']?->format('Y/m/d H:i:s'),
                'conf_password' => '',
            ]);

        return $this;
    }
    
    /**
     * 
     * @return array
     */
    private function findAdminAccount() : array
    {
        return $this->adminAccountsTable
            ->find()
            ->where([
                'AdminAccounts.id' => $this->adminAuth->getId(),
            ])
            ->first()
            ?->toArray() ?? throw new Exception(
                'adminAccountsTable Data not fund'
                . '[id: ' . (string) $this->adminAuth->getId() . ']'
            );
    }
    
    /**
     * 
     * @return self
     */
    public function updateInput() : self
    {
        $classSession = ClassSession::getInstance(self::class, $this->serverRequest);
        
        $classSession->write(array_merge($classSession->read(), [
            'name' => $this->serverRequest->getData('name'),
            'email' => $this->serverRequest->getData('email'),
            'tel' => $this->serverRequest->getData('tel'),
            'conf_password' => $this->serverRequest->getData('conf_password'),
        ]));
        
        return $this;
    }
    
    /**
     * 
     * @return bool
     */
    public function checkInput() : bool
    {
        return $this->serverRequest->getParam('tmp_id')
            && ClassSession::getInstance(self::class, $this->serverRequest)->check();
    }

    /**
     * 
     * @return array
     */
    public function getInput() : array
    {
        return ClassSession::getInstance(self::class, $this->serverRequest)->read();
    }
    
    /**
     * 
     * @return self
     */
    public function deleteInput() : self
    {
        ClassSession::getInstance(self::class, $this->serverRequest)->delete();

        return $this;
    }
    
    /**
     * 
     * @return self
     */
    public function resetErrors() : self
    {
        ClassSession::getInstance(self::class . '.errors', $this->serverRequest)
            ->delete();

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getErrorMessages() : array
    {
        $errors = (array) ClassSession::getInstance(self::class . '.errors', $this->serverRequest)
            ->read();
        
        return Hash::flatten($errors);
    }

    /**
     * 
     * @return array
     */
    public function getErrorClasses() : array
    {
        $errors = (array) ClassSession::getInstance(self::class . '.errors', $this->serverRequest)
            ->read();
        
        return array_map(function() {
            
            return 'message error';
        }, $errors);
    }
    
    /**
     * 
     * @return self
     */
    public function runValidate() : self
    {
        $errors = $this
            ->createValidator()
            ->validate($this->getInput());
        
        if ($errors !== []) {
            
            ClassSession::getInstance(self::class . '.errors', $this->serverRequest)
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
        $conn = $this->adminAccountsTable->getConnection();
            
        try {
            $conn->begin();
            
            $this
                ->lockAdminAccounts()
                ->updateAdminAccounts()
                ->createAdminAccountHistories()
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
     */
    public function authRefresh() : self
    {   
        LoginAuthRefresh::getInstance($this->currentDatetime, $this->serverRequest)
            ->execute();

        return $this;
    }

    /**
     * 
     * @return Validator
     */
    private function createValidator() : Validator
    {
        return (new Validator())
            ->add('modified', [
                'other_edited' => [
                    'rule' => function($value, $context) {
                
                        return $this->adminAccountsTable->exists([
                            'id' => $context['data']['id'],
                            'modified' => $value,
                        ]);
                    },
                    'message' => __('別プロセスで{0}が更新されました。', __('アカウント情報')),
                ],
            ])
            ->notEmptyString('name', __('{0}を入力してください。', __('表示名')))
            ->maxLength('name', 50, __('{0}は{1}文字以内で入力してください。', __('表示名'), 50))
            ->notEmptyString('email', __('{0}を入力してください。', __('メールアドレス')))
            ->maxLength('email', 50, __('{0}は{1}文字以内で入力してください。', __('メールアドレス'), 50))
            ->email('email', false, __('{0}のフォーマットが不正です。', __('メールアドレス')))
            ->add('email', [
                'unique' => [
                    'rule' => function($value, $context) {
          
                        return !$this->adminAccountsTable->exists([
                            'email' => $value,
                            'id !=' => $context['data']['id'],
                        ]);
                    },
                    'message' => __('入力された{0}は登録済みです。', __('メールアドレス')),
                ],
            ])
            // ->notEmptyString('tel', __('{0}を入力してください。', '電話番号'))
            ->maxLength('tel', 20, __('{0}は{1}文字以内で入力してください。', __('電話番号'), 20))
            ->notEmptyString('conf_password', __('{0}を入力してください。', __('確認パスワード')))
            ->add('conf_password', [
                'password_match' => [
                    'rule' => function($value, $context) {
                
                        $data = $this->adminAccountsTable
                            ->find()
                            ->where([
                                'id' => $context['data']['id'],
                            ])
                            ->first()
                            ?->toArray() ?? throw new Exception(
                                'adminAccountsTable Data not fund'
                                . '[id: ' . $context['data']['id'] . ']'
                            );

                        return (new DefaultPasswordHasher())->check($value, $data['password']);
                    },
                    'message' => __('{0}が違います。', __('確認パスワード')),
                ],
            ]);
    }
    
    /**
     * 
     * @return self
     * @throws Exception
     */
    private function lockAdminAccounts() : self
    {
        $input = $this->getInput();
        
        $this->adminAccount = $this->adminAccountsTable
            ->find()
            ->where([
                'id' => $input['id'],
                'modified' => $input['modified'],
            ])
            ->modifier('SQL_NO_CACHE')
            ->epilog('FOR UPDATE')
            ->first() ?? throw new Exception(
                'adminAccountsTable data not fund'
                . '[id: ' . $input['id'] . ']'
                . '[modified: ' . $input['modified'] . ']'
            );
        
        return $this;
    }

    /**
     * 
     * @return self
     * @throws Exception
     */
    private function updateAdminAccounts() : self
    {
        $input = $this->getInput();
        
        $this->adminAccountsTable->patchEntity($this->adminAccount, [
            'name' => $input['name'], 
            'email' => $input['email'], 
            'tel' => $input['tel'],
            'modified' => $this->currentDatetime->toDateTimeString(),
            'modified_account_id' => $this->adminAuth->getId(),
            'modified_ip' => $this->serverRequest->clientIp(),
        ]);
        
        if (!$this->adminAccountsTable->save($this->adminAccount, [
            'checkExisting' => false,
        ])) {

            throw new Exception(
                'adminAccountsTable save Error'
                . '[' . print_r($this->adminAccount->toArray(), true) . ']'
            );
        }

        return $this;
    }

    /**
     * 
     * @return self
     * @throws Exception
     */
    private function createAdminAccountHistories() : self
    {
        $data = $this->adminAccount->toArray();
        
        $adminAccountHistory = $this->adminAccountHistoriesTable->newEntity(array_merge($data, [
            'id' => UUID::uuid7(),
            'admin_account_id' => $data['id'],
            'remarks' => 'ログイン管理者情報、アカウント情報更新機能から更新',
            'created' => $data['modified'],
            'created_account_id' => $data['modified_account_id'],
            'created_ip' => $data['modified_ip'],
        ]));
        
        if (!$this->adminAccountHistoriesTable->save($adminAccountHistory, [
            'checkExisting' => false,
        ])) {
            
            throw new Exception(
                'adminAccountHistoriesTable save Error'
                . '[' . print_r($adminAccountHistory, true) . ']'
            );
        }
        
        return $this;
    }
}
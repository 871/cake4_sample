<?php
declare(strict_types=1);

namespace App\Action\Admin\SelfInfo\AccountInfo;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Model\Table\Admin\AdminAccountsTable;
use Exception;
use App\Exception\ValidateException;
use App\Action\Admin\Common\AdminClassSession as ClassSession;
use App\Lib\Auth\Admin\LoginAuthRefresh;
use Cake\Validation\Validator;

class EditAction implements AdminActionInterface
{
    use AdminActionBaseTrait;
    
    /**
     * 
     * @var AdminAccountsTable
     */
    private AdminAccountsTable $adminAccountsTable;
    
    
    private function __construct()
    {
        $this->adminAccountsTable = AdminAccountsTable::getInstance();
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
    public function resetErrorMessages() : self
    {
        ClassSession::getInstance(self::class . '.errorMessages', $this->serverRequest)
            ->delete();

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getErrorMessages() : array
    {
        return (array) ClassSession::getInstance(self::class . '.errorMessages', $this->serverRequest)
            ->read();
    }
    
    /**
     * 
     * @return self
     */
    public function runValidation() : self
    {
        $errors = (new Validator())
            ->notEmptyString('name', __('{0}を入力してください。', '表示名'))
            ->maxLength('name', 50, __('{0}は{1}文字以内で入力してください。', '表示名', 50))
            ->notEmptyString('email', __('{0}を入力してください。', 'メールアドレス'))
            ->maxLength('email', 50, __('{0}は{1}文字以内で入力してください。', 'メールアドレス', 50))
            ->email('email', false, __('{0}のフォーマットが不正です。', 'メールアドレス'))
            ->add('email', [
                'unique' => [
                    'rule' => function($value, $context) {
                
                        return $this->adminAccountsTable->exists([
                            'email' => $value,
                            'id !=' => $context['data']['id'],
                        ]);
                    },
                    'message' => __('入力された{0}は登録済みです。', 'メールアドレス'),
                ],
            ])
            // ->notEmptyString('tel', __('{0}を入力してください。', '電話番号'))
            ->maxLength('tel', 20, __('{0}は{1}文字以内で入力してください。', '電話番号', 20))
            ->notEmptyString('conf_password', __('{0}を入力してください。', '確認パスワード'))
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
                    'message' => __('入力された{0}は登録済みです。', 'メールアドレス'),
                ],
            ])
            ->validate($this->getInput());
        
        if ($errors !== []) {
            
            throw new ValidateException($errors);
        }
        
        return $this;
    }
    /**
     * 
     * @return self
     */
    public function save() : self
    {
        
        
        
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
}
<?php
declare(strict_types=1);

namespace App\Action\Admin\SelfInfo\Password;

use App\Action\Admin\Common\AdminActionInterface;
use App\Action\Admin\Common\AdminActionBaseTrait;
use App\Model\Table\Admin\AdminAccountsTable;
use App\Model\Entity\Admin\AdminAccount;
use App\Model\Table\Admin\AdminAccountHistoriesTable;
use Exception;
use App\Exception\ValidateException;
use App\Action\Admin\Common\AdminActionInputTrait;
use App\Action\Admin\Common\AdminInput;
use App\Action\Admin\Common\AdminInitInput;
use App\Action\Admin\Common\AdminError;
use App\Lib\Auth\Admin\LoginAuthRefresh;
use Cake\Validation\Validator;
use App\Lib\Util\UUID;
use Cake\Auth\DefaultPasswordHasher;
use App\Lib\Util\Password;
use Carbon\Carbon;


class EditAction implements AdminActionInterface
{
    use AdminActionBaseTrait,
        AdminActionInputTrait;
    
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
    public function initializeInput(string $input_id) : self
    {
        $data = $this->findAdminAccount();
        
        $initData = [
            'id' => $data['id'],
            'username' => $data['username'],
            'new_password' => '',
            'new_password_conf' => '',
            'old_password' => '',
            'expiration_datetime' => $data['expiration_datetime']?->format('Y-m-d H:i:s'),
            'modified' => $data['modified']?->format('Y-m-d H:i:s'),
            'show_expiration_datetime' => $data['expiration_datetime']?->format('Y/m/d H:i:s'),
            'show_created' => $data['created']?->format('Y/m/d H:i:s'),
            'show_modified' => $data['modified']?->format('Y/m/d H:i:s'),
        ];

        AdminInput::getInstance(self::class, $this->serverRequest, $input_id)
            ->write($initData);
        
        AdminInitInput::getInstance(self::class, $this->serverRequest, $input_id)
            ->write($initData);

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
        $classSession = AdminInput::getInstance(self::class, $this->serverRequest);
        
        $classSession->write(array_merge($classSession->read(), [
            'new_password' => $this->serverRequest->getData('new_password'),
            'new_password_conf' => $this->serverRequest->getData('new_password_conf'),
            'old_password' => $this->serverRequest->getData('old_password'),
        ]));
        
        return $this;
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
                    'message' => __('別プロセスで{0}が更新されました。', __('パスワード')),
                ],
            ])
            ->notEmptyString('new_password', __('{0}を入力してください。', __('新パスワード')))
            ->add('new_password', [
                'format' => [
                    'rule' => function($value) {
                        
                        return Password::checkFormat($value);
                    },
                    'message' => Password::formatErrorMsg(__('新パスワード')),
                ],
            ])
            ->add('new_password', [
                'change' => [
                    'rule' => function($value) {
                        
                        $list = $this->adminAccountHistoriesTable
                            ->find()
                            ->select([
                                'password',
                            ])
                            ->distinct()
                            ->where([
                                'admin_account_id' => $this->adminAuth->getId(),
                                'created >=' => $this->currentDatetime->clone()->subMonths(6),
                            ])
                            ->all()
                            ->filter(function($row) use ($value) {
                                
                                return (new DefaultPasswordHasher())->check($value, $row->toArray()['password']);
                            })
                            ->toArray();
                        
                        return $list === [];
                    },
                    'message' => __('過去半年以内に利用されたパスワードは使用できません。'),
                ],
            ])
            ->notEmptyString('new_password_conf', __('{0}を入力してください。', __('新パスワード(確認)')))
            ->add('new_password_conf', [
                'input_conf' => [
                    'rule' => function($value, $context) {
                        
                        return $value === $context['data']['new_password_conf'];
                    },
                    'message' => __('入力された{0}が異なります。', __('新パスワード(確認)')),
                ],
            ])
            ->add('old_password', [
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
                    'message' => __('{0}が違います。', __('現在のパスワード')),
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
            'password' => (new DefaultPasswordHasher())->hash($input['new_password']),
            'expiration_datetime' => (function() use ($input) {
                
                $old = Carbon::parse($input['expiration_datetime']);
                $new = $this->currentDatetime->clone()->addMonths(3);
                
                return $old->gt($new) // > 
                    ? $old->toDateTimeString()
                    : $new->toDateTimeString();
            })(),
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
            'remarks' => 'ログイン管理者情報、パスワード更新機能から更新',
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
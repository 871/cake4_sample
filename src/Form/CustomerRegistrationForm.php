<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * CustomerRegistration Form.
 */
class CustomerRegistrationForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
                ->minLength('username', 10, __('10文字以上で入力してください。'))
                ->maxLength('username', 255, __('255文字以下で入力してください。'))
                ->email('username', false, __('メールアドレスを入力してください。'));
        
        $validator
                ->lengthBetween('password', [8, 20], __('パスワードは8文字以上20文字以内で入力してください、'))
                ;
        
        
        return $validator;
    }

    /**
     * Defines what to execute once the Form is processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        
        return true;
    }
}

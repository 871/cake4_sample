<?php

declare(strict_types=1);

namespace App\Action\Admin\Common;


use Cake\ORM\Query;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

trait AdminActionSearchTrait
{
    use AdminActionBaseTrait;

    /**
     * 
     * @var array
     */
    private array $results = [];

    /**
     * 
     * @var array
     */
    private array $errors = [];
    
    /**
     * 
     * @return array
     */
    public function initSearchQuery() : array
    {
        return [];
    }

    /**
     * 
     * @return self
     */
    public function runValidate() : self
    {
        $this->errors = $this
            ->createValidator()
            ->validate($this->serverRequest->getQuery());

        return $this;
    }

    /**
     * 
     * @return array
     */
    public function getErrorMessages() : array
    {   
        return array_unique(Hash::flatten($this->errors));
    }

    /**
     * Memo: バリデータの戻り値からCSS用のエラークラス情報を作成
     * 
     *  Before
     *  [
     *      'user_accounts' => [
     *          'id' => [
     *              'naturalNumber' => 'ユーザアカウントIDは1000000001-1999999999の整数を入力してください。',
     *              'greaterThanOrEqual' => 'ユーザアカウントIDは1000000001-1999999999の整数を入力してください。',
     *              'lessThanOrEqual' => 'ユーザアカウントIDは1000000001-1999999999の整数を入力してください。',
     *          ],
     *      ],
     *  ]
     *  After
     *  [
     *      'user_accounts' => [
     *          'id' => 'message error'
     *      ],
     *  ]
     *  
     * @return array
     */
    public function getErrorClasses() : array
    {
        return Hash::expand(array_column(array_map(function($path) {
            
            return [
                'path' => preg_replace('/\.[^\.]+$/', '', $path),
                'val' => 'message error',
            ];
        }, array_keys(Hash::flatten($this->errors))), 'val', 'path'));
    }

    /**
     * 
     * @return Query
     */
    public function getSearchQuery() : Query
    {
        return $this->errors === [] 
            ? $this->createSearchQuery()
            : $this->createSearchErrorQuery();
    }

    /**
     * Controller::paginate()の第2引数に渡すパラメータ
     * 
     * @return array
     */
    public function getPaginateSetting() : array
    {
        return [
            'limit' => 50,
            'maxLimit' => 100,
            'page' => 1,
            'order' => [
                'modified' => 'desc'
            ], 
        ];
    }

    /**
     * 検索系処理の入力チェック
     * 
     * @return Validator
     */
    private function createValidator() : Validator
    {
        return new Validator();
    }
    
    /**
     * 正常系処理でController::paginate()の第一引数に渡すクエリインスタンス
     * 
     * @return Query
     */
    private abstract function createSearchQuery() : Query;

    /**
     * 入力エラー系の処理でController::paginate()の第一引数に渡すクエリインスタンス
     * 検索件数0件となるクエリを用意する
     * 
     * 例：
     *  　return $this->userAccountsTable
     *       ->find()
     *       ->where([
     *           '1 != 1'
     *       ]);
     * 
     * @return Query
     */
    private abstract function createSearchErrorQuery() : Query;
}
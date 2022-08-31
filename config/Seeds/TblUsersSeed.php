<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * TblUsers seed.
 */
class TblUsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'username' => 'member',
                'password' => '$2y$10$chRR/dnRQgyJ4gVlscsIc.aiDsFs1QUT/.AiCfPf.Rru5LixtAfP6',
                'created' => 
                Cake\I18n\FrozenTime::__set_state(array(
                'date' => '2022-08-27 07:01:04.000000',
                'timezone_type' => 3,
                'timezone' => 'Asia/Tokyo',
                )),
                'modified' => 
                Cake\I18n\FrozenTime::__set_state(array(
                'date' => '2022-08-27 07:01:04.000000',
                'timezone_type' => 3,
                'timezone' => 'Asia/Tokyo',
                )),
            ],
        ];

        $table = $this->table('tbl_users');
        $table->insert($data)->save();
    }
}

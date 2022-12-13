<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'username'    => 'admin',
                'password' => password_hash('admin',PASSWORD_BCRYPT),
                'email' => 'aaaabbb@gmail.com',
                'status' => 2,
                'created' => date('Y-m-d H:i:s'),
            ],
        ];

        $users = $this->table('Users');
        $users = $this->table('Users');
        $column = $users->hasColumn('name');
        if (!$column) {
            $users->insert($data)->saveData();
        }
        else{
            $users->truncate();
            $users->insert($data)->saveData();
        }
    }
}

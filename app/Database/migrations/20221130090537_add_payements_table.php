<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPayementsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $exists = $this->hasTable('Payments');
        if($exists){
            $table = $this->table('Payments')->drop()->save();
        }
        $table = $this->table('Payments');
        $table->addColumn('user_id','int')
            ->addForeignKey('user_id','Users','id',['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addColumn('sum', 'int')
            ->addColumn('execution_time', 'datetime')
            ->create();
    }
}

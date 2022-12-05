<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddReservationsTable extends AbstractMigration
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
        $exists = $this->hasTable('Reservations');
        if($exists){
            $table = $this->table('Reservations')->drop()->save();
        }
        $table = $this->table('Reservations');
        $table->addColumn('user_id','int')
            ->addForeignKey('user_id','Users','id',['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addColumn('space_id','int')
            ->addForeignKey('space_id','ParkingSpaces','id',['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addColumn('from', 'datetime')
            ->addColumn('till', 'datetime')
            ->addColumn('reservation_code', 'string', ['limit' => 7])
            ->create();
    }
}

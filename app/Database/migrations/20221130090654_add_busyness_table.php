<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBusynessTable extends AbstractMigration
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
        $exists = $this->hasTable('Busyness');
        if($exists){
            $table = $this->table('Busyness')->drop()->save();
        }
        $table = $this->table('Busyness');
        $table->addColumn('lot_id','int')
            ->addForeignKey('lot_id','ParkingLots','id',['delete'=> 'NO_ACTION', 'update'=> 'NO_ACTION'])
            ->addColumn('from', 'datetime')
            ->addColumn('till', 'datetime')
            ->addColumn('spaces_filled','int')
            ->create();
    }
}

<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddParkingSpaceTable extends AbstractMigration
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
        $exists = $this->hasTable('ParkingSpaces');
        if($exists){
            $table = $this->table('ParkingSpaces')->drop()->save();
        }
        $table = $this->table('ParkingSpaces');
        $table->addColumn('number','int')
            ->addColumn('lot_id','int')
            ->addForeignKey('lot_id','ParkingLots','id',['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->addColumn('hourly_rate', 'int')
            ->create();
    }
}

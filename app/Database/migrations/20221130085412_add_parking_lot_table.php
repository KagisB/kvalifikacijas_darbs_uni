<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddParkingLotTable extends AbstractMigration
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
        $exists = $this->hasTable('ParkingLots');
        if($exists){
            $table = $this->table('ParkingLots')->drop()->save();
        }
        $table = $this->table('ParkingLots');
        $table->addColumn('address','string', ['limit' => 50])
            ->addColumn('space_count','int')
            ->addColumn('hourly_rate', 'int')
            ->create();
    }
}

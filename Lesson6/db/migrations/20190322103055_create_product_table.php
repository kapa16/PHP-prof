<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateProductTable extends AbstractMigration
{
    public function change()
    {
        $this->table('products')
            ->addColumn('name', 'string')
            ->addColumn('img_src', 'string')
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('description', 'text')
            ->addColumn('rating', 'integer', ['limit' => MysqlAdapter::INT_TINY])
            ->create();
    }
}

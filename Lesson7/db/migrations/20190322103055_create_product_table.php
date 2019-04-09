<?php


use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class CreateProductTable extends AbstractMigration
{
    public function change()
    {
        $this->table('product_categories', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 50])
            ->create();


        $this->table('products', ['signed' => false])
            ->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('image', 'string')
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('description', 'text')
            ->addColumn('rating', 'integer', ['limit' => MysqlAdapter::INT_TINY])
            ->addColumn('is_available', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 1,
                'comment' => '0 - disable, 1- available'
            ])
            ->addColumn('category_id', 'integer', [
                'limit' => MysqlAdapter::INT_TINY,
                'default' => 1
            ])
            ->addColumn('create_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('change_date', 'datetime', ['null' => true])
            ->create();
    }
}

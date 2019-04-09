<?php


use Phinx\Seed\AbstractSeed;
use Faker\Factory;

class ProductsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Factory::create();
        $data = [];
        for ($i = 0; $i < 1000; $i++) {
            $imgNum = $i % 20 + 1;
            $data[] = [
                'name'        => $faker->text(30),
                'image'       => "/img/products/product{$imgNum}.jpg",
                'price'       => $faker->numberBetween(20, 130),
                'description' => $faker->text(100),
                'rating'      => $faker->numberBetween(1, 5),
            ];
        }

        $this->table('products')->insert($data)->save();
    }
}

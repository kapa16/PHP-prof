<?php
//1-4
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__ . '/../' . $class . '.php';
});

use App\Models\Products\{DigitalProduct, PieceProduct, WeightProduct};

//Создаем цифровой товар
$license = new DigitalProduct(
    'Лицензия на программу',
    'Лицензия на программу',
    1000,
    1
);
$license->insert();
echo 'Стоимость лицензии на программу: ' . $license->finalCost();
echo '<br>';
echo 'Продали лицензию на программу: ' . $license->sale();
echo '<hr>';

//Создаем штучный товар
$shirt = new PieceProduct(
    'Рубашка',
    'Рубашка длинная',
    580,
    2
);
$shirt->insert();
echo 'Стоимость 3-x рубашек: ' . $shirt->finalCost(3);
echo '<br>';
echo 'Продали 3 рубашки: ' . $shirt->sale(3);
echo '<br>';
echo 'Продали 2 рубашки: ' . $shirt->sale(2);
echo '<hr>';

//Создаем весовой товар
$sugar = new WeightProduct(
    'Сахар',
    'Сахар на развес',
    45,
    3
);
$sugar->insert();
echo 'Стоимость 3-x рубашек: ' . $sugar->finalCost(8.745);
echo '<br>';
echo 'Продали 3 рубашки: ' . $sugar->sale(8.745);
echo '<hr>';

echo 'Всего доход от цифровых товаров: ' . DigitalProduct::getRevenue();
echo '<br>';
echo 'Всего доход от штучных товаров: ' . PieceProduct::getRevenue();
echo '<br>';
echo 'Всего доход от весовых товаров: ' . WeightProduct::getRevenue();
<?php
//1-4
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__ . '/../' . $class . '.php';
});

use engine\Models\{Product, Users, Category};

//Создаем категории товаров
$categoryMan = new Category('Женская одежда', 0, 0);
echo $categoryMan->insert();
echo '<br>';
$categoryShirt = new Category('Рубашки', 5, $categoryMan->id);
echo $categoryShirt->insert();
echo '<br>';

//Создаем товар
$shirt = new Product(
    'Рубашка',
    'Рубашка длинная',
    580,
    $categoryShirt->id
);
echo $shirt->insert();
echo '<br>';

var_dump(Category::getAll());
var_dump(Product::getAll());

//Загрузка из базы всех пользователей
$users = Users::getAll();
var_dump($users);

echo $users[0]->getFullName() ?? 'No users';
echo '<hr>';

//--------------------------------------------------
//5. Дан код:
class A
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

$a1 = new A();
$a2 = new A();
$a1->foo();   //- 1
$a2->foo();   //- 2
$a1->foo();   //- 3
$a2->foo();   //- 4
echo '<hr>';

//Что он выведет на каждом шаге? Почему?
//используется статическая переменна $x, она инициализируется при первом вызове метода
//она не теряет своего значения, когда заканчивается выполнение функции - метода класса
//она доступна во всех экземплярах класса


//------------------------------------------------------
//Немного изменим п.5:
class A6
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

class B extends A6
{
}

$a1 = new A6();
$b1 = new B();
$a1->foo();     // - 1
$b1->foo();     // - 1
$a1->foo();     // - 2
$b1->foo();     // - 2
echo '<hr>';

//6. Объясните результаты в этом случае.
//В классе В создается своя статическая переменная,
// которая существует независимо от переменной в классе А6,
// каждая переменная доступна, для экземпляров своих классов

//------------------------------------------------------

//7. *Дан код:
class A7
{
    public function foo()
    {
        static $x = 0;
        echo ++$x;
    }
}

class B7 extends A7
{
}

$a1 = new A7;
$b1 = new B7;
$a1->foo();     // - 1
$b1->foo();     // - 1
$a1->foo();     // - 2
$b1->foo();     // - 2
//Что он выведет на каждом шаге? Почему?
//результат как и в п.6, т.к. это такой же код, просто создание новых экземпляров делается без скобок.
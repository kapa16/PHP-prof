<?php

namespace App\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use RuntimeException;

class CartController extends ApiController
{
    protected $cart = [];
    protected $product_id;
    protected $quantity;

    /**
     * CartController constructor.
     */
    public function __construct()
    {
        if (!empty($_COOKIE['cart'])) {
            $this->cart = unserialize($_COOKIE['cart'], [false]);
        }
        $this->product_id = $_POST['postData']['product_id'] ?? '';
        $this->quantity = $_POST['postData']['quantity'] ?? 0;
    }

    private function save()
    {
        setcookie('cart', serialize($this->cart));
        return $this;
    }

    public function get(): array
    {
        $filter = [];
        foreach ($this->cart as $product_id => $cartItem) {
            $filter[] = [
                'col'   => 'id',
                'oper'  => '=',
                'value' => $product_id,
            ];
        }

        $select = [
            'id',
            'name',
            'price',
        ];

        $selectFields = $select;
        $filters = $filter;
        Product::setQueryParams($selectFields, $filters, 'OR');
        $products = Product::getAllArray();
        foreach ($products as &$product) {
            $product['quantity'] = $this->cart[$product['id']];
        }
        return $products;
    }

    public function add()
    {
        if (!$this->product_id) {
            throw new RuntimeException('No product id');
        }

        $product = Product::getOne('id', $this->product_id);
        if (!$product) {
            throw new RuntimeException('No product find');
        }
        $this->cart[$this->product_id] = $this->quantity;

        return $this->save()->success();
    }

    public function delete()
    {
        if (!$this->product_id) {
            throw new RuntimeException('No product id');
        }
        unset($this->cart[$this->product_id]);
        return $this->save()->success();
    }

    public function update()
    {
        if (!$this->product_id) {
            throw new RuntimeException('No product id');
        }
        $this->cart[$this->product_id] = $this->quantity;
        return $this->save()->success();
    }

    public function clear(): void
    {
        setcookie('cart', null, 1);
    }

    public function order(): void
    {
        if (!$this->cart) {
            throw new RuntimeException('Cart is empty');
        }
        $user = User::getAuthorized();
        if (!$user) {
            $this->locationRedirect = '/user/login';
            throw new RuntimeException('You need login to make order');
        }

        $orderProducts = $this->get();
        $result = Order::create($user->id, $orderProducts);

        if ($result) {
            $this->clear();
        }

        $this->locationRedirect = '/personal';
    }
}
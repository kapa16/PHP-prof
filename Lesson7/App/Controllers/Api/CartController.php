<?php

namespace App\Controllers\Api;

use App\App;
use App\Models\Order;
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
        $postData = App::call()->request->post('postData');
        $this->product_id = $postData['product_id'] ?? '';
        $this->quantity = (int) ($postData['quantity'] ?? '');
    }

    private function save()
    {
        setcookie('cart', serialize($this->cart));
        return $this;
    }

    public function get(): array
    {
        $filters = [];
        foreach ($this->cart as $product_id => $cartItem) {
            $filters[] = [
                'col'   => 'id',
                'oper'  => '=',
                'value' => $product_id,
            ];
        }
        $selectFields = [
            'id',
            'name',
            'price',
        ];

        $products = App::call()
            ->getRepository('Product')
            ->setQueryParams($selectFields, $filters, 'OR')
            ->getAllArray();

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

        $filters[] = [
            'col'   => 'id',
            'oper'  => '=',
            'value' => $this->product_id,
        ];
        $product = App::call()
            ->getRepository('Product')
            ->setQueryParams(null, $filters)
            ->getOne();
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
        $user = User::getAuthorizedUser();
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
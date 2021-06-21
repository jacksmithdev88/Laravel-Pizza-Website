<?php

namespace Tests\Unit;

use App\Http\Controllers\orderController;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\TestCase;

class PizzaTest extends TestCase
{

    public function test_create_pizza()
    {
        $pizza = (new orderController())->createPizza('Small', 'Original', [], 8);

        $expected = new Pizza();
        $expected->pizzaSize = 'Small';
        $expected->pizzaType = 'Original';
        $expected->pizzaToppings = [];
        $expected->price = 8;
        $this->assertEquals($pizza, $expected);
    }

    public function test_no_price(){
        $pizza = (new orderController())->createPizza('Small', 'Original', [], 0);

        $expected = new Pizza();
        $expected->pizzaSize = 'Small';
        $expected->pizzaType = 'Original';
        $expected->pizzaToppings = [];
        $expected->price = 0;
        $this->assertEquals($pizza, $expected);
    }

    public function test_toppings(){
        $pizza = (new orderController())->createPizza('Small', 'Create Your Own', ['cheese', 'pepperoni'], 9.80);

        $expected = new Pizza();
        $expected->pizzaSize = 'Small';
        $expected->pizzaType = 'Create Your Own';
        $expected->pizzaToppings = ['cheese', 'pepperoni'];
        $expected->price = 9.80;
        $this->assertEquals($pizza, $expected);
    }

    public function test_no_toppings(){
        $pizza = (new orderController())->createPizza('Small', 'Create Your Own', [], 8);

        $expected = new Pizza();
        $expected->pizzaSize = 'Small';
        $expected->pizzaType = 'Create Your Own';
        $expected->pizzaToppings = [];
        $expected->price = 8;
        $this->assertEquals($pizza, $expected);
    }

    public function test_no_values(){
        $pizza = (new orderController())->createPizza('', '', [], 0);
        $expected = new Pizza();
        $expected->pizzaSize = '';
        $expected->pizzaType = '';
        $expected->pizzaToppings = [];
        $expected->price = 0;
        $this->assertEquals($pizza, $expected);
    }
}

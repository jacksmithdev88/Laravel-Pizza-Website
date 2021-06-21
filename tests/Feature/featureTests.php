<?php

namespace Tests\Feature;

use App\Http\Controllers\orderController;
use App\Models\Pizza;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class featureTests extends TestCase
{
    public function test_login() {
      $this->post( '/Login', ['password' => 'test', 'username' => 'test']);
      $this->session(['username' => 'test']);
      $this->assertEquals("Logged in", "Logged in");
    }

    public function test_add_pizza()
    {
        $this->post( '/addPizza', ['pizza' => 'Original', 'size' => 'Small', 'create' => []]);
        $this->assertEquals("correct", "correct");
    }

    public function test_wrong_login(){
        $this->post('/Login', ['password' => 'asdasd', 'username' => 'asdasdasd']);
        $this->assertEquals('Wrong login', 'Wrong login');
    }

    public function test_logout(){
        $this->post('/logout');
        $this->assertEquals('Logged out', 'Logged out');
    }

    public function test_clear(){
        $this->post('/clear');
        $this->assertEquals('cleared', 'cleared');
    }

    public function test_correct_order(){
        $this->post('/submit');
        $this->session(['username' => 'test', 'order' => 'not null']);
        $this->assertEquals('ordered', 'ordered');
    }

}

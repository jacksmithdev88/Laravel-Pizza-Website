<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;
    protected $fillable = [
        'pizzaSize',
        'pizzaType',
        'pizzaToppings',
        'pizzaPrice'
    ];

    private $pizzaToppings;
    private $pizzaSize;
    private $pizzaType;
    private $price;
}

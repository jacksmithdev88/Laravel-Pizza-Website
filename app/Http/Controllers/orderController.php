<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Illuminate\Http\Request;

class orderController extends Controller
{
    public function addPizza(Request $request)
    {
        $pizzaType = $request->get('pizza');
        $pizzaSize = $request->get('size');
        $toppings = $request->input('create');
        $pizzaPrice = 0;

        if (!session('Order')) {
            session()->put('Order', []);
        }


        if ($pizzaType == 'Original') {
            if ($pizzaSize == 'Small') {
                $pizzaPrice = 8;
            } elseif ($pizzaSize == 'Medium') {
                $pizzaPrice = 9;
            } else {
                $pizzaPrice = 11;
            }
        } elseif ($pizzaType == 'Gimme the Meat') {
            if ($pizzaSize == 'Small') {
                $pizzaPrice = 11;
            } elseif ($pizzaSize == 'Medium') {
                $pizzaPrice = 14.50;
            } else {
                $pizzaPrice = 16.50;
            }
        } elseif ($pizzaType == 'Veggie Delight') {
            if ($pizzaSize == 'Small') {
                $pizzaPrice = 10;
            } elseif ($pizzaSize == 'Medium') {
                $pizzaPrice = 13;
            } else {
                $pizzaPrice = 15;
            }
        } elseif ($pizzaType == 'Make Mine Hot') {
            if ($pizzaSize == 'Small') {
                $pizzaPrice = 11;

            } elseif ($pizzaSize == 'Medium') {
                $pizzaPrice = 13;
            } else {
                $pizzaPrice = 13;
            }
        } elseif ($pizzaType == 'Create Your Own') {
            if($request->has('create')) {
                if ($pizzaSize == 'Small') {
                    $pizzaPrice = (8 + (count($toppings) * 0.90));
                } elseif ($pizzaSize == 'Medium') {
                    $pizzaPrice = (9 + (count($toppings) * 1));
                } elseif ($pizzaSize == 'Large') {
                    $pizzaPrice = (11 + (count($toppings) * 1.15));
                }
            } else {
                if ($pizzaSize == 'Small') {
                    $pizzaPrice = (8);
                } elseif ($pizzaSize == 'Medium') {
                    $pizzaPrice = (9);
                } elseif ($pizzaSize == 'Large') {
                    $pizzaPrice = (11);
                }
            }

        }

        $pizza = $this->createPizza($pizzaSize, $pizzaType,$toppings, $pizzaPrice);

        session()->push('Order', $pizza);
        $newTotal = session('total') + $pizzaPrice;
        session()->put('total', $newTotal);


        $this->checkDeals($request);
        return redirect('/')->withInput($request->input());
    }

    public function createPizza($size, $type, $toppings, $price) {
        $pizza = new Pizza();
        $pizza->pizzaSize = $size;
        $pizza->pizzaType = $type;
        if($type == 'Create Your Own') {
            $pizza->pizzaToppings = $toppings;
        }  else {
            $pizza->pizzaToppings = [];
        }
        $pizza->price = $price;

        return($pizza);
    }

    public function checkDeals(Request $request)
    {
        if (!session('dealsUsed')) {
            session()->put('dealsUsed', [0, 0, 0, 0, 0, 0]);
        }

        if ($request->has('deals')) {
            $deals = $request->get('deals');
            foreach ($deals as $deal) {
                if ($deal == 'deal1') {
                    $eligible = [];
                    $eligibleCount1 = 0;
                    if(session()->has('Order')){
                        for ($i = 0; $i < count(session()->get('Order')); $i++) {
                            if (session('Order')[$i]['pizzaSize'] == 'Medium' or session('Order')[$i]['pizzaSize'] == 'Large') {
                                array_push($eligible, $i);
                                $eligibleCount1 += 1;
                            }
                        }
                    }

                    if ($eligibleCount1 == 2) {
                        if (session()->get('dealsUsed.0') == 0) {
                            $prices = [];
                            $currentPrice = session()->get('total');
                            if(session()->has('Order')){
                                for ($i = 0; $i <= 1; $i++) {
                                    array_push($prices, session()->get('Order')[$i]['price']);
                                }
                            }

                            rsort($prices);
                            $newPrice = ($currentPrice - array_sum($prices)) + $prices[0];

                            session()->put('dealsUsed.0', '1');
                            session()->put('total', $newPrice);
                        }
                    } else {
                        if (session()->get('dealsUsed.0') == 1) {
                            session()->put('dealsUsed.0', 0);
                            $discountPrices = [];
                            if(session()->has('Order')){
                                for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                    if (session('Order')[$i]['pizzaSize'] == 'Medium' or session('Order')[$i]['pizzaSize'] == 'Large') {
                                        array_push($discountPrices, session('Order')[$i]['price']);
                                    }
                                }
                            }

                            $total = session()->get('total');
                            rsort($discountPrices);
                            $total = $total + ($discountPrices[1]);
                            session()->put('total', $total);
                        }
                    }
                } elseif ($deal == 'deal2') {
                    $medium = [];
                    $mediumCount = 0;
                    if(session()->has('Order')){
                        for ($i = 0; $i < count(session()->get('Order')); $i++) {
                            if (session('Order')[$i]['pizzaSize'] == 'Medium') {
                                array_push($medium, $i);
                                $mediumCount += 1;
                            }
                        }
                    }
                    if ($mediumCount == 3) {
                        if (session()->get('dealsUsed.1') == 0) {
                            $mediumPrices = [];
                            $currentPrice = session()->get('total');
                            if(session()->has('Order')){
                                for ($i = 0; $i <= 2; $i++) {
                                    array_push($mediumPrices, session()->get('Order')[$i]['price']);
                                }
                            }


                            rsort($mediumPrices);
                            $newPrice = ($currentPrice - ($mediumPrices[2]));
                            session()->put('dealsUsed.1', '1');
                            session()->put('total', $newPrice);
                        }
                    } else {
                        if (session()->get('dealsUsed.1') == "1") {
                            session()->put('dealsUsed.1', 0);
                            $discountPrices = [];
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Medium' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    array_push($discountPrices, session('Order')[$i]['price']);
                                }
                            }
                            $total = session()->get('total');
                            rsort($discountPrices);
                            $total = $total + ($discountPrices[2]);
                            session()->put('total', $total);
                        }
                    }
                } elseif ($deal == 'deal3') {
                    if ($request->get('pickup') == "collection") {
                        $eligibleCount3 = 0;
                        $prices = [];
                        if(session()->has('Order')){
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Medium' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    $eligibleCount3 += 1;
                                    array_push($prices, session('Order')[$i]['price']);
                                }
                            }
                        }

                        if ($eligibleCount3 == 4) {
                            if (session()->get('dealsUsed.2') == 0) {
                                $currentPrice = session()->get('total');
                                $newPrice = ($currentPrice - array_sum($prices)) + 30;
                                session()->put('dealsUsed.2', '1');
                                session()->put('total', $newPrice);
                            }
                        }else {
                            session()->put('dealsUsed.2', '0');
                        }
                    } else {
                        if(session()->get('dealsUsed.2') == 1) {
                            session()->put('dealsUsed.2', 0);
                            $discountPrices = [];
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Medium' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    array_push($discountPrices, session('Order')[$i]['price']);
                                }
                            }
                            $total = session()->get('total');
                            $discountSum = array_sum($discountPrices);
                            $total = $total - 30 + ($discountSum);
                            session()->put('total', $total);
                        }
                    }

                } elseif ($deal == 'deal4') {
                    if ($request->get('pickup') == "collection") {
                        $eligibleCount4 = 0;
                        $prices = [];
                        if(session()->has('Order')){
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Large' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    $eligibleCount4 += 1;
                                    array_push($prices, session('Order')[$i]['price']);
                                }
                            }
                        }


                        if ($eligibleCount4 == 2) {
                            if (session()->get('dealsUsed.3') == 0) {
                                $currentPrice = session()->get('total');
                                $newPrice = ($currentPrice - array_sum($prices)) + 25;
                                session()->put('total', $newPrice);
                                session()->put('dealsUsed.3', '1');

                            }
                        } else {
                            session()->put('dealsUsed.3', '0');
                        }
                    } else {
                        if(session()->get('dealsUsed.3') == 1) {
                            session()->put('dealsUsed.3', 0);
                            $discountPrices = [];
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Large' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    array_push($discountPrices, session('Order')[$i]['price']);
                                }
                            }
                            $total = session()->get('total');
                            $discountSum = array_sum($discountPrices);
                            $total = $total + ($discountSum - 25);
                            session()->put('total', $total);
                        }
                    }
                } elseif ($deal == 'deal5') {
                    if ($request->get('pickup') == 'collection') {
                        $eligibleCount5 = 0;
                        $prices = [];
                        if(session()->has('Order')){
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Medium' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    $eligibleCount5 += 1;
                                    array_push($prices, session('Order')[$i]['price']);
                                }
                            }
                        }

                        if ($eligibleCount5 == 2) {
                            if (session()->get('dealsUsed.4') == 0) {
                                $currentPrice = session()->get('total');
                                $newPrice = ($currentPrice - array_sum($prices) + 18);
                                session()->put('total', $newPrice);
                                session()->put('dealsUsed.4', '1');
                            }
                        } else {
                            session()->put('dealsUsed.4', '0');

                        }

                    } else {
                        if(session()->get('dealsUsed.4') == 1) {
                            session()->put('dealsUsed.4', 0);
                            $discountPrices = [];
                            if(session()->has('Order')){
                                for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                    if (session('Order')[$i]['pizzaSize'] == 'Medium' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                        array_push($discountPrices, session('Order')[$i]['price']);
                                    }
                                }
                            }
                            $total = session()->get('total');
                            $discountSum = array_sum($discountPrices);
                            $total = $total + ($discountSum - 18);
                            session()->put('total', $total);
                        }
                    }
                } elseif ($deal == 'deal6') {
                    if ($request->get('pickup') == 'collection') {
                        $eligibleCount = 0;
                        $prices = [];
                        if(session()->has('Order')) {
                            for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                if (session('Order')[$i]['pizzaSize'] == 'Small' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                    $eligibleCount += 1;
                                    array_push($prices, session('Order')[$i]['price']);
                                }
                            }
                        }
                        if ($eligibleCount == 2) {
                            if (session()->get('dealsUsed.5') == 0) {
                                $currentPrice = session()->get('total');
                                $newPrice = ($currentPrice - array_sum($prices) + 12);
                                session()->put('total', $newPrice);
                                session()->put('dealsUsed.5', '1');

                            }
                        } else {
                            session()->put('dealsUsed.5', '0');

                        }
                    } else {
                        if(session()->get('dealsUsed.5') == 1) {
                            session()->put('dealsUsed.5', 0);
                            $discountPrices = [];
                            if (session()->has('Order')) {
                                for ($i = 0; $i < count(session()->get('Order')); $i++) {
                                    if (session('Order')[$i]['pizzaSize'] == 'Small' and session('Order')[$i]['pizzaType'] != 'Create Your Own') {
                                        array_push($discountPrices, session('Order')[$i]['price']);
                                    }
                                }
                            }

                            $total = session()->get('total');
                            $discountSum = array_sum($discountPrices);
                            $total = $total + ($discountSum - 12);
                            session()->put('total', $total);
                        }

                    }
                }
            }
        } else {
            $pizzas = session()->get('Order');
            $totalprice = 0;
            if(session()->has('Order')){
                foreach ($pizzas as $pizza) {
                    $totalprice += $pizza->price;
                }
                session()->put('total', $totalprice);
                session()->put('dealsUsed', [0, 0, 0, 0, 0, 0]);
            }


        }
        return redirect('/')->withInput($request->input());
    }

    public function clear() {
        session()->forget('Order');
        session()->forget('total');
        session()->put('dealsUsed', [0, 0, 0, 0, 0, 0]);
        return redirect('');
    }

    public function submit(Request $request) {
        if(session()->has('username')) {
            if(session()->has('Order')){
                session()->forget('Order');
                session()->forget('total');
                session()->put('dealsUsed', [0, 0, 0, 0, 0, 0]);
                return redirect('')->with('submitted', 'Order succesfully placed!');
            } else {
                return redirect('')->with('submitted', 'Please add an item to submit')->withInput($request->input());
            }
        }
        else {
            return redirect('')->with('submitted', 'You must be logged in to submit an order')->withInput($request->input());
        }
    }
}

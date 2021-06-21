<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class favouriteController extends Controller
{
    public function addFav(Request $request)
    {
        if (session()->has('username')) {
            $order = session()->get('Order');
            if (count((array)$order) > 0) {
                $username = session('username');

                $test = DB::select("select * from favourites where favouriteID='1'" );
                if($test == null) {
                    $favColNum = 1;
                } else {
                    $favNum = DB::table('favourites')
                        ->where('username', $username)
                        ->distinct()
                        ->count('favouriteID');
                    $favColNum = $favNum + 1;
                }

                for ($i = 0; $i < count($order); $i++) {
                    DB::table('favourites')->insert([
                        'pizzaSize' => $order[$i]['pizzaSize'],
                        'pizzaType' => $order[$i]['pizzaType'],
                        'pizzaPrice' => $order[$i]['price'],
                        'pizzaToppings' => serialize($order[$i]['pizzaToppings']),
                        'username' => $username,
                        'favouriteID' => $favColNum
                    ]);
                }

                DB::table('favouritesprice')->insert(['favouriteID' => $favColNum, 'favPrice' => session()->get('total'), 'username' => $username]);



                DB::table('favouritedeals')->insert([
                    'favouriteID' => $favColNum,
                    'dealString' => serialize(session()->get('dealsUsed')),
                    'username' => $username
                ]);
            } else {
                return redirect('')->with('favError', 'You must have at least 1 item to add a favourite!')->withInput($request->input());
            }
            return redirect('')->withInput($request->input());

        } else {
            return redirect('')->with('favError', 'You must be logged in to add a favourite!')->withInput($request->input());
        }
    }


    public function orderFav(Request $request) {
        if(session()->has('username')) {
            $username = session('username');
            $order = DB::select("select * from favourites where username='" .$username."' AND favouriteID='". $request->input('favSelect') . "'");
            $deals = DB::select("select dealString from favouritedeals where favouriteID='" . $request->input('favSelect') . "'");
            $totalQ = DB::select("select favPrice from favouritesprice where favouriteID='". $request->input('favSelect') . "'");
            $total=$totalQ[0]->favPrice;
            session()->forget('Order');
            session()->forget('total');
            session()->put('dealsUsed', [0, 0, 0, 0, 0, 0]);
            session()->put('Order', []);



            foreach ($order as $o) {
                $pizza = new Pizza();
                $pizza->pizzaSize = $o->pizzaSize;
                $pizza->pizzaType = $o->pizzaType;
                $pizza->pizzaToppings = unserialize($o->pizzaToppings);
                $pizza->price = $o->pizzaPrice;
                session()->push('Order', $pizza);

            }
            session()->put('dealsUsed', unserialize($deals[0]->dealString));
            session()->put('total', $total);

            return redirect('');


        } else {
            return redirect('')->with('favError', 'You must be logged in to order a favourite!')->withInput($request->input());
        }
    }

    public function removeFav(Request $request) {
        if(session()->has('username')) {
            $username = session('username');
            DB::table('favourites')->where('username', '=', $username)->where('favouriteID', '=', $request->input('favSelect'))->delete();
            DB::table('favouritedeals')->where('username', '=', $username)->where('favouriteID', '=', $request->input('favSelect'))->delete();
            DB::table('favouritesprice')->where('username', '=', $username)->where('favouriteID', '=', $request->input('favSelect'))->delete();
            return back()->with('favError', 'Favourite deleted')->withInput($request->input());
        }
    }

    public function showFav(Request $request) {
        if(session()->has('username')) {
            $username = session('username');
            $favNo=[];

            $items = DB::select("select distinct(favouriteID) from favourites where username='" . $username . "'");
            foreach($items as $item) {
                array_push($favNo, $item->favouriteID);
            }

            if(count($favNo) != 0) {
                return back()->with('favourites', $favNo)->withInput($request->input());
            } else {
                return back()->withInput($request->input());
            }

        } else {
            return back()->with('favError', 'Please login to view favourites');
        }
    }
}

@if(session()->has('username'))
    <form method="post" action="logout">
        <fieldset>
            @csrf
            <p>You are logged in as {{session('username')}}</p>
            <p>
                <button type="submit">Logout</button>
            </p>
        </fieldset>
    </form>
@else
    <form method="post" action="Login">
        <fieldset>
            @csrf
            <legend>Login</legend>
            <p>
                <label for="username">Username:</label>
                <input type="text" name="username" placeholder="Please enter your username">
            </p>

            <p>
                <label for="password">Password:</label>
                <input type="password" name="password" placeholder="Please enter your password">
            </p>

            <button type="submit">Submit</button>
            <p>
            @if(session('loginError'))
                {{session('loginError')}}
            @endif
            </p>
        </fieldset>
    </form>
@endif
{!! session()->get('redirectError') !!}

<form method="post" action="addPizza">
    <fieldset>
        @csrf
        <legend>Pizzas</legend>

        <fieldset>
            @csrf
            <legend>Deals</legend>

            <p>
                <input type="checkbox" id="deals" name="deals[]" value="deal1" {{ (is_array(old('deals')) && in_array('deal1', old('deals'))) ? ' checked' : '' }} {{(session()->get('dealsUsed.0') == '1') ? 'checked' : ''}}>
                <label>Two for One Tuesdays
                (Two medium or large pizzas)</label>

                <input type="checkbox" name="deals[]" id="deals" value="deal2" {{ (is_array(old('deals')) && in_array('deal2', old('deals'))) ? ' checked' : '' }} {{(session()->get('dealsUsed.1') == '1') ? 'checked' : ''}}>
                <label>Three for Two Thursdays
                (Three medium pizzas)</label>

                <input type="checkbox"  name="deals[]" id="deals" value="deal3" {{ (is_array(old('deals')) && in_array('deal3', old('deals'))) ? ' checked' : '' }} {{(session()->get('dealsUsed.2') == '1') ? 'checked' : ''}}>
                <label>Family Friday
                (Four named pizzas)</label>

                <input type="checkbox" name="deals[]" id="deals" value="deal4" {{ (is_array(old('deals')) && in_array('deal4', old('deals'))) ? ' checked' : '' }} {{(session()->get('dealsUsed.3') == '1') ? 'checked' : ''}}>
                <label>Two Large
                (Two large pizzas)</label>

                <input type="checkbox" name="deals[]" id="deals" value="deal5" {{ (is_array(old('deals')) && in_array('deal5', old('deals'))) ? ' checked' : ''}} {{(session()->get('dealsUsed.4') == '1') ? 'checked' : ''}}>
                <label>Two Medium
                (Two medium pizzas)</label>

                <input type="checkbox" name="deals[]" id="deals" value="deal6" {{ (is_array(old('deals')) && in_array('deal6', old('deals'))) ? ' checked' : '' }} {{(session()->get('dealsUsed.5') == '1') ? 'checked' : ''}}>
                <label >Two Small
                (Two small pizzas)</label>
            </p>
            <button type="submit" formaction="checkDeals">Select deals</button>
        </fieldset>

        <p>
        <label for="size">Select a size:</label>
        <select name="size">
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>
        </p>

        <p>
        <label for="pizza">Select a pizza:</label>
        <select name="pizza" id="pizza">
            <option value="Original" >Original</option>
            <option value="Gimme the Meat">Gimme the Meat</option>
            <option value="Veggie Delight">Veggie Delight</option>
            <option value="Make Mine Hot">Make Mine Hot</option>
            <option value="Create Your Own">Create Your Own</option>
        </select>
        </p>

        <div id="createLab" hidden="true">
        <label for="create" >Add Toppings:</label>
            <input type="checkbox" name="create[]" id="create" value="Cheese">
            <label for="create">Cheese</label>

            <input type="checkbox" name="create[]" id="create" value="Tomato Sauce">
            <label for="create">Tomato Sauce</label>

            <input type="checkbox" name="create[]" id="create" value="Pepperoni">
            <label for="pepperoni">Pepperoni</label>

            <input type="checkbox" name="create[]" id="create" value="Ham">
            <label for="ham">Ham</label>

            <input type="checkbox" name="create[]" id="create" value="Chicken">
            <label for="chicken">Chicken</label>

            <input type="checkbox" name="create[]" id="create" value="Minced Beef">
            <label for="beef">Minced beef</label>

            <input type="checkbox" name="create[]" id="create" value="Onions">
            <label for="onions">Onions</label>

            <input type="checkbox" name="create[]" id="create" value="Green Peppers">
            <label for="peppers">Green peppers</label>

            <input type="checkbox" name="create[]" id="create" value="Mushrooms">
            <label for="mushrooms">Mushrooms</label>

            <input type="checkbox" name="create[]" id="create" value="Sweetcorn">
            <label for="sweetcorn">Sweetcorn</label>

            <input type="checkbox" name="create[]" id="create" value="Jalapeno Peppers">
            <label>Jalapeno Peppers</label>

            <input type="checkbox" name="create[]" id="create" value="Pineapple">
            <label >Pineapple</label>

            <input type="checkbox" name="create[]" id="create" value="Sausage">
            <label>Sausage</label>

            <input type="checkbox" name="create[]" id="create" value="Bacon">
            <label>Bacon</label>
        </p>
        </div>

        <p>
            <button type="submit">Add Pizza</button>
        <p>
            {!! session()->get('discount') !!}

        </p>
        </p>
    </fieldset>

    <form>
        <fieldset>
            <legend>Your order</legend>


            @if(session()->has('Order'))
                @for($i=0;$i < count(session()->get('Order')); $i++)

                    £{{session()->get('Order')[$i]['price']}}
                    {{ session('Order')[$i]['pizzaType'] }} (
                    {{ session('Order')[$i]['pizzaSize']}} )
                @if(session()->get('Order')[$i]['pizzaToppings'] != null)
                    @for($y=0;$y < count(session()->get('Order')[$i]['pizzaToppings']); $y++)
                            <br>
                        -{{session()->get('Order')[$i]['pizzaToppings'][$y]}}
                            ({{ session('Order')[$i]['pizzaSize']}})
                        @endfor
                    @endif
                    <br>
                -----------------------------
                    <br>
                @endfor
            @endif
            Deals:<br>

            @if(session()->get('dealsUsed.0') == 1)
                <p>Two for one Tuesdays applied</p>
            @endif

            @if(session()->get('dealsUsed.1') == 1)
                <p>Three for two Thursdays applied</p>
            @endif

            @if(session()->get('dealsUsed.2') == 1)
                <p>Family friday applied</p>
            @endif

            @if(session()->get('dealsUsed.3') == 1)
                <p>Two large applied</p>
            @endif

            @if(session()->get('dealsUsed.4') == 1)
                <p>Two medium applied</p>
            @endif

            @if(session()->get('dealsUsed.5') == 1)
                <p>Two small applied</p>
            @endif





            @if(session()->has('total'))
                <p id="total">Total: £{{number_format(session()->get('total'), 2, '.', '')}}</p>
            @else
                <p id="total">Total: £0</p>
            @endif

            <input type="radio" value="collection" checked="checked" id="collection" name="pickup" formaction="checkDeal" {{(old('pickup') == 'collection') ? 'checked' : ''}}>
            <label for="collection">Collection</label>
            <input type="radio" value="delivery" id="delivery" name="pickup" formaction="checkDeal" {{(old('pickup') == 'delivery') ? 'checked' : ''}}>
            <label for="delivery">Delivery</label>
            <button type="submit" formaction="checkDeals">Change options</button>
            <p>
            <button type="submit" formaction="subOrder">Submit Order</button>
                <button type="submit" formaction="clear">Clear Order</button>

            </p>
            {!! session()->get('submitted') !!}
            {!! session()->get('removed') !!}


        </fieldset>

        <fieldset>
            @csrf
            <legend>Favourites</legend>
            <button type="submit" formaction="addFav">Add as favourite</button>

            <button type="submit" id="show" formaction="showFav">Load favourites</button>
            <br>

            @if(session()->has('favourites'))
                <p>
                <select name="favSelect">
                    @if(count(session('favourites')) != 0)
                        @for($i=0;$i<count(session('favourites'));$i++)
                            <option value="{{ session('favourites')[$i] }}">Favourite number {{ session('favourites')[$i] }}</option><br>
                        @endfor
                    @endif
                </select>
                </p>
                <button type="submit" id="show" formaction="orderFav">Order favourite</button>
                <button type="submit" id="remove" formaction="removeFav">Remove favourite</button>
            @else

                <p>

                <select name="favSelect" disabled>
                    <option disabled selected hidden>No favourites found.</option>
                </select>
                </p>
                <button type="button">Order favourite</button>
            @endif


            <p>
                {!! session()->get('favError') !!}
            </p>
        </fieldset>
    </form>
</form>

<script type="text/javascript">
    document.getElementById('pizza').addEventListener('change', selectFun)

    function selectFun() {
        const selectPizza = document.getElementById('pizza');
        const select = document.getElementById('create');
        const createLab = document.getElementById('createLab');

        if(selectPizza.value == 'Create Your Own') {
            select.hidden = false;
            createLab.hidden = false;
        } else {
            select.hidden = true;
            createLab.hidden = true;
        }
    }
</script>


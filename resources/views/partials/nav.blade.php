<header>
    <div class="top-nav container">
        <div class="logo"><a href="/">Laravel eStore</a></div>
        <ul>
            <li><a href="{{ route('shop.index') }}">Shop</a></li>
            <li><a href="#">About</a></li>
            <li><a href="{{ route('cart.index') }}"> Cart
                 @if (Cart::instance('default')->count() > 0)
                        <span class="cart-count"><span>{{ Cart::instance('default')->count() }}</span></span>
                 @endif
                 </a>
            </li>
        </ul>
    </div> <!-- end top-nav-->
    @yield('header-block')
</header>

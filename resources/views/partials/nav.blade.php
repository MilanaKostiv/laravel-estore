<header>
    <div class="top-nav container">
        <div class="logo"><a href="/">Laravel eStore</a></div>
        <ul>
            <li><a href="{{ route('shop.index') }}">Shop</a></li>
            <li><a href="#">About</a></li>
            <li><a href="">Cart</a></li>
        </ul>
    </div> <!-- end top-nav-->
    @yield('header-block')
</header>

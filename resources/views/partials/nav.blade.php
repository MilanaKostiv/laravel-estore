<header>
    <div class="top-nav container">
        <div class="top-nav-left">
            <div class="logo"><a href="/">Laravel eStore</a></div>
            @if (! (request()->is('checkout') || request()->is('guestCheckout')))
                @include('partials/menu')
            @endif
        </div>
        <div class="top-nav-right">
            @if (! (request()->is('checkout') || request()->is('guestCheckout')))
                @include('partials/menu-right')
            @endif
        </div>
    </div> <!-- end top-nav-->
    @yield('header-block')
</header>

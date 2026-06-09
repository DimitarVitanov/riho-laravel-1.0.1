<header>
    <nav class="navbar navbar-b navbar-dark navbar-trans navbar-expand-xl fixed-top nav-padding" id="sidebar-menu">
      <a class="navbar-brand p-0" href="#">
        <img class="img-fluid" src="{{ asset(@$content['header']['logo']) }}" alt="">
      </a>
     
      <div class="navbar-collapse justify-content-center collapse hidenav" id="navbarDefault">
        <ul class="navbar-nav navbar_nav_modify" id="scroll-spy">
          @isset($content['header']['menus'])
            @forelse($content['header']['menus'] as $menu)
              <li class="nav-item"><a class="nav-link" href="#{{ strtolower($menu) }}">{{ $menu }}</a></li>
            @empty
              <p>Not Found Menu</p>
            @endforelse
          @endisset

          @isset($content['header']['links'])
            @forelse($content['header']['links'] as $link)
                <li class="nav-item">
                  <a class="nav-link" id="Portfolio" href="{{ $link['url'] }}" target="_blank">{{ $link['text'] }}</a>
                </li>
            @empty
              <p>Not Found Menu</p>
            @endforelse
          @endisset
        </ul>
      </div>
      <div class="buy-btn ms-auto">
        <a href="{{ @$content['header']['btn_url'] }}" class="nav-link js-scroll" target="_blank">
          {{ @$content['header']['btn_text'] }}
        </a>
        <i class="fa fa-shopping-cart"></i>
      </div> 

      <div class="buy-btn">
        @if (auth()->user())
            <a href="{{ route('admin.default_dashboard') }}" class="nav-link js-scroll" target="_blank">
                Dashboard
            </a> 
            <i class="fa fa-home"></i>
      @else 
            <a href="{{ route('login') }}" class="nav-link js-scroll" target="_blank">
                Login
            </a> 
              <i class="fa fa-sign-in"></i>
        @endif
    </div>
    <button class="navbar-toggler ms-2 navabr_btn-set custom_nav" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDefault" aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span><span></span></button>
    </nav>
</header>

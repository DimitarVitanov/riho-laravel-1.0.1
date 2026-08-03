@php
    $onHome = $onHome ?? false;
    $homeUrl = \App\Support\Est8adsRoute::to('home');
    $anchor = fn (string $hash) => $onHome ? $hash : $homeUrl . $hash;
@endphp
<footer class="site-footer">
  <div class="section-shell footer-grid">
    <div><p>{{ __('EST8ADS — Property Chain Intelligence based on Villa Bit AI technology.') }}</p></div>
    <div><h4>{{ __('Platform') }}</h4><a href="{{ $anchor('#create') }}">{{ __('Create your move') }}</a><a href="{{ $anchor('#what') }}">{{ __('What is EST8ADS?') }}</a><a href="{{ $anchor('#how') }}">{{ __('How it works') }}</a><a href="{{ $anchor('#faq') }}">{{ __('FAQ') }}</a></div>
    <div><h4>{{ __('For users') }}</h4><a href="{{ $anchor('#create') }}">{{ __('Private buyers and sellers') }}</a><a href="{{ $anchor('#create') }}">{{ __('Real estate agents') }}</a><a href="{{ \App\Support\Est8adsRoute::to('login') }}">{{ __('Sign in') }}</a></div>
    <div><h4>{{ __('Legal') }}</h4><a href="{{ \App\Support\Est8adsRoute::to('privacy') }}">{{ __('Privacy Policy') }}</a><a href="{{ \App\Support\Est8adsRoute::to('terms') }}">{{ __('Terms of Use') }}</a><a href="{{ \App\Support\Est8adsRoute::to('contact') }}">{{ __('Contact') }}</a></div>
  </div>
  <div class="section-shell footer-bottom"><span>{{ __('© 2026 EST8ADS. All rights reserved.') }}</span><span>{{ __('Powered by Villa Bit AI') }}</span></div>
</footer>

@php
    $onHome = $onHome ?? false;
    $homeUrl = \App\Support\Est8adsRoute::to('home');
    $anchor = fn (string $hash) => $onHome ? $hash : $homeUrl . $hash;
    // A visitor who is signed in with EST8ADS access is funnelled to their
    // workspace instead of the public "Sign in" / anonymous move form.
    $est8adsMember = $est8adsMember ?? (auth()->check() && auth()->user()->canAccessPlatform('est8ads'));
    $dashboardUrl = $dashboardUrl ?? ($est8adsMember ? \App\Support\Est8adsRoute::to('dashboard') : null);
@endphp
<header class="site-header" id="top">
  <div class="nav-shell">
    <a class="brand" href="{{ $homeUrl }}" aria-label="EST8ADS home"><img src="{{ asset('est8ads-assets/est8ads-logo.svg') }}" alt="EST8ADS logo"></a>
    <nav class="desktop-nav" aria-label="Primary navigation">
      <a href="{{ $anchor('#create') }}">{{ __('Create your move') }}</a>
      <a href="{{ $anchor('#what') }}">{{ __('What is EST8ADS?') }}</a>
      <a href="{{ $anchor('#how') }}">{{ __('How it works') }}</a>
      <a href="{{ $anchor('#faq') }}">{{ __('FAQ') }}</a>
      <a href="{{ \App\Support\Est8adsRoute::to('contact') }}">{{ __('Contact') }}</a>
    </nav>
    <div class="nav-actions">
      <div class="language-picker">
        <button class="language-button" type="button" aria-label="{{ __('Choose language') }}" aria-haspopup="listbox" aria-expanded="false" data-language-toggle>{{ strtoupper($locale ?? 'en') }}</button>
        <ul class="language-menu" role="listbox" aria-label="{{ __('Choose language') }}" data-language-menu hidden>
          @foreach (($languages ?? \App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages()) as $code => $name)
            <li role="option" aria-selected="{{ ($locale ?? 'en') === $code ? 'true' : 'false' }}">
              <button type="button" data-language-option="{{ $code }}" class="{{ ($locale ?? 'en') === $code ? 'active' : '' }}">
                <span class="language-code">{{ strtoupper($code) }}</span><span>{{ $name }}</span>
              </button>
            </li>
          @endforeach
        </ul>
      </div>
      @if ($est8adsMember)
        <a class="sign-in" href="{{ $dashboardUrl }}">{{ __('My dashboard') }}</a>
        <a class="primary-small" href="{{ $dashboardUrl }}">{{ __('Add a property') }}</a>
      @else
        <a class="sign-in" href="{{ \App\Support\Est8adsRoute::to('login') }}">{{ __('Sign in') }}</a>
        <a class="primary-small" href="{{ $anchor('#create') }}">{{ __('Get started') }}</a>
      @endif
    </div>
    <button class="menu-button" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
  </div>
  <div class="mobile-menu" hidden>
    <a href="{{ $anchor('#create') }}">{{ __('Create your move') }}</a>
    <a href="{{ $anchor('#what') }}">{{ __('What is EST8ADS?') }}</a>
    <a href="{{ $anchor('#how') }}">{{ __('How it works') }}</a>
    <a href="{{ $anchor('#faq') }}">{{ __('FAQ') }}</a>
    <a href="{{ \App\Support\Est8adsRoute::to('contact') }}">{{ __('Contact') }}</a>
    @if ($est8adsMember)
      <a href="{{ $dashboardUrl }}">{{ __('My dashboard') }}</a>
    @else
      <a href="{{ \App\Support\Est8adsRoute::to('login') }}">{{ __('Sign in') }}</a>
    @endif
  </div>
</header>
<script>
(() => {
  document.querySelectorAll('[data-language-toggle]').forEach((toggle) => {
    const picker = toggle.closest('.language-picker');
    const menu = picker && picker.querySelector('[data-language-menu]');
    if (!menu) return;

    const close = () => { menu.hidden = true; toggle.setAttribute('aria-expanded', 'false'); toggle.classList.remove('open'); };
    const open = () => { menu.hidden = false; toggle.setAttribute('aria-expanded', 'true'); toggle.classList.add('open'); };

    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      menu.hidden ? open() : close();
    });
    menu.querySelectorAll('[data-language-option]').forEach((option) => {
      option.addEventListener('click', () => {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', option.dataset.languageOption);
        window.location.href = url.toString();
      });
    });
    document.addEventListener('click', (event) => {
      if (!menu.hidden && !picker.contains(event.target)) close();
    });
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') close();
    });
  });
})();
</script>

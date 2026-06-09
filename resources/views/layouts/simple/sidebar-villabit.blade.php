@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
    $user = auth()->user();
@endphp
<!-- VillaBit Sidebar -->
<div class="sidebar-wrapper" data-layout="stroke-svg">
    <div class="logo-wrapper">
        <a class="menu-link vb-logo-text" href="{{ route('dashboard') }}">Villa Bit AI</a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"></i></div>
    </div>
    <div class="logo-icon-wrapper"><a class="menu-link" href="{{ route('dashboard') }}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div class="pin-title" style="display:none;"></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                    <a class="menu-link" href="{{ route('dashboard') }}"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                </li>

                {{-- ===================== WAITLIST SIDEBAR ===================== --}}
                @if($user && $user->isOnWaitlist())
                <li class="sidebar-main-title"><div><h6>My Account</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- ===================== ADMIN / SUPER ADMIN SIDEBAR ===================== --}}
                @elseif($user && $user->isAdmin())
                <li class="sidebar-main-title"><div><h6>Admin Panel</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.users.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use></svg>
                        <span>User Management</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use></svg>
                        <span>Add Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-manager') }}">Add Manager</a></li>
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-agency') }}">Add Agency</a></li>
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-investor') }}">Add Investor</a></li>
                    </ul>
                </li>

                <li class="sidebar-main-title"><div><h6>Content & AI</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.content-review.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use></svg>
                        <span>Content Review</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.ai-prompts.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-editors') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-editors') }}"></use></svg>
                        <span>Global AI Prompts</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Affiliate</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-tracking') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use></svg>
                        <span>Affiliate Tracking</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-commissions') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"></use></svg>
                        <span>Commissions</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-payouts') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Payouts</span>
                    </a>
                </li>

                @if(session('impersonating'))
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.impersonate.stop') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-others') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-others') }}"></use></svg>
                        <span class="text-danger">Stop Impersonation</span>
                    </a>
                </li>
                @endif

                {{-- ===================== MANAGER SIDEBAR ===================== --}}
                @elseif($user && $user->isManager())
                <li class="sidebar-main-title"><div><h6>Manager Panel</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.agencies.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use></svg>
                        <span>Assigned Agencies</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.investors.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use></svg>
                        <span>Assigned Investors</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Content & Tasks</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.content-review.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use></svg>
                        <span>Content Review</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.ai-reports.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use></svg>
                        <span>AI Reports</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Finance</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.capital-calls.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Capital Call Follow-up</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.payout-preparation.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"></use></svg>
                        <span>Payout Preparation</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.support-notes.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-others') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-others') }}"></use></svg>
                        <span>Support Notes</span>
                    </a>
                </li>

                {{-- ===================== AGENCY SIDEBAR ===================== --}}
                @elseif($user && $user->isAgency())
                <li class="sidebar-main-title"><div><h6>Agency Panel</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>AI Features</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'daily_ai_employee') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"></use></svg>
                        <span>Daily AI Employee</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'invisible_lead_magnet') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use></svg>
                        <span>Invisible Lead Magnet</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'local_seo_presence_boost') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-search') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-search') }}"></use></svg>
                        <span>Local SEO</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'ai_search_ranking') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-learning') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-learning') }}"></use></svg>
                        <span>AI Search Ranking</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'daily_competitor_scan') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use></svg>
                        <span>Competitor Scan</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'ai_authority_builder') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-editors') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-editors') }}"></use></svg>
                        <span>Authority Builder</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'small_ai_actions') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-knob') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-knob') }}"></use></svg>
                        <span>Small AI Actions</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Content</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.generated-pages.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use></svg>
                        <span>Generated Pages</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.leads.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"></use></svg>
                        <span>Leads</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Settings</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.language') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-internationalization') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-internationalization') }}"></use></svg>
                        <span>Language Settings</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.features') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-knob') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-knob') }}"></use></svg>
                        <span>Feature Toggles</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.affiliate.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Affiliate</span>
                    </a>
                </li>

                {{-- ===================== INVESTOR SIDEBAR ===================== --}}
                @elseif($user && $user->isInvestor())
                <li class="sidebar-main-title"><div><h6>Investor Portal</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.dashboard') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.investments.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use></svg>
                        <span>My Investments</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.capital-calls.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Capital Calls</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.earnings.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"></use></svg>
                        <span>Earnings</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.payouts.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Payouts</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.documents.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use></svg>
                        <span>Documents</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Support</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.support.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-others') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-others') }}"></use></svg>
                        <span>Messages & Support</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
</div>

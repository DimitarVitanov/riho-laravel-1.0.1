@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
    $user = auth()->user();
@endphp
<!-- VillaBit Sidebar -->
<div class="sidebar-wrapper" data-layout="stroke-svg">
    <div class="logo-wrapper">
        <a class="menu-link vb-logo-text" href="https://villabit.ai/" target="_blank">Villa Bit AI</a>
        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"></i></div>
    </div>
    <div class="logo-icon-wrapper"><a class="menu-link" href="https://villabit.ai/" target="_blank"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div class="pin-title" style="display:none;"></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn">
                    <a class="menu-link" href="https://villabit.ai/" target="_blank"><img class="img-fluid" src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
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

                @if($user->isInvestor())
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.documents.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use></svg>
                        <span>KYC Documents</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.affiliate.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>Affiliate & Reseller</span>
                    </a>
                </li>
                @endif

                {{-- Domain Settings for agencies on step 4+ --}}
                @if($user->isAgency() && $user->onboarding_step >= \App\Models\User::ONBOARDING_DOMAIN_CONNECTION)
                <li class="sidebar-main-title"><div><h6>Settings</h6></div></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.domain') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use></svg>
                        <span>Domain Settings</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ $user->isInvestor() ? route('investor.support.index') : route('agency.support.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-others') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-others') }}"></use></svg>
                        <span>Support Tickets</span>
                    </a>
                </li>

                {{-- ===================== ADMIN / SUPER ADMIN SIDEBAR ===================== --}}
                @elseif($user && $user->isAdmin())
                <li class="sidebar-main-title"><div><h6>Admin Panel</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.dashboard') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.users.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/user_management.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/user_management.svg') }}" alt="">
                        <span>User Management</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/add_users.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/add_users.svg') }}" alt="">
                        <span>Add Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-manager') }}">Add Manager</a></li>
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-agency') }}">Add Agency</a></li>
                        <li><a class="menu-link" href="{{ route('admin.villabit.users.create-investor') }}">Add Investor</a></li>
                    </ul>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.managers.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/managers.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/managers.svg') }}" alt="">
                        <span>Managers</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.agencies.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/agencies.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/agencies.svg') }}" alt="">
                        <span>Agencies</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.investors.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/investors.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/investors.svg') }}" alt="">
                        <span>Investors</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Content & AI</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.content-review.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/content_review.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/content_review.svg') }}" alt="">
                        <span>Content Review</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.ai-prompts.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/global_ai_prompts.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/global_ai_prompts.svg') }}" alt="">
                        <span>Global AI Prompts</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.usage-limits.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/usage_limits.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/usage_limits.svg') }}" alt="">
                        <span>Usage Limits</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.ai-settings.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/ai_api_settings.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/ai_api_settings.svg') }}" alt="">
                        <span>AI API Settings</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Support</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.support-tickets.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/support_tickets.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/support_tickets.svg') }}" alt="">
                        <span>Support Tickets</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Affiliate</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-tracking') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate_tracking.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate_tracking.svg') }}" alt="">
                        <span>Affiliate Tracking</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-commissions') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/commissions.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/commissions.svg') }}" alt="">
                        <span>Commissions</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.affiliate-payouts') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/payouts.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/payouts.svg') }}" alt="">
                        <span>Payouts</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Villa Ready Croatia</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.villa-ready.properties.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/agencies.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/agencies.svg') }}" alt="">
                        <span>Properties</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.villabit.villa-ready.referrals.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate_tracking.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate_tracking.svg') }}" alt="">
                        <span>Property Referrals</span>
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
                @elseif($user && $user->isManager() && !$user->managerProfile?->can_view_agency_readonly)
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
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.urls.index') }}">
                        <svg class="stroke-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use></svg>
                        <svg class="fill-icon"><use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use></svg>
                        <span>My URLs & Commissions</span>
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
                @elseif($user && ($user->isAgency() || ($user->isManager() && $user->managerProfile?->can_view_agency_readonly)))
                <li class="sidebar-main-title"><div><h6>{{ __('messages.agency_panel') }}</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.dashboard') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <span>{{ __('messages.dashboard') }}</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>DAILY AI EMPLOYEE</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.leads.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/09_leads_people.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/09_leads_people.svg') }}" alt="">
                        <span>{{ __('messages.leads') }}</span>
                    </a>
                </li>
                  <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.generated-pages.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/08_generated_pages_document.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/08_generated_pages_document.svg') }}" alt="">
                        <span>{{ __('messages.generated_pages') }}</span>
                    </a>
                </li>



                <li class="sidebar-list" style="display: none"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.daily-ai-employee.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/01_daily_ai_employee_robot.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/01_daily_ai_employee_robot.svg') }}" alt="">
                        <span>{{ __('messages.daily_ai_employee') }}</span>
                    </a>
                </li>


                <li class="sidebar-main-title"><div><h6>{{ __('messages.ai_features') }}</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'local_seo_presence_boost') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/03_local_seo_pin_search.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/03_local_seo_pin_search.svg') }}" alt="">
                        <span>{{ __('messages.local_seo') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'ai_search_ranking') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/04_ai_search_ranking.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/04_ai_search_ranking.svg') }}" alt="">
                        <span>{{ __('messages.ai_search_ranking') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'daily_competitor_scan') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/05_competitor_scan_radar.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/05_competitor_scan_radar.svg') }}" alt="">
                        <span>{{ __('messages.competitor_scan') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'ai_authority_builder') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/06_authority_builder_shield_check.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/06_authority_builder_shield_check.svg') }}" alt="">
                        <span>{{ __('messages.authority_builder') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.features.show', 'invisible_lead_magnet') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/02_invisible_lead_magnet.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/02_invisible_lead_magnet.svg') }}" alt="">
                        <span>{{ __('messages.invisible_lead_magnet') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.villa-ready.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <span>Villa Ready Croatia</span>
                    </a>
                </li>


                <li class="sidebar-main-title"><div><h6>{{ __('messages.settings') }}</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.domain') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/10_domain_globe.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/10_domain_globe.svg') }}" alt="">
                        <span>Domain</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.website-design') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/website_design.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/website_design.svg') }}" alt="">
                        <span>Website Design</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.settings.language') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/11_language_settings_globe_translation.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/11_language_settings_globe_translation.svg') }}" alt="">
                        <span>{{ __('messages.language_settings') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.usage-limits.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/13_my_usage_limits_gauge.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/13_my_usage_limits_gauge.svg') }}" alt="">
                        <span>{{ __('messages.my_usage_limits') }}</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.affiliate.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <span>{{ __('messages.affiliate') }}</span>
                    </a>
                </li>

                {{-- Manager viewing agency in read-only mode - show their URLs & Commissions --}}
                @if($user->isManager() && $user->managerProfile?->can_view_agency_readonly)
                <li class="sidebar-main-title"><div><h6>Manager</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('manager.urls.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/14_affiliate_handshake.svg') }}" alt="">
                        <span>My URLs & Commissions</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-main-title"><div><h6>{{ __('messages.support') }}</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('agency.support.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/15_messages_support_chat_question.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/15_messages_support_chat_question.svg') }}" alt="">
                        <span>{{ __('messages.messages_and_support') }}</span>
                    </a>
                </li>

                {{-- ===================== INVESTOR SIDEBAR ===================== --}}
                @elseif($user && $user->isInvestor())
                <li class="sidebar-main-title"><div><h6>Investor Portal</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.dashboard') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/dashboard.svg') }}" alt="">
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.investments.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/my_investments.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/my_investments.svg') }}" alt="">
                        <span>My Investments</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.capital-calls.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/capital_calls.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/capital_calls.svg') }}" alt="">
                        <span>Capital Calls</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.earnings.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/earnings.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/earnings.svg') }}" alt="">
                        <span>Earnings</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.payouts.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/payouts.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/payouts.svg') }}" alt="">
                        <span>Payouts</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.documents.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/kyc_documents.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/kyc_documents.svg') }}" alt="">
                        <span>KYC Documents</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.projects.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/project_reports.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/project_reports.svg') }}" alt="">
                        <span>Project Reports</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.profile.show') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/settings.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/settings.svg') }}" alt="">
                        <span>Settings</span>
                    </a>
                </li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.affiliate.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/affiliate.svg') }}" alt="">
                        <span>{{ __('messages.affiliate') }}</span>
                    </a>
                </li>

                <li class="sidebar-main-title"><div><h6>Support</h6></div></li>

                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i>
                    <a class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('investor.support.index') }}">
                        <img class="stroke-icon svg-icon" src="{{ asset('assets/images/svg-icons/messages_support.svg') }}" alt="">
                        <img class="fill-icon svg-icon" src="{{ asset('assets/images/svg-icons/messages_support.svg') }}" alt="">
                        <span>Messages & Support</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>
</div>

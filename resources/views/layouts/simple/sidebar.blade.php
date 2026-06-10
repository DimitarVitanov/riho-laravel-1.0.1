@use('App\Helpers\Helpers')
@php
    $settings = Helpers::getSettings();
@endphp
<!-- Page Sidebar Start-->
<div class="sidebar-wrapper" data-layout="stroke-svg">
    <div class="logo-wrapper">
        <a class="menu-link" href="{{ route('admin.default_dashboard') }}">
            <img class="img-fluid" src="{{ asset($settings['general']['light_logo']) }}" alt="site-logo">
        </a>
        <div class="back-btn"><i class="fa fa-angle-left"> </i></div>
        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i>
        </div>
    </div>
    <div class="logo-icon-wrapper"><a class="menu-link" href="https://villabit.ai/" target="_blank"><img class="img-fluid"
                src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn"><a class="menu-link" href="https://villabit.ai/" target="_blank"><img class="img-fluid"
                            src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
                    <div class="mobile-back text-end"> <span>Back </span><i class="fa fa-angle-right ps-2"
                            aria-hidden="true"></i></div>
                </li>
                <li class="pin-title sidebar-main-title">
                    <div>
                        <h6>Pinned</h6>
                    </div>
                </li>
                <li class="sidebar-main-title">
                    <div>
                        <h6 class="lan-1">General</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"> </i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                        </svg><span class="lan-3">Dashboard </span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.default_dashboard') }}">Default</a></li>
                        <li><a class="menu-link" href="{{ route('admin.ecommerce_dashboard') }}">E-commerce</a></li>
                        <li><a class="menu-link" href="{{ route('admin.project_dashboard') }}">Project</a></li>
                    </ul>
                </li>
                @canAny(['role.index', 'user.index','blog.index','category.index','tag.index','page.index'])
                    <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                            href="javascript:void(0)">
                            <svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-sample-page') }}"></use>
                            </svg><span class="lan-7-1">Laravel Example</span></a>
                        <ul class="sidebar-submenu">
                            @can('role.index')
                                <li><a class="menu-link" href="{{ route('admin.role.index') }}">Roles</a></li>
                            @endcan
                            @can('user.index')
                                <li><a class="menu-link" href="{{ route('admin.user.index') }}">Users</a></li>
                            @endcan
                            @can('blog.index')
                                <li><a class="menu-link" href="{{ route('admin.blog.index') }}">Blogs</a></li>
                            @endcan
                            @can('category.index')
                                <li><a class="menu-link" href="{{ route('admin.category.index') }}">Categories</a></li>
                            @endcan
                            @can('tag.index')
                                <li><a class="menu-link" href="{{ route('admin.tag.index') }}">Tags</a></li>
                            @endcan
                            @can('page.index')
                                <li><a class="menu-link" href="{{ route('admin.page.index') }}">Pages</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanAny
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-widget') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-widget') }}"></use>
                        </svg><span class="lan-6">Widgets</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.general_widget') }}">General</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_widget') }}">Chart</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-layout') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-layout') }}"></use>
                        </svg><span class="lan-7">Page layout</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.box_layout') }}">Boxed</a></li>
                        <li><a class="menu-link" href="{{ route('admin.rtl_layout') }}">RTL</a></li>
                        <li><a class="menu-link" href="{{ route('admin.dark_layout') }}">Dark Layout</a></li>
                        <li><a class="menu-link" href="{{ route('admin.hide_on_scroll') }}">Hide Nav Scroll</a></li>
                    </ul>
                </li>
                @canAny(['setting.index', 'landingPage.index'])
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Settings</h6>
                        </div>
                    </li>
                    @can('setting.index')
                        <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title link-nav menu-link"
                                href="{{ route('admin.settings.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#setting') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-setting') }}"> </use>
                                </svg>
                                <span>Settings</span>
                            </a>
                            <label class="badge custom-badge-label badge-light-light mb-0 new-feature">New</label>
                        </li> 
                    @endcan
                    @can('landingPage.index')
                        <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.landing.index') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-task') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#fill-task') }}"> </use>
                                </svg><span>Landing page</span>
                            </a>
                            <label class="badge custom-badge-label badge-light-light mb-0 new-feature">New</label> 
                            </li>
                    @endcan
                @endcanAny
                <li class="sidebar-main-title">
                    <div>
                        <h6 class="lan-8">Applications</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-project') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-project') }}"></use>
                        </svg><span>Project </span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.list_project') }}">Project List</a></li>
                        <li><a class="menu-link" href="{{ route('admin.create_project') }}">Create new</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.file_manager') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use>
                        </svg><span>File manager</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.kanban') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-board') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-board') }}"></use>
                        </svg><span>kanban Board</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ecommerce') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-ecommerce') }}"></use>
                        </svg><span>Ecommerce</span></a>
                    <ul class="sidebar-submenu">
                        <li> <a class="menu-link" href="{{ route('admin.add_products') }}">Add Products</a></li>
                        <li><a class="menu-link" href="{{ route('admin.product') }}">Product</a></li>
                        <li><a class="menu-link" href="{{ route('admin.category_page') }}">Category page</a></li>
                        <li><a class="menu-link" href="{{ route('admin.product_page') }}">Product page</a></li>
                        <li><a class="menu-link" href="{{ route('admin.list_products') }}">Product list</a></li>
                        <li><a class="menu-link" href="{{ route('admin.payment_details') }}">Payment Details</a></li>
                        <li><a class="menu-link" href="{{ route('admin.order_history') }}">Order History</a></li>
                        <li><a class="submenu-title" href="javascript:void(0)">Invoices<span class="sub-arrow"><i
                                        class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.invoice_1') }}">Invoice-1</a></li>
                                <li><a class="menu-link" href="{{ route('admin.invoice_2') }}">Invoice-2</a></li>
                                <li><a class="menu-link" href="{{ route('admin.invoice_3') }}">Invoice-3</a></li>
                                <li><a class="menu-link" href="{{ route('admin.invoice_4') }}">Invoice-4</a></li>
                                <li><a class="menu-link" href="{{ route('admin.invoice_5') }}">Invoice-5</a></li>
                                <li><a class="menu-link" href="{{ route('admin.invoice_6') }}">Invoice-6</a></li>
                            </ul>
                        </li>
                        <li><a class="menu-link" href="{{ route('admin.cart') }}">Cart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.list_wish') }}">Wishlist</a></li>
                        <li><a class="menu-link" href="{{ route('admin.checkout') }}">Checkout</a></li>
                        <li><a class="menu-link" href="{{ route('admin.pricing') }}">Pricing </a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.letter_box') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-email') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-email') }}"></use>
                        </svg><span>Letter Box </span></a>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-chat') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-chat') }}"></use>
                        </svg><span>Chat</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.chat_private') }}">Private Chat</a></li>
                        <li> <a class="menu-link" href="{{ route('admin.chat_group') }}">Group Chat</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"> <i class="fa fa-thumb-tack"> </i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                        </svg><span>Users</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.user_profile') }}">Users Profile</a></li>
                        <li><a class="menu-link" href="{{ route('admin.edit_profile') }}">Users Edit</a></li>
                        <li><a class="menu-link" href="{{ route('admin.user_cards') }}">Users Cards</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.bookmark') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-bookmark') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-bookmark') }}"> </use>
                        </svg><span>Bookmarks</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.contacts') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-contact') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-contact') }}"> </use>
                        </svg><span>Contacts</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.task') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-task') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-task') }}"> </use>
                        </svg><span>Tasks</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.calendar_basic') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-calendar') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-calender') }}"></use>
                        </svg><span>Calendar</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.social_app') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-socia') }}l"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-social') }}"> </use>
                        </svg><span>Social App</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.to_do') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-to-do') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-to-do') }}"> </use>
                        </svg><span>To-Do</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.search') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-search') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-search') }}"> </use>
                        </svg><span>Search Result</span></a></li>
                <li class="sidebar-main-title">
                    <div>
                        <h6>Forms & Table</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-form') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-form') }}"> </use>
                        </svg><span>Forms</span></a>
                    <ul class="sidebar-submenu">
                        <li> <a class="submenu-title" href="javascript:void(0)">Form Controls <span
                                    class="sub-arrow">
                                    <i class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.form_validation') }}">Form Validation</a></li>
                                <li><a class="menu-link" href="{{ route('admin.base_input') }}">Base Inputs</a></li>
                                <li><a class="menu-link" href="{{ route('admin.radio_checkbox_control') }}">Checkbox & Radio</a></li>
                                <li><a class="menu-link" href="{{ route('admin.input_group') }}">Input Groups</a></li>
                                <li><a class="menu-link" href="{{ route('admin.input_mask') }}">Input Mask</a></li>
                                <li><a class="menu-link" href="{{ route('admin.megaoptions') }}">Mega Options</a></li>
                            </ul>
                        </li>
                        <li><a class="submenu-title" href="javascript:void(0)">
                                Form Widgets<span class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.datepicker') }}">Datepicker</a></li>
                                <li><a class="menu-link" href="{{ route('admin.touchspin') }}">Touchspin</a></li>
                                <li><a class="menu-link" href="{{ route('admin.select2') }}">Select2</a></li>
                                <li><a class="menu-link" href="{{ route('admin.switch') }}">Switch</a></li>
                                <li><a class="menu-link" href="{{ route('admin.typeahead') }}">Typeahead</a></li>
                                <li><a class="menu-link" href="{{ route('admin.clipboard') }}">Clipboard</a></li>
                            </ul>
                        </li>
                        <li><a class="submenu-title" href="javascript:void(0)">Form layout<span class="sub-arrow"><i
                                        class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.form_wizard') }}">Form Wizard 1</a></li>
                                <li><a class="menu-link" href="{{ route('admin.form_wizard_two') }}">Form Wizard 2</a></li>
                                <li><a class="menu-link" href="{{ route('admin.two_factor') }}">Two Factor</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-table') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-table') }}"></use>
                        </svg><span>Tables</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="submenu-title" href="javascript:void(0)">Bootstrap Tables<span
                                    class="sub-arrow"><i class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.bootstrap_basic_table') }}">Basic Tables</a></li>
                                <li><a class="menu-link" href="{{ route('admin.table_components') }}">Table components</a></li>
                            </ul>
                        </li>
                        <li><a class="submenu-title" href="javascript:void(0)">Data Tables<span class="sub-arrow"><i
                                        class="fa fa-angle-right"></i></span></a>
                            <ul class="nav-sub-childmenu submenu-content">
                                <li><a class="menu-link" href="{{ route('admin.datatable_basic_init') }}">Basic Init</a></li>
                                <li> <a class="menu-link" href="{{ route('admin.datatable_advance_init') }}">Advance Init </a></li>
                                <li> <a class="menu-link" href="{{ route('admin.datatable_api') }}">API </a></li>
                                <li><a class="menu-link" href="{{ route('admin.datatable_data_source') }}">Data Sources</a></li>
                            </ul>
                        </li>
                        <li><a class="menu-link" href="{{ route('admin.datatable_ext_autofill') }}">Ex. Data Tables</a></li>
                        <li><a class="menu-link" href="{{ route('admin.jsgrid_table') }}">Js Grid Table </a></li>
                    </ul>
                </li>
                <li class="sidebar-main-title">
                    <div>
                        <h6>Components</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-ui-kits') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-ui-kits') }}"></use>
                        </svg><span>Ui Kits</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.typography') }}">Typography</a></li>
                        <li><a class="menu-link" href="{{ route('admin.avatars') }}">Avatars</a></li>
                        <li><a class="menu-link" href="{{ route('admin.helper_classes') }}">helper classes</a></li>
                        <li><a class="menu-link" href="{{ route('admin.grid') }}">Grid</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tag_pills') }}">Tag & pills</a></li>
                        <li><a class="menu-link" href="{{ route('admin.progress_bar') }}">Progress</a></li>
                        <li><a class="menu-link" href="{{ route('admin.modal') }}">Modal</a></li>
                        <li><a class="menu-link" href="{{ route('admin.alert') }}">Alert</a></li>
                        <li><a class="menu-link" href="{{ route('admin.popover') }}">Popover</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tooltip') }}">Tooltip</a></li>
                        <li><a class="menu-link" href="{{ route('admin.dropdown') }}">Dropdown</a></li>
                        <li><a class="menu-link" href="{{ route('admin.according') }}">Accordion</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tabs') }}">Tabs</a></li>
                        <li><a class="menu-link" href="{{ route('admin.list') }}">Lists</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-bonus-kit') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-bonus-kit') }}"></use>
                        </svg><span>Bonus Ui</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.scrollable') }}">Scrollable</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tree') }}">Tree view</a></li>
                        <li><a class="menu-link" href="{{ route('admin.toasts') }}">Toasts</a></li>
                        <li><a class="menu-link" href="{{ route('admin.rating') }}">Rating</a></li>
                        <li><a class="menu-link" href="{{ route('admin.dropzone') }}">dropzone</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tour') }}">Tour</a></li>
                        <li><a class="menu-link" href="{{ route('admin.sweet_alert2') }}">SweetAlert2</a></li>
                        <li><a class="menu-link" href="{{ route('admin.modal_animated') }}">Animated Modal</a></li>
                        <li><a class="menu-link" href="{{ route('admin.owl_carousel') }}">Owl Carousel</a></li>
                        <li><a class="menu-link" href="{{ route('admin.ribbons') }}">Ribbons</a></li>
                        <li><a class="menu-link" href="{{ route('admin.pagination') }}">Pagination</a></li>
                        <li><a class="menu-link" href="{{ route('admin.breadcrumb') }}">Breadcrumb</a></li>
                        <li><a class="menu-link" href="{{ route('admin.range_slider') }}">Range Slider</a></li>
                        <li><a class="menu-link" href="{{ route('admin.image_cropper') }}">Image cropper</a></li>
                        <li><a class="menu-link" href="{{ route('admin.basic_card') }}">Basic Card</a></li>
                        <li><a class="menu-link" href="{{ route('admin.creative_card') }}">Creative Card</a></li>
                        <li><a class="menu-link" href="{{ route('admin.dragable_card') }}">Draggable Card</a></li>
                        <li><a class="menu-link" href="{{ route('admin.timeline') }}">Timeline </a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-animation') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-animation') }}"></use>
                        </svg><span>Animation</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.animate') }}">Animate</a></li>
                        <li><a class="menu-link" href="{{ route('admin.scroll_reveal') }}">Scroll Reveal</a></li>
                        <li><a class="menu-link" href="{{ route('admin.aos') }}">AOS animation</a></li>
                        <li><a class="menu-link" href="{{ route('admin.tilt') }}">Tilt Animation</a></li>
                        <li><a class="menu-link" href="{{ route('admin.wow') }}">Wow Animation</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-icons') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-icons') }}"></use>
                        </svg><span>Icons</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.flag_icon') }}">Flag icon</a></li>
                        <li><a class="menu-link" href="{{ route('admin.font_awesome') }}">Fontawesome Icon</a></li>
                        <li><a class="menu-link" href="{{ route('admin.ico_icon') }}">Ico Icon</a></li>
                        <li><a class="menu-link" href="{{ route('admin.themify_icon') }}">Themify Icon</a></li>
                        <li><a class="menu-link" href="{{ route('admin.feather_icon') }}">Feather icon</a></li>
                        <li><a class="menu-link" href="{{ route('admin.whether_icon') }}">Whether Icon</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-button') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-botton') }}"></use>
                        </svg><span>Buttons</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.buttons') }}">Default Style</a></li>
                        <li><a class="menu-link" href="{{ route('admin.buttons_flat') }}">Flat Style</a></li>
                        <li><a class="menu-link" href="{{ route('admin.buttons_edge') }}">Edge Style</a></li>
                        <li><a class="menu-link" href="{{ route('admin.raised_button') }}">Raised Style</a></li>
                        <li><a class="menu-link" href="{{ route('admin.button_group') }}">Button Group</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-charts') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-charts') }}"></use>
                        </svg><span>Charts</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.echarts') }}">Echarts</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_apex') }}">Apex Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_google') }}">Google Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_sparkline') }}">Sparkline chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_flot') }}">Flot Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_knob') }}">Knob Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_morris') }}">Morris Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chartjs') }}">Chatjs Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chartist') }}">Chartist Chart</a></li>
                        <li><a class="menu-link" href="{{ route('admin.chart_peity') }}">Peity Chart</a></li>
                    </ul>
                </li>
                <li class="sidebar-main-title">
                    <div>
                        <h6>Pages</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.sample_page') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-sample-page') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-sample-page') }}"></use>
                        </svg><span>Sample page</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link"
                        href="{{ route('admin.internationalization') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-internationalization') }}">
                            </use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-internationalization') }}">
                            </use>
                        </svg><span>Internationalization</span></a></li>
                <li class="mega-menu sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-others') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-others') }}"></use>
                        </svg><span>Others</span></a>
                    <div class="mega-menu-container menu-content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col mega-box">
                                    <div class="link-section">
                                        <div class="submenu-title">
                                            <h5>Error Page</h5>
                                        </div>
                                        <ul class="submenu-content opensubmegamenu">
                                            <li><a class="menu-link" href="{{ route('admin.error_400') }}">Error 400</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.error_401') }}">Error 401</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.error_403') }}">Error 403</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.error_404') }}">Error 404</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.error_500') }}">Error 500</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.error_503') }}">Error 503</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col mega-box">
                                    <div class="link-section">
                                        <div class="submenu-title">
                                            <h5> Authentication</h5>
                                        </div>
                                        <ul class="submenu-content opensubmegamenu">
                                            <li><a class="menu-link" href="{{ route('admin.login') }}" target="_blank">Login
                                                    Simple</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.login_one') }}" target="_blank">Login bg
                                                    image</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.login_two') }}" target="_blank">Login
                                                    image two </a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.login_bs_validation') }}"
                                                    target="_blank">Login
                                                    validation</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.login_bs_tt_validation') }}"
                                                    target="_blank">Login
                                                    tooltip</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.login_sa_validation') }}"
                                                    target="_blank">Login
                                                    sweetalert</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.sign_up') }}" target="_blank">Register
                                                    Simple</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.sign_up_one') }}" target="_blank">Register
                                                    Bg-Image</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.sign_up_two') }}" target="_blank">Register
                                                    two-image
                                                </a></li>
                                            <li><a class="menu-link" href="{{ route('admin.sign_up_wizard') }}"
                                                    target="_blank">Register
                                                    wizard</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.unlock') }}">Unlock User</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.forget_password') }}">Forget Password</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.reset_password') }}">Reset Password</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.maintenance') }}">Maintenance</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col mega-box">
                                    <div class="link-section">
                                        <div class="submenu-title">
                                            <h5>Coming Soon</h5>
                                        </div>
                                        <ul class="submenu-content opensubmegamenu">
                                            <li><a class="menu-link" href="{{ route('admin.comingsoon') }}">Coming Simple</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.comingsoon_bg_video') }}">Coming with Bg
                                                    video</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.comingsoon_bg_img') }}">Coming with Bg
                                                    Image</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col mega-box">
                                    <div class="link-section">
                                        <div class="submenu-title">
                                            <h5>Email templates</h5>
                                        </div>
                                        <ul class="submenu-content opensubmegamenu">
                                            <li><a class="menu-link" href="{{ route('admin.basic_template') }}">Basic Email</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.email_header') }}">Basic With Header</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.template_email') }}">Ecomerce Tem...</a>
                                            </li>
                                            <li><a class="menu-link" href="{{ route('admin.template_email_2') }}">Email Template
                                                    2</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.ecommerce_templates') }}">Ecommerce
                                                    Email</a></li>
                                            <li><a class="menu-link" href="{{ route('admin.email_order_success') }}">Order
                                                    Success</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="sidebar-main-title">
                    <div>
                        <h6>Miscellaneous</h6>
                    </div>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-gallery') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-gallery') }}"></use>
                        </svg><span>Gallery</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.gallery') }}">Gallery Grid</a></li>
                        <li><a class="menu-link" href="{{ route('admin.gallery_with_description') }}">Gallery Grid Desc</a></li>
                        <li><a class="menu-link" href="{{ route('admin.gallery_masonry') }}">Masonry Gallery</a></li>
                        <li><a class="menu-link" href="{{ route('admin.masonry_gallery_with_disc') }}">Masonry with Desc</a></li>
                        <li><a class="menu-link" href="{{ route('admin.gallery_hover') }}">Hover Effects</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-blog') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-blog') }}"></use>
                        </svg><span>Blog</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.blog') }}">Blog Details</a></li>
                        <li><a class="menu-link" href="{{ route('admin.blog_single') }}">Blog Single</a></li>
                        <li><a class="menu-link" href="{{ route('admin.add_post') }}">Add Post</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.faq') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-faq') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-faq') }}"></use>
                        </svg><span>FAQ</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-job-search') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-job-search') }}"></use>
                        </svg><span>Job Search</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.job_cards_view') }}">Cards view</a></li>
                        <li><a class="menu-link" href="{{ route('admin.job_list_view') }}">List View</a></li>
                        <li><a class="menu-link" href="{{ route('admin.job_details') }}">Job Details</a></li>
                        <li><a class="menu-link" href="{{ route('admin.job_apply') }}">Apply</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-learning') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-learning') }}"></use>
                        </svg><span>Learning</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.learning_list_view') }}">Learning List</a></li>
                        <li><a class="menu-link" href="{{ route('admin.learning_detailed') }}">Detailed Course</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-maps') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-maps') }}"></use>
                        </svg><span>Maps</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.map_js') }}">Maps JS</a></li>
                        <li><a class="menu-link" href="{{ route('admin.vector_map') }}">Vector Maps</a></li>
                    </ul>
                </li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a class="sidebar-link sidebar-title"
                        href="javascript:void(0)">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-editors') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-editors') }}"></use>
                        </svg><span>Editors</span></a>
                    <ul class="sidebar-submenu">
                        <li><a class="menu-link" href="{{ route('admin.quill_editor') }}">Quill editor</a></li>
                        <li><a class="menu-link" href="{{ route('admin.ace_code_editor') }}">ACE code editor </a></li>
                    </ul>
                </li>
                <li class="sidebar-list"> <i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.knowledgebase') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-knowledgebase') }}"></use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-knowledgebase') }}"></use>
                        </svg><span>Knowledgebase</span></a></li>
                <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                        class="sidebar-link sidebar-title link-nav menu-link" href="{{ route('admin.support_ticket') }}">
                        <svg class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-support-tickets') }}">
                            </use>
                        </svg>
                        <svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-support-tickets') }}"></use>
                        </svg><span>Support Ticket</span></a></li>
            </ul>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</div>
<!-- Page Sidebar Ends-->

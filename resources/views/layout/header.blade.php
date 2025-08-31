<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 d-flex align-items-center header-left">
                                <span class="header-toggle me-3">
                                    <i class="ti ti-category"></i>
                                </span>

                                <div class="header-searchbar">
                                    <form class="me-3 app-form app-icon-form " action="#">
                                        <div class="position-relative">
                                            <input type="search" class="form-control" placeholder="Search..."
                                                aria-label="Search">
                                            <i class="ti ti-search text-dark"></i>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="col-6 d-flex align-items-center justify-content-end header-right">
                                <ul class="d-flex align-items-center">
                                    <li class="header-search">
                                        <a href="#" class="d-block head-icon" role=button
                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop"
                                            aria-controls="offcanvasTop">
                                            <i class="ti ti-search"></i>
                                        </a>

                                        <div class="offcanvas offcanvas-top search-canvas" tabindex="-1"
                                            id="offcanvasTop">
                                            <div class="offcanvas-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-grow-1">
                                                        <form class="me-3 app-form app-icon-form " action="#">
                                                            <div class="position-relative">
                                                                <input type="search" class="form-control"
                                                                    placeholder="Search..." aria-label="Search">
                                                                <i class="ti ti-search f-s-15"></i>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                                        aria-label="Close"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    {{-- <li class="header-language">
                                        <div id="lang_selector" class="flex-shrink-0 dropdown">
                                            <a href="#" class="d-block head-icon ps-0" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <div class="lang-flag lang-en ">
                                                    <span class="flag rounded-circle overflow-hidden">
                                                        <i class=""></i>
                                                    </span>
                                                </div>
                                            </a>
                                            <ul
                                                class="dropdown-menu language-dropdown dropdown-menu-end header-card border-0 p-0">
                                                <li>
                                                    <div class="card-header bg-primary py-2">
                                                        <h5 class="text-white">language <span class="float-end"><i
                                                                    class="ti ti-language"></i></span></h5>
                                                    </div>
                                                </li>
                                                <li class="lang lang-en selected dropdown-item p-2" title="US">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-usa flag-icon-squared b-r-50 f-s-22"></i>
                                                        <span class="ps-2 text-secondary">US</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-pt dropdown-item p-2" title="FR">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-fra flag-icon-squared b-r-50 f-s-22"></i>
                                                        <span class="ps-2 text-secondary">France</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" title="UK">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-gbr flag-icon-squared b-r-50 f-s-22"></i>
                                                        <span class="ps-2 text-secondary">UK</span>
                                                    </span>
                                                </li>
                                                <li class="lang lang-es dropdown-item p-2" title="IT">
                                                    <span class="d-flex align-items-center">
                                                        <i
                                                            class="flag-icon flag-icon-ita flag-icon-squared b-r-50 f-s-22"></i>
                                                        <span class="ps-2 text-secondary">Italy</span>
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                    </li> --}}

                                    {{-- <li class="header-apps">
                                        <div class="flex-shrink-0 app-dropdown">
                                            <a href="#" class="d-block head-icon" data-bs-toggle="dropdown"
                                                data-bs-auto-close="true" aria-expanded="false">
                                                <i class="ti ti-apps"></i>
                                            </a>
                                            <div class="dropdown-menu headerapp-dropdown dropdown-menu-center bg-transparent border-0">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <h5 class="text-white">Shortcut
                                                            <span><i class="ti ti-apps text-white"></i></span>
                                                        </h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row row-cols-3">
                                                            <div class="d-flex-center text-center mb-3">
                                                                <a href="#">
                                                                    <span
                                                                        class="text-light-success h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-shopping-bag f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">E-shop</p>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex-center text-center mb-3">
                                                                <a href="#">
                                                                    <span
                                                                        class="text-light-primary h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-mail f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">Email</p>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex-center text-center mb-3">
                                                                <a href="#">
                                                                    <span
                                                                        class="text-light-info h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-brand-hipchat f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">Chat</p>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex-center text-center">
                                                                <a href="#">
                                                                    <span
                                                                        class="text-light-warning h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-presentation f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">Project</p>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex-center text-center">
                                                                <a href="#">
                                                                    <span
                                                                        class="text-light-secondary h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-file-dollar f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">Invoice</p>
                                                                </a>
                                                            </div>
                                                            <div class="d-flex-center text-center">
                                                                <a href="{{ 'blog' }}">
                                                                    <span
                                                                        class="text-light-danger h-60 w-60 d-flex-center b-r-100">
                                                                        <i class="ti ti-news f-s-26"></i>
                                                                    </span>
                                                                    <p class="mt-2 f-w-500 text-muted">Blog</p>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="button" class="btn btn-primary w-100"><i
                                                                class="ti ti-plus"></i> View
                                                            More</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li> --}}

                                    {{-- <li class="header-cart d-none d-sm-block">
                                        <div class="flex-shrink-0 app-dropdown">
                                            <a href="#" class="d-block head-icon position-relative"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="false">
                                                <i class="ti ti-shopping-cart"></i>
                                                <span
                                                    class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification">4</span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end bg-transparent border-0">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <h5 class="text-white">Cart <span class="float-end"><i
                                                                    class="ti ti-shopping-cart"></i></span></h5>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="head-container app-scroll">
                                                            <div class="head-box">
                                                                <img src="{{ asset('assets/images/ecommerce/10.png') }}"
                                                                    alt="cart"
                                                                    class="h-50 me-2 bg-light-secondary p-1 b-r-100">
                                                                <div class="flex-grow-1">
                                                                    <a class="mb-0 text-dark f-w-500"
                                                                        href="#">
                                                                        Headphone
                                                                    </a>
                                                                    <p class="text-muted">$95 x 2</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <div>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12  star-filled"></i>
                                                                        <i
                                                                            class="ti ti-star-filled f-s-12 star-filled"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="head-box">
                                                                <img src="{{ asset('assets/images/ecommerce/07.png') }}"
                                                                    alt="cart"
                                                                    class="h-50 object-fit-cover me-2 bg-light-dark p-1 b-r-100">
                                                                <div class="flex-grow-1">
                                                                    <a class="mb-0 text-dark f-w-500"
                                                                        href="#"> Smart
                                                                        Watch</a>
                                                                    <p class="text-muted">$50.00</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <div>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12  star-filled"></i>
                                                                        <i
                                                                            class="ti ti-star-filled f-s-12 star-filled"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="head-box">
                                                                <img src="{{ asset('assets/images/ecommerce/02.png') }}"
                                                                    alt="cart"
                                                                    class="h-50 object-fit-cover me-2 bg-light-dark p-1 b-r-100">
                                                                <div class="flex-grow-1">
                                                                    <a class="mb-0 text-dark f-w-500"
                                                                        href="#">Shoes
                                                                        Black</a>
                                                                    <p class="text-muted">$150.00</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <div>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled f-s-12  star-filled"></i>
                                                                        <i
                                                                            class="ti ti-star-filled f-s-12 star-filled"></i>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="head-box ">
                                                                <img src="{{ asset('assets/images/ecommerce/04.png') }}"
                                                                    alt="cart"
                                                                    class="h-50 object-fit-cover me-2 bg-light-dark p-1 b-r-100">
                                                                <div class="flex-grow-1">
                                                                    <a class="mb-0 text-dark f-w-500"
                                                                        href="#"> Sports
                                                                        shoes</a>
                                                                    <p class="text-muted">$200.00</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i
                                                                        class="ti ti-x text-dark f-s-15 close-btn last-box"></i>
                                                                    <div>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning f-s-12  star-filled"></i>
                                                                        <i
                                                                            class="ti ti-star-filled text-warning  f-s-12 star-filled"></i>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="hidden-massage py-4 px-3">
                                                                <img src="{{ asset('assets/images/icons/cart.png') }}"
                                                                    alt="cart" class="w-50 h-50 mb-3">
                                                                <div>
                                                                    <h6 class="mb-0">Your Cart is Empty</h6>
                                                                    <p class="text-secondary mb-0">Add some items :)
                                                                    </p>
                                                                    <a class="btn btn-light-primary btn-xs mt-2"
                                                                        href="#">Shop
                                                                        Now</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <div class="mb-2">
                                                            <h6 class="text-muted">Total <span
                                                                    class="float-end text-dark f-w-600">$495.00
                                                                </span></h6>
                                                        </div>
                                                        <button type="button" class="btn btn-primary w-100">
                                                            <i class="ti ti-shopping-cart"></i> Checkout</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li> --}}

                                    <li class="header-dark head-icon">
                                        <div class="sun-logo">
                                            <i class="ti ti-moon-off"></i>
                                        </div>
                                        <div class="moon-logo">
                                            <i class="ti ti-moon-filled"></i>
                                        </div>
                                    </li>
                                    {{-- 
                                    <li class="header-notification">
                                        <div class="flex-shrink-0 app-dropdown">
                                            <a href="#" class="d-block head-icon position-relative"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                aria-expanded="false">
                                                <i class="ti ti-bell"></i>
                                                <span
                                                    class="position-absolute translate-middle p-1 bg-success border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__slower"></span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end bg-transparent border-0">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <h5 class="text-white">Notification <span class="float-end">
                                                                <i class="ti ti-bell text-white"></i></span></h5>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="head-container app-scroll">
                                                            <div class="head-box">
                                                                <span
                                                                    class="bg-secondary h-35 w-35 d-flex-center b-r-50 position-relative">
                                                                    <img src="{{ asset('assets/images/ai_avtar/6.jpg') }}"
                                                                        alt="" class="img-fluid b-r-50">
                                                                    <span
                                                                        class="position-absolute bottom-0 end-0 p-1 bg-secondary border border-light rounded-circle"></span>
                                                                </span>
                                                                <div class="flex-grow-1 ps-2">
                                                                    <h6 class="mb-0 ">Gene Hart</h6>
                                                                    <p class="text-secondary f-s-13"> New account
                                                                        created</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <p class="f-s-12 text-muted">sep 23</p>
                                                                </div>
                                                            </div>
                                                            <div class="head-box">
                                                                <span
                                                                    class="text-light-primary h-40 w-40 d-flex-center b-r-50">
                                                                    <i class="ti ti-gift text-primary f-s-22"></i>
                                                                </span>
                                                                <div class="flex-grow-1 ps-2">
                                                                    <h6 class="mb-0">Gift-Voucher</h6>
                                                                    <p class="text-secondary f-s-13">50% sale active
                                                                    </p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <p class="f-s-12 text-muted">min 02</p>
                                                                </div>
                                                            </div>
                                                            <div class="head-box">
                                                                <span
                                                                    class="bg-secondary h-35 w-35 d-flex-center b-r-50 position-relative">
                                                                    <img src="{{ asset('assets/images/ai_avtar/4.jpg') }}"
                                                                        alt="" class="img-fluid b-r-50">
                                                                    <span
                                                                        class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                                                </span>
                                                                <div class="flex-grow-1 ps-2">
                                                                    <h6 class="mb-0">Simon Young</h6>
                                                                    <p class="text-secondary f-s-13">Hello ..!</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <p class="f-s-12 text-muted">Oct 10</p>
                                                                </div>
                                                            </div>
                                                            <div class="head-box">
                                                                <span
                                                                    class="text-light-success h-40 w-40 d-flex-center b-r-50">
                                                                    <i
                                                                        class="ti ti-shopping-cart text-success f-s-22"></i>
                                                                </span>
                                                                <div class="flex-grow-1 ps-2">
                                                                    <h6 class="mb-0">Order Massage</h6>
                                                                    <p class="text-secondary f-s-13">Purchase
                                                                        ecommerce..</p>
                                                                </div>
                                                                <div class="text-end">
                                                                    <i class="ti ti-x text-dark f-s-15 close-btn"></i>
                                                                    <p class="f-s-12 text-muted">day 4</p>
                                                                </div>
                                                            </div>
                                                            <div class="hidden-massage py-4 px-3">
                                                                <img src="{{ asset('assets/images/icons/bell.png') }}"
                                                                    class="w-50 h-50 mb-3 mt-2" alt="">
                                                                <div>
                                                                    <h6 class="mb-0">Notification Not Found</h6>
                                                                    <p class="text-secondary">When you have any
                                                                        notifications added here,will
                                                                        appear here.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button type="button" class="btn btn-primary w-100">
                                                            <i class="ti ti-plus"></i> View All</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li> --}}

                                    <li class="header-profile">
                                        <div class="flex-shrink-0 dropdown">
                                                                                         <a href="#" class="d-block head-icon pe-0" data-bs-toggle="dropdown"
                                                 aria-expanded="false">
                                                 @if(Auth::user()->getAvatarUrl())
                                                     <img src="{{ Auth::user()->getAvatarUrl() }}" alt="{{ Auth::user()->name }}"
                                                         class="rounded-circle h-35 w-35 object-fit-cover">
                                                 @else
                                                     <div class="rounded-circle h-35 w-35 d-flex align-items-center justify-content-center" style="{{ Auth::user()->getAvatarBackgroundStyle() }}">
                                                         <span class="text-white fw-bold fs-5">{{ Auth::user()->getInitials() }}</span>
                                                     </div>
                                                 @endif
                                             </a>
                                            <ul class="dropdown-menu dropdown-menu-end header-card border-0 px-2">
                                                <li class="dropdown-item d-flex align-items-center p-2">
                                                    <span class="h-35 w-35 d-flex-center b-r-50 position-relative">
                                                                                                                 @if(Auth::user()->getAvatarUrl())
                                                             <img src="{{ Auth::user()->getAvatarUrl() }}"
                                                                 alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle object-fit-cover" style="width: 35px; height: 35px;">
                                                         @else
                                                             <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px; {{ Auth::user()->getAvatarBackgroundStyle() }}">
                                                                 <span class="text-white fw-bold fs-6">{{ Auth::user()->getInitials() }}</span>
                                                             </div>
                                                         @endif
                                                        <span
                                                            class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__fast"></span>
                                                    </span>
                                                    <div class="flex-grow-1 ps-2">
                                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                                        <p class="f-s-12 mb-0 text-secondary">
                                                            {{ Auth::user()->role->pretty_name }}</p>
                                                    </div>
                                                </li>

                                                <li class="app-divider-v dotted py-1"></li>
                                                <li>
                                                    @if(Auth::user()->role->name === 'admin')
                                                        <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile Details
                                                        </a>
                                                    @elseif(Auth::user()->role->name === 'social_worker')
                                                        <a class="dropdown-item" href="{{ route('social.profile.edit') }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile Details
                                                        </a>
                                                    @elseif(Auth::user()->role->name === 'healthcare')
                                                        <a class="dropdown-item" href="{{ route('healthcare.profile.edit') }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile Details
                                                        </a>
                                                    @elseif(Auth::user()->role->name === 'law_enforcement')
                                                        <a class="dropdown-item" href="{{ route('law.profile.edit') }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile Details
                                                        </a>
                                                    @else
                                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                            <i class="ti ti-user-circle pe-1 f-s-18"></i> Profile Details
                                                        </a>
                                                    @endif
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="ti ti-notification pe-1 f-s-18"></i> Notification
                                                    </a>
                                                </li>

                                                <li class="app-divider-v dotted py-1"></li>
                                                <li class="btn-light-danger b-r-5">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item mb-0 text-danger">
                                                        <i class="ti ti-logout pe-1 f-s-18 text-danger"></i> Log Out
                                                    </button>
                                                </form>
                                            </li>

                                            </ul>
                                        </div>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Section ends -->

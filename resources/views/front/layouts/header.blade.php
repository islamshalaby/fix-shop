
<body>
<script>
    <!-- ======= Facebook login ======= -->
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '222878809766585',
            cookie     : true,
            xfbml      : true,
            version    : 'v11.0',
        });

        FB.AppEvents.logPageView();

    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>

    <!-- ======= sitebar Section ======= -->
    <div class="click-closed"></div>
    <!--/ Form Search Star /-->
    <div class="box-collapse">
        <div class="closeSidebars">
            <span class="close-box-collapse right-boxed bi bi-x"></span>
        </div>

        <div class="box-collapse-wrap form">
            <a href="#" class="LogoSidebars"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_112,h_101,q_100/v1581928924/{{ $settings->logo }}"></a>
            <div class="flex-shrink-0 bg-white">
                <a href="#" class="d-flex align-items-left pb-3 mb-3 link-dark text-decoration-none border-bottom">
                    <span class="fs-5 fw-semibold">التسوق حسب التصنيف</span>
                </a>
                <ul class="list-unstyled ps-0">
                    @if (count(config('cats')) > 0)
                    @foreach (config('cats') as $item)
                    <li class="mb-1">
                        <a href="{{ $item->next_level == false ? route('front.products_ar') . '?category_id=' . $item->id : '#' }}" class="btn btn-toggle {{ $item->next_level == true ? 'btn-dropdown' : '' }} rounded" data-bs-toggle="{{ $item->next_level == true ? 'collapse' : '' }}" data-bs-target="#home-collapse{{ $item->id }}" aria-expanded="false">
                            {{ $item->title_ar }}
                        </a>
                        @if ($item->next_level == true)
                        @foreach ($item->sub_categories as $scat)
                        <div class="collapse" id="home-collapse{{ $item->id }}" style="">
                            <ul class="btn-toggle-nav list-unstyled fw-normal ">
                                <li>
                                    <a href="{{ $scat->next_level == false ? route('front.products_ar') . '?category_id=' . $item->id . '&sub_category_id=' . $scat->id : '#' }}" class="btn btn-toggle {{ $scat->next_level == true ? 'btn-dropdown collapsed' : '' }} rounded" data-bs-toggle="{{ $scat->next_level == true ? 'collapse' : '' }}" data-bs-target="#Level2-collapse{{ $scat->id }}" aria-expanded="false">{{ $scat->title_ar }}</a>
                                    @if ($scat->next_level == true)
                                        @foreach ($scat->sub_categories as $scat2)
                                        <div class="collapse" id="Level2-collapse{{ $scat->id }}" style="">
                                            <ul class="btn-toggle-nav list-unstyled fw-normal  ">
                                                <li>
                                                    <a href="{{ $scat2->next_level == false ? route('front.products_ar') . '?category_id='. $item->id . '&sub_category_id=' . $scat->id . '&sub_category_two_id=' . $scat2->id : '#' }}" class="btn btn-toggle {{ $scat2->next_level == true ? 'btn-dropdown collapsed' : '' }} rounded" data-bs-toggle="{{ $scat2->next_level == true ? 'collapse' : ''}}" data-bs-target="#Level3-collapse{{ $scat2->id }}" aria-expanded="false">{{ $scat2->title_ar }}</a>
                                                    @if ($scat2->next_level == true)
                                                    @foreach ($scat2->sub_categories as $scat3)
                                                    <div class="collapse" id="Level3-collapse{{ $scat2->id }}" style="">
                                                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 ">
                                                            <li>
                                                                <a href="{{ $scat3->next_level == false ? route('front.products_ar') . '?category_id='. $item->id . '&sub_category_id=' . $scat->id . '&sub_category_two_id=' . $scat2->id . '&sub_category_three_id=' . $scat3->id : '#' }}" class="btn btn-toggle {{ $scat3->next_level == true ? 'btn-dropdown collapsed' : '' }} rounded" data-bs-toggle="{{ $scat3->next_level == true ? 'collapse' : '' }}" data-bs-target="#Level4-collapse{{ $scat3->id }}" aria-expanded="false">{{ $scat3->title_ar }}</a>
                                                                @if ($scat3->next_level == true)
                                                                @foreach ($scat3->sub_categories as $scat4)
                                                                <div class="collapse" id="Level4-collapse{{ $scat3->id }}" style="">
                                                                    <ul class="btn-toggle-nav list-unstyled fw-normal ">
                                                                        <li>
                                                                            <a href="{{ $scat4->next_level == false ? route('front.products_ar') . '?category_id='. $item->id . '&sub_category_id=' . $scat->id . '&sub_category_two_id=' . $scat2->id . '&sub_category_three_id=' . $scat3->id . '&sub_category_four_id=' . $scat4->id : '#' }}" class="btn btn-toggle {{ $scat4->next_level == true ? 'btn-dropdown collapsed' : '' }} rounded" data-bs-toggle="{{ $scat4->next_level == true ? 'collapse' : '' }}" data-bs-target="#Level5-collapse{{ $scat4->id }}" aria-expanded="false">{{ $scat4->title_ar }}</a>
                                                                            @if ($scat4->next_level == true)
                                                                            @foreach ($scat4->sub_categories as $scat5)
                                                                            <div class="collapse" id="Level5-collapse{{ $scat4->id }}" style="">
                                                                                <ul class="btn-toggle-nav list-unstyled fw-normal ">
                                                                                    <li><a href="{{ route('front.products_ar') . '?category_id='. $item->id . '&sub_category_id=' . $scat->id . '&sub_category_two_id=' . $scat2->id . '&sub_category_three_id=' . $scat3->id . '&sub_category_four_id=' . $scat4->id . '&&sub_category_four_id=' . $scat5->id }}" class="link-dark rounded">{{ $scat5->title_ar }}</a></li>
                                                                                </ul>
                                                                            </div> 
                                                                            @endforeach
                                                                            @endif
                                                                               
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                @endforeach
                                                                @endif
                                                                
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                    
                                                </li>
                                            </ul>
                                        </div>
                                        @endforeach
                                    @endif
                                    
                                </li>
                            </ul>
                        </div>
                        @endforeach 
                        @endif
                        
                    </li>
                    @endforeach
                    @endif
                    @if (auth()->guard('user')->user())
                    <li class="border-top my-3"></li>
                    <li class="mb-1">
                        <button class="btn btn-toggle btn-dropdown rounded collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                            الحساب الشخصى
                        </button>
                        <div class="collapse" id="account-collapse">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 smجمبع">
                                <li><a href="{{ route('front.profile') }}" class="link-dark rounded">الملف الشخصى</a></li>
                                <li><a href="#" class="link-dark rounded">الاعدادات</a></li>
                                <li><a href="{{ route('front.logout') }}" class="link-dark rounded">نسجيل الخروج</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif
                    
                </ul>
            </div>
        </div>
    </div>
    <!-- End sitebar Section -->

    <!-- ======= Header/Navbar ======= -->
    <header>
        <!-- TopBar -->
        <div id="topbar" class="d-flex align-items-center">
            <div class="container d-flex justify-content-between">
                <div class="d-none d-sm-flex TopBarRight align-items-center">
                    <a target="_blank" href="{{ route('terms', 'ar') }}" class="twitter"><i class="fa fa-arrow"></i> الشروط والأحكام</a>
                </div>
                <div class="contact-info d-flex TopBarLeft align-items-center">
                    <ul class="navbar-nav">
                        {{--  <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="bi bi-currency-exchange"></i>العمله</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="#">الدينار الكويتى (KWD)</a>
                                <a class="dropdown-item " href="#">الريال السعودى (SAR)</a>
                                <a class="dropdown-item " href="#">الجنيه المصرى (EGP)</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span><img
                                        src="/front/assets/img/fg-sa.PNG" border="0" /> </span>عربى</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="#"><span><img src="/front/assets/img/fg-usa.PNG" border="0" />
                                    </span>Einglish </a>
                                <a class="dropdown-item " href="#"><span><img src="/front/assets/img/fg-fr.PNG" border="0" />
                                    </span>French</a>
                            </div>
                        </li>  --}}
                        <li class="nav-item dropdown">

                            @if (isset(Auth::guard('user')->user()->id))
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-person-fill"></i>حسابى</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item " href="{{ route('front.orders') }}">الطلبات السابقة</a>
                                <a class="dropdown-item " href="{{ route('front.change.password') }}">تغيير كلمة المرور</a>
                                <a class="dropdown-item " href="{{ route('front.logout') }}">تسجيل الخروج</a>
                            </div>
                            @else
                            <a class="nav-link" href="{{ route('front.login') }}"><i class="bi bi-person-fill"></i>تسحيل الدخول</a>
                            @endif


                        </li>

                    </ul>

                </div>

            </div>
        </div>
        <!-- End TopBar -->

        <!-- Header Logo & Search -->
        <div class="container">
            <div class="inner-header">
                <div class="row align-items-end align-items-md-center">
                    <div class="col-lg-2 col-md-2 col-5 order-md-1">
                        <div class="logo">
                            <a href="/">
                                <img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_112,h_101,q_100/v1581928924/{{ $settings->logo }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 text-right col-md-3 col-7 order-md-3">
                        <div class="d-flex justify-content-end">
                            <ul class="nav-right">
                                <li class="heart-icon">
                                    <a href="{{ route('front.favorites') }}">
                                        <div> <i class="bi bi-heart"></i>
                                            @if (Auth::guard('user')->user())
                                            <span>{{ count(Auth::guard('user')->user()->favorites) }}</span>
                                            @endif
                                        </div>
                                        <p>
                                            المفضلة
                                        </p>
                                    </a>
                                </li>
                                <li class="cart-icon">
                                    <a href="#">
                                        <div>
                                            <i class="bi bi-bag"></i>
                                            <span>{{ count($carts) }}</span>
                                        </div>
                                        <p>العربه</p>
                                    </a>
                                    <div class="cart-hover">
                                        @if(count($carts) > 0)
                                        <div class="select-items">
                                            <table>
                                                <tbody>
                                                    @foreach ($carts as $item)
                                                    <tr class="cartSlide">
                                                        <td class="si-pic"><img src="https://res.cloudinary.com/{{ cloudinary_app_name() }}/image/upload/w_37,h_54,q_100/v1581928924/{{ $item->main_image }}" alt=""></td>
                                                        <td class="si-text">
                                                            <div class="product-selected">
                                                                <p>{{ $item->final_price }} {{ $currency->currency_ar }}</p>
                                                                <span style="font-size: 14px">الكمية : {{ $item->count }}</span>
                                                                <h6>{{ $item->title_ar }}</h6>
                                                            </div>
                                                        </td>
                                                        <td class="si-close">
                                                            <form action="{{ route('front.delete.cart') }}" method="post">
                                                                @csrf
                                                                <input name="_method" type="hidden" value="DELETE">
                                                                <input type="hidden" name="id" value="{{ $item->id }}"   />
                                                                <a href="#" class="remov-cart"><i class="bi bi-x"></i></a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="select-total">
                                            <span>الاجمالى:</span>
                                            <h5>{{ $totalAdded }} {{ $currency->currency_ar }}</h5>
                                        </div>
                                        <div class="select-button">
                                            <a href="{{ route('front.cart') }}" class="primary-btn view-card">عرض السله</a>
                                            <a href="{{ route('front.cart.payment') }}" class="primary-btn checkout-btn">الدفع</a>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7 order-md-2">
                        <form method="GET" action="{{ route('front.products.search') }}" >
                            <div class="input-group search_top select">
                                <select class="minimal" name="category" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                                    <option value="0">الكل</option>
                                    @if (count(config('cats')) > 0)
                                    @foreach (config('cats') as $item)
                                    <option {{ app('request')->input('category') !== null && app('request')->input('category') == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title_ar }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <span class="lineSearch"></span>
                                <input type="text" class="form-control" name="product" placeholder="بحث ..." value="{{ app('request')->input('product') !== null ? app('request')->input('product') : '' }}">

                                <span class="input-group-btn">
                                    <a href="#" class="btn search-submit btn-default hvr-icon-pop" type="button">بحث</a>
                                </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- End Header Logo & Search -->

        <!-- Nav -->
        <nav class="navbar navbar-default navbar-trans navbar-expand-lg fixed-top">
            <div class="container navbarTop">

                <button type="button" class="btn btn-b-n navbar-toggle-box navbar-toggle-box-collapse" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-container d-flex align-items-center justify-content-between">
                    <div id="navbar" class="navbar">
                        <ul>
                            <li><a class="nav-link  @if(Route::current()->getName() == 'front.home') active @endif" href="{{route('front.home')}}">الرئيسية</a></li>
                            <li class=""><a class="nav-link @if(Route::current()->getName() == 'front.about_ar') active @endif" href="{{route('front.about_ar')}}">عن التطبيق</a></li>
                            <li class="dropdown scr Mob-d"><a href="#"><span> جميع الفئات</span> <i
                                        class="bi bi-chevron-down"></i></a>
                                <ul>
                                    @if (count(config('cats')) > 0)
                                    @foreach (config('cats') as $item)
                                    <li class="{{ $item->next_level == true ? 'dropdown' : '' }}">
                                        <a href="{{ $item->next_level == false ? route('front.products_ar') . '?category_id=' . $item->id : '#' }}"><span>{{ $item->title_ar }}</span> 
                                            @if ($item->next_level == true)
                                            <i class="bi bi-chevron-left"></i>
                                            @endif
                                        </a>
                                        <ul>
                                            @if ($item->next_level == true)
                                                @foreach ($item->sub_categories as $scat)
                                                <li><a href="{{ $scat->next_level == false ? route('front.products_ar') . '?category_id=' . $item->id . '&sub_category_id=' . $scat->id : route('front.subcategories') . '?category_id=' . $item->id . '&sub_category_id=' . $scat->id }}">{{ $scat->title_ar }}</a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                    @endforeach
                                        
                                    @endif
                                </ul>
                            </li>
                            <li><a class="nav-link @if(Route::current()->getName() == 'front.offers') active @endif" href="{{route('front.offers')}}">العروض<span class="Hot">Hot</span></a></li>
                            <li class=""><a class="nav-link @if(Route::current()->getName() == 'front.contact_ar') active @endif " href="{{route('front.contact_ar')}}">تواصل معنا</a></li>

                        </ul>
                    </div>
                    <!-- .navbar -->

                </div>


            </div>
        </nav>
        <!-- End nav -->
    </header>
    <div class="container">
        @include('front.layouts.errors')
    </div>

    <!-- End Header/Navbar -->

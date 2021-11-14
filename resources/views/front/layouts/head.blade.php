<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    <meta content="@yield('description')" name="description">
    <meta content="{{ $meta->home_meta_ar }}" name="keywords">

    <!-- Favicons -->
    <link href="/front/assets/img/favicon.png" rel="icon">
    <link href="/front/assets/img/web-icon.png" rel="web-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- lib CSS Files -->
    <link href="/front/assets/lib/animate.css/animate.min.css" rel="stylesheet">
    <link href="/front/assets/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/front/assets/lib/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/front/assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/front/assets/lib/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="/front/assets/css/style-rtl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
    <style>
        .page-item.active .page-link {
            background-color: #ff7226 !important;
            border-color : #ff7226 !important;
        }
        a.page-link {
            color: #ff7226 !important;
        }
        .navbar .dropdown ul {
            right: 14px;
            left: auto
        }
        .navbar .dropdown>ul:before {
            right: 12%;
            left : auto
        }
        .navbar .dropdown .dropdown ul {
            right: auto;
        }
        .inner-header .nav-right li.cart-icon .cart-hover .select-total h5 {
            float : left
        }
        .inner-header .nav-right li.cart-icon .cart-hover .select-total span {
            float : right
        }
        .DiscountBox {
            padding: 4px 5px 0;
        }
        .iti--allow-dropdown {
            width : 100%
        }
    </style>

    
</head>
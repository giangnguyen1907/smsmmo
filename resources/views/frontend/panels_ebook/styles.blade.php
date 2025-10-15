<link rel="stylesheet" href="{{ asset('themes/frontend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/normalize.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/icomoon.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/owl.carousel.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/transitions.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/color.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/responsive.css') }}">
<link rel="stylesheet" href="{{ asset('themes/frontend/css/customer.css') }}">
<script src="{{ asset('themes/frontend/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css">

<link rel="stylesheet" href="{{ asset('/themes/frontend/select2/select2.min.css') }}">

<script src="{{ asset('/themes/frontend/js/jquery.min.js') }}"></script>
@isset($web_information->source_code->css)
    <style>
        {!! $web_information->source_code->css !!}
    </style>
@endisset
<style>
    :root {
        --primary-color: #da2b27;
        --linear-gradient: linear-gradient(to bottom, var(--primary-color), rgba(218, 43, 39, 0.2));
    }

    .tg-innerbanner {
        height: 150px !important;
    }

    .tg-innerbannercontent h1 {
        font-size: 25px;
    }

    .tg-breadcrumb {
        font-size: 14px;
    }

    .tg-sectionhead {
        padding: 0;
    }

    .tg-sectionhead h2 {
        font-size: 22px;
        line-height: 50px;
    }

    .tg-productcontent .tg-booktitle h3 {
        font-size: 22px !important;
        line-height: 25px !important;
    }

    .tg-newsdetail .tg-posttitle h3 {
        line-height: unset !important;
        font-size: 14px !important;

        display: -webkit-inline-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-weight: 600;
    }

    .line-1 {
        line-height: 1.5 !important;
    }

    .tg-newsdetail>.tg-posttitle h3 {
        font-size: 20px !important;
    }

    .tg-prevpost {
        height: 210px !important;
    }

    h3 {
        font-size: 22px;
    }

    @media (min-width: 992px) {

        .btn-down-hover {
            margin-top: -35px !important;
        }
    }


    /* .tg-btnstyletwo i {
   color: var(--primary-color);
  }

  .tg-btnstyletwo i, .tg-btnstyletwo em {
   color: var(--primary-color);
  }

  .btn-down-hover:hover {
   background: unset;
   opacity: .8;
  }
  .btn-down-hover {
   background: unset !important;
  }

  .tg-btn:hover, .tg-btn.tg-active {
   box-shadow: inset 0 -2px 0 0 transparent;
  } */

    @media (max-width: 767px) {
		.navbar-nav{
			margin: 0;
		}
		.navbar-nav .open .dropdown-menu > li > a {
			padding: 5px 0px 5px 0px;
		}
		.tg-innerbanner {
			height: 75px !important;
		}
        .modal-footer .btn {
            width: 100% !important;
            margin-bottom: 10px !important;
        }

        .modal-footer .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

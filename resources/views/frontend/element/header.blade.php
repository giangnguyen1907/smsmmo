<?php

$urlComponents = parse_url($_SERVER['REQUEST_URI']);
$home = '';
// Kiểm tra xem có tồn tại thành phần path và không rỗng hay không
if (isset($urlComponents['path']) && $urlComponents['path'] == '/') {
    $home = 'current-menu-item';
}
$total = 0;
?>

<header id="tg-header" class="tg-header tg-haslayout">
    <div class="tg-topbar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="tg-addnav">
                        <li title="{{ $web_information->information->email }}">
                            <a href="mailto: {{ $web_information->information->email }}">
                                <i class="icon-envelope"></i>
                                <em
                                    class="hidden-xs">{{ $array_translate[strtolower('Email')]->$locale ?? 'Email' }}</em>
                            </a>
                        </li>
                        <li title="{{ $web_information->information->phone }}">
                            <a href="callto: {{ $web_information->information->phone }}">
                                <i class="icon-phone"></i>
                                <em class="hidden-xs">{{ $web_information->information->phone }}</em>
                            </a>
                        </li>
                        <li class="hidden-lg hidden-sm hidden-md">
                            <a href="{{ route('frontend.cms.list-like-document') }}">
                                <i class="icon-heart"></i>
                            </a>
                        </li>
                        <li class="hidden-lg hidden-sm hidden-md">
                            <a href="{{ route('frontend.order.cart') }}">
                                <i class="icon-cart"></i>
                            </a>
                        </li>
                    </ul>

                    @if (Auth::check())
                        <div class="dropdown tg-themedropdown tg-currencydropdown" style="float: right;">
                            <a href="javascript:void(0);" class="tg-btnthemedropdown" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" id="tg-userlogin">
                                <i class="fa fa-user"></i>
                                <span>{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu tg-themedropdownmenu" aria-labelledby="tg-currenty">
                                <li>
                                    <a href="{{ route('frontend.user.index') }}">
                                        {{ $array_translate[strtolower('Personal profile')]->$locale ?? 'Personal profile' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="button-login" id="showBanthaoForm"
                                        data-toggle="modal" data-target="#banthaoModal">
                                        Gửi bản thảo
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        {{ $array_translate[strtolower('Surplus')]->$locale ?? 'Surplus' }}:
                                        {{ number_format(auth()->user()->wallet) . ' VNĐ' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('frontend.logout') }}">
                                        {{ $array_translate[strtolower('Logout')]->$locale ?? 'Logout' }}
                                    </a>
                            </ul>
                        </div>
                    @else
                        <div class="dropdown tg-themedropdown tg-currencydropdown" style="float: right;">
                            <a href="javascript:void(0);" class="tg-btnthemedropdown" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false" id="tg-userlogin">
                                <i class="fa fa-user"></i>
                                <span>{{ $array_translate[strtolower('Account')]->$locale ?? 'Account' }}</span>
                            </a>
                            <ul class="dropdown-menu tg-themedropdownmenu" aria-labelledby="tg-currenty">
                                <li>
                                    <a href="javascript:void(0);" class="button-login" id="showLoginForm"
                                        data-toggle="modal" data-target="#loginModal">
                                        {{ $array_translate[strtolower('Login')]->$locale ?? 'Login' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="button-register" id="showRegisterForm"
                                        data-toggle="modal" data-target="#loginModal">
                                        {{ $array_translate[strtolower('Register')]->$locale ?? 'Register' }}
                                    </a>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="tg-middlecontainer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <strong class="tg-logo"><a href="{{ route('frontend.home') }}"><img
                                src="{{ $web_information->image->logo_header }}" alt="company name here"></a></strong>
                    <div class="tg-wishlistandcart hidden-xs">

                        @if (Auth::check())
                            <div class="dropdown tg-themedropdown tg-wishlistdropdown">
                                <a href="{{ is_countable(auth()->user()->like_document) && count(auth()->user()->like_document) > 0 ? route('frontend.cms.list-like-document') : 'javascript:void(0)' }}"
                                    id="tg-wishlisst" class="tg-btnthemedropdown">
                                    <span
                                        class="tg-themebadge">{{ is_countable(auth()->user()->like_document) && count(auth()->user()->like_document) > 0 ? count(auth()->user()->like_document) : '' }}</span>
                                    <i class="icon-heart"></i>
                                    <span>{{ $array_translate[strtolower('Like')]->$locale ?? 'Like' }}</span>
                                </a>
                            </div>
                            <div class="dropdown tg-themedropdown tg-minicartdropdown">
                                <a href="{{ route('frontend.ebook.buyebook-document') }}" class="tg-btnthemedropdown">
                                    <span class="tg-themebadge"></span>
                                    <i class="fa fa-book"></i>
                                    <span>{{ $array_translate[strtolower('Purchased ebooks')]->$locale ?? 'Purchased ebooks' }}</span>
                                </a>
                            </div>
                        @else
                            <div class="dropdown tg-themedropdown tg-wishlistdropdown">
                                <a href="javascript:void(0);" id="tg-wishlisst" class="tg-btnthemedropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    onclick="checkLogin()">
                                    <span class="tg-themebadge"></span>
                                    <i class="icon-heart"></i>
                                    <span>{{ $array_translate[strtolower('Like')]->$locale ?? 'Like' }}</span>
                                </a>
                            </div>
                            <div class="dropdown tg-themedropdown tg-minicartdropdown">
                                <a href="javascript:void(0);" class="tg-btnthemedropdown" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" onclick="checkLogin()">
                                    <span class="tg-themebadge"></span>
                                    <i class="fa fa-book"></i>
                                    <span>{{ $array_translate[strtolower('Purchased ebooks')]->$locale ?? 'Purchased ebooks' }}</span>
                                </a>
                            </div>
                        @endif

                        {{-- giỏ hàng --}}
                        {{-- <div class="dropdown tg-themedropdown tg-minicartdropdown">
                            <a href="javascript:void(0);" id="tg-minicart" class="tg-btnthemedropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="tg-themebadge"
                                    id="countCart">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                                <i class="icon-cart"></i>
                                <span></span>
                            </a>
                            <div class="dropdown-menu tg-themedropdownmenu" aria-labelledby="tg-minicart"
                                id="listCart">
                                <div class="tg-minicartbody">
                                    @if (null !== session('cart'))
                                        @foreach (session('cart') as $id => $details)
                                            @php
                                                $total += $details['price'] * $details['quantity'];
                                                $temp = $details['price'] * $details['quantity'];
                                                $alias_detail = Str::slug($details['title']);
                                                $image = $details['image_thumb'] ?? $details['image'];
                                                $url_link = route('frontend.cms.view', [
                                                    'alias' => $alias_detail,
                                                ]);
                                            @endphp
                                            <div class="tg-minicarproduct">
                                                <figure>
                                                    <a href="{{ $url_link }}">
                                                        <img src="{{ $image }}" alt="image description"
                                                            style="object-fit: contain; width: 60px; height: 70px;">
                                                    </a>
                                                </figure>
                                                <div class="tg-minicarproductdata">
                                                    <h5><a href="{{ $url_link }}">{{ $details['title'] }}</a>
                                                    </h5>
                                                    <h6><a
                                                            href="javascript:void(0);">{{ number_format($details['price'] * $details['quantity']) }}&#8363;</a>
                                                    </h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="tg-minicarproduct">
                                            <figure>
                                                <span
                                                    class="text-center">{{ $array_translate[strtolower('Cart is empty')]->$locale ?? 'Cart is empty' }}!</span>
                                            </figure>
                                        </div>
                                    @endif
                                </div>
                                @if (null !== session('cart'))
                                    <div class="tg-minicartfoot">
                                        <a class="tg-btnemptycart" href="javascript:void(0);" onclick="clearCart()">
                                            <i class="fa fa-trash-o"></i>
                                            <span>{{ $array_translate[strtolower('Clear cart')]->$locale ?? 'Clear cart' }}</span>
                                        </a>
                                        <span
                                            class="tg-subtotal">{{ $array_translate[strtolower('Subtotal')]->$locale ?? 'Subtotal' }}:
                                            <strong>{{ number_format($total) }}&#8363;</strong></span>
                                        <div class="tg-btns">
                                            <a class="tg-btn tg-active"
                                                href="{{ route('frontend.order.cart') }}">{{ $array_translate[strtolower('View cart')]->$locale ?? 'View cart' }}</a>
                                            <a class="tg-btn"
                                                href="{{ route('frontend.order.tracking') }}">{{ $array_translate[strtolower('Order tracking')]->$locale ?? 'Order tracking' }}</a>
                                        </div>
                                    </div>
                                @else
                                    <div class="tg-minicartfoot">
                                        <div class="tg-btns">
                                            <a class="tg-btn tg-active"
                                                href="{{ route('frontend.order.cart') }}">{{ $array_translate[strtolower('View cart')]->$locale ?? 'View cart' }}</a>
                                            <a class="tg-btn"
                                                href="{{ route('frontend.order.tracking') }}">{{ $array_translate[strtolower('Order tracking')]->$locale ?? 'Order tracking' }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                    </div>

                    {{-- tìm kiếm --}}
                    <div class="tg-searchbox" style="height: unset;">
                        <form class="tg-formtheme tg-formsearch" action="{{ route('frontend.search.document') }}">
                            <fieldset>
                                <input type="text" name="search" class="typeahead form-control"
                                    placeholder="{{ $array_translate[strtolower('Search document')]->$locale ?? 'Search document' }}"
                                    value="{{ $keyword ?? '' }}">
                                <button type="submit"><i class="icon-magnifier"></i></button>
                            </fieldset>
                            <a
                                id="search-advance-btn">+{{ $array_translate[strtolower('Advanced Search')]->$locale ?? 'Advanced Search' }}</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- search advance form --}}
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner hidden" id="search-advance">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form action="{{ route('frontend.search.advanced') }}" class="form-horizontal" method="GET">
                        <div class="form-group form-center">
                            <div class="col-md-2">
                                <label class="form-control">
                                    <b>Bộ sưu tập:</b>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="category">
                                    <option value="">--Tất cả--</option>
                                    @foreach ($taxonomy_all as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['category']) && $params['category'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->title->$locale }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="input_category"
                                    value="{{ isset($params['input_category']) ? $params['input_category'] : '' }}">
                            </div>
                        </div>
                        <div class="form-group form-center">
                            <div class="col-md-2">
                                <select name="logical_operator2" class="form-control">
                                    @foreach (App\Consts::SELECT_OPERATOR as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['logical_operator2']) && $params['logical_operator2'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="colection2">
                                    {{-- <option value="">--Vui lòng chọn--</option> --}}
                                    @foreach (App\Consts::SELECT_COLECTION as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['colection2']) && $params['colection2'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="field2"
                                    value="{{ isset($params['field2']) ? $params['field2'] : '' }}">
                            </div>
                        </div>
                        <div class="form-group form-center">
                            <div class="col-md-2">
                                <select name="logical_operator3" class="form-control">
                                    @foreach (App\Consts::SELECT_OPERATOR as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['logical_operator3']) && $params['logical_operator3'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="colection3">
                                    {{-- <option value="">--Vui lòng chọn--</option> --}}
                                    @foreach (App\Consts::SELECT_COLECTION as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['colection3']) && $params['colection3'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="field3"
                                    value="{{ isset($params['field3']) ? $params['field3'] : '' }}">
                            </div>
                        </div>
                        <div class="form-group form-center">
                            <div class="col-md-2">
                                <select name="logical_operator4" class="form-control">
                                    @foreach (App\Consts::SELECT_OPERATOR as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['logical_operator4']) && $params['logical_operator4'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="colection4">
                                    {{-- <option value="">--Vui lòng chọn--</option> --}}
                                    @foreach (App\Consts::SELECT_COLECTION as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['colection4']) && $params['colection4'] == $key ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="field4"
                                    value="{{ isset($params['field4']) ? $params['field4'] : '' }}">
                            </div>
                        </div>
                        <div class="form-group padd-right-100">
                            <div class="col-md-8"></div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary mar-bottom-10"
                                    style="width: 100%">{{ $array_translate[strtolower('Search')]->$locale ?? 'Search' }}</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('frontend.search.advanced') }}">
                                    <button type="button" class="btn btn-primary mar-bottom-10"
                                        style="width: 100%">{{ $array_translate[strtolower('Reset')]->$locale ?? 'Reset' }}</button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        #search-advance-btn {
            cursor: pointer;
        }

        #search-advance {
            position: absolute;
            z-index: 2;
            margin-top: 20vh;
            height: auto !important;
            background: #f0f0f0;
            padding: 30px 50px 10px;
            animation: slideDown .1s forwards;
        }

        .hidden {
            display: none;
        }

        .form-center {
            display: flex;
            justify-content: center;
        }

        @keyframes slideDown {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            0% {
                transform: translateY(0);
                opacity: 1;
            }

            100% {
                transform: translateY(-100%);
                opacity: 0;
            }
        }

        @media (min-width: 993px) {
            .padd-right-100 {
                padding-right: 100px;
            }
        }

        @media (max-width: 992px) {
            #search-advance {
                margin-top: 30vh;
            }

            .form-center {
                display: block;
            }

            .mar-bottom-10 {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 400px) {
            #search-advance {
                margin-top: 35vh;
            }
        }

        @media (max-width: 390px) {
            #search-advance {
                margin-top: 40vh;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $searchBtn = document.getElementById('search-advance-btn');
            $searchForm = document.getElementById('search-advance');

            $searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if ($searchForm.classList.contains("hidden")) {
                    $searchForm.classList.remove('hidden');
                    $searchForm.style.animation = 'slideDown 0.5s forwards';
                } else {
                    $searchForm.style.animation = 'slideUp 0.5s forwards';
                    setTimeout(function() {
                        $searchForm.classList.add('hidden');
                    }, 1000);
                }
            })
        })
    </script>

    {{-- Danh mục header --}}
    <div class="tg-navigationarea">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <nav id="tg-nav" class="tg-nav">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#tg-navigation" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <div id="tg-navigation" class="collapse navbar-collapse tg-navigation">
                            <ul class="menu-header">
                                {{-- danh mục pc --}}
                                {{--
								<li class="menu-item-has-children menu-item-has-mega-menu menu-item-displaypc display-none-mobile">
                                    <a href="javascript:void(0);">{{ $array_translate[strtolower('Category')]->$locale ?? 'Category' }}</a>
									<div class="parent-menu-header display-none-mobile">
										<ul>
											@foreach ($taxonomy_all as $taxonomy)
												@php
													$url =url('') .'/' .$taxonomy->taxonomy .'/' .$taxonomy->url_part .'.html';
													$active = $url == url()->current() ? 'current-menu-item' : '';
													$hienthi = trim($taxonomy->hienthi, ';');
													$vitrihienthi = explode(';', $hienthi);
												@endphp

												@if ($taxonomy->taxonomy == 'document' and $taxonomy->parent_id == null || $taxonomy->parent_id == '')

														<div style="cursor: pointer;">
															@if (count($taxonomy->childMenus) > 0)
																<div class="group-parent-menu">
																	<li class="mega-parent-menu">{{ $taxonomy->title->$locale }}</li>
																	<i class="fa-solid fa-angle-right" style="padding-right: 20px"></i>
																</div>
																<ul class="child-menu-header">
																	@foreach ($taxonomy->childMenus as $item)
																		
																		<li><a href="{{ url('') . '/' . $item->taxonomy . '/' . $item->url_part . '.html' }}">{{ $item->title->$locale }}</a></li>
																	@endforeach
																</ul>
															@else
																<li><a href="{{ $url }}" style="color: #000">{{ $taxonomy->title->$locale }}</a></li>
															@endif
														</div>
												@endif
                                            @endforeach
											
										</ul>
									</div>
                                </li>
								--}}
                                {{-- danh mục mobile --}}
                                {{--
								<li class="menu-item-has-children menu-item-has-mega-menu display-none-pc">
                                    <a href="javascript:void(0);">{{ $array_translate[strtolower('Category')]->$locale ?? 'Category' }}</a>
                                    <div class="mega-menu">
                                        <ul class="tg-themetabnav" role="tablist">

                                            @foreach ($taxonomy_all as $taxonomy)
                                                @php
                                                    $url = url('') .'/' .$taxonomy->taxonomy .'/' .$taxonomy->url_part .'.html';
                                                    $active = $url == url()->current() ? 'current-menu-item' : '';
                                                    $hienthi = trim($taxonomy->hienthi, ';');
                                                    $vitrihienthi = explode(';', $hienthi);
                                                @endphp
                                                @if ($taxonomy->taxonomy == 'document' and $taxonomy->parent_id == null || $taxonomy->parent_id == '')
                                                    
													@if (count($taxonomy->childMenus) > 0)
													<li role="presentation" class="parent-menu">
														<a href="#artandphotography_{{ $taxonomy->id }}"
															aria-controls="artandphotography" role="tab"
															data-toggle="tab"
															data-menu-id="{{ $taxonomy->id }}">{{ $taxonomy->title->$locale }}</a>
													</li>
													@else
													<li role="presentation" class="">
														<a href="{{ $url }}" onclick="loadUrl('{{ $url }}')"
															aria-controls="artandphotography" role="tab"
															data-toggle="">{{ $taxonomy->title->$locale }}</a>
													</li> 
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
										
                                        <div class="tab-content tg-themetabcontent">
                                            @foreach ($taxonomy_all as $taxonomy)
                                                @if (count($taxonomy->childMenus) > 0 and $taxonomy->parent_id == null || $taxonomy->parent_id == '')
                                                    <div role="tabpanel" class="tab-pane "
                                                        id="artandphotography_{{ $taxonomy->id }}">
                                                        <ul>
                                                            @foreach ($taxonomy_all as $sub_taxonomy)
                                                                @if ($sub_taxonomy->parent_id == $taxonomy->id)
                                                                    <li>
                                                                        <div class="tg-linkstitle">
                                                                            <h2><a
                                                                                    href="{{ url('') . '/' . $sub_taxonomy->taxonomy . '/' . $sub_taxonomy->url_part . '.html' }}">
                                                                                    {{ $sub_taxonomy->title->$locale }}</a>
                                                                            </h2>
                                                                        </div>
                                                                        <ul>
                                                                            @foreach ($taxonomy_all as $sub_taxonomy2)
                                                                                @if ($sub_taxonomy2->parent_id == $sub_taxonomy->id)
                                                                                    <li><a
                                                                                            href="{{ url('') . '/' . $sub_taxonomy2->taxonomy . '/' . $sub_taxonomy2->url_part . '.html' }}">{{ $sub_taxonomy2->title->$locale }}</a>
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
										
                                    </div>
								</li>
								--}}
                                <?php
								foreach($taxonomy_all as $taxonomy){
									$url =  url('').'/'.$taxonomy->taxonomy.'/'.$taxonomy->url_part.'.html';
									$active = $url == url()->current() ? 'current-menu-item' : '';
									$hienthi = trim($taxonomy->hienthi,';');
									$vitrihienthi = explode(';',$hienthi); // chuyển về mảng
									if(in_array('0',$vitrihienthi)){
									  if(in_array($taxonomy->id,$array_category)){
									?>
                                <li class="menu-item-has-children">
                                    <a href="javascript:void(0);">{{ $taxonomy->title->$locale }}</a>
                                    <ul class="sub-menu">
                                        <?php $i=0; foreach($taxonomy_all as $sub_taxonomy){ 
												if($sub_taxonomy->parent_id == $taxonomy->id){ $i++; ?>
                                        <li><a
                                                href="{{ url('') . '/' . $sub_taxonomy->taxonomy . '/' . $sub_taxonomy->url_part . '.html' }}">{{ $sub_taxonomy->title->$locale }}</a>
                                        </li>
                                        <?php }} ?>
                                    </ul>
                                </li>
                                <?php }else{ ?>
                                <li class="{{ $active }}"><a
                                        href="{{ $url }}">{{ $taxonomy->title->$locale }}</a></li>
                                <?php  } } } ?>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- style menu header danh mục --}}
<style>
    .menu-item-displaypc:hover .parent-menu-header {
        display: block;
    }

    .group-parent-menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mega-parent-menu {
        color: #000;
    }

    .parent-menu-header {
        display: none;
        position: absolute;
        background-color: #fff;
        text-align: left;
        width: 230px;
        left: 0;
        box-shadow: 1px 1px 5px 1px #ccc;
    }

    .parent-menu-header ul {
        margin: 0;
    }

    .parent-menu-header ul li {
        padding: 10px 15px;
        border-bottom: 1px solid #fafafa;
    }

    .child-menu-header {
        display: none;
        position: absolute;
        left: 100%;
        background-color: #fafafa;
        box-shadow: 1px 1px 5px #ccc;
    }

    .child-menu-header li {
        padding: 10px 20px !important;
        border-bottom: 1px solid #ccc;
    }

    .child-menu-header li a {
        color: #000;
    }

    @media (min-width: 768px) {
        .modal-dialog.banthao {
            width: 1034px;
        }
    }
</style>
{{-- script menu header danh mục --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var parentMenus = document.querySelectorAll(".group-parent-menu");

        parentMenus.forEach(function(parentMenu) {
            var childMenu = parentMenu.nextElementSibling;
            var menuContainer = parentMenu.closest('.parent-menu-header');
            var hoverTimer;

            parentMenu.addEventListener("mouseenter", function() {
                clearTimeout(hoverTimer);
                childMenu.style.display = "block";
                var childMenuHeight = childMenu.offsetHeight;
                if (childMenuHeight > menuContainer.offsetHeight) {
                    childMenu.style.maxHeight = menuContainer.offsetHeight + "px";
                }
            });

            parentMenu.addEventListener("mouseleave", function() {
                // Đặt một khoảng thời gian chờ trước khi ẩn menu con
                hoverTimer = setTimeout(function() {
                    childMenu.style.display = "none";
                }, 200); // Đợi 500ms trước khi ẩn
            });

            childMenu.addEventListener("mouseenter", function() {
                clearTimeout(hoverTimer);
            });

            childMenu.addEventListener("mouseleave", function() {
                childMenu.style.display = "none";
            });
        });
    });
</script>

<div class="modal fade" id="banthaoModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog banthao" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('frontend.user.banthao') }}" class="form-horizontal" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">GỬI BẢN THẢO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="tacpham">Tên tác phẩm <span style="color: red"> * </span></label>
                            <input type="text" class="form-control" id="tacpham"
                                placeholder="Nhập tên tác phẩm..." name="tacpham" required>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="tacgia">Tên tác giả <span style="color: red"> * </span></label>
                            <input type="text" class="form-control" id="tacgia"
                                placeholder="Nhập tên tác giả..." name="tacgia" required>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="butdanh">Bút danh <span style="color: red"> * </span></label>
                            <input type="text" class="form-control" id="butdanh" placeholder="Nhập bút danh..."
                                name="butdanh" required>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="diachi">Địa chỉ liên hệ</label>
                            <input type="text" class="form-control" id="diachi"
                                placeholder="Nhập địa chỉ liên hệ..." name="diachi">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dienthoai">Điện thoại</label>
                            <input type="text" class="form-control" id="dienthoai"
                                placeholder="Nhập điện thoại..." name="dienthoai">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="email">Email <span style="color: red"> * </span></label>
                            <input type="email" class="form-control" id="email" placeholder="Nhập email..."
                                name="email" required>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="loginEmail">Thể loại <span style="color: red"> * </span></label>
                            <select class="form-control" name="theloai" id="theloai" required>
                                @foreach (App\Consts::THELOAI_BANTHAO as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="sotrang">Số trang</label>
                            <input type="text" class="form-control" id="sotrang" placeholder="Nhập số trang..."
                                name="sotrang">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="khuonkho">Khuôn khổ</label>
                            <input type="text" class="form-control" id="khuonkho"
                                placeholder="Nhập khuôn khổ..." name="khuonkho">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dinhdang">Định dạng</label>
                            <input type="text" class="form-control" id="dinhdang"
                                placeholder="Nhập định dạng..." name="dinhdang">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="dungluong">Dung lượng (MB)</label>
                            <input type="text" class="form-control" id="dungluong"
                                placeholder="Nhập dung lượng..." name="dungluong">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="lanxuatban">Lần xuất bản</label>
                            <input type="text" class="form-control" id="lanxuatban"
                                placeholder="Nhập lần xuất bản..." name="lanxuatban">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="lantaiban">Lần tái bản</label>
                            <input type="text" class="form-control" id="lantaiban"
                                placeholder="Nhập lần tái bản..." name="lantaiban">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="soluong">Số lượng (cuốn)</label>
                            <input type="text" class="form-control" id="soluong" placeholder="Nhập số lượng..."
                                name="soluong">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="nhain">Nhà in</label>
                            <input type="text" class="form-control" id="nhain" placeholder="Nhập nhà in..."
                                name="nhain">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="diachinhain">Địa chỉ nhà in</label>
                            <input type="text" class="form-control" id="diachinhain"
                                placeholder="Nhập địa chỉ nhà in..." name="diachinhain">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="trangweb">Xuất bản tại nền tảng</label>
                            <input type="text" class="form-control" id="trangweb" placeholder="Nhập nền tảng..."
                                name="trangweb">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="list_file_goc">Bản thảo đính kèm</label>
                            <input type="file" class="form-control" name="list_file_goc[]" multiple
                                title="Chọn file bản thảo"
                                accept=".doc,.docx,.pdf,.txt,.rtf,.odt,.xls,.xlsx,.csv,.ods,.ppt,.pptx,.odp">
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="noidung">Nội dung tóm tắt</label>
                            <textarea name="noidung" id="noidung" placeholder="Nhập nội dung..."></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Gửi bản
                        thảo</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Form Đăng nhập -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div id="errorMessages" style="color: red;"></div>

                <form id="loginForm" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title">Đăng nhập</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="loginEmail">Tài khoản</label>
                        <input type="email" class="form-control" id="loginEmail"
                            placeholder="Nhập tài khoản hoặc email..." name="email">
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Mật khẩu</label>
                        <input type="password" class="form-control" id="loginPassword" placeholder="Mật khẩu"
                            name="password">

                        <span class="pull-right"
                            style="font-style: italic; font-size: 12px; margin-top: 5px; cursor: pointer;"
                            onclick="switchToReset()">
                            Quên mật khẩu?</span>
                    </div>
                    <button type="button" class="btn btn-primary btn-block" onclick="submitLoginForm()">Đăng
                        nhập</button>
                    {{-- <hr> --}}
                    <br>
                    <div class="btn-login hidden-xs hidden-sm" style="border-right: unset;">
                        <button type="button" class="btn btn-primary btn-facebook"><a
                                href="{{ route('login.facebook') }}" style="color: #fff;">Đăng nhập với
                                Facebook</a></button>
                        <button type="button" class="btn btn-primary btn-google">
                            <a href="{{ route('login.google') }}" style="color: #fff;">Đăng nhập với
                                Google</a></button>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-light btn-block" onclick="switchToRegister()">Chưa có tài
                        khoản? Đăng ký ngay</button>
                </form>

                <!-- Form Đăng ký -->
                <form id="registerForm" style="display: none;" action="{{ route('frontend.register') }}"
                    method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Đăng ký</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="registerName">Họ và tên</label>
                        <input type="text" class="form-control" id="registerName" placeholder="Nhập họ và tên"
                            name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="registerEmail">Email</label>
                        <input type="email" class="form-control" id="registerEmail" placeholder="Nhập email"
                            name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Mật khẩu</label>
                        <input type="password" class="form-control" id="registerPassword" placeholder="Mật khẩu"
                            pattern=".{6,}" title="Mật khẩu phải có ít nhất 6 ký tự" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" id="confirmPassword"
                            placeholder="Nhập lại mật khẩu" pattern=".{6,}" title="Mật khẩu phải có ít nhất 6 ký tự">
                    </div>
                    <span id="passwordError" class="error" style="font-style: italic; color: red;"></span>
                    <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                    <hr>
                    <button type="button" class="btn btn-light btn-block" onclick="switchToLogin()">Đã có tài khoản?
                        Đăng nhập ngay</button>
                </form>

                {{-- Quên mật khẩu --}}
                <form id="sendEmailPassword" style="display: none;">
                    <div class="modal-header">
                        <h5 class="modal-title">Quên mật khẩu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="sendEmail">Email</label>
                        <input type="email" class="form-control" id="sendEmail" placeholder="Nhập email"
                            name="email">
                    </div>
                    <button type="button" class="btn btn-primary btn-block" id="submitSendEmail">Gửi</button>

                    <div>
                        <span id="emailSuccess" class="success" style="color: #3c763d;"></span>
                        <span id="emailError" class="error" style="font-style: italic; color: red;"></span>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-light btn-block" onclick="switchToRegister()">Chưa có tài
                        khoản? Đăng ký ngay</button>
                </form>

                {{-- Form reset password --}}
                <form action="{{ route('frontend.reset.password.post') }}" method="POST" id="resetPasswordForm"
                    style="display: none;">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Đặt lại mật khẩu</h5>
                        <input type="hidden" name="token" value="{{ $token ?? '' }}" id="token">

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="resetEmail">Email</label>
                        <input type="email" class="form-control" id="resetEmail" placeholder="Nhập email"
                            name="email">
                    </div>
                    <div class="form-group">
                        <label for="resetPassword">Mật khẩu</label>
                        <input type="password" class="form-control" id="resetPassword" placeholder="Mật khẩu"
                            pattern=".{6,}" title="Mật khẩu phải có ít nhất 6 ký tự" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="resetConfirmPassword">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" id="resetConfirmPassword"
                            placeholder="Nhập lại mật khẩu" pattern=".{6,}" title="Mật khẩu phải có ít nhất 6 ký tự"
                            name="password_confirmation">
                    </div>
                    <button type="button" class="btn btn-primary btn-block" id="submitResetPassword">Gửi</button>
                    <div>
                        <span id="resetSuccess" class="success" style="color: #3c763d;"></span>
                        <span id="resetError" class="error" style="font-style: italic; color: red;"></span>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-light btn-block" onclick="switchToLogin()">Đã có tài khoản?
                        Đăng nhập ngay</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- all --}}
<style>
    .tg-userlogin {
        display: flex;
        align-items: center;
    }

    .tg-currencydropdown .tg-themedropdownmenu li a span:before {
        display: none !important;
    }

    .button-login,
    .button-register {
        display: flex;
        justify-content: center !important;
    }

    .menu-header {
        white-space: nowrap;
        text-overflow: ellipsis;
        display: -webkit-inline-box !important;
    }

    @media (max-width: 991px) {
        .menu-header {
            display: block !important;
        }

        .display-none-mobile {
            display: none;
        }
    }

    @media (min-width: 992px) {
        .display-none-pc {
            display: none;
        }
    }
</style>

{{-- style form login --}}
<style>
    .modal-header {
        border-block: unset;
    }

    .modal-title {
        text-align: center;
        font-size: 20px;
        font-weight: 600;
    }

    .modal-header .close {
        font-size: 25px;
        opacity: 1;
        margin: 15px 10px 0 0;
        color: #000;
    }

    .modal-header .close:hover {
        opacity: 0.6;
    }

    .btn-login {
        display: flex;
        justify-content: space-between;
    }

    .btn-block {
        border-radius: 20px;
        padding: 10px;
    }

    .btn-facebook {
        width: 48%;
        padding: 10px;
        border-radius: 20px;
    }

    .btn-google {
        width: 48%;
        padding: 10px;
        border-radius: 20px;
    }
</style>
<style>
    .searchPopup {
        align-items: center;
        display: flex;
        justify-content: space-around;
        padding: 10px;
    }

    .inputPopup {
        border: none !important;
        width: 90%;
        border-right: 1px solid #ccc !important;
    }

    /* Style for the overlay */
    .popup-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        /* Semi-transparent black overlay */
        z-index: 9999;
        /* Ensure the popup is on top of other content */
    }

    /* Style for the popup content */
    .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50%;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    /* Style for the close button */
    .close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 20px;
        color: #aaa;
    }

    .close:hover {
        color: #000;
    }
</style>

{{-- button like --}}
<script></script>

<script>
    function toggleDangNhap() {
        var dangNhapDiv = document.getElementById("dang-nhap");
        if (dangNhapDiv.style.display === "block") {
            dangNhapDiv.style.display = "none";
        } else {
            dangNhapDiv.style.display = "block";
        }
    }
</script>

{{-- script form login --}}
<script>
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const closePopup = document.getElementById('closePopup');
    const sendForm = document.getElementById('sendEmailPassword');
    const resetForm = document.getElementById('resetPasswordForm');

    function switchToRegister() {
        loginForm.style.display = "none";
        sendForm.style.display = "none";
        resetForm.style.display = "none";
        registerForm.style.display = "block";
    }

    function switchToLogin() {
        registerForm.style.display = "none";
        sendForm.style.display = "none";
        resetForm.style.display = "none";
        loginForm.style.display = "block";
    }

    function switchToReset() {
        loginForm.style.display = "none";
        registerForm.style.display = "none";
        resetForm.style.display = "none";
        sendForm.style.display = "block";
    }

    document.getElementById("showLoginForm").addEventListener("click", function() {
        // console.log('a');
        loginForm.style.display = "block";
        registerForm.style.display = "none";
        sendForm.style.display = "none";
        resetForm.style.display = "none";
    });

    document.getElementById("showRegisterForm").addEventListener("click", function() {
        loginForm.style.display = "none";
        sendForm.style.display = "none";
        resetForm.style.display = "none";
        registerForm.style.display = "block";
    });

    function submitLoginForm() {
        var email = document.getElementById('loginEmail');
        var password = document.getElementById('loginPassword');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var f = "?email=" + email.value + "&password=" + password.value;
        var _url = "/login-post";

        jQuery.ajax({
            type: "POST",
            url: _url,
            data: {
                _token: "{{ csrf_token() }}",
                email: email.value,
                password: password.value
            },
            // data: f,
            // _token: "csrf_token()",
            // context: document.body,
            cache: false,
            success: function(data) {
                if (data.success) {
                    // alert("Đăng nhập thành công!");
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Tài khoản hoặc mật khẩu không đúng, vui lòng kiểm tra lại.");
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        closePopup.addEventListener('click', function() {
            loginForm.style.display = "none";
            registerForm.style.display = "none";
            sendForm.style.display = "none";
            resetForm.style.display = "none";
        })
    })

    function checkLogin() {
        $('#loginModal').modal('show');
        loginForm.style.display = "block";
        registerForm.style.display = "none";

        sendForm.style.display = "none";
        resetForm.style.display = "none";
    }
</script>


<script>
    // script form register confirm password
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        var name = $('#registerName').val();
        var email = $('#registerEmail').val();
        var password = $('#registerPassword').val();
        var confirmPassword = $('#confirmPassword').val();
        var passwordError = $('#passwordError');

        if (password !== confirmPassword) {
            passwordError.text('Mật khẩu không hợp lệ!');
            e.preventDefault();
            return;
        } else {
            passwordError.text('');
        }
    })

    // ajax send password
    $(document).on('click', '#submitSendEmail', function(e) {
        let email = $('#sendEmail').val();

        $.ajax({
            url: '{{ route('frontend.send.email.password') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                email: email
            },
            success: function(data) {
                if (data.success) {

                    $('#emailSuccess').text(data.message);

                    setTimeout(function() {
                        $('#emailSuccess').text('');
                        $('#sendEmail').val('');
                    }, 10000)
                } else {
                    $('#emailError').text(data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.response);
            }
        })
    })

    // hiện form reset password khi click từ gmail
    $(document).ready(function() {
        // Kiểm tra nếu URL chứa tham số resetPassword
        if (window.location.href.indexOf('reset-password-view') > -1) {
            // Hiển thị modal trước
            $('#loginModal').modal('show');

            // Hiển thị form reset password sau khi modal được hiển thị
            $('#resetPasswordForm').css('display', 'block');
        }
    });

    // ajax reset password form
    $(document).ready(function() {

        $('#submitResetPassword').on('click', function(e) {
            e.preventDefault();

            // validate confirm password
            var token = $('#token').val();
            var email = $('#resetEmail').val();
            var password = $('#resetPassword').val();
            var confirmPassword = $('#resetConfirmPassword').val();
            var passwordError = $('#resetError');

            if (password !== confirmPassword) {
                passwordError.text('Xác nhận mật khẩu không đúng!');
                e.preventDefault();
                return;
            } else {
                passwordError.text('');
            }

            $.ajax({
                url: '{{ route('frontend.reset.password.post') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email,
                    password: password,
                    token: token
                },
                success: function(data) {

                    if (data.success) {

                        $('#resetSuccess').text(data.message);
                        history.pushState(null, '', '{{ url('/') }}');

                        // Gửi AJAX request để logout
                        //  $.ajax({
                        // 	url: '{{ route('frontend.check.login') }}',
                        // 	type: 'GET',
                        // 	success: function (data) {
                        // 		if (data.loggedIn) {

                        // 			// Nếu người dùng đang đăng nhập, thực hiện logout
                        // 			$.ajax({
                        // 				url: '{{ route('frontend.logout') }}',
                        // 				type: 'GET',
                        // 				success: function () {
                        // 					// Sau khi logout thành công, cập nhật URL
                        // 					window.location.replace('{{ url('/') }}');
                        // 				},
                        // 				error: function (jqXHR, textStatus, errorThrown) {
                        // 					console.log('Logout error:', errorThrown);
                        // 				}
                        // 			});
                        // 		} else {
                        // 			// Nếu không có tài khoản nào đăng nhập, chuyển hướng đến trang chính
                        // 			window.location.replace('{{ url('/') }}');
                        // 		}
                        // 	},
                        // 	error: function (jqXHR, textStatus, errorThrown) {
                        // 		console.log('Logout error:', errorThrown);
                        // 	}
                        // });
                    } else {
                        $('#resetError').text(data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 422) {
                        let errors = jqXHR.responseJSON.errors;
                        if (errors.email) {
                            $('#resetError').text(errors.email[
                                0]); // Hiển thị lỗi của email
                        }
                        if (errors.password) {
                            $('#resetError').text(errors.password[
                                0]); // Hiển thị lỗi của mật khẩu
                        }
                    } else {
                        console.log(jqXHR.responseText);
                    }
                }
            });
        })
    });
</script>

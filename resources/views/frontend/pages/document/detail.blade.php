@extends('frontend.layouts.default')
@php
    $title_detail = $detail->title;
    $brief_detail = $detail->brief;
    $content = $detail->description;
    $image = $detail->image != '' ? $detail->image : null;

    $filepdf = $detail->filepdf;
    $txt_author = trim($detail->authors, ',');
    $arr_author = explode(',', $txt_author);

    // For taxonomy
    $taxonomy_title = json_decode($detail->taxonomy_title)->$locale ?? null;
    $taxonomy_url = '/' . $detail->taxonomy . '/' . $detail->taxonomy_url_part . '.html';

    $image_background = json_decode($detail->taxonomy_json_params)->image_background ?? null;

    $seo_title = $detail->meta_title ?? $title_detail;
    $seo_keyword = $detail->meta_keyword ?? null;
    $seo_description = $detail->meta_description ?? $brief_detail;
    $seo_image = $image ?? ($image_thumb ?? null);

    $filepdf = base64_encode(base64_encode($filepdf));

    $limit_page = $limit_page;
    // Kiểm tra xem có bắt phải đăng nhập hay không
    if ($detail->is_public == 1) {
        // Bắt đăng nhập
        if (!Auth::check()) {
            $limit_page = $limit_page;
        }
    }

    $encodeFilePdf = base64_encode($detail->filepdf);
    $encodeFileOther = base64_encode($detail->file_other);

    $check_status_sach = $detail->status_hang == 1;

    $randomNumber = time() . mt_rand(100, 999);

@endphp

@section('content')
    <link rel='stylesheet' id='wp-block-library-css' href="{{ asset('themes/frontend/css/dflip.min.css') }}" rel="stylesheet" />
    <link rel='stylesheet' id='wp-block-library-css' href="{{ asset('themes/frontend/css/themify-icons.min.css') }}" rel="stylesheet" />

    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ $web_information->image->bread_crumb ?? '' }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1 class="hidden">{{ $title_detail }}</h1>
                        <ol class="tg-breadcrumb">
                            <li><a href="/">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a></li>
                            <li><a href="{{ $taxonomy_url }}">{{ $taxonomy_title }}</a></li>

                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="tg-main" class="tg-main tg-haslayout">
        <div class="tg-sectionspace tg-haslayout">
            <div class="container">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div id="tg-twocolumns" class="tg-twocolumns">
                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9 pull-right">
                            <div id="tg-content" class="tg-content">
                                <div class="tg-productdetail">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="tg-postbook">
                                                <figure class="tg-featureimg" style="text-align: center;">
                                                    <img src="{{ $image }}" alt="{{ $title_detail }}"
                                                        style="height: 250px;">
                                                </figure>

                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="tg-productcontent">
                                                <div class="tg-booktitle">
                                                    <h3>{{ $title_detail }}</h3>
                                                </div>

                                                <div class="tg-description">
                                                    {{ $detail->brief }}
                                                </div>
                                                <ul class="tg-postmetadata" style="margin-top: 10px;">
                                                    <li>
                                                        <a
                                                            href="{{ route('frontend.cms.authors-documents', ['id' => $detail->main_author]) }}">
                                                            @if (isset($array_authors[$detail->main_author]))
                                                                <i class="fa fa-user-o" aria-hidden="true"></i>
                                                                <i>{{ $array_authors[$detail->main_author] }}</i>
                                                            @endif
                                                        </a>
                                                    </li>
                                                    <li
                                                        style="{{ empty($arr_author) || in_array('', $arr_author) ? 'display: none' : '' }}">
                                                        <?php $athor_name = '';
                                                        foreach ($arr_author as $aid) {
                                                            if (isset($array_authors[$aid])) {
                                                                $athor_name .= $array_authors[$aid] . ', ';
                                                            }
                                                        }
                                                        echo trim($athor_name, ', '); ?>
                                                    </li>
                                                </ul>
                                                <ul class="tg-postmetadata" style="margin-top: 10px;">
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="fa fa-shopping-cart"></i> {{ number_format($detail->price) ?? 'Liên hệ' }}&#8363;
                                                        </a>
                                                    </li>
                                                </ul>
                                                <ul class="tg-postmetadata" style="margin-top: 5px;">
                                                    <li><a href="javascript:void(0);"><i class="fa fa-file-pdf-o"
                                                                aria-hidden="true"></i><i>{{ $detail->number_page }}
                                                                {{ $array_translate[strtolower('Page')]->$locale ?? 'Page' }}</i></a>
                                                    </li>
                                                    <li><a href="javascript:void(0);"><i
                                                                class="fa fa-eye"></i><i>{{ $detail->view }}</i></a></li>
                                                    <li><a href="javascript:void(0);"><i class="fa fa-arrow-circle-o-down"
                                                                aria-hidden="true"></i><i>{{ $detail->download }} </i></a>
                                                    </li>
                                                    <li><a href="javascript:void(0);"><i class="fa fa-calendar"
                                                                aria-hidden="true"></i><i>{{ date('d/m/Y', strtotime($detail->created_at)) }}
                                                            </i></a></li>
                                                </ul>
                                            </div>
                                            <div class="tg-postbookcontent">
                                                @if (Auth::check())
                                                    <a class="tg-btnaddtowishlist icon-product-detail" id="icon-like"
                                                        data-document-id="{{ $detail->id }}" href="javascript:void(0)"
                                                        style="{{ auth()->user()->like_document && in_array($detail->id, auth()->user()->like_document) ? 'background: red' : '' }}">
                                                        <i class="icon-heart"></i>
                                                        <span
                                                            style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Like')]->$locale ?? 'Like' }}</span>
                                                    </a>
                                                    <?php
													$url_detail = '';
                                                    if ($encodeFilePdf == '' and $encodeFileOther == '') {
                                                        $url_detail = 'onclick=checkDownload2()';
                                                    } else {
                                                        // $url_detail = 'href=' . route('frontend.cms.download', ['file' => $encodeFilePdf ?: $encodeFileOther]);
                                                        $url_detail = 'onclick=openModalEbook()';
                                                    } ?>
                                                    @if ($check_status_sach)
                                                        <?php if($isValid == true){  $url_detail = ''; // Đã mua?>
                                                        <a class="tg-btnaddtowishlist icon-product-detail download"
                                                            href="javascript:void(0)" {{ $url_detail }}>
                                                            <i class="fa fa-check"></i>
                                                            <span
                                                                style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Buy Ebooked')]->$locale ?? 'Buy Ebooked' }}
                                                            </span>
                                                        </a>

                                                        <?php }else{ ?>
                                                        <a class="tg-btnaddtowishlist icon-product-detail download"
                                                            href="javascript:void(0)" {{ $url_detail }}>
                                                            <i class="icon-download"></i>
                                                            <span
                                                                style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Buy Ebook')]->$locale ?? 'Buy Ebook' }}
                                                            </span>
                                                        </a>
                                                        <?php } ?>
                                                    @endif
                                                @else
                                                    <a class="tg-btnaddtowishlist icon-product-detail" onclick="checkLike()"
                                                        style="cursor: pointer">
                                                        <i class="icon-heart"></i>
                                                        <span
                                                            style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Like')]->$locale ?? 'Like' }}
                                                        </span>
                                                    </a>
                                                    <?php if ($detail->is_public == 1) {
                                                        $url_detail = 'onclick=checkDownload()';
                                                    } elseif ($encodeFilePdf == '' and $encodeFileOther == '') {
                                                        $url_detail = 'onclick=checkDownload2()';
                                                    } else {
                                                        // $url_detail = 'href=' . route('frontend.cms.download', ['file' => $encodeFilePdf ?: $encodeFileOther]);
                                                        $url_detail = 'onclick=checkDownload()';
                                                    } ?>
                                                    @if ($check_status_sach)
                                                        <a class="tg-btnaddtowishlist icon-product-detail download"
                                                            {{ $url_detail }} style="cursor: pointer">
                                                            <i class="icon-download"></i>
                                                            <span
                                                                style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Buy Ebook')]->$locale ?? 'Buy Ebook' }}</span>
                                                        </a>
                                                    @endif
                                                @endif

                                                {{-- @if ($check_status_sach)
                                                    <a class="tg-btnaddtowishlist icon-product-detail addCart"
                                                        id="icon-cart" data-document-id="{{ $detail->id }}"
                                                        onclick="addToCart({{ $detail->id }})"
                                                        href="javascript:void(0)" style="">
                                                        <i class="icon-cart"></i>
                                                        <span
                                                            style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Cart')]->$locale ?? 'Cart' }}</span>
                                                    </a>
                                                @endif --}}

                                                <?php if($filepdf != ''){ ?>
                                                <a class="tg-btnaddtowishlist icon-product-detail readbook" id="icon-cart"
                                                    data-document-id="{{ $detail->id }}" href="#readBook"
                                                    style="">
                                                    <i class="icon-book"></i>
                                                    <span
                                                        style="font-family: 'Open Sans';">{{ $array_translate[strtolower('Read book')]->$locale ?? 'Read book' }}</span>
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <style>
                                            .left-text {
                                                float: left;
                                            }

                                            .right-text {
                                                float: right;
                                                text-align: right;
                                            }

                                            span.currency {
                                                font-size: smaller;
                                            }

                                            h4::after {
                                                content: "";
                                                display: table;
                                                clear: both;
                                            }
                                        </style>

                                        <div class="modal fade" id="bookModal" tabindex="-1" role="dialog"
                                            aria-labelledby="bookModalLabel">
                                            <form id="buyEbook" action="{{ route('frontend.ebook.store') }}" method="POST">
                                                @csrf
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="bookModalLabel">Thông tin sách
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-4">
                                                                    <img src="{{ $image }}"
                                                                        alt="{{ $title_detail }}" class="img-responsive">
                                                                </div>
                                                                <div class="col-sm-8">
                                                                    <h4>{{ $title_detail }}</h4>
                                                                    @if ($brief_detail)
                                                                        <p>{{ $brief_detail }}</p>
                                                                    @endif
                                                                    <div class="form-group">
                                                                        <label for="ebookPackage">Chọn gói ebook</label>
                                                                        <select class="form-control" id="ebookPackage"
                                                                            name="ebookPackage"
                                                                            onchange="updateTotalPrice(this)">
                                                                            @foreach ($ebook as $item)
                                                                                <option value="{{ $item->id }}">
                                                                                    {{ $item->title }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <h4>
                                                                        <span class="left-text">Tổng tiền:</span>
                                                                        <span class="right-text price"><span
                                                                                id="totalPriceEbook">0</span> <span
                                                                                class="currency">VNĐ</span></span>
                                                                    </h4>
                                                                    @if (Auth::check())
                                                                        <h4>
																		<span class="left-text">Tài khoản ví:</span>
																		<span class="right-text price">{{ number_format(auth()->user()->wallet) }}
																			<span class="currency">VNĐ</span>
																		</span>
                                                                        </h4>
                                                                    @endif
																	
                                                                </div>
																
                                                            </div>
															<div class="row" id="info-banking" style="display: none">
																<div class="col-sm-6">
																	<img src="/themes/frontend/images/qr_mb.jpg" style="width: 100%" />
																</div>
																<div class="col-sm-6">
																	<h3>Thông tin chuyển khoản</h3>
																	<p><b>{{ $web_information->payment_information->name_bank_1 ?? "" }}</b></p>
																	<p>STK: <b>{{ $web_information->payment_information->stk_1 ?? "" }}</b></p>
																	<p>CTK: <b>{{ $web_information->payment_information->name_1 ?? "" }}</b></p>
																	<?php $randomNumber = $detail->id; ?>
																	<p>Nội dung: <b id="rechargeInfo">Eb-{{ $randomNumber }}</b>
																		<button type="button" class="btn-success" onclick="coppyContent()"
																			style="width: max-content; padding: 5px 10px;margin-top: 10px; background: #3f58ab;">
																			Sao chép nội dung chuyển khoản
																			<i id="check_coppy" style="display:none" class="fa fa-check"></i>
																		</button>
																	</p>
																</div>
																<p style="padding: 0px 15px;"><b>Chú ý:</b> Quý khách vui lòng kiểm tra đúng thông tin thanh toán, "Sao chép nội dung" thanh toán. Sau khi thanh toán thành công xong quý khách bấm nút <b>"Xác nhận đã chuyển khoản"</b> để hoàn thành giao dịch. Xin cảm ơn!</p>
															</div>
															
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="submit" value="payVnpay"
                                                                class="btn btn-primary hidden">Thanh toán bằng VNPay</button>
															
															<a id="banking" onclick="checkBanking()" class="btn btn-primary">Chuyển khoản</a>
															
															<button style="display: none" id="accept" type="submit" name="submit" value="accept" class="btn btn-primary"><i class="fa fa-spinner" id="loading" style="display:none;"></i> Xác nhận đã chuyển khoản</button>
                                                            
                                                            <button type="submit" name="submit" value="payAcc"
                                                                class="btn btn-success">Tài khoản ví</button>
                                                            <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Đóng</button>

                                                            <input type="hidden" name="documentId" id="documentId"
                                                                value="{{ $detail->id }}">

                                                            <input type="hidden" name="madonhang" id="madonhang"
                                                                value="Eb-{{ $randomNumber }}">

                                                            <input type="hidden" name="totalPayment" id="totalPayment"
                                                                value="">
                                                            <input type="hidden" name="bookDetailUrl" id="bookDetailUrl"
                                                                value="{{ url()->current() }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
											
                                        </div>

                                        @if ($detail->description)
                                            <div class="tg-productdescription" style="padding-top: 20px">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <h4>{{ $array_translate[strtolower('Information')]->$locale ?? 'Information' }}
                                                    </h4>
                                                    {!! $detail->description !!}
                                                </div>
                                            </div>
                                        @endif

                                        @if ($filepdf)
                                            <div class="tg-productdescription" id="readBook" style="padding-top: 20px">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                    <div class="_df_book" style="height: 100vh" webgl="true"
                                                        backgroundcolor="gray" source="{{ $filepdf }}"
                                                        data-value="{{ $limit_page }}" id="df_manual_book">
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="h4">
                                                {{ $array_translate[strtolower('Publishing information')]->$locale ?? 'Publishing information' }}
                                            </div>
                                            <div class="chi-tiet-sach-main-table-list">
                                                <table style="margin: 20px 0 40px 0;">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_1.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_1.png') }}">
                                                                    {{ $array_translate[strtolower('NXB')]->$locale ?? 'NXB' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->publisher_title }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_2.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_2.png') }}">{{ $array_translate[strtolower('Người dịch')]->$locale ?? 'Người dịch' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->translater }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_4.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_4.png') }}">
                                                                    {{ $array_translate[strtolower('Năm XB')]->$locale ?? 'Năm XB' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->publishing_year }}{{ $detail->publishing_year_ebook ? ', ' . $detail->publishing_year_ebook : '' }}
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_11.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_11.png') }}">{{ $array_translate[strtolower('Loại sách')]->$locale ?? 'Loại sách' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->percent_paper == 1 ? 'Sách giấy' : '' }}
                                                                {{ $detail->percent_ebook == 1 ? ', Sách ebook' : '' }}
                                                                {{ $detail->percent_audio == 1 ? ', Sách audio' : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_3.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_3.png') }}">{{ $array_translate[strtolower('Khổ sách')]->$locale ?? 'Khổ sách' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->book_size_title }} (cm)</td>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_12.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_12.png') }}">
                                                                    {{ $array_translate[strtolower('Số trang')]->$locale ?? 'Số trang' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->number_page != '' ? $detail->number_page : '' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_5.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_5.png') }}">
                                                                    {{ $array_translate[strtolower('Quốc gia')]->$locale ?? 'Quốc gia' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->country }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon entered loaded"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_13.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_13.png') }}">
                                                                    {{ $array_translate[strtolower('Ngôn ngữ')]->$locale ?? 'Ngôn ngữ' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->language }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_10.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_10.png') }}">{{ $array_translate[strtolower('Mã ISBN')]->$locale ?? 'Mã ISBN' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->isbn }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center"><img
                                                                        class="lazy icon"
                                                                        data-src="{{ asset('themes/frontend/icon/ic_10.png') }}"
                                                                        alt="" data-ll-status="loaded"
                                                                        src="{{ asset('themes/frontend/icon/ic_10.png') }}">{{ $array_translate[strtolower('Mã ISBN Điện tử')]->$locale ?? 'Mã ISBN Điện tử' }}
                                                                </div>
                                                            </td>
                                                            <td>{{ $detail->isbne }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- thong tin xuat ban --}}
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                            style="{{ $filepdf != '' ? '' : 'margin-top: 40px;' }}">

                                            {{-- comment --}}
                                            <div class="tg-commentsarea">
                                                <div class="tg-sectionhead">
                                                    <h2 class="float-right">{{ count($comments) ?? '0' }}
                                                        {{ $array_translate[strtolower('Comment')]->$locale ?? 'Comment' }}
                                                    </h2>
                                                </div>
                                                <ul id="tg-comments" class="tg-comments">
                                                    @foreach ($comments as $item)
                                                        <li>
                                                            <div class="tg-authorbox">
                                                                <div class="tg-authorinfo">
                                                                    <div class="tg-authorhead">
                                                                        <div class="tg-leftarea">
                                                                            <div class="tg-authorname">
                                                                                <h2>{{ $item->member_name }}</h2>
                                                                                <span>{{ date('d/m/Y', strtotime($item->created_at)) }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="tg-description">
                                                                        <p>{!! $item->content !!}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="tg-bottomarrow"></div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                {{ $comments->links() }}
                                            </div>

                                            @if (Auth::check())
                                                <div class="tg-leaveyourcomment">
                                                    <div class="tg-sectionhead">
                                                        <h2>{{ $array_translate[strtolower('Comment')]->$locale ?? 'Comment' }}
                                                        </h2>
                                                    </div>
                                                    <form class="tg-formtheme tg-formleavecomment">
                                                        <fieldset>
                                                            <div class="form-group hidden">
                                                                <input type="text" name="full name"
                                                                    class="form-control"
                                                                    placeholder="{{ $array_translate[strtolower('Full name')]->$locale ?? 'Full name' }}*"
                                                                    id="user-name" value="{{ auth()->user()->name }}">
                                                            </div>
                                                            <div class="form-group hidden">
                                                                <input type="email" name="email address"
                                                                    class="form-control" placeholder="Email*"
                                                                    id="user-email" value="{{ auth()->user()->email }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea placeholder="{{ $array_translate[strtolower('Enter content')]->$locale ?? 'Enter content' }}" id="user-comment"></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <a class="tg-btn tg-active" href="javascript:void(0);"
                                                                    onclick="submitComment(this)"
                                                                    data-user-id="{{ $detail->id }}">{{ $array_translate[strtolower('Submit')]->$locale ?? 'Submit' }}</a>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            @else
                                                <div class="tg-leaveyourcomment">
                                                    <div class="tg-sectionhead">
                                                        <h2 class="float-right">
                                                            {{ $array_translate[strtolower('Please login to comment')]->$locale ?? 'Please login to comment' }}
                                                            !
                                                        </h2>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>

                                        {{-- lien quan --}}
                                        <?php if(count($posts) > 0){ ?>
                                        <div class="tg-relatedproducts">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="tg-sectionhead">
                                                    <h2>{{ $array_translate[strtolower('List of related documents')]->$locale ?? 'List of related documents' }}
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div id="tg-relatedproductslider"
                                                    class="tg-relatedproductslider tg-relatedbooks owl-carousel">
                                                    @foreach ($posts as $item)
                                                        @php
                                                            $title = $item->title;
                                                            $brief = $item->brief;
                                                            $image = $item->image != '' ? $item->image : '';
                                                            $date = date('d/m/Y', strtotime($item->created_at));
                                                            $url =
                                                                route('frontend.cms.view', ['alias' => $item->alias]) .
                                                                '.html';

                                                            $txt_author = trim($item->authors, ',');
                                                            $arr_author = explode(',', $txt_author);

                                                        @endphp
                                                        <div class="item">
                                                            <div class="tg-postbook">
                                                                <figure class="tg-featureimg">
                                                                    <div class="tg-bookimg">
                                                                        <div class="tg-frontcover"><img
                                                                                src="{{ $image }}"
                                                                                alt="image description"></div>
                                                                        <div class="tg-backcover"><img
                                                                                src="{{ $image }}"
                                                                                alt="image description"></div>
                                                                    </div>
                                                                    <a class="tg-btnaddtowishlist"
                                                                        href="javascript:void(0);">
                                                                        <i class="icon-heart"></i>
                                                                        <span>{{ $array_translate[strtolower('Like')]->$locale ?? 'Like' }}</span>
                                                                    </a>
                                                                </figure>
                                                                <div class="tg-postbookcontent">
                                                                    <div class="tg-booktitle">
                                                                        <h3><a href="{{ $url }}"
                                                                                class="line-2">{{ $title }}</a>
                                                                        </h3>
                                                                    </div>
                                                                    <span class="tg-bookwriter line-1">
                                                                        <i class="fa fa-user-o" aria-hidden="true"></i>
                                                                        <a
                                                                            href="{{ route('frontend.cms.authors-documents', ['id' => $item->main_author]) }}">
                                                                            {{ $array_authors[$item->main_author] }}
                                                                        </a>
                                                                    </span>
                                                                    <span class="tg-bookprice">
                                                                        <ins style="margin-right: 5px"><i
                                                                                class="fa fa-eye" aria-hidden="true"></i>
                                                                            {{ $item->view }}</ins>
                                                                        <ins style="margin-right: 5px"><i
                                                                                class="fa fa-download"
                                                                                aria-hidden="true"></i>
                                                                            {{ $item->download }}</ins>
                                                                        <ins><i class="fa fa-file-text-o"
                                                                                aria-hidden="true"></i>
                                                                            {{ $item->number_page }}</ins>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @include('frontend.element.left')

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('themes/frontend/dflip/js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('themes/frontend/dflip/js/dflip.min.js') }}"></script>

    {{-- like, download --}}
    <script>
        $(document).ready(function() {
            $("a.readbook").click(function(event) {
                event.preventDefault();
                $("html, body").animate({
                    scrollTop: $($(this).attr("href")).offset().top
                }, 1000);
            });
			
			var form = document.getElementById("buyEbook");

			$('#accept').click(function(event) {
				$('#loading').attr('style','display:inline-block');
				$('#accept').attr('onclick','');
				form.submit();
				
				//alert('AAAAAAAA');
				//$('#accept').attr('disabled','disabled');
				
			});
			
        });

        $(document).ready(function() {
            $('#icon-like').click(function(e) {
                e.preventDefault();
                e.stopPropagation();

                const id = $(this).data('document-id');
                const button = $(this);
                const csrf_token = "{{ csrf_token() }}";

                $.ajax({
                    url: "{{ route('frontend.cms.like-document') }}",
                    method: 'POST',
                    data: {
                        _token: csrf_token,
                        id: id
                    },
                    success: function(response) {
                        if (response.message == 'Document added to favorites') {
                            button.css('background-color', 'red');
                        } else if (response.message == 'Document removed from favorites') {
                            button.css('background-color', '#f16945');
                        }
                    }
                })
            });
        })
		function coppyContent() {
            navigator.clipboard.writeText($('#rechargeInfo').html());
			$("#check_coppy").attr('style','display: inline-block');
        }
        function toggleFavorite(element) {
            const id = element.getAttribute('data-document2-id');
            const token = '{{ csrf_token() }}';

            $.ajax({
                url: '{{ route('frontend.cms.like-document') }}',
                method: 'POST',
                data: {
                    _token: token,
                    id: id
                },
                success: function(response) {
                    if (response.message == 'Document removed from favorites') {
                        $(element).css('background-color', '#f16945');
                    } else if (response.message == 'Document added to favorites') {
                        $(element).css('background-color', 'red');
                    }
                }
            })
        }
    </script>

    {{-- Comment --}}
    <script>
        function submitComment(e) {
            const name = document.getElementById('user-name').value;
            const email = document.getElementById('user-email').value;
            const comment = document.getElementById('user-comment').value;
            const postId = e.getAttribute('data-user-id');
            const token = '{{ csrf_token() }}';

            if (name.trim() === '' || email.trim() === '' || comment.trim() === '') {
                alert('Vui lòng nhập đủ thông tin');
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Email không hợp lệ!');
                return;
            }

            $.ajax({
                url: '{{ route('frontend.cms.comment') }}',
                method: 'POST',
                data: {
                    _token: token,
                    name: name,
                    email: email,
                    comment: comment,
                    postId: postId
                },
                success: function(response) {
                    alert(response.message);
                    $('#user-comment').val('');
                },
                error: function(xhr, status, error) {
                    const errorMsg = JSON.parse(xhr.responseText).message;
                    alert('Lỗi khi gửi bình luận: ' + errorMsg);
                }
            })
        }
    </script>
@endsection

@extends('frontend.layouts.default')

@section('content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ $web_information->image->bread_crumb }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <ol class="tg-breadcrumb">
                            <li><a href="/">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a></li>
                            <li class="tg-active">
                                {{ $array_translate[strtolower('Purchased ebooks')]->$locale ?? 'Purchased ebooks' }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main id="tg-main" class="tg-main tg-haslayout">
        <div class="tg-sectionspace tg-haslayout">
            <div class="container">
                <div class="row">
                    <div id="tg-twocolumns" class="tg-twocolumns">

                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9 pull-right">
                            <div id="tg-content" class="tg-content">
                                <div class="tg-newslist">
                                    <div class="tg-sectionhead" style="display: flex; justify-content: space-between;">
                                        <h2>{{ $array_translate[strtolower('Purchased ebooks list')]->$locale ?? 'Purchased ebooks list' }}
                                        </h2>

                                        <div class="view-mode-toggle">
                                            <button id="grid-view-btn" class="btn-view"><i class="fa fa-th"
                                                    aria-hidden="true"></i></button>
                                            <button id="list-view-btn" class="btn-view active"><i class="fa fa-list-ul"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                    <div class="row view-doc-container list-view-doc">
                                        @foreach ($listBuyEbook as $item)
                                            @php
                                                $title = $item->title_document;
                                                $brief = $item->brief_document;
                                                $image = $item->image_document != '' ? $item->image_document : '';
                                                $date = date('d/m/Y', strtotime($item->buy_date));
                                                $url = '/view/' . $item->alias_document . '.html';
                                                $id_author = $item->id_author;
                                                $title_author = $item->title_author;
                                                $number_page_document = $item->number_page_document;
                                                $view_document = $item->view_document;
                                                $payment = $item->payment;

                                            @endphp

                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 view-doc-col">
                                                <article class="tg-post">
                                                    <figure class="new"><a href="{{ $url }}"><img
                                                                src="{{ $image }}" alt="image description"></a>
                                                    </figure>
                                                    <div class="tg-postcontent">

                                                        <div class="tg-posttitle">
                                                            <h3><a href="{{ $url }}"
                                                                    class="line-2">{{ $title }}</a></h3>
                                                        </div>
                                                        @if ($brief)
                                                            <div class="tg-description line-4">
                                                                <p>{{ $brief }}</p>
                                                            </div>
                                                        @endif
                                                        <a
                                                            href="{{ route('frontend.cms.authors-documents', ['id' => $id_author]) }}">{{ $title_author }}</a>
                                                        <ul class="tg-postmetadata">
                                                            <li><a href="javascript:void(0);"><i class="fa fa-file-pdf-o"
                                                                        aria-hidden="true"></i><i>{{ $number_page_document }}</i></a>
                                                            </li>
                                                            <li><a href="javascript:void(0);"><i
                                                                        class="fa fa-eye"></i><i>{{ $view_document }}</i></a>
                                                            </li>
                                                            <li><a href="javascript:void(0);"><i class="fa fa-money"
                                                                        aria-hidden="true"></i><i>{{ number_format($payment) }}
                                                                        VNĐ
                                                                    </i></a></li>
                                                            <li class="tg-postmetadata-date"><a
                                                                    href="javascript:void(0);"><i class="fa fa-calendar"
                                                                        aria-hidden="true"></i><i>{{ $date }}
                                                                    </i></a></li>
                                                        </ul>
                                                    </div>
                                                </article>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{ $listBuyEbook->links() }}
                                </div>
                            </div>
                        </div>

                        @include('frontend.element.left')

                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .grid-view-doc {
            display: flex;
            flex-wrap: wrap;
        }

        .grid-view-doc .tg-post:hover {
            box-shadow: 1px 1px 5px 1px #ccc;
        }

        .grid-view-doc .tg-post:hover figure {
            border-color: #f4f4f4;
        }

        .grid-view-doc .col-md-3 {
            padding: 5px;
        }

        .grid-view-doc .tg-post {
            flex-direction: column;
            background-color: #f4f4f4;
            margin: 0;
            padding-bottom: 12px;
        }

        .grid-view-doc .new {
            width: 100% !important;
            -webkit-box-shadow: unset !important;
            box-shadow: unset !important;
        }

        .grid-view-doc .new a {
            padding: 10px 0;
        }

        .grid-view-doc .new a img {
            object-fit: contain;
        }

        .grid-view-doc .tg-postcontent {
            padding: 0 15px !important;
        }

        .grid-view-doc .tg-postcontent .line-2 {
            font-size: 14px;
        }

        .btn-view {
            padding: 6px 10px;
            background-color: #ccc;
        }

        .btn-view.active {
            background-color: var(--primary-color);
            border-color: #fff;
            color: #fff;
        }

        .btn-view:hover {
            opacity: 0.8;
        }
    </style>

    <script>
        // Thêm dữ liệu trong Local Storage
        function saveCurrentView(view) {
            localStorage.setItem('currentView', view);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.getElementById('grid-view-btn');
            const listViewBtn = document.getElementById('list-view-btn');

            const viewDocContainer = document.querySelector('.view-doc-container');
            const viewDocCols = Array.from(document.querySelectorAll('.view-doc-col'));
            const descs = document.querySelectorAll('.view-doc-container .tg-description');
            const dates = document.querySelectorAll('.tg-postmetadata .tg-postmetadata-date');
            const downloads = document.querySelectorAll('.tg-postcontent .tg-btnstyletwo');

            // lấy ra giá trị localstorage
            let savedView = localStorage.getItem('currentView');
            // let savedView = 'grid';
            if (savedView === 'grid') {
                showGridView();
            } else {
                showListView();
            }

            gridViewBtn.addEventListener('click', function() {
                showGridView();
                saveCurrentView('grid');
            })

            listViewBtn.addEventListener('click', function() {
                showListView();
                saveCurrentView('list');
            })

            function showGridView() {
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                viewDocContainer.classList.add('grid-view-doc');
                viewDocContainer.classList.remove('list-view-doc');

                viewDocCols.forEach(function(col) {
                    ['col-md-3', 'col-xs-6'].forEach(function(cls) {
                        col.classList.add(cls);
                    });
                    ['col-xs-12', 'col-sm-12', 'col-md-12', 'col-lg-12'].forEach(function(cls) {
                        col.classList.remove(cls);
                    });
                });

                descs.forEach(function(desc) {
                    desc.style.display = 'none';
                })

                dates.forEach(function(date) {
                    date.style.display = 'none';
                })

                downloads.forEach(function(download) {
                    download.style.display = 'none';
                })
            }

            function showListView() {
                gridViewBtn.classList.remove('active');
                listViewBtn.classList.add('active');
                viewDocContainer.classList.remove('grid-view-doc');
                viewDocContainer.classList.add('list-view-doc');

                viewDocCols.forEach(function(col) {
                    ['col-md-3', 'col-xs-6'].forEach(function(cls) {
                        col.classList.remove(cls);
                    });
                    ['col-xs-12', 'col-sm-12', 'col-md-12', 'col-lg-12'].forEach(function(cls) {
                        col.classList.add(cls);
                    });
                });

                descs.forEach(function(desc) {
                    desc.style.display = '-webkit-box';
                })

                dates.forEach(function(date) {
                    date.style.display = 'block';
                })

                downloads.forEach(function(download) {
                    download.style.display = 'block';
                })
            }
        })
    </script>
@endsection

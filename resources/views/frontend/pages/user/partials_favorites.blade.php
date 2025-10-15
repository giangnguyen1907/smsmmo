<div id="tg-content" class="tg-content">
    <div class="tg-newslist">
        <div class="tg-sectionhead" style="display: flex; justify-content: space-between;">
            <h2>{{ $array_translate[strtolower('')]->$locale ?? '' }}</h2>

            <div class="view-mode-toggle">
                <button id="grid-view-btn" class="btn-view"><i class="fa fa-th" aria-hidden="true"></i></button>
                <button id="list-view-btn" class="btn-view active"><i class="fa fa-list-ul"
                        aria-hidden="true"></i></button>
            </div>
        </div>

        @if (is_null($user->like_document) || empty($user->like_document))
            <div class="row view-doc-container list-view-doc">
                <h3 style="text-align: center; font-style: italic;">
                    {{ $array_translate[strtolower('Not Found')]->$locale ?? 'Not Found' }} !
                </h3>
            </div>
        @else
            <div class="row view-doc-container list-view-doc">
                @foreach ($listDocuments as $item)
                    @php
                        $title = $item->title;
                        $brief = $item->brief;
                        $image = $item->image != '' ? $item->image : '';
                        $date = date('d/m/Y', strtotime($item->created_at));
                        $url = '/view/' . $item->alias . '.html';
                        $authorId = $item->main_author;

                        $encodeFilePdf = base64_encode($item->filepdf);
                        $encodeFileOther = base64_encode($item->file_other);

                    @endphp

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 view-doc-col">
                        <article class="tg-post">
                            <figure class="new"><a href="{{ $url }}"><img src="{{ $image }}"
                                        alt="image description"></a></figure>
                            <div class="tg-postcontent">

                                <div class="tg-posttitle">
                                    <h3><a href="{{ $url }}" class="line-2">{{ $title }}</a></h3>
                                </div>
                                @if ($brief)
                                    <div class="tg-description line-4">
                                        <p>{{ $brief }}</p>
                                    </div>
                                @endif
                                @if (isset($array_authors[$authorId]))
                                    <a
                                        href="{{ route('frontend.cms.authors-documents', ['id' => $authorId]) }}">{{ $array_authors[$authorId] }}</a>
                                @endif
                                <ul class="tg-postmetadata">
                                    <li><a href="javascript:void(0);"><i class="fa fa-file-pdf-o"
                                                aria-hidden="true"></i><i>{{ $item->number_page }}</i></a></li>
                                    <li><a href="javascript:void(0);"><i
                                                class="fa fa-eye"></i><i>{{ $item->view }}</i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fa fa-arrow-circle-o-down"
                                                aria-hidden="true"></i><i>{{ $item->download }} </i></a></li>
                                    <li class="tg-postmetadata-date"><a href="javascript:void(0);"><i
                                                class="fa fa-calendar" aria-hidden="true"></i><i>{{ $date }}
                                            </i></a></li>
                                </ul>

                                {{-- @if (Auth::check())
                                    <a class="tg-btn tg-btnstyletwo new btn-down-hover" href="{{ route('frontend.cms.download', ['file' => $encodeFilePdf ?: $encodeFileOther]) }}">
                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                        <em>{{ $array_translate[strtolower('Download')]->$locale ?? 'Download' }}</em>
                                    </a>
                                @else
                                    @php
                                    if ($item->is_public == 1) {
                                        $url_detail = 'onclick=checkDownload() href="javascript:;"';
                                    } elseif ($encodeFilePdf == '' and $encodeFileOther == '') {
                                        $url_detail = 'onclick=checkDownload2() href="javascript:;"';
                                    } else {
                                        $url_detail = 'href=' . route('frontend.cms.download', ['file' => $encodeFilePdf ?: $encodeFileOther]);
                                    }
                                    @endphp
                                    <a class="tg-btn tg-btnstyletwo new btn-down-hover" {{ $url_detail }}>
                                        <i class="fa fa-cloud-download" aria-hidden="true"></i>
                                        <em>{{ $array_translate[strtolower('Download')]->$locale ?? 'Download' }}</em>
                                    </a>
                                @endif 
                                <a href="javascript:;" onclick="addToCart({{ $item->id }})"
                                    class="tg-btn tg-btnstyletwo new btn-down-hover" style="margin-right: 8px">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    <em>{{ $array_translate[strtolower('Cart')]->$locale ?? 'Cart' }}</em>
                                </a> --}}
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            {{ $listDocuments->links() }}
        @endif
    </div>
</div>

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
    function checkLike() {
        alert('Vui lòng đăng nhập để thêm tài liệu vào danh mục yêu thích!');
    }

    function checkDownload() {
        alert('Vui lòng đăng nhập để tải tài liệu!');
    }

    function checkDownload2() {
        alert('Chưa có nội dung tải xuống, vui lòng chờ nhà xuất bản cập nhật. Xin cảm ơn!');
    }
</script>

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

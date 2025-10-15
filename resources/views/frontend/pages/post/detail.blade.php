@extends('frontend.layouts.default')
@php
    $title_detail = $detail->title->$locale;
    $brief_detail = $detail->brief->$locale;
    $content = $detail->content->$locale;
    $image = $detail->image != '' ? $detail->image : null;

    // For taxonomy
    $taxonomy_title = $taxonomy->title->$locale ?? null;
    $intro_about = $taxonomy->brief->$locale ?? null;
    $taxonomy_id = $taxonomy->id;
    $image_background = $taxonomy->json_params->image_background ?? null;

    $seo_title = $detail->meta_title ?? $title_detail;
    $seo_keyword = $detail->meta_keyword ?? null;
    $seo_description = $detail->meta_description ?? $brief_detail;
    $seo_image = $image ?? ($image_thumb ?? null);
    //echo "AAAAAAAAAAAA".$content;die;
@endphp

@section('content')
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ $image_background }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="tg-innerbannercontent">
                        <h1 class="p-4 hidden">{{ $title_detail }}</h1>
                        <ol class="tg-breadcrumb">
                            <li><a
                                    href="javascript:void(0);">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a>
                            </li>
                            <li><a href="javascript:void(0);">{{ $taxonomy_title }}</a></li>
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
                                <div class="tg-newsdetail">
                                    <div class="tg-sectionhead">
                                        <h2>{{ $taxonomy_title }}</h2>
                                    </div>
                                    <div class="tg-posttitle">
                                        <h3><a href="javascript:void(0);">{{ $title_detail }}</a></h3>
                                    </div>
                                    @if ($detail->brief->$locale)
                                        <div class="tg-posttitle">
                                            <h5><span>{{ $detail->brief->$locale }}</span></h5>
                                        </div>
                                    @endif
                                    @if ($detail->image)
                                        <img src="{{ $detail->image }}" alt="" class="image-post">
                                    @endif
                                    @if ($content)
                                        <div class="tg-description"
                                            style="border-bottom: 2px solid #dbdbdb; padding-bottom: 30px;">
                                            {!! $content !!}
                                        </div>
                                    @endif

                                    <div class="tg-nextprevpost">
                                        @foreach ($posts as $item)
                                            @php
                                                $title = $item->title->$locale;
                                                $brief = $item->brief->$locale;
                                                $image = $item->image != '' ? $item->image : '';
                                                $date = date('H:i d/m/Y', strtotime($item->created_at));
                                                $url = '/detail/' . $item->url_part . '.html';
                                            @endphp
                                            <div class="tg-prevpost">
                                                <a href="{{ $url }}">
                                                    <figure>
                                                        <img class="img-relation" src="{{ $image }}"
                                                            alt="image description">
                                                    </figure>
                                                    <div class="tg-posttitle">
                                                        <h3>{{ $title }}</h3>
                                                        <span>{{ $array_translate[strtolower('View')]->$locale ?? 'View' }}</span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach

                                    </div>

                                    <div class="tg-commentsarea">
                                        <div class="tg-sectionhead">
                                            <h2>{{ $detail->number_comment ?? '0' }}
                                                {{ $array_translate[strtolower('Comment')]->$locale ?? 'Comment' }}</h2>
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
                                                                        <span>{{ $item->created_at }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tg-description">
                                                                <p>{{ $item->content }}</p>
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
                                                    <div class="form-group">
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="{{ $array_translate[strtolower('Full name')]->$locale ?? 'Full name' }}*"
                                                            id="user-name-news">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" name="email" class="form-control"
                                                            placeholder="Email*" id="user-email-news">
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea name="comment" placeholder="{{ $array_translate[strtolower('Content')]->$locale ?? 'Content' }}"
                                                            id="user-comment-news"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <a class="tg-btn tg-active submit-comment"
                                                            href="javascript:void(0);" onclick="submitComment(this)"
                                                            data-user-id="{{ $detail->id }}">{{ $array_translate[strtolower('Submit')]->$locale ?? 'Submit' }}</a>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                    @else
                                        <div class="tg-leaveyourcomment">
                                            <div class="tg-sectionhead">
                                                <h2>{{ $array_translate[strtolower('Please login to comment')]->$locale ?? 'Please login to comment' }}!
                                                </h2>
                                            </div>
                                        </div>
                                    @endif
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
        .image-post {
            height: 350px;
            object-fit: contain;
            width: 100%;
            padding-bottom: 20px;
        }

        .tg-prevpost {
            padding: 5px;
        }

        .submit-comment:hover {
            opacity: .8;
        }
    </style>

    {{-- Comment --}}
    <script>
        function submitComment(e) {
            const name = document.getElementById('user-name-news').value;
            const email = document.getElementById('user-email-news').value;
            const comment = document.getElementById('user-comment-news').value;
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
                url: '{{ route('frontend.cms.post.comment') }}',
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
                    $('#user-name-news').val('');
                    $('#user-email-news').val('');
                    $('#user-comment-news').val('');
                },
                error: function(xhr, status, error) {
                    const errorMsg = JSON.parse(xhr.responseText).message;
                    alert('Lỗi khi gửi bình luận: ' + errorMsg);
                }
            })
        }
    </script>
@endsection

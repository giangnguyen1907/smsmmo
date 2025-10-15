@php
    $seo_title = $seo_title ?? ($page->title ?? ($web_information->information->seo_title ?? ''));
    $seo_keyword = $seo_keyword ?? ($page->keyword ?? ($web_information->information->seo_keyword ?? ''));
    $seo_description =
        $seo_description ?? ($page->description ?? ($web_information->information->seo_description ?? ''));
@endphp

<!doctype html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <title>
        {{ $seo_title }}
    </title>
    <meta name='robots' content='max-image-preview:large' />
    <meta name="copyright" content="Copyright" />
    <meta name="title" content="{{ $seo_title }}" />
    <meta name="description" content="{{ $seo_description }}" />
    <meta name="keywords" content="{{ $seo_keyword }}" />
    <meta property="og:image" content="{{ $web_information->image->seo_og_image ?? '' }}">
    <meta name="robots" content="max-image-preview:large">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com/">
    <link rel="dns-prefetch" href="https://use.fontawesome.com/">
    <link rel="icon" type="image/png" href="{{ $web_information->image->favicon ?? '' }}">

    @include('frontend.panels.styles')
	
    <meta name="generator" content="Laravel 8.x" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel='shortlink' href='{{ url()->current() }}' />
    <meta name="generator" content="{{ $seo_description }}">

</head>

<body class="tg-home tg-homevtwo">

    <div id="tg-wrapper" class="tg-wrapper tg-haslayout">

        @include('frontend.element.header')

        @yield('content')

        @include('frontend.element.footer')

    </div>
	
	<script>
		/*
		document.addEventListener("keydown", function (event) {
			if (event.ctrlKey && (event.key === 'u' || event.key === 'U' || event.shiftKey && event.key === 'I')) {
				event.preventDefault();
			}
			if (event.key === 'F12') {
				event.preventDefault();
			}
		});
		document.addEventListener("contextmenu", function (event) {
			event.preventDefault();
		});
		*/
	</script>
	
    <script>
        function updateTotalPrice(selectElement) {
            var selectedOption = selectElement.value;
            var documentId = $('#documentId').val();;

            $.ajax({
                url: '{{ route('frontend.ebook.calculatePrice') }}',
                type: 'GET',
                data: {
                    ebookId: selectedOption,
                    documentId: documentId
                },
                success: function(response) {
                    $('#totalPriceEbook').text(response.totalPrice.toLocaleString());
                    $('#totalPayment').val(response.totalPrice);
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                }
            });
        }

        $(document).ready(function() {
            $('#ebookPackage').trigger('change');
        });
		
        function checkLike() {
            $('#loginModal').modal('show');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            loginForm.style.display = "block";
            registerForm.style.display = "none";
            // alert('Vui lòng đăng nhập để thêm tài liệu vào danh mục yêu thích!');
        }

        function checkDownload() {
            $('#loginModal').modal('show');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            loginForm.style.display = "block";
            registerForm.style.display = "none";
            // alert('Vui lòng đăng nhập để mua tài liệu!');
        }
		
		function checkBanking() {
			
			//alert('AAAA'+$('#documentId').val());
			$('#banking').attr('style','display:none');
			$('#info-banking').attr('style','display:block');
			$('#accept').attr('style','display:inline-block');
		}
		
        function checkDownload2() {
            alert('Chưa có nội dung tải xuống, vui lòng chờ nhà xuất bản cập nhật. Xin cảm ơn!');
        }

        function openModalEbook() {
            $('#bookModal').modal('show');
        }

        // Thêm giỏ hàng
        function addToCart(id) {
            var f = "?quantity=1" + "&id=" + id;
            var _url = "{{ route('frontend.order.add_to_cart') }}" + f;

            var cat = $('#countCart').text();
            //alert(cat);
            // Lấy giá trị của biến linear-gradient từ CSS
            var linearGradient = getComputedStyle(document.documentElement).getPropertyValue('--linear-gradient');

            jQuery.ajax({
                type: "GET",
                url: _url,
                data: f,
                cache: false,
                context: document.body,
                success: function(response) {

                    cat = Number(cat) + 1;
                    $('#countCart').text(cat);

                    $('#listCart').html(response);

                    // alert(response.success);
                    Toastify({
                        text: "Đã thêm vào giỏ hàng thành công!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: linearGradient,
                    }).showToast();

                },
                error: function(xhr, status, error, response) {
                    alert(response.error);
                    console.log(xhr.responseText);
                }
            });
        }

        function clearCart() {
            var linearGradient = getComputedStyle(document.documentElement).getPropertyValue('--linear-gradient');
            jQuery.ajax({
                type: "GET",
                url: "{{ route('frontend.order.clear_cart') }}",
                data: "",
                cache: false,
                context: document.body,
                success: function(response) {

                    cat = 0;
                    $('#countCart').text(cat);

                    $('#listCart').html('');

                    // alert(response.success);
                    Toastify({
                        text: "Xóa giỏ hàng thành công",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: linearGradient,
                    }).showToast();

                },
                error: function(xhr, status, error, response) {
                    alert(response.error);
                    console.log(xhr.responseText);
                }
            });
        }
    </script>

    {{-- like, download --}}
    <script>
        function toggleFavorite(element) {
            const id = element.getAttribute('data-document3-id');
            const token = '{{ csrf_token() }}';
            console.log(token);

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

        function toggleFavorite2(element) {
            const id = element.getAttribute('data-document4-id');
            const token = '{{ csrf_token() }}';
            console.log(id);

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
    @include('frontend.panels.scripts')

</body>

</html>

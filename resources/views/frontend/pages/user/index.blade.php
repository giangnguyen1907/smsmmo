@extends('frontend.layouts.default')
@section('content')
    @php
        $user = '';
        if (Auth::check()) {
            $user = Auth::user();
        }
    @endphp
    <div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ $web_information->image->bread_crumb }}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>{{ $array_translate[strtolower('Personal information')]->$locale ?? 'Personal information' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <main class="tg-main tg-haslayout" id="tg-main">
        <div class="tg-sectionspace tg-haslayout">

            <div class="container container-fluid">
                <div class="row">

                    <div class="col-md-3">
                        <div class="sidebar">
                            <ul class="list-group">
                                <li class="list-group-item active" onclick="showForm('form1')" data-category="form1">
                                    {{ $array_translate[strtolower('Personal information')]->$locale ?? 'Personal information' }}
                                </li>
                                <li class="list-group-item" onclick="showForm('form2')" data-category="form2">
                                    {{ $array_translate[strtolower('Change password')]->$locale ?? 'Change password' }}</li>
                                <li class="list-group-item" onclick="showForm('form3')" data-category="form3">
                                    {{ $array_translate[strtolower('Favorites list')]->$locale ?? 'Favorites list' }}</li>
                                <li class="list-group-item" onclick="showForm('form4')" data-category="form4">
                                    {{ $array_translate[strtolower('Order tracking')]->$locale ?? 'Order tracking' }}</li>
                                <li class="list-group-item" onclick="showForm('form5')" data-category="form5">
                                    {{ $array_translate[strtolower('Purchased ebooks')]->$locale ?? 'Purchased ebooks' }}
                                </li>
                                <li class="list-group-item" onclick="showForm('form6')" data-category="form6">
                                    {{ $array_translate[strtolower('Recharge')]->$locale ?? 'Recharge' }}
                                </li>
                                <li class="list-group-item" onclick="showForm('form7')" data-category="form7">
                                    Danh sách bản thảo</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-9 border-right">
                        <div id="infoContainer">
                            <form class="form-container" id="form1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Tên:</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ auth()->user()->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại:</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{ auth()->user()->phone ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ auth()->user()->email ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="note">Ghi chú:</label>
                                            <input type="text" class="form-control" id="note" name="note"
                                                value="{{ auth()->user()->note ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="address">Địa chỉ:<small class="text-red">*</small></label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="tinhthanh" id="tinhthanh" class="form-control select2" required>
                                                <option value="">-Chọn tỉnh/thành phố-</option>
                                                <?php foreach($listProvince as $province){ ?>
                                                <option value="{{ $province->s_code }}"
                                                    {{ isset($user->json_params->province_id) && $province->s_code == $user->json_params->province_id ? 'selected' : '' }}>
                                                    {{ $province->name }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="quanhuyen" id="quanhuyen" class="form-control select2" required>
                                                @if (isset($user->json_params->district_id))
                                                    <option value="{{ $user->json_params->district_id }}">
                                                        {{ $user->json_params->district_name }}</option>
                                                @else
                                                    <option value="">-Chọn quận/huyện-</option>
                                                @endif

                                                {{-- <option value="">-Chọn quận/huyện-</option>
												@foreach ($listDistrict as $item)
													<option value="{{ $item->s_code }}"
														{{ $item->s_code == $user->json_params->district_id ? 'selected' : '' }}>
														{{ $item->name }}
													</option>
												@endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="xaphuong" id="xaphuong" class="form-control select2" required>
                                                @if (isset($user->json_params->ward_id))
                                                    <option value="{{ $user->json_params->ward_id }}">
                                                        {{ $user->json_params->ward_name }}</option>
                                                @else
                                                    <option value="">-Chọn xã/ phường-</option>
                                                @endif
                                                {{-- <option value="">-Chọn xã/ phường-</option>
												@foreach ($listWard as $item)
													<option value="{{ $item->s_code }}"
														{{ $item->s_code == $user->json_params->ward_id ? 'selected' : '' }}>
														{{ $item->name }}
													</option>
												@endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="txt_xaphuong" id="txt_xaphuong"
                                        value="{{ $user->json_params->ward_name ?? '' }}">
                                    <input type="hidden" name="txt_quanhuyen" id="txt_quanhuyen"
                                        value="{{ $user->json_params->district_name ?? '' }}">
                                    <input type="hidden" name="txt_tinhthanh" id="txt_tinhthanh"
                                        value="{{ $user->json_params->provice_name ?? '' }}">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">Chi tiết:</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="{{ auth()->user()->address ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary btn-submit"
                                    onclick="submitInfor()">Lưu</button>
                            </form>
                            <form class="form-container" id="form2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="old-password">Mật khẩu cũ:</label>
                                            <input type="password" class="form-control" id="old-password"
                                                name="old-password">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="new-password">Mật khẩu mới:</label>
                                            <input type="password" class="form-control" id="new-password"
                                                name="new-password">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="confirm-password">Nhập lại mật khẩu:</label>
                                            <input type="password" class="form-control" id="confirm-password"
                                                name="confirm-password">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-submit">Lưu</button>
                            </form>

                            <div class="form-container" id="form3">
                                @include('frontend.pages.user.partials_favorites', [
                                    'listDocuments',
                                    $listDocuments,
                                ])
                            </div>
                            <div class="form-container" id="form4">
                                <div class="btn-group" role="group"
                                    style="display: flex; justify-content: space-between;">
                                    {{-- @foreach (App\Consts::ORDER_STATUS as $key => $item)
										<button type="button" class="btn btn-order" onclick="loadOrders('{{ $key }}')">{{ $item }}</button>
									@endforeach --}}
                                    <button type="button" class="btn btn-order" onclick="loadOrders('pending')">Chờ
                                        duyệt</button>
                                    <button type="button" class="btn btn-order"
                                        onclick="loadOrders('reject')">Hủy</button>
                                    <button type="button" class="btn btn-order click"
                                        onclick="loadOrders('complete')">Hoàn thành</button>
                                    <button type="button" class="btn btn-order" onclick="loadOrders('delivery')">Đang
                                        giao</button>
                                </div>

                                <div id="orders-table" class="mt-3">
                                    @include('frontend.pages.user.partials_order_tracking')
                                </div>
                            </div>

                            <div class="form-container" id="form5">
                                <div class="mt-3">
                                    @include('frontend.pages.user.buy_ebook', ['buyEbook', $buyEbook])
                                </div>
                            </div>

                            <div class="form-container" id="form6">
                                <div class="mt-3">
                                    @include('frontend.pages.user.recharge_user')
                                </div>
                            </div>

                            <div class="form-container" id="form7">
                                <div class="btn-group" role="group"
                                    style="display: flex; justify-content: space-between;">
                                    <button type="button" class="btn btn-order-2"
                                        onclick="loadBanthao('chosogiayphep')">Chờ số
                                        giấy phép</button>
                                    <button type="button" class="btn btn-order-2"
                                        onclick="loadBanthao('choqdcapphep')">Chờ QĐ
                                        cấp phép</button>
                                    <button type="button" class="btn btn-order-2 click"
                                        onclick="loadBanthao('choluuchieu')">Chờ lưu chiểu</button>
                                    <button type="button" class="btn btn-order-2" onclick="loadBanthao('phathanh')">Phát
                                        hành</button>
                                    <button type="button" class="btn btn-order-2" onclick="loadBanthao('thuhoi')">Thu
                                        hồi</button>
                                </div>

                                <div id="banthao-table" class="mt-3">
                                    @include('frontend.pages.user.banthao')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection


{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
<style>
    /* CSS tùy chỉnh */
    .sidebar {
        /*
        background-color: #f8f9fa;
        border: 1px solid #ccc;
  height: 100vh;*/
    }

    .list-group-item {
        background-color: unset;
        cursor: pointer;

    }

    .btn-submit {
        padding: 10px 35px;
        width: 20%;
        background-color: var(--primary-color) !important;
        border-color: unset;
    }

    .active {
        background-color: var(--primary-color) !important;
        color: #fff;
    }

    .form-container {
        display: none;
    }

    .block {
        display: block;
    }

    .form-container.block {
        display: block;
    }

    .select2 {
        width: 100% !important;
    }

    ul.select2-results__options li {
        list-style: none;
    }

    .btn-order {
        border: 1px solid #fafafa !important;
        background: #ccc;
        width: 100%;
    }

    .btn-order.click {
        background: var(--primary-color);
        color: #fff !important;
    }

    .btn-order-2 {
        border: 1px solid #fafafa !important;
        background: #ccc;
        width: 100%;
    }

    .btn-order-2.click {
        background: var(--primary-color);
        color: #fff !important;
    }

    @media (max-width: 991px) {
        .sidebar {
            height: fit-content;
        }

        .border-right {
            border-left: unset;
        }

        .container-fluid {
            margin-top: auto;
        }

        #orders-table {
            overflow-x: scroll;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
            border: 1px solid #ccc;
        }

        #banthao-table {
            overflow-x: scroll;
            -webkit-overflow-scrolling: touch;
            white-space: nowrap;
            border: 1px solid #ccc;
        }
    }

    @media (max-width: 800px) {
        .container-fluid {
            margin-top: auto;
        }
    }

    @media (max-width: 480px) {
        .container-fluid {
            margin-top: auto;
        }
    }

    @media (max-width: 430px) {
        .container-fluid {
            margin-top: auto;
        }
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        $('.list-group-item').first().addClass('active');
        $('.form-container').first().addClass('block');
    });

    function showForm(formId) {
        $('.list-group-item').removeClass('active');
        $(event.target).addClass('active');
        $('.form-container').removeClass('block');
        $('#' + formId).addClass('block');
    }

</script> --}}

{{-- submit form --}}
<script>
    // sửa thông tin
    function submitInfor() {
        name = $('#name').val();
        phone = $('#phone').val();
        email = $('#email').val();
        address = $('#address').val();
        note = $('#note').val();

        tinhthanh = $('#tinhthanh').val();
        txt_tinhthanh = $('#txt_tinhthanh').val();
        quanhuyen = $('#quanhuyen').val();
        txt_quanhuyen = $('#txt_quanhuyen').val();
        xaphuong = $('#xaphuong').val();
        txt_xaphuong = $('#txt_xaphuong').val();

        token = '{{ csrf_token() }}';

        $.ajax({
            url: '{{ route('frontend.edit.infor') }}',
            method: 'POST',
            data: {
                _token: token,
                name: name,
                phone: phone,
                email: email,
                address: address,
                note: note,
                tinhthanh: tinhthanh,
                txt_tinhthanh: txt_tinhthanh,
                quanhuyen: quanhuyen,
                txt_quanhuyen: txt_quanhuyen,
                xaphuong: xaphuong,
                txt_xaphuong: txt_xaphuong,
            },
            success: function(response) {
                if (response.success) {
                    alert('Thay đổi thông tin thành công');
                    location.reload();
                } else {
                    alert('Đã xảy ra lỗi, vui lòng thử lại sau!');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('Đã xảy ra lỗi, vui lòng thử lại sau!');
            }
        })
    }

    $('document').ready(function() {
        $('#form2').on('submit', function(e) {
            e.preventDefault();

            const oldPassword = $('#old-password').val();
            const newPassword = $('#new-password').val();
            const confirmPassword = $('#confirm-password').val();
            const token = '{{ csrf_token() }}';

            if (newPassword != confirmPassword) {
                alert('Xác nhận lại mật khẩu không khớp!');
                return;
            }

            $.ajax({
                url: '{{ route('frontend.change.password') }}',
                method: 'POST',
                data: {
                    _token: token,
                    oldPassword: oldPassword,
                    newPassword: newPassword,
                },
                success: function(response) {
                    alert(response.message);
                    $('#form2')[0].reset();
                },
                error: function(xhr, status, error) {
                    console.log(error.responseText);
                    alert('Đã có lỗi xảy ra, vui lòng thử lại sau!');
                }
            })
        })
    })

    // địa chỉ
    $(document).ready(function() {
        $('.select2').select2();

        $('#tinhthanh').change(function() {
            $('#quanhuyen').select2('val', '');
            $('#xaphuong').select2('val', '');
            $('#quanhuyen').empty().append('<option value="" selected>-Chọn quận/huyện-</option>');
            $('#xaphuong').empty().append('<option value="" selected>-Chọn xã/phường-</option>');

            var p = $('#tinhthanh').val();
            var f = "?id=" + p;

            var n = $("#tinhthanh option:selected").text().trim();
            $('#txt_tinhthanh').val(n);
            jQuery.ajax({
                type: "GET",
                url: "{{ route('frontend.load_district') }}" + f,
                data: f,
                cache: false,
                context: document.body,
                success: function(response) {
                    //alert(response);
                    $('#quanhuyen').append(response);
                    // Đóng Select2 sau khi chọn tỉnh
                    setTimeout(function() {
                        $('#tinhthanh').select2('close');
                    }, 100);
                }
            });
        })

        $('#quanhuyen').change(function() {
            $('#xaphuong').select2('val', '');
            $('#xaphuong').empty().append('<option value="">-Chọn xã/phường-</option>');

            var p = $('#quanhuyen').val();
            var f = "?id=" + p;
            //alert(p);
            var n = $("#quanhuyen option:selected").text();
            $('#txt_quanhuyen').val(n);
            jQuery.ajax({
                type: "GET",
                url: "{{ route('frontend.load_ward') }}" + f,
                data: f,
                cache: false,
                context: document.body,
                success: function(response) {
                    $('#xaphuong').append(response);
                    setTimeout(function() {
                        $('#quanhuyen').select2('close');
                    }, 100);
                }
            });
        })

        $('#xaphuong').change(function() {
            var p = $('#xaphuong').val();
            var n = $("#xaphuong option:selected").text();
            $('#txt_xaphuong').val(n);
            if (p != "") {
                $('#address').attr('readonly', false);
            } else {
                $('#address').attr('readonly', true);
            }
            setTimeout(function() {
                $('#xaphuong').select2('close');
            }, 100);
        })
    });
</script>

{{-- click danh mục yêu thích --}}
<script>
    function initializeCategorySelection() {
        // Thiết lập sự kiện click cho các nút danh mục
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.addEventListener('click', function() {
                let category = this.getAttribute('data-category');
                localStorage.setItem('selectedCategory', category);
                updateCategorySelection(category);
            });
        });

        // Kiểm tra nếu không có selectedCategory trong localStorage thì mặc định chọn danh mục đầu tiên
        let selectedCategory = localStorage.getItem('selectedCategory');
        if (!selectedCategory) {
            let firstCategoryElement = document.querySelector('.list-group-item');
            let firstCategory = firstCategoryElement ? firstCategoryElement.getAttribute('data-category') : null;
            if (firstCategory) {
                localStorage.setItem('selectedCategory', firstCategory);
                selectedCategory = firstCategory;
                updateCategorySelection(selectedCategory);
            }
        } else {
            updateCategorySelection(selectedCategory);
        }
    }

    function updateCategorySelection(category) {
        // Reset tất cả các nút
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.classList.remove('active');
        });
        $('.form-container').removeClass('block');

        // Đổi background của nút được chọn
        let selectedButton = document.querySelector(`.list-group-item[data-category="${category}"]`);
        if (selectedButton) {
            selectedButton.classList.add('active');
        }
        $('#' + category).addClass('block');
    }

    $(document).ready(function() {
        initializeCategorySelection();
    });
</script>

{{-- click danh mục order --}}
<script>
    function loadOrders(status) {
        //đổi background nút được click
        document.querySelectorAll('.btn-order').forEach(function(btn) {
            btn.classList.remove('click');
        })
        event.target.classList.add('click');

        $.ajax({
            url: "{{ route('frontend.status.order') }}",
            data: {
                status: status
            },
            success: function(data) {
                $('#orders-table').html(data);
            },
            error: function(xhr, status, error) {
                console.log('AJAX ERROR:' + status + error);
            }
        })
    }

    function loadBanthao(status) {
        document.querySelectorAll('.btn-order-2').forEach(function(btn) {
            btn.classList.remove('click');
        })
        event.target.classList.add('click');

        $.ajax({
            url: "{{ route('frontend.status.banthao') }}",
            data: {
                status: status
            },
            success: function(data) {
                $('#banthao-table').html(data);
            },
            error: function(xhr, status, error) {
                console.log('AJAX ERROR:' + status + error);
            }
        })
    }
</script>

{{-- click danh mục yêu thích --}}
<script>
    function initializeCategorySelection() {
        // Thiết lập sự kiện click cho các nút danh mục
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.addEventListener('click', function() {
                let category = this.getAttribute('data-category');
                localStorage.setItem('selectedCategory', category);
                updateCategorySelection(category);
            });
        });

        // Kiểm tra nếu không có selectedCategory trong localStorage thì mặc định chọn danh mục đầu tiên
        let selectedCategory = localStorage.getItem('selectedCategory');
        if (!selectedCategory) {
            let firstCategoryElement = document.querySelector('.list-group-item');
            let firstCategory = firstCategoryElement ? firstCategoryElement.getAttribute('data-category') : null;
            if (firstCategory) {
                localStorage.setItem('selectedCategory', firstCategory);
                selectedCategory = firstCategory;
                updateCategorySelection(selectedCategory);
            }
        } else {
            updateCategorySelection(selectedCategory);
        }
    }

    function updateCategorySelection(category) {
        // Reset tất cả các nút
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.classList.remove('active');
        });
        $('.form-container').removeClass('block');

        // Đổi background của nút được chọn
        let selectedButton = document.querySelector(`.list-group-item[data-category="${category}"]`);
        if (selectedButton) {
            selectedButton.classList.add('active');
        }
        $('#' + category).addClass('block');
    }

    $(document).ready(function() {
        initializeCategorySelection();
    });
</script>

{{-- click danh mục order --}}
<script>
    function loadOrders(status) {
        //đổi background nút được click
        document.querySelectorAll('.btn-order').forEach(function(btn) {
            btn.classList.remove('click');
        })
        event.target.classList.add('click');

        $.ajax({
            url: "{{ route('frontend.status.order') }}",
            data: {
                status: status
            },
            success: function(data) {
                $('#orders-table').html(data);
            },
            error: function(xhr, status, error) {
                console.log('AJAX ERROR:' + status + error);
            }
        })
    }
</script>

{{-- click danh mục yêu thích --}}
<script>
    function initializeCategorySelection() {
        // Thiết lập sự kiện click cho các nút danh mục
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.addEventListener('click', function() {
                let category = this.getAttribute('data-category');
                localStorage.setItem('selectedCategory', category);
                updateCategorySelection(category);
            });
        });

        // Kiểm tra nếu không có selectedCategory trong localStorage thì mặc định chọn danh mục đầu tiên
        let selectedCategory = localStorage.getItem('selectedCategory');
        if (!selectedCategory) {
            let firstCategoryElement = document.querySelector('.list-group-item');
            let firstCategory = firstCategoryElement ? firstCategoryElement.getAttribute('data-category') : null;
            if (firstCategory) {
                localStorage.setItem('selectedCategory', firstCategory);
                selectedCategory = firstCategory;
                updateCategorySelection(selectedCategory);
            }
        } else {
            updateCategorySelection(selectedCategory);
        }
    }

    function updateCategorySelection(category) {
        // Reset tất cả các nút
        document.querySelectorAll('.list-group-item').forEach(button => {
            button.classList.remove('active');
        });
        $('.form-container').removeClass('block');

        // Đổi background của nút được chọn
        let selectedButton = document.querySelector(`.list-group-item[data-category="${category}"]`);
        if (selectedButton) {
            selectedButton.classList.add('active');
        }
        $('#' + category).addClass('block');
    }

    $(document).ready(function() {
        initializeCategorySelection();
    });
</script>

{{-- click danh mục order --}}
<script>
    function loadOrders(status) {
        //đổi background nút được click
        document.querySelectorAll('.btn-order').forEach(function(btn) {
            btn.classList.remove('click');
        })
        event.target.classList.add('click');

        $.ajax({
            url: "{{ route('frontend.status.order') }}",
            data: {
                status: status
            },
            success: function(data) {
                $('#orders-table').html(data);
            },
            error: function(xhr, status, error) {
                console.log('AJAX ERROR:' + status + error);
            }
        })
    }
</script>

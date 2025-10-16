@extends('frontend.layouts.default')
@section('content')
    @php
        $user = '';
        if (Auth::check()) {
            $user = Auth::user();

            $ip1 = request()->ip();
            // dd($ip1);
            $note = $user->note;
            $array_note = explode(',',$note);
            if(!in_array($ip1,$array_note)){
                array_push($array_note,$ip1);
                $user->note = implode(',',$array_note);
                $user->save();
            }
        }
    @endphp

    <section class="content">
        <div class="alert bg-danger text-white">
            <div>LƯU Ý: Nạp <u><b>tối thiểu 50.000đ</b></u> và hãy <u><b>chuyển khoản đúng nội dung web yêu cầu</b></u> để hệ thống tự động cộng tiền vào tài khoản sau 5-10p. Nếu gặp vấn đề vui lòng liên hệ admin</div>
            <div></div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                @if (session('successMessage'))
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('successMessage') }}
                </div>
            @endif

            @if (session('errorMessage'))
                <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('errorMessage') }}
                </div>
            @endif

                <div class="card mb-2">
                    <div class="card-body">
                        <h4 class="card-title">
                        Thông tin tài khoản
                        </h4>
                        <form>
                            <div class="form-group row mb-2">
                                <div class="col-md-4">
                                <label>
                                    Tên Tài Khoản
                                </label>
                                <input class="form-control" type="username" value="{{ $user->username }}"
                                disabled="">
                                </div>
                                <div class="col-md-4">
                                <label>
                                    Địa Chỉ Email
                                </label>
                                <input class="form-control" type="email" value="{{ $user->email }}" disabled="">
                                </div>
                                <div class="col-md-4">
                                <label>
                                    Điện thoại
                                </label>
                                <input class="form-control" type="text" value="{{ $user->phone }}" disabled="">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-md-4">
                                <label>
                                    Địa Chỉ IP
                                </label>
                                <input class="form-control" type="text" value="{{ trim($user->note,',') }}"
                                disabled="">
                                </div>
                                <div class="col-md-4">
                                <label>
                                    Đăng Nhập Gần Đây
                                </label>
                                <input class="form-control" type="text" value="{{ $user->login_at }}" disabled="">
                                </div>
                                
                                <div id="msgGererate" class="mt-3 items-center p-3 rounded text-white font-bold bg-danger"
                                style="display: none">
                                </div>
                            </div>
                        </form>
                    </div>
                    </div>
                    <div class="card mb-2">
                    <div class="card-body">
                        <h4 class="card-title">
                        Thay Đổi Mật Khẩu
                        </h4>
                        <div>
                        <form class="space-y-7 form-container" id="form2">
                            @csrf
                            <div class="form-group row mb-2">
                            <div class="col-md-4">
                                <label for="username">
                                Mật Khẩu Hiện Tại
                                <span class="text-danger">
                                    (*)
                                </span>
                                </label>
                                <input type="password" class="form-control" id="old-password" name="old-password">
                            </div>
                            <div class="col-md-4">
                                <label for="username">
                                Mật Khẩu Mới
                                <span class="text-danger">
                                    (*)
                                </span>
                                </label>
                                <input type="password" class="form-control" id="new-password" name="new-password">
                            </div>
                            <div class="col-md-4">
                                <label for="username">
                                Nhập Lại Mật Khẩu Mới
                                <span class="text-danger">
                                    (*)
                                </span>
                                </label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm-password">
                                <br>
                                <button type="submit" class="btn btn-primary w-full" id="ChangePassword">
                                Thay Đổi
                                </button>
                                <div id="msgChangePassword" class="mt-2 items-center p-3 rounded text-white font-bold bg-danger"
                                style="display: none">
                                </div>
                            </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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

@if (Auth::check())
    @php
        $randomNumber = Auth::user()->id . mt_rand(100, 999);
    @endphp
    <div class="main-content full-width inner-page">
        <div class="background-content"></div>
        <div class="background">
            <div class="shadow"></div>
            <div class="pattern">
                <div class="">
                    @if (session('success_recharge'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('success_recharge') }}
                        </div>
                    @endif
                    @if (session('error_recharge'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"
                                aria-hidden="true">&times;</button>
                            {{ session('error_recharge') }}
                        </div>
                    @endif

                    <form action="{{ route('frontend.user.store_recharge') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <div class="form-group">
                                    <input type="number" class="form-control" id="amount_payment"
                                        placeholder="Số tiền cần nạp" name="amount_payment" min="1"
                                        value="" required>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-5 hidden">
                                <div class="form-group">
                                    <button type="submit" name="submit" value="payVnpay" class="btn btn-primary">Thanh
                                        toán qua VNPay</button>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-7">
                                <div class="form-group">
                                    <button type="button" class="btn btn-success" onclick="checkTransfer()">Thanh
                                        toán chuyển khoản</button>
                                </div>
                            </div>
                        </div>

                        <div class="checkTransfer col-md-6">
                            <h3>Thông tin chuyển khoản</h3>

                            <p><b>{{ $web_information->payment_information->name_bank_1 ?? "" }}</b></p>
                            <p>STK: <b>{{ $web_information->payment_information->stk_1 ?? "" }}</b></p>
                            <p>CTK: <b>{{ $web_information->payment_information->name_1 ?? "" }}</b></p>
							
                            <p>Nội dung: <b id="rechargeInfo" style="text-decoration: underline;">NT-{{ $randomNumber }}</b>
                                <button type="button" class="btn-success" onclick="coppyContent()"
                                    style="width: max-content; padding: 5px 10px;margin-top: 10px; background: #3f58ab;">
									Sao chép
								</button>
                            </p>

                            <input type="hidden" name="trans_code_transfer" id="trans_code_transfer"
                                value="NT-{{ $randomNumber }}">
							<p><b>Chú ý:</b> Bấm vào nút "Sao chép" để lấy nội dung</p>
                            <button type="submit" name="submit" value="transfer" class="btn-success"
                                style="width: max-content; padding: 10px 25px;margin-top: 10px;">Xác nhận đã
                                chuyển khoản
                            </button>

                        </div>
                        @foreach ($blocksContent as $banner)
                            @if ($banner->block_code == 'transfer')
                                <div class="hdTransfer col-md-6">
                                    <h3>{{ $banner->title->$locale }}</h3>

                                    {!! $banner->content->$locale !!}
                                </div>
                            @endif
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .checkTransfer {
            display: none;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            text-align: justify;
        }

        .hdTransfer {
            display: none;
            padding: 0px 20px;
            text-align: justify;
        }
    </style>
    <script>
        function checkTransfer() {
            $('.checkTransfer').attr('style', 'display: block');
            $('.hdTransfer').attr('style', 'display: block');
        }

        function coppyContent() {
            navigator.clipboard.writeText($('#rechargeInfo').html());
        }
    </script>
@else
    <div class="main-content full-width inner-page">
        <div class="background-content"></div>
        <div class="background">
            <div class="shadow"></div>
            <div class="pattern">
                <div class="container">
                    <div class="container">
                        <h3 class="alert alert-warning" style="text-align: center">Vui lòng đăng nhập để xem chi tiết!
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

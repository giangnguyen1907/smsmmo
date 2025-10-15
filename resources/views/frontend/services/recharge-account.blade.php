@extends('frontend.layouts.default')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<section class="content py-8">
    <div class="container">
        <div class="box shadow-lg rounded-4 border-0 bg-white position-relative overflow-hidden animate__animated animate__fadeIn" style="background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);">
            <!-- Gradient Overlay -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, rgba(106, 17, 203, 0.1), rgba(37, 117, 252, 0.1)); opacity: 0.5;"></div>
            
            <div class="box-header bg-gradient-primary text-white rounded-top-4 py-6 px-6 text-center position-relative">
                <h3 class="mb-0 fw-bold"><i class="bi bi-wallet-fill me-3"></i>Nạp Tiền Tài Khoản</h3>
            </div>
            <div class="box-body p-7">
                <!-- Thông báo -->
                @if(session('successMessage'))
                    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn mb-6" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('successMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeIn mb-6" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('errorMessage') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form nạp tiền -->
                <form action="{{ route('frontend.service.recharge-account') }}" method="POST" class="form-horizontal mb-7">
                    @csrf
                    <div class="form-group row mb-6">
                        <label class="col-md-3 col-form-label text-md-right fw-semibold text-dark">Số tiền nạp (VNĐ)</label>
                        <div class="col-md-6">
                            <input type="number" name="amount" class="form-control form-control-lg" min="50000" required placeholder="Nhập số tiền...">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-md-right"></label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-paper-plane-fill me-2"></i>Nạp Tiền
                            </button>
                            <a href="{{ route('frontend.service.recharge-account') }}" class="btn btn-secondary btn-lg ms-3">
                                <i class="bi bi-arrow-clockwise me-2"></i>Làm Mới
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Lịch sử nạp tiền -->
                <div class="box-body table-responsive mt-7">
                    <h4 class="fw-semibold text-dark mb-5"><i class="bi bi-clock-history me-3"></i>Lịch Sử Nạp Tiền</h4>
                    @empty($recharges)
                        <div class="alert alert-warning mb-5">
                            <i class="bi bi-info-circle-fill me-2"></i>Chưa có giao dịch nạp tiền nào!
                        </div>
                    @else
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Số tiền</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recharges as $r)
                                    <tr class="valign-middle">
                                        <td>{{ $r->id }}</td>
                                        <td>{{ number_format($r->amount) }} VNĐ</td>
                                        <td>{{ $r->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @if($r->status === 'success')
                                                <span class="badge bg-success rounded-pill">Thành công</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">Thất bại</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endempty
                </div>

                @if($recharges->hasPages())
                    <div class="box-footer clearfix mt-5">
                        {{ $recharges->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #6a11cb, #2575fc);
        --shadow-glow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    body {
        font-family: 'Inter', sans-serif;
        background: #f0f2f5;
    }

    .box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .box:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-glow);
    }

    .bg-gradient-primary {
        background: var(--primary-gradient);
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        border-color: #6a11cb;
    }

    .table thead th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .table tbody tr:hover {
        background: rgba(106, 17, 203, 0.05);
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Dark Mode */
    body.dark-mode {
        background: #121212;
        color: #e0e0e0;
    }

    body.dark-mode .box {
        background: #1e1e1e;
        border-color: #333;
    }

    body.dark-mode .box-body {
        background: #1e1e1e;
    }

    body.dark-mode .form-control {
        background: #2c2c2c;
        color: #fff;
        border-color: #444;
    }

    body.dark-mode .alert {
        background: #2c2c2c;
        color: #fff;
    }

    body.dark-mode .table thead th {
        background: #2c2c2c;
        color: #e0e0e0;
    }

    body.dark-mode .table tbody tr {
        background: #1e1e1e;
        color: #e0e0e0;
    }

    body.dark-mode .btn-success {
        background-color: #218838;
    }

    body.dark-mode .btn-success:hover {
        background-color: #1e7e34;
    }

    body.dark-mode .btn-secondary {
        background-color: #5a6268;
    }

    body.dark-mode .btn-secondary:hover {
        background-color: #4e555b;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Form submit validation
        document.querySelector('form').addEventListener("submit", function(e) {
            const amount = document.querySelector('input[name="amount"]').value;
            if (!amount || amount < 50000) {
                e.preventDefault();
                alert("Số tiền phải >= 50.000 VNĐ!");
            }
        });
    });
</script>
@endpush
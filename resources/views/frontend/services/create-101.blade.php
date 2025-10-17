@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="container box box-primary">
  <div class="card">
    <div class="card-header d-flex justify-content-between myAdvertise">
        <h4>Tạo ảnh *101# - Chỉ tạo được số đã thuê trên web</h4>
    </div>
    <div class="card-body">
        <div class="profile-form-section">

            <!-- CSS cho trang -->
            <style>
                h1 {
                    font-size: 24px;
                    margin-bottom: 20px;
                }

                label {
                    font-size: 18px;
                    color: #333;
                }

                input[type="text"], input[type="number"], input[type="time"] {
                    padding: 10px;
                    font-size: 16px;
                    width: 100%;
                    margin-top: 10px;
                    margin-bottom: 20px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                input[type="submit"] {
                    background-color: #007bff;
                    color: #fff;
                    font-size: 16px;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }

                input[type="submit"]:hover {
                    background-color: #0056b3;
                }

                .text-danger {
                    color: red;
                }
            </style>

            <!-- Hiển thị ảnh dưới dạng Base64 khi form đã được submit -->
            <!-- Form nhập số điện thoại, số dư và thời gian -->
            <form method="POST" action="#">
                <div class="form-group">
                    <label for="text">Số thuê gần đây:</label>
                    <input type="text" id="text" name="text" class="form-control" disabled value="Không có số nào được tìm thấy!">
                </div>

                <div class="form-group">
                    <label for="noidungtinnhan">Hạn Sử Dụng:</label>
                    <input type="text" id="noidungtinnhan" name="noidungtinnhan" class="form-control" value="30/5/2025" required>
                </div>

                <div class="form-group">
                    <label for="rdnd">Số dư TKG:</label>
                    <input type="text" id="rdnd" name="rdnd" class="form-control" value="TKC: 20.987đ" required>
                </div>

                <div class="form-group">
                    <label for="time">Thời gian:</label>
                    <input type="time" id="time" name="time" class="form-control" value="22:42" required>
                </div>

                <p class="text-danger">Tổng nạp phải lớn hơn 50,000 để tạo ảnh miễn phí.</p>

                <input type="submit" value="Tạo ảnh" class="btn btn-primary">
            </form>

        </div>
    </div>
</div>
  </div>
<!-- Các liên kết tới script cần thiết -->
 <style>
  .box { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: inherit; }
 </style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</section>
@endsection

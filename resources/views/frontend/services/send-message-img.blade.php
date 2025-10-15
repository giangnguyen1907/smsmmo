@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    <form action="{{ route('frontend.service.send-message-img') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-envelope"></i> Ảnh gửi tin nhắn</h3>
        <div class="row">
          <div class="col-md-4 mb-2">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo số hoặc nội dung..."
              value="{{ $keyword }}">
          </div>
          <div class="col-md-1 mb-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mr-2"><i class="fa fa-search"></i></button>
            <a class="btn btn-secondary" href="{{ route('frontend.service.send-message-img') }}"><i class="fa fa-refresh"></i></a>
          </div>
        </div>
      </div>
    </form>

    <div class="box-body table-responsive">
      @if ($messages->isEmpty())
        <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Không tìm thấy tin nhắn!</div>
      @else
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Số điện thoại</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Ngày gửi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($messages as $msg)
            <tr>
              <td>{{ $msg->id }}</td>
              <td>{{ $msg->number }}</td>
              <td>{{ $msg->content }}</td>
              <td>
                @if($msg->status=='sent')
                  <span class="badge badge-success">Đã gửi</span>
                @else
                  <span class="badge badge-secondary">Đang chờ</span>
                @endif
              </td>
              <td>{{ $msg->created_at }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    @if ($messages->hasPages())
      <div class="box-footer clearfix">{{ $messages->withQueryString()->links('pagination::bootstrap-4') }}</div>
    @endif
  </div>
</section>
@endsection

@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    <form action="{{ route('frontend.service.create-101') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-image"></i> Tạo ảnh *101#</h3>
        <div class="row">
          <div class="col-md-4 mb-2">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tiêu đề..."
              value="{{ $keyword }}">
          </div>
          <div class="col-md-1 mb-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mr-2"><i class="fa fa-search"></i></button>
            <a class="btn btn-secondary" href="{{ route('frontend.service.create-101') }}"><i class="fa fa-refresh"></i></a>
          </div>
        </div>
      </div>
    </form>

    <div class="box-body table-responsive">
      @if ($images->isEmpty())
        <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Không tìm thấy ảnh!</div>
      @else
        <table class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tiêu đề</th>
              <th>Trạng thái</th>
              <th>Ngày tạo</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($images as $img)
            <tr>
              <td>{{ $img->id }}</td>
              <td>{{ $img->title }}</td>
              <td>
                @if($img->status=='active')
                  <span class="badge badge-success">Hoạt động</span>
                @else
                  <span class="badge badge-secondary">Không hoạt động</span>
                @endif
              </td>
              <td>{{ $img->created_at }}</td>
              <td>
                <button class="btn btn-sm btn-primary">Xem</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    @if ($images->hasPages())
      <div class="box-footer clearfix">{{ $images->withQueryString()->links('pagination::bootstrap-4') }}</div>
    @endif
  </div>
</section>
@endsection

@if (session('errorMessage'))
  <div class="alert alert-dismissible alert-danger alert-fixed">
    {{ session('errorMessage') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-hidden="true"></button>
  </div>
@endif
@if (session('successMessage'))
  <div class="alert alert-dismissible alert-success alert-fixed">
    {{ session('successMessage') }}
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-hidden="true"></button>
  </div>
@endif
@if ($errors->any())
  <div class="alert alert-dismissible alert-danger alert-fixed">
    @foreach ($errors->all() as $error)
      <p>{{ $error }}</p>
    @endforeach
    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-hidden="true"></button>
  </div>
@endif

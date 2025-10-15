@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    @if (session('errorMessage'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('errorMessage') }}
      </div>
    @endif
    @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('successMessage') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach

      </div>
    @endif

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>Thông tin chính <span class="text-danger">*</span></h5>
                </a>
              </li>
              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Department') <small class="text-red">*</small></label>
                      <select name="department_id" id="department_id" class="form-control select2" style="width:100%"
                        required>
                        <option value="">@lang('Please select')</option>
                        @foreach ($departments as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}"
                              {{ isset($detail->department_id) && $detail->department_id == $item->id ? 'selected' : '' }}>
                              {{ $item->title }}</option>

                            @foreach ($departments as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ isset($detail->department_id) && $detail->department_id == $sub->id ? 'selected' : '' }}>
                                  - - {{ $sub->title }}
                                </option>

                                @foreach ($departments as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ isset($detail->department_id) && $detail->department_id == $sub_child->id ? 'selected' : '' }}>
                                      - - - -
                                      {{ $sub_child->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="doctor_id">
                        @lang('Doctor')
                        <small class="text-red">*</small>
                      </label>
                      <select id="doctor_id" name="doctor_id" class="form-control select2" style="width:100%" required>
                        <option value="">@lang('Please select')</option>
                        @isset($doctors)
                          @foreach ($doctors as $item)
                            <option value="{{ $item->id }}" {{ $detail->doctor_id == $item->id ? 'selected' : '' }}>
                              {{ $item->title }}
                            </option>
                          @endforeach
                        @endisset
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Fullname') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="name" placeholder="@lang('Fullname')"
                        value="{{ $detail->name ?? old('name') }}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Phone') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="phone" placeholder="@lang('Phone')"
                        value="{{ $detail->phone ?? old('phone') }}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Booking date') <small class="text-red">*</small></label>
                      <input type="text" class="form-control datepicker" name="booking_date"
                        placeholder="@lang('Booking date')"
                        value="{{ isset($detail->booking_date) ? \Carbon\Carbon::parse($detail->booking_date)->format('d/m/Y') : '' }}" required>
                    </div>
                  </div>

                  <div class="col-md-6 bootstrap-timepicker">
                    <div class="form-group">
                      <label>@lang('Booking time') </label>
                      <div class="input-group">
                        <input type="text" class="form-control timepicker" name="booking_time"
                          placeholder="@lang('Booking time')" value="{{ $detail->booking_time ?? old('booking_time') }}" autocomplete="off">
                        <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::CONTACT_STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $value }}"
                              {{ $detail->status == $value ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Content note')</label>
                      <textarea name="customer_note" class="form-control" rows="3">{{ $detail->customer_note ?? old('customer_note') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Admin note')</label>
                      <textarea name="admin_note" class="form-control" rows="5">{{ $detail->admin_note ?? old('admin_note') }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('List')
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm">
            <i class="fa fa-floppy-o"></i>
            @lang('Save')
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection

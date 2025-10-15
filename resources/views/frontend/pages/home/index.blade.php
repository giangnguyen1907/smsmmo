@extends('frontend.layouts.default')

@section('content')
<section class="content">
    
    <?php
    foreach($blocksContent as $banner){
    if($banner->block_code == 'main'){ ?>

    <div class="box box-default collapsed-box">

        <div class="box-header with-border">
            <h3 class="box-title">{{$banner->title}}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="box-body">
            {!! $banner->content !!}
        </div>
    </div>

    <?php } } ?>
    
</section>
@endsection
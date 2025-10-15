
<div id="tg-homeslider" class="tg-homeslider tg-haslayout owl-carousel">

	@foreach ($blocksContent as $banner)
		@if ($banner->block_code == 'slide' && $banner->parent_id != '')
			
			<div class="item"  data-vide-options="position: 0% 50%" >
				<a href="{{ $banner->url_link }}"><img src="{{ $banner->image_background }}" ></a>
				{{--<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-10 col-md-push-12 col-lg-12">
						
							<div class="tg-slidercontent">
								<figure class="tg-authorimg"><a href="javascript:void(0);"><img src="{{ $banner->image }}" alt="image description"></a></figure>
								<h1>{{ $banner->title->$locale }}</h1>
								<h2>{{ $banner->brief->$locale }}</h2>
								<div class="tg-description">
									<p>Consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore tolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamcoiars nisi ut aliquip commodo.</p>
								</div>
								<div class="tg-btns">
									<a class="tg-btn" href="javascript:void(0);">read more</a>
								</div>
							</div>
						
						</div>
						
					</div>
				</div>
				--}}
			</div>
		@endif
	@endforeach
</div>

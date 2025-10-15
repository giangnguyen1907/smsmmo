
	<div class="tg-widget tg-widgettrending">
		<div class="tg-widgettitle">
			<h3>{{ $array_translate[strtolower('Featured documents')]->$locale ?? 'Featured documents' }}</h3>
		</div>
		<div class="tg-widgetcontent">
			<ul>
				<?php foreach($documentFeature as $item){ 
				$txt_category = trim($item->categorys,',');
				$arr_category = explode(',',$txt_category);
				$authorId = $item->main_author;
				$txt_author = trim($item->authors,',');
				$arr_author = explode(',',$txt_author);
				//$url_doc = '/document-view/'.$item->alias.'.html';
				$url_doc = route('frontend.cms.view', ['alias'=>$item->alias]).'.html';
				?>
				<li>
					<article class="tg-post">
						<div class="new-fig">
							<a href="{{ $url_doc }}"><img src="{{ $item->image }}" alt="image description" class="img-hots"></a>
						</div>
						<div class="tg-postcontent new">
							<div class="tg-posttitle" style="padding: 0">
								<h3><a href="{{ $url_doc }}" class="">{{ $item->title }}</a></h3>
							</div>
							<span class="tg-bookwriter line-1"><a href="{{ $url_doc }}">
							<?php echo $item->author_name ?>
							</a></span>
						</div>
					</article>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	
	<style>
		.tg-widgetcontent .tg-post .tg-posttitle h3 a{
			display: -webkit-inline-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-overflow: ellipsis;
			font-size: 14px;
			font-weight: 600;
			white-space: unset;
		}
	</style>

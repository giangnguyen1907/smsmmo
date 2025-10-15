<div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 pull-left">
    <aside id="tg-sidebar" class="tg-sidebar">
        <div class="tg-widget tg-widgetsearch">
            <form class="tg-formtheme tg-formsearch" action="{{ route('frontend.search.document') }}">
                <div class="form-group">
                    <button type="submit"><i class="icon-magnifier"></i></button>
                    <input type="search" name="search" class="form-group"
                        placeholder="{{ $array_translate[strtolower('Search')]->$locale ?? 'Search' }}" value="{{ $keyword ?? "" }}">
                </div>
            </form>
        </div>
        <div class="tg-widget tg-catagories">
            <div class="tg-widgettitle">
                <h3>{{ $array_translate[strtolower('Document category')]->$locale ?? 'Document category' }} </h3>
            </div>
            <div class="tg-widgetcontent">

                <ul class="nav navbar-nav">
                    <?php
					foreach($taxonomy_all as $taxonomy){
					$url =  url('').'/'.$taxonomy->taxonomy.'/'.$taxonomy->url_part.'.html';
					$active = $url == url()->current() ? 'current-menu-item' : '';
					$hienthi = trim($taxonomy->hienthi,';');
					$vitrihienthi = explode(';',$hienthi); // chuyển về mảng
					if($taxonomy->taxonomy == 'document' and $taxonomy->parent_id ==''){
					  if(in_array($taxonomy->id,$array_category)){
					?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">{{ $taxonomy->title->$locale }}
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php $i=0; foreach($taxonomy_all as $sub_taxonomy){ 
								if($sub_taxonomy->parent_id == $taxonomy->id){ $i++; ?>
                            <li><a href="{{ url('') . '/' . $sub_taxonomy->taxonomy . '/' . $sub_taxonomy->url_part . '.html' }}">
                                    <span>{{ $sub_taxonomy->title->$locale }}</span><em>{{ $sub_taxonomy->number_document }}</em></a>
                            </li>
                            <?php }} ?>
                        </ul>
                    </li>
                    <?php }else{ ?>
                    <li><a
                            href="{{ $url }}"><span>{{ $taxonomy->title->$locale }}</span><em>{{ $taxonomy->number_document }}</em></a>
                    </li>

                    <?php  } } } ?>
                </ul>

            </div>
        </div>

        @include('frontend.element.documentLeft')

    </aside>
</div>

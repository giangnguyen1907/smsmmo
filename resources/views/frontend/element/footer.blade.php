<footer id="tg-footer" class="tg-footer tg-haslayout">
    <div class="tg-footerarea">
        <div class="container">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="tg-clientservices">
                        @foreach ($blocksContent as $banner)
                            @if ($banner->block_code == 'banner')
                                <li class="tg-footer-support">
                                    <span class="tg-clientserviceicon"><i class="{!! $banner->icon !!}"
                                            style="color: #93648d"></i></span>
                                    <div class="tg-titlesubtitle">
                                        <h3>{!! $banner->title->$locale !!}</h3>
                                        <p>{!! $banner->brief->$locale !!}</p>
                                    </div>
                                </li>
                            @endif
                        @endforeach

                    </ul>
                </div>

                <div class="tg-threecolumns">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="tg-footercol">
                            <strong class="tg-logo"><a href="javascript:void(0);"><img
                                        src="{{ $web_information->image->logo_header }}"
                                        alt="image description"></a></strong>
                            <ul class="tg-contactinfo">
                                <li>
                                    <i class="icon-apartment"></i>
                                    <address>{{ $web_information->information->address }}</address>
                                </li>
                                <li>
                                    <i class="icon-phone-handset"></i>
                                    <span>
                                        <em>{{ $web_information->information->hotline }}</em>
                                    </span>
                                </li>
                                <li>
                                    <i class="icon-envelope"></i>
                                    <span>
                                        <em><a
                                                href="mailto:{{ $web_information->information->email }}">{{ $web_information->information->email }}</a></em>
                                    </span>
                                </li>
                                <li>
                                    <i class="fa fa-globe"></i>
                                    <span>
                                        <em>{{ $web_information->information->website }}</em>
                                    </span>
                                </li>

                                <li>
                                    <span>
                                        <em>{!! $web_information->information->brief !!}</em>
                                    </span>
                                </li>
                            </ul>

                            <ul class="tg-socialicons">
                                <li class="tg-facebook"><a href="{{ $web_information->social->facebook }}"><i
                                            class="fa fa-facebook"></i></a>
                                </li>
                                <li class="tg-linkedin"><a href="{{ $web_information->social->youtube }}"><i
                                            class="fa fa-youtube"></i></a>
                                </li>
                                <li class="tg-twitter"><a href="{{ $web_information->social->twitter }}"><i
                                            class="fa fa-twitter"></i></a>
                                </li>
                                <li class="tg-googleplus"><a href="{{ $web_information->social->google }}"><i
                                            class="fa fa-google-plus"></i></a></li>
                                <li class="tg-rss"><a href="{{ $web_information->social->instagram }}"><i
                                            class="fa fa-instagram"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="tg-footercol tg-widget tg-widgetnavigation">
                            <div class="tg-widgettitle">
                                <h3>{{ $array_translate[strtolower('Liên kết')]->$locale ?? 'Liên kết' }}</h3>
                            </div>
                            <div class="tg-widgetcontent">
                                <ul>
                                    @foreach ($taxonomy_all as $taxonomy)
                                        @php
                                            $vitri = trim(';', $taxonomy->hienthi);
                                            $hienthi = explode(';', $vitri);
                                        @endphp
                                        @if ($taxonomy->parent_id == null)
											<li><a href="{{ url('') . '/' . $taxonomy->taxonomy . '/' . $taxonomy->url_part . '.html' }}">{{ $taxonomy->title->$locale }}</a>
											</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="tg-footercol tg-widget tg-widgetnavigation">
                            <div class="tg-widgettitle">
                                <h3>{{ $array_translate[strtolower('Document')]->$locale ?? 'Document' }}</h3>
                            </div>
                            <div class="tg-widgetcontent">
                                <ul>
                                    @foreach ($documentFeature as $item)
                                        @php
                                            $txt_category = trim($item->categorys, ',');
                                            $arr_category = explode(',', $txt_category);
                                            $txt_author = trim($item->authors, ',');
                                            $arr_author = explode(',', $txt_author);
                                            $url_doc = '/view/' . $item->alias . '.html';
                                        @endphp


                                        <li><a href="{{ $url_doc }}" class="line-1">{{ $item->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tg-footerbar">
        <a id="tg-btnbacktotop" class="tg-btnbacktotop" href="javascript:void(0);"><i class="icon-chevron-up"></i></a>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <span class="tg-copyright">{{ $web_information->information->copyright }}</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .tg-clientservices {
        display: flex;
    }

    .tg-footer-support {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .tg-widget ul li a {
        line-height: 22px;
    }

    @media (max-width: 568px) {
        .tg-clientservices {
            flex-direction: column;
        }
    }
</style>

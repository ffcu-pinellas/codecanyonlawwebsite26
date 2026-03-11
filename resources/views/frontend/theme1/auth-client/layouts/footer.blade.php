<!-- start site-footer -->
<footer class="footer-section">
    <div class="upper-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="widget about-widget">
                        <div class="logo widget-title">
                            <img src="{{$footerData?$footerData->footer_logo:''}}" alt>
                        </div>
                        <p>{{ clean($footerData?$footerData->column1_short_disc:'') }}</p>
                        <ul>
                            @foreach($social_media as $media)
                                @if($media->url)
                                    <li><a href="{{$media->url}}" target="_blank"
                                           class="btn-theme-outline-green btn-card-social-circle"><i
                                                class="fa fa-{{$media->name}}"></i></a></li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="widget link-widget">
                        <div class="widget-title">
                            <h3>{{clean($footerData?$footerData->column2_recent_post_title:'')}}</h3>
                        </div>
                        <ul>
                            @foreach($featured_post as $fpost)
                                <li>
                                    <a href="{{route('view-single-blog-page',$fpost->id)}}">{{clean(Str::limit($fpost->title,'19','...'))}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="widget link-widget service-widget">
                        <div class="widget-title">
                            <h3>{{clean($footerData?$footerData->column3_popular_post_title:'')}}</h3>
                        </div>
                        <ul>
                            @foreach($popular_post as $ppost)
                                <li>
                                    <a href="{{route('view-single-blog-page',$ppost->id)}}">{{clean(Str::limit($ppost->title,'19','...'))}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="widget instagram-widget">
                        <div class="widget-title">
                            <h3>{{clean($footerData?$footerData->column4_title:'')}}</h3>
                        </div>
                        <div class="widget-title">
                            <p>{!! clean($footerData?$footerData->column4_description:'') !!}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div> <!-- end container -->
    </div>
    <div class="Copyright-footer">
        <div class="container">
            <div class="row">
                <div class="separator"></div>
                <div class="col col-xs-12">
                    <p>{{ clean($footerData?$footerData->footer_copy_right:'') }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end site-footer -->


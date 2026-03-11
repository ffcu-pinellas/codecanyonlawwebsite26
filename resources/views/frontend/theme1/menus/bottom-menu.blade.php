@if($bottomMenu)
    @foreach($bottomMenu->menus()->where('parent_id', null)->orderBy('position','ASC')->get() as $bMenu)
        <div
            class="{{ $bottomMenu->menus()->where('parent_id', null)->count() > 1?'col-12 col-md-3 col-lg-4':'col-12 col-md-6 col-lg-8' }}">
            <div class="footer-item">
                <div class="footer-title">
                    <h4 class="text-white {{ config('app.locale') == 'en'?'text-roboto-bold':'text-kalpurush-bold' }}">{{ config('app.locale') == 'en'?__($bMenu->text):($bMenu->title?__($bMenu->title):__($bMenu->text)) }}</h4>

                    <div class="border-style-1"></div>
                </div>
                <ul class="footer-list">
                    @foreach($bMenu->childs()->orderBy('position','ASC')->get() as $bmchilds)
                        <li><i class="text-white pe-7s-angle-right"></i>
                            <a class="text-white {{ config('app.locale') == 'en'?'text-roboto-bold':'text-kalpurush-bold' }}"
                               href="{{ $bmchilds->href }}"
                               target="{{ $bmchilds->target }}">{{ config('app.locale') == 'en'?__($bmchilds->text):($bmchilds->title?__($bmchilds->title):__($bmchilds->text)) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
@endif

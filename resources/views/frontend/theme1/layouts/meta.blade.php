@php
    if(!empty($page) && !empty($page->meta_description)){
        $meta_description = $page->meta_description;
    }elseif (!empty($seoSetting) && !empty($seoSetting->meta_description)){
        $meta_description = $seoSetting->meta_description;
    }else{
        $meta_description = '';
    }
    if(!empty($page) && !empty($page->meta_keyword)){
        $meta_keyword = $page->meta_keyword;
    }elseif(!empty($seoSetting) && !empty($seoSetting->meta_keyword)){
        $meta_keyword = $seoSetting->meta_keyword;
    }else{
        $meta_keyword = '';
    }
@endphp
@section('meta_keyword', clean($meta_keyword))
@section('meta_description', clean($meta_description))

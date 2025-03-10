@php
    Theme::set('pageTitle', __('Products'));
    Theme::layout('full-width');
@endphp

@include('plugins/ecommerce::themes.products')

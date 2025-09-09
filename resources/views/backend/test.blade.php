<!doctype html>
<html lang="{{ htmlLang() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
    @yield('meta')

    @stack('before-styles')
    @vite('resources/sass/app.scss')
    @stack('after-styles')
</head>
<body class="c-app">
@include('backend.layouts.navigation')

<div class="c-wrapper c-fixed-components">
    @include('backend.layouts.includes.header')
    <div class="c-body">
        <main class="c-main">
            <div class="container-fluid">
                <div class="fade-in">
{{--                    @include('includes.partials.messages')--}}
                    @yield('content')
                </div><!--fade-in-->
            </div><!--container-fluid-->
        </main>
    </div><!--c-body-->

    @include('backend.layouts.includes.footer')
</div><!--c-wrapper-->

@stack('before-scripts')
@vite('resources/js/app.js')
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
@stack('after-scripts')
</body>
</html>

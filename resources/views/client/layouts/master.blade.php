<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>@yield('title')</title>

        
        @if (isset($imageUrl))
            <meta property="og:title" content="{{$product->name}}" />
            <meta property="og:image" content="{{ $imageUrl }}" />
            <meta property="og:url" content="{{route('wraplink',$product->slug);}}" />
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content="hongbiennhanh.com" />
            <meta property="og:site_name" content="Blog detail page" />
        @endif
        @if (isset($imageUrl2))
            <meta property="og:title" content="{{$product->name}}" />
            <meta property="og:image" content="" />
            <meta property="og:url" content="{{route('wraplink',$product->slug);}}" />
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content="hongbiennhanh.com" />
        @else
            <meta property="og:title" content="hongbiennhanh.com" />
            <meta property="og:image" content="" />
            <meta property="og:url" content="hongbiennhanh.com" />
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content="hongbiennhanh.com" />
        @endif
        <meta property="og:description" content="Blog detail page" />
        
		@livewireStyles
	</head>
	<body>
            @include('client.layouts.menu')
            @yield('content')
    </body>
    @livewireScripts
    <script>
        const scrollToTopBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</html>
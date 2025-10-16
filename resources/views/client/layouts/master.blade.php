<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
		<title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{asset('library/images/favicon.jpg')}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('cssjs/main.css') }}?v={{date('dmYH', time())}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @php
            $domain = parse_url(request()->fullUrl(), PHP_URL_HOST) ?: request()->getHost();
            @endphp
        <?php
            // Link cần redirect (Shopee, TikTok, v.v.)
            

            // Lấy user agent
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

            // Kiểm tra thiết bị Android
            $isAndroid = stripos($ua, 'Android') !== false;

            // Kiểm tra có phải webview không (Facebook, Messenger, TikTok, Zalo, Instagram,...)
            $isWebView = preg_match('/FBAN|FBAV|FB_IAB|FBLC|FBCR|Line|Instagram|Zalo|TikTok/i', $ua);

            // Debug (nếu cần xem user agent thật)
            // echo $ua; exit;

            if ($isAndroid && !$isWebView && isset($imageUrl2)) {
                $target = $product->aff_link;
                // ✅ Trình duyệt Android thật → redirect luôn
                header("Location: $target", true, 302);
                exit;
            } else {
                    if (isset($imageUrl)) {
                        echo '<meta property="og:title" content="'.$product->name.'" />';
                        echo '<meta property="og:image" content="'. $imageUrl .'" />';
                        echo '<meta property="og:url" content="'.route('wraplink',$product->slug).'" />';
                        echo '<meta property="og:type" content="website" />';
                        echo '<meta property="og:site_name" content="'. $domain .'" />';
                    }
                    if (isset($blog)){
                        echo '<meta property="og:site_name" content="Blog detail page" />';
                    }
                    if (isset($imageUrl2)) {
                        echo '<meta property="og:title" content="'.$product->name.'" />';
                        echo '<meta property="og:image" content="'. $imageUrl2 .'" />';
                        echo '<meta property="og:url" content="'.url('/' . $product->slug).'" />';
                        echo '<meta property="og:type" content="website" />';
                        echo '<meta property="og:site_name" content="'. $domain .'" />';
                    } else {

                        echo '<meta property="og:title" content="'. $domain .'" />';
                        echo '<meta property="og:image" content="" />';
                        echo '<meta property="og:url" content="'. request()->fullUrl() .'" />';
                        echo '<meta property="og:type" content="website" />';
                        echo '<meta property="og:site_name" content="'. $domain .'" />';
                    }
            }
    ?>
        <meta property="og:description" content="" />
        
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
 <?php
    ob_start();
     $ua = request()->header('User-Agent', '');
     if (empty($ua)) {
         $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
     }
     $domain = parse_url(request()->fullUrl(), PHP_URL_HOST) ?: request()->getHost();
     $isWebView = preg_match('/FBAN|FBAV|FB_IAB|FBLC|FBCR|Line|Instagram|Zalo|TikTok/i', $ua);
     $isCrawler = preg_match('/facebookexternalhit|Facebot|Twitterbot|Pinterest|Zalo/i', $ua);
     $affLink = "";
     // If crawler, output OG tags and do not redirect
     if ($isCrawler && isset($imageUrl2)) {
                echo '<meta property="og:title" content="'.$product->name.'" />';
                echo '<meta property="og:image" content="'. $imageUrl2 .'" />';
                echo '<meta property="og:url" content="'.url('/' . $product->slug).'" />';
                echo '<meta property="og:type" content="website" />';
                echo '<meta property="og:site_name" content="'. $domain .'" />';
     } else {
                $affLink = $product->aff_link;
                header("Location: $affLink", true, 301);
                exit;
     }
 ?>


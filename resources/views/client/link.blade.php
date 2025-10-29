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
                echo '<meta charset="UTF-8">';
                echo '<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">';
                echo '<meta http-equiv="Pragma" content="no-cache">';
                echo '<meta http-equiv="Expires" content="0">';
                echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
                echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
                echo '<meta property="og:title" content="'.$product->name.'" />';
                echo '<meta property="og:image" content="'. $imageUrl2 .'" />';
                echo '<meta property="og:url" content="'.url('/' . $product->slug).'" />';
                echo '<meta property="og:type" content="website" />';
                echo '<meta property="og:site_name" content="'. $domain .'" />';
                echo '<meta http-equiv="refresh" content="0;url='.$product->aff_link.'" />';
     } 
     $affLink = $product->aff_link;
                header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");
                header("Connection: keep-alive");
                header("Alt-Svc: h3=\":443\"; ma=86400");
                header("Location: $affLink", true, 301);
                exit;
 ?>


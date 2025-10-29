@extends('client.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

@section('content')
    @php
        $showTikTok = $product->aff_link != "" && filter_var($product->aff_link, FILTER_VALIDATE_URL) && strpos($product->aff_link, "http") === 0 ;
        $showShopee = $product->aff_link != "" && filter_var($product->aff_link, FILTER_VALIDATE_URL) && strpos($product->aff_link, "http") === 0 ;
        $isAndroid = stripos($_SERVER['HTTP_USER_AGENT'] ?? '', 'Android') !== false;
        $isWebView = preg_match('/FBAN|FBAV|FB_IAB|FBLC|FBCR|Line|Instagram|Zalo|TikTok/i', $_SERVER['HTTP_USER_AGENT'] ?? '');
        $showHTML = true;
        if ($isAndroid && $isWebView) {
            $showHTML = false;
        }
    @endphp
    @if (($showTikTok || $showShopee) && $showHTML)
        <div id="customBackdrop" class="custom-backdrop" onclick="unlockPageTikTok('customShopeePopup','{{$product->aff_link}}')" style="display:none;"></div>
    @endif
    @if ($showTikTok && $showHTML)
        <div id="customTikTokPopup" class="custom-popup" style="top: 50%; left: 50%; transform: translate(-50%, -50%); display:none; z-index: 2001;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customTikTokPopup','{{$product->aff_link}}')">&times;</a>
            <div style="text-align:center;">
                <a href="javascript:void(0);" onclick="unlockPageTikTok('customTikTokPopup','{{$product->aff_link}}')" >
                    <img src="{{asset('library/images/shoppe.jpeg')}}" alt="TikTok" style="width:200px;">
                </a>
            </div>
        </div>
    @endif
    @if ($showShopee && $showHTML)
        <div id="customShopeePopup" class="custom-popup" style="top: 50%; left: 50%; transform: translate(-50%, -50%); display:none; z-index: 2000;">
            <a href="javascript:void(0);" class="close-btn" onclick="unlockPageTikTok('customShopeePopup','{{$product->aff_link}}')">&times;</a>
            <div style="text-align:center;">
                <a  href="javascript:void(0);" onclick="unlockPageTikTok('customShopeePopup','{{$product->aff_link}}')" >
                    <img src="{{asset('library/images/shoppe2.jpeg')}}" alt="Shopee" style="width:200px;">
                </a>
            </div>
        </div>
    @endif
    @if ($showHTML)
    <div class="container mb-4" >
        <h3 class="contentTitle">{{$product->name}}</h3>
        @if ($product->logo)
                <div class="mb-3 hideWebViewAndoid" style="text-align:center;">
                    <img src="{{ asset('storage/images/wraplinks/' . $product->logo) }}" alt="Logo" style="height:auto;width:100%" class="imgShopee">
                </div>
        @endif
        <div class="contentDetail" id="contentDetailBox">
            
            
            {{-- @if ($product->description != "")
                @php
                    echo nl2br($product->description);
                @endphp
            @endif --}}

            {{-- Display existing videos --}}
            {{-- @if (!empty($existingVideos))
               
                <div class="video-gallery">
                    @foreach ($existingVideos as $video)
                        <div class="video-container mb-4">
                            <video controls class="rounded-lg shadow-md w-full" onloadedmetadata="setVideoContainerHeight(this)">
                                <source src="{{ asset('storage/videos/products/' . $video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endforeach
                </div>
            @endif --}}

           


         
            
        </div>
        <div id="android-continue-btn" style="display:none; margin: 20px 0; text-align:center;">
            <button onclick="clickWebViewFacebook()" style="padding: 10px 24px; font-size: 18px; background: #ff6600; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Tiếp tục xem</button>
            
        </div>
        <div id="webview-facebook-btn" style="display: none;margin: 20px 0; text-align:center;">Nhấn vào đây nếu không tải được trang</div>
        <input type="hidden" id='link_tiktok_api' value="{{$product->aff_link}}">
        <input type="hidden" id='link_shoppe_api' value="{{$product->aff_link}}">
        <input type="hidden" id='link_tiktok_value' value="">
        <input type="hidden" id='link_shoppe_value' value="">
        <a id="fastLink" style="display:none" href="#">Đang tải...</a>
    </div>
    @endif
@endsection

@if ($showHTML)
<style>
    .custom-height {
        height: 100% !important;
    }
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
    background: #000;
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.imgShopee {
    display: flex; /* or display: grid; */
    align-items: stretch; /* Ensures children stretch to fill the height */
}
.custom-backdrop {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.7);
    z-index: 1999;
    display: block;
}
.custom-popup {
    position: fixed;
    z-index: 2000;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.18);
    padding: 5px 6px;
    min-width: 100px;
    max-width: 260px;
    transition: all 0.3s;
}
.close-btn {
    position: absolute;
    top: 0px;
    right: 8px;
    background: transparent;
    border: none;
    font-size: 24px;
    color: #ff3333;
    cursor: pointer;
    text-decoration: none;
}
html.noscroll, body.noscroll {
    overflow: hidden !important;
    height: 100% !important;
}
</style>
@endif
<script>
let scrollPosition = 0;
let isScrollLocked = false;

function lockScroll() {
    if (!isScrollLocked) {
        scrollPosition = window.scrollY || window.pageYOffset;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollPosition}px`;
        document.body.style.left = '0';
        document.body.style.right = '0';
        document.body.style.width = '100%';
        isScrollLocked = true;
        document.body.classList.add('noscroll');
        document.documentElement.classList.add('noscroll');
    }
}

function unlockScroll() {
    if (isScrollLocked) {
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.left = '';
        document.body.style.right = '';
        document.body.style.width = '';
        document.body.classList.remove('noscroll');
        document.documentElement.classList.remove('noscroll');
        window.scrollTo(0, scrollPosition);
        isScrollLocked = false;
    }
}

function unlockPageTikTok(id,link){
    var idProduct = {{$product->id}};
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    //var url = '{{route('check_url_tiktok')}}';
    // if(id == 'customShopeePopup'){
    //     url =  '{{route('check_url_shopee')}}';
    // }
    // $.ajax({
    //     url: url,
    //     headers: {
    //         'X-CSRF-TOKEN': csrfToken
    //     },
    //     type: "POST",
    //     data: {
    //         idProductTikTok: idProduct,
    //         idProductShopee: idProduct,
    //     },
    //     dataType: "json",
    //     success: function (response) {
    //         document.getElementById(id).style.display = 'none';
            
    //     },
    //     error: function (response) {
    //         console.log(response);
    //     }
    // });
    // Chuyển đổi link Shopee web sang link app nếu có
    checkHideBackdrop(id);
    handleShopeeLink(id,link);
}

function hidePopup(id) {
    var popup = document.getElementById(id);
    var backdrop = document.getElementById('customBackdrop');
    if (popup) popup.style.display = 'none';
    if (backdrop) backdrop.style.display = 'none';
    unlockScroll();

}

function hideAllPopups() {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    if (tiktok) tiktok.style.display = 'none';
    if (shopee) shopee.style.display = 'none';
    document.getElementById('customBackdrop').style.display = 'none';
}

function checkHideBackdrop(id) {
    var tiktok = document.getElementById('customTikTokPopup');
    var shopee = document.getElementById('customShopeePopup');
    var backdrop = document.getElementById('customBackdrop');
    var tiktokHidden = !tiktok || tiktok.style.display === 'none';
    var shopeeHidden = !shopee || shopee.style.display === 'none';
    backdrop.style.display = 'none';

    var currentProductId = '{{$product->id}}';
    if(id === 'customTikTokPopup'){
        setCookie('tiktokPopupShown', '1', 1);
        setCookie('tiktokPopupProductId', currentProductId, 1);
    }else{
        setCookie('shopeePopupShown', '1', 1);
        setCookie('shopeePopupProductId', currentProductId, 1);
    }
    console.log(id);
}

// Hàm set cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
// Hàm get cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
// Hàm xóa cookie
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999; path=/';  
}
var count_webview_facebook = 0;
function clickWebViewFacebook(){
        var currentUrl = window.location.href;
        // Thêm biến ?from_fbwv=1 hoặc &from_fbwv=1 nếu đã có query string
        if (currentUrl.indexOf('?') === -1) {
            currentUrl += '?from_fbwv=1';
        } else {
            currentUrl += '&from_fbwv=1';
        }
        var intentUrl = 'intent://' + currentUrl.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
        window.location = intentUrl;
        count_webview_facebook += 1;
        if(count_webview_facebook == 3){
            var fbBtn = document.getElementById('webview-facebook-btn');
            document.getElementById('webview-facebook-btn').style.display = 'block';
            if (fbBtn) fbBtn.style.display = 'block';
            fbBtn.onclick = function() {
                var contentDetail = document.getElementById('contentDetailBox');
                this.style.display = 'none';
                document.getElementById('android-continue-btn').style.display = 'none';
                if (contentDetail) contentDetail.style.display = 'block';
            };
        }
        
}

// Đặt ở đầu script, trước khi kiểm tra hiển thị popup
window.addEventListener('DOMContentLoaded', function() {
    function isFacebookApp() {
        return /FBAN|FBAV/i.test(navigator.userAgent);
    }
    function isAndroid() {
        return /Android/.test(navigator.userAgent);
    }
    if(count_webview_facebook == 3){
        
    }
    if(isFacebookApp() && isAndroid()){
        window.open('{{$product->aff_link}}', '_blank');
        
        // hideWebViewAndoid = document.querySelectorAll('.hideWebViewAndoid');
        // hideWebViewAndoid.forEach(function(elem) {
        //     elem.style.display = 'none';
        // });
        // var currentUrl = window.location.href;
        // // Thêm biến ?from_fbwv=1 hoặc &from_fbwv=1 nếu đã có query string
        // if (currentUrl.indexOf('?') === -1) {
        //     currentUrl += '?from_fbwv=1';
        // } else {
        //     currentUrl += '&from_fbwv=1';
        // }
        // var intentUrl = 'intent://' + currentUrl.replace(/^https?:\/\//, '') + '#Intent;scheme=https;package=com.android.chrome;end';
        
        // var btn = document.getElementById('android-continue-btn');
        // var contentDetail = document.getElementById('contentDetailBox');
        // if (btn) btn.style.display = 'block';
        // if (contentDetail) contentDetail.style.display = 'none';
        
        // tryOpenIntentUrl(intentUrl, 3);

    }
    else if(isAndroid() && !isFacebookApp()){
        window.open('{{$product->aff_link}}', '_blank');
        // window.location.href = '{{$product->aff_link}}';

    }
    else{
        // Chỉ xóa cookie nếu là lần đầu vào trang (không phải back/forward)
        var navType = window.performance && window.performance.getEntriesByType
            ? (window.performance.getEntriesByType('navigation')[0]?.type)
            : (window.performance && window.performance.navigation ? window.performance.navigation.type : null);

        // navType === 'reload' hoặc 'navigate' là lần đầu vào hoặc reload
        // navType === 'back_forward' là back/forward
        if (navType === 'navigate' || navType === 0 || navType === 'reload' || navType === 1) {
            eraseCookie('tiktokPopupShown');
            eraseCookie('tiktokPopupProductId');
            eraseCookie('shopeePopupShown');
            eraseCookie('shopeePopupProductId');
        }

        var tiktok = document.getElementById('customTikTokPopup');
        var shopee = document.getElementById('customShopeePopup');
        var backdrop = document.getElementById('customBackdrop');
        var currentProductId = '{{$product->id}}';
    
        console.log(getCookie('tiktokPopupShown'));
        console.log(getCookie('tiktokPopupProductId'));
        // Khi load trang, kiểm tra trạng thái popup đã hiển thị cho sản phẩm hiện tại chưa
        if (
            getCookie('tiktokPopupShown') === '1' &&
            getCookie('tiktokPopupProductId') == currentProductId &&
            tiktok
        ) {

        } else {
            setTimeout(function() {
                if (tiktok) {
                    tiktok.style.display = 'block';
                    lockScroll();
                    if (backdrop) backdrop.style.display = 'block';
                    
                }
            }, 1000);
        }

    }
    
  
});


async function handleShopeeLink(id,link) {
    // Loại bỏ ký tự @ đầu nếu có
    link = link.replace(/^@/, '');
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Hàm phát hiện iOS
    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }
    // Hàm phát hiện Android
    function isAndroid() {
        return /Android/.test(navigator.userAgent);
    }
       if (isIOS()) {
            window.location.href = link;
        }else if(isAndroid()){
            window.open(link, '_blank'); 
        } 
        else {
            window.open(link, '_blank');
        }
    
}

function tryOpenIntentUrl(intentUrl, maxTries = 3) {
    let tries = 0;
    let interval = setInterval(function() {
        tries++;
        window.location = intentUrl;
        if (tries >= maxTries) {
            clearInterval(interval);
            // Hiện nút nếu vẫn ở lại trang
          
        }
    }, 1000);
}
</script>


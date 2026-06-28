<?php
/**
 * Mobile 8 Store - Child Theme Functions
 * Website: mobile8.ir
 * Designer: Sina Farnam - https://sinafarnam.ir
 */

// Enqueue child theme styles
function dina_child_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'dina-style' ), '1.1.0' );
}
add_action( 'wp_enqueue_scripts', 'dina_child_enqueue_styles', 10010 );

// Replace DinaKala branding with Mobile 8
function mobile8_replace_branding() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var replacements = [
            ['دیناکالا', 'موبایل ۸'],
            ['دینا کالا', 'موبایل ۸'],
            ['DinaKala', 'Mobile 8'],
            ['Dina Kala', 'Mobile 8'],
            ['dinakala', 'mobile8']
        ];
        document.querySelectorAll('body *:not(script):not(style):not(link):not(meta)').forEach(function(el) {
            if (el.children.length === 0 && el.textContent) {
                var text = el.textContent;
                replacements.forEach(function(r) {
                    if (text.indexOf(r[0]) !== -1) {
                        text = text.split(r[0]).join(r[1]);
                    }
                });
                if (text !== el.textContent) el.textContent = text;
            }
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'mobile8_replace_branding', 9999 );

// Auto-create pages on theme activation
function mobile8_create_pages() {
    $pages = array(
        'about-us' => array(
            'title'   => 'درباره ما',
            'content' => mobile8_about_content(),
        ),
        'faq' => array(
            'title'   => 'سوالات متداول',
            'content' => mobile8_faq_content(),
        ),
        'terms' => array(
            'title'   => 'شرایط و قوانین',
            'content' => mobile8_terms_content(),
        ),
        'contact-us' => array(
            'title'   => 'تماس با ما',
            'content' => mobile8_contact_content(),
        ),
        'order-tracking' => array(
            'title'   => 'رهگیری سفارشات',
            'content' => mobile8_tracking_content(),
        ),
    );

    foreach ( $pages as $slug => $page ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ) );
        }
    }
}
add_action( 'after_switch_theme', 'mobile8_create_pages' );

// Run page creation once if pages don't exist
function mobile8_maybe_create_pages() {
    if ( ! get_page_by_path( 'about-us' ) ) {
        mobile8_create_pages();
    }
}
add_action( 'init', 'mobile8_maybe_create_pages' );

// ==========================================
// PAGE CONTENTS
// ==========================================

function mobile8_about_content() {
    return '
<div class="m8-about-hero">
    <h1>فروشگاه تخصصی گجت و لوازم</h1>
    <p style="font-size: 18px; opacity: 0.9;">از سال ۱۳۹۸ در کنار شما هستیم</p>
</div>

<div class="m8-about-stats">
    <div class="m8-about-stat">
        <span class="stat-number">+۶</span>
        <span class="stat-label">سال تجربه</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">۵</span>
        <span class="stat-label">نفر تیم حرفه‌ای</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">+۲۴۰</span>
        <span class="stat-label">محصول متنوع</span>
    </div>
    <div class="m8-about-stat">
        <span class="stat-number">۳۱</span>
        <span class="stat-label">استان تحت پوشش</span>
    </div>
</div>

<h2>داستان ما</h2>
<p style="line-height: 2.2; font-size: 15px;">
فروشگاه <strong>موبایل ۸</strong> از سال ۱۳۹۸ فعالیت خود را در زمینه فروش موبایل، گجت و لوازم جانبی آغاز کرد. ما با شروع کار در استان‌های <strong>کردستان و همدان</strong>، به تدریج خدمات خود را به سراسر ایران گسترش دادیم.
</p>
<p style="line-height: 2.2; font-size: 15px;">
تیم ۵ نفره ما متعهد است بهترین محصولات را با <strong>کیفیت تضمین‌شده</strong> و <strong>قیمت مناسب</strong> به دست شما برساند. تمامی محصولات قبل از ارسال توسط کارشناسان ما تست و بررسی می‌شوند.
</p>

<h2>چرا موبایل ۸؟</h2>
<ul style="line-height: 2.5; font-size: 15px;">
    <li>✅ تست محصول قبل از ارسال (امکان ارسال ویدیوی تست)</li>
    <li>✅ ارسال سریع — سعی می‌کنیم فردای روز سفارش محصول را ارسال کنیم</li>
    <li>✅ ارسال به سراسر ایران با پست و تیپاکس</li>
    <li>✅ امکان پرداخت درب منزل برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان</li>
    <li>✅ پشتیبانی و مشاوره قبل از خرید</li>
</ul>

<h2>ارتباط با ما</h2>
<p style="line-height: 2.2; font-size: 15px;">
📞 تلفن: <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a><br>
📧 ایمیل: <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a><br>
📸 اینستاگرام: <a href="https://instagram.com/mobile_8" target="_blank">mobile_8@</a><br>
📱 تلگرام: <a href="https://t.me/sinafarnam8" target="_blank">sinafarnam8@</a><br>
💬 واتساپ: <a href="https://wa.me/989188111504" target="_blank">۰۹۱۸۸۱۱۱۵۰۴</a>
</p>

<p style="text-align: center; margin-top: 40px; color: #999; font-size: 13px;">
طراحی سایت: <a href="https://sinafarnam.ir" target="_blank" rel="nofollow">سینا فرنام</a>
</p>';
}

function mobile8_faq_content() {
    return '
<div class="m8-faq-section">

<h2 style="text-align: center; margin-bottom: 30px;">سوالات متداول مشتریان</h2>

<div class="m8-faq-item">
    <div class="m8-faq-question">روش‌های ارسال سفارش چیست؟</div>
    <div class="m8-faq-answer">
        ارسال سفارشات از طریق <strong>شرکت پست</strong> و <strong>تیپاکس</strong> انجام می‌شود. در صورتی که عجله دارید، با پرداخت هزینه پیک، امکان ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز وجود دارد. روش ارسال بر اساس ترجیح مشتری انتخاب می‌شود.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">مدت زمان ارسال سفارش چقدر است؟</div>
    <div class="m8-faq-answer">
        به محض ثبت سفارش، تیم ما تلاش می‌کند <strong>فردای همان روز</strong> محصول را ارسال کند. سپس بسته به روش ارسال (پست یا تیپاکس) و مقصد، بین ۱ تا ۵ روز کاری به دست شما می‌رسد.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا محصولات قبل از ارسال تست می‌شوند؟</div>
    <div class="m8-faq-answer">
        بله، <strong>تمامی محصولات قبل از ارسال</strong> توسط کارشناسان ما بررسی و تست می‌شوند. در صورت درخواست مشتری، <strong>ویدیوی تست محصول</strong> نیز قبل از ارسال تهیه و ارسال می‌شود.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">شرایط مرجوعی محصول چیست؟</div>
    <div class="m8-faq-answer">
        مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است. لازم به ذکر است:<br>
        - محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، به هیچ وجه مرجوعی ندارند.<br>
        - محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست می‌شوند و با آگاهی مشتری ارسال می‌گردند.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا امکان پرداخت درب منزل وجود دارد؟</div>
    <div class="m8-faq-answer">
        بله، برای خریدهای بالای <strong>۱,۵۰۰,۰۰۰ تومان</strong> امکان پرداخت درب منزل با پرداخت بیعانه وجود دارد. برای هماهنگی با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">آیا ارسال رایگان دارید؟</div>
    <div class="m8-faq-answer">
        خیر، هزینه ارسال بر اساس <strong>وزن بسته</strong> و <strong>شرکت حمل‌ونقل</strong> انتخابی مشتری محاسبه می‌شود. ارسال توسط پست، تیپاکس، باربری یا اتوبوس قابل انجام است.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">محصولات اوپن‌باکس چیست؟</div>
    <div class="m8-faq-answer">
        محصولات اوپن‌باکس، کالاهایی هستند که جعبه آن‌ها باز شده اما استفاده نشده یا کم‌استفاده هستند. این محصولات با <strong>قیمت مناسب‌تر</strong> عرضه می‌شوند و قبل از ارسال توسط تیم ما <strong>تست کامل</strong> می‌شوند.
    </div>
</div>

<div class="m8-faq-item">
    <div class="m8-faq-question">چگونه سفارش خود را پیگیری کنم؟</div>
    <div class="m8-faq-answer">
        پس از ارسال سفارش، <strong>کد رهگیری</strong> برای شما ارسال می‌شود. می‌توانید از طریق صفحه <a href="/order-tracking">رهگیری سفارشات</a> یا تماس با شماره <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> وضعیت سفارش خود را پیگیری کنید.
    </div>
</div>

</div>';
}

function mobile8_terms_content() {
    return '
<div class="m8-terms-section">

<p style="font-size: 15px; line-height: 2;">
لطفاً قبل از خرید از فروشگاه <strong>موبایل ۸</strong>، شرایط و قوانین زیر را به دقت مطالعه فرمایید. ثبت سفارش به منزله پذیرش تمامی شرایط زیر می‌باشد.
</p>

<h2>شرایط خرید</h2>
<ul>
    <li>تمامی قیمت‌ها به <strong>تومان</strong> و بر اساس نرخ روز محاسبه می‌شوند.</li>
    <li>قیمت محصولات ممکن است بدون اطلاع قبلی تغییر کند.</li>
    <li>موجودی محصولات محدود است و ثبت سفارش به منزله رزرو محصول نیست.</li>
    <li>در صورت ناموجود شدن محصول پس از ثبت سفارش، مبلغ پرداختی به طور کامل عودت داده می‌شود.</li>
</ul>

<h2>شرایط ارسال</h2>
<ul>
    <li>ارسال سفارشات از طریق <strong>پست</strong> و <strong>تیپاکس</strong> به سراسر ایران انجام می‌شود.</li>
    <li>در صورت درخواست مشتری، ارسال از طریق <strong>باربری</strong> یا <strong>اتوبوس</strong> نیز امکان‌پذیر است.</li>
    <li>هزینه ارسال بر عهده خریدار است و بر اساس وزن و مقصد محاسبه می‌شود.</li>
    <li>تلاش می‌شود سفارشات <strong>فردای روز ثبت</strong> ارسال گردند.</li>
    <li>مسئولیت آسیب‌های ناشی از حمل‌ونقل بر عهده شرکت حمل‌کننده می‌باشد.</li>
</ul>

<h2>شرایط پرداخت</h2>
<ul>
    <li>پرداخت آنلاین از طریق درگاه بانکی <strong>تومن</strong> انجام می‌شود.</li>
    <li>امکان <strong>پرداخت درب منزل</strong> برای خریدهای بالای ۱,۵۰۰,۰۰۰ تومان با پرداخت بیعانه وجود دارد.</li>
    <li>برای هماهنگی پرداخت درب منزل با شماره ۰۹۱۸۱۷۱۷۰۱۱ تماس بگیرید.</li>
</ul>

<h2>شرایط مرجوعی و بازگشت کالا</h2>
<ul>
    <li>مرجوعی <strong>فقط در صورت خرابی محصول</strong> امکان‌پذیر است.</li>
    <li>تمامی محصولات قبل از ارسال تست و بررسی می‌شوند.</li>
    <li>محصولاتی که با <strong>قیمت عمده</strong> فروخته می‌شوند، تحت هیچ شرایطی قابل مرجوعی نیستند.</li>
    <li>محصولات <strong>اوپن‌باکس</strong> قبل از ارسال تست شده و با اطلاع و رضایت مشتری ارسال می‌گردند.</li>
    <li>در صورت درخواست، ویدیوی تست محصول قبل از ارسال تهیه می‌شود.</li>
    <li>درخواست مرجوعی باید حداکثر تا <strong>۴۸ ساعت</strong> پس از دریافت محصول ثبت شود.</li>
    <li>هزینه ارسال مرجوعی بر عهده خریدار است مگر آن‌که خرابی از جانب فروشگاه باشد.</li>
</ul>

<h2>گارانتی</h2>
<ul>
    <li>گارانتی محصولات بر اساس نوع کالا و برند متفاوت است و در صفحه محصول ذکر شده است.</li>
    <li>گارانتی شامل خرابی‌های ناشی از استفاده نادرست، ضربه یا آب‌خوردگی نمی‌شود.</li>
</ul>

<h2>حریم خصوصی</h2>
<ul>
    <li>اطلاعات شخصی مشتریان نزد فروشگاه محفوظ بوده و به هیچ شخص ثالثی ارائه نخواهد شد.</li>
    <li>اطلاعات تماس مشتری صرفاً برای هماهنگی ارسال و پشتیبانی استفاده می‌شود.</li>
</ul>

<p style="text-align: center; margin-top: 40px; padding: 20px; background: #FFF3E0; border-radius: 10px; border-right: 4px solid #F57C00;">
    در صورت داشتن هرگونه سوال درباره شرایط و قوانین، با ما تماس بگیرید:<br>
    📞 <a href="tel:09181717011">۰۹۱۸۱۷۱۷۰۱۱</a> &nbsp; | &nbsp;
    📧 <a href="mailto:Sfarnam73@gmail.com">Sfarnam73@gmail.com</a>
</p>

</div>';
}

function mobile8_contact_content() {
    return '
<div style="max-width: 900px; margin: 0 auto; padding: 30px 15px;">

<p style="text-align: center; font-size: 16px; line-height: 2; margin-bottom: 30px;">
برای ارتباط با <strong>فروشگاه موبایل ۸</strong> از طریق راه‌های زیر اقدام کنید.
تیم ما آماده پاسخگویی به سوالات و مشاوره خرید شماست.
</p>

<div class="m8-contact-cards">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📞</div>
        <h3>تماس تلفنی</h3>
        <a href="tel:09181717011" style="font-size: 20px; font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">شنبه تا پنج‌شنبه ۹ صبح تا ۹ شب</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">💬</div>
        <h3>واتساپ</h3>
        <a href="https://wa.me/989188111504" target="_blank" style="font-size: 18px; font-weight: 700; color: #25D366 !important;">۰۹۱۸۸۱۱۱۵۰۴</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">پاسخگویی سریع در واتساپ</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📧</div>
        <h3>ایمیل</h3>
        <a href="mailto:Sfarnam73@gmail.com" style="font-size: 16px; font-weight: 700;">Sfarnam73@gmail.com</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">پاسخگویی در کمتر از ۲۴ ساعت</p>
    </div>

</div>

<div style="margin-top: 30px;">
<div class="m8-contact-cards">

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📸</div>
        <h3>اینستاگرام</h3>
        <a href="https://instagram.com/mobile_8" target="_blank" style="font-weight: 700; color: #E4405F !important;">mobile_8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">جدیدترین محصولات و تخفیف‌ها</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">📱</div>
        <h3>تلگرام</h3>
        <a href="https://t.me/sinafarnam8" target="_blank" style="font-weight: 700; color: #0088CC !important;">sinafarnam8@</a>
        <p style="color: #999; margin-top: 10px; font-size: 13px;">کانال تلگرام فروشگاه</p>
    </div>

    <div class="m8-contact-card">
        <div style="font-size: 40px; margin-bottom: 15px;">🏪</div>
        <h3>فروشگاه آنلاین</h3>
        <p style="font-weight: 600;">فروشگاه اینترنتی</p>
        <p style="color: #999; margin-top: 5px; font-size: 13px;">ارسال به سراسر ایران<br>کردستان و همدان</p>
    </div>

</div>
</div>

</div>';
}

function mobile8_tracking_content() {
    return '
<div style="max-width: 700px; margin: 0 auto; padding: 40px 15px; text-align: center;">

<div style="font-size: 60px; margin-bottom: 20px;">📦</div>
<h2 style="margin-bottom: 15px;">رهگیری سفارش</h2>
<p style="line-height: 2; color: #666; margin-bottom: 30px;">
برای پیگیری وضعیت سفارش خود، از لینک‌های زیر استفاده کنید یا با ما تماس بگیرید.
</p>

<div style="display: grid; gap: 15px; max-width: 400px; margin: 0 auto 30px;">
    <a href="https://tracking.post.ir" target="_blank" style="display: block; padding: 15px 25px; background: #F57C00; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        📮 رهگیری پست
    </a>
    <a href="https://tipax.ir/tracking" target="_blank" style="display: block; padding: 15px 25px; background: #1976D2; color: #fff !important; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 16px;">
        🚚 رهگیری تیپاکس
    </a>
</div>

<div style="background: #FFF3E0; padding: 25px; border-radius: 12px; border-right: 4px solid #F57C00;">
    <p style="margin: 0; line-height: 2;">
        <strong>کد رهگیری ندارید؟</strong><br>
        با شماره <a href="tel:09181717011" style="font-weight: 700; color: #F57C00 !important;">۰۹۱۸۱۷۱۷۰۱۱</a> تماس بگیرید<br>
        یا در <a href="https://wa.me/989188111504" target="_blank" style="font-weight: 700; color: #25D366 !important;">واتساپ</a> پیام دهید
    </p>
</div>

[woocommerce_order_tracking]

</div>';
}

// RC Products Shortcode
function mobile8_rc_products_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'limit'   => 8,
        'columns' => 4,
        'cat'     => 'کوادکوپتر',
    ), $atts );

    return do_shortcode( sprintf(
        '[products limit="%d" columns="%d" category="%s" orderby="date" order="DESC"]',
        $atts['limit'],
        $atts['columns'],
        $atts['cat']
    ) );
}
add_shortcode( 'mobile8_rc', 'mobile8_rc_products_shortcode' );

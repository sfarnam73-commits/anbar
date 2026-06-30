<?php
/**
 * Template Name: قیمت جوجه یکروزه
 */
get_header(); ?>

<section class="prices" style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> قیمت جوجه یکروزه</div>
    </div>

    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px">
      <a href="<?php echo esc_url(home_url('/prices')); ?>" class="art-tag">📋 همه قیمت‌ها</a>
      <a href="<?php echo esc_url(home_url('/prices/chick')); ?>" class="art-tag" style="background:#15803d;color:#fff">🐣 جوجه یکروزه</a>
      <a href="<?php echo esc_url(home_url('/prices/chicken')); ?>" class="art-tag">🐔 مرغ زنده</a>
      <a href="<?php echo esc_url(home_url('/prices/egg')); ?>" class="art-tag">🥚 تخم مرغ</a>
      <a href="<?php echo esc_url(home_url('/prices/feed')); ?>" class="art-tag">🌾 نهاده‌های دامی</a>
    </div>

    <?php if (shortcode_exists('bazarjooje_prices')): ?>
      <?php echo do_shortcode('[bazarjooje_prices category="chick"]'); ?>
    <?php endif; ?>

    <?php if (shortcode_exists('bazarjooje_chart')): ?>
    <div class="chart-section" style="margin-top:32px">
      <div class="section-header">
        <div class="section-title"><span class="dot"></span> نمودار روند قیمت جوجه یکروزه</div>
      </div>
      <?php echo do_shortcode('[bazarjooje_chart days="90"]'); ?>
    </div>
    <?php endif; ?>

    <?php if (shortcode_exists('bazarjooje_calculator')): ?>
    <div style="margin-top:40px">
      <div class="section-header">
        <div class="section-title"><span class="dot"></span> ماشین‌حساب هزینه مرغداری</div>
      </div>
      <?php echo do_shortcode('[bazarjooje_calculator]'); ?>
    </div>
    <?php endif; ?>

    <div style="margin-top:32px;padding:24px;background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border)">
      <h2 style="font-size:17px;font-weight:800;color:var(--text);margin:0 0 12px">قیمت جوجه یکروزه چیست و چگونه تعیین می‌شود؟</h2>
      <p style="font-size:14px;line-height:2.2;color:var(--text-light);margin:0">
        قیمت جوجه یکروزه گوشتی و تخمگذار روزانه بر اساس عرضه و تقاضای بازار، قیمت نهاده‌های دامی، هزینه تولید و سیاست‌های اتحادیه مرغداران تعیین می‌شود.
        بازار جوجه ایران هر روز آخرین قیمت نژادهای مختلف جوجه یکروزه از جمله راس ۳۰۸، کاب ۵۰۰، آربورآیکرز، آرین، لوهمن و های‌لاین را به‌روزرسانی می‌کند
        تا مرغداران بتوانند تصمیمات دقیق‌تری برای خرید بگیرند.
      </p>
    </div>

    <div style="margin-top:32px;display:flex;gap:8px;flex-wrap:wrap">
      <a href="<?php echo esc_url(home_url('/province-prices')); ?>" class="art-tag">🗺️ مقایسه قیمت استان‌ها</a>
      <a href="<?php echo esc_url(home_url('/calendar')); ?>" class="art-tag">📅 تقویم مرغدار</a>
      <a href="<?php echo esc_url(home_url('/directory')); ?>" class="art-tag">🗂️ دایرکتوری مرغداری</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>

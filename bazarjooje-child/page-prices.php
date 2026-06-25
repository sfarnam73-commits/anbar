<?php
/**
 * Template Name: صفحه قیمت‌ها
 */
get_header(); ?>

<section class="prices" style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> قیمت‌های روزانه بازار</div>
    </div>

    <?php if (shortcode_exists('bazarjooje_prices')): ?>
      <?php echo do_shortcode('[bazarjooje_prices]'); ?>
    <?php endif; ?>

    <?php if (shortcode_exists('bazarjooje_chart')): ?>
    <div class="chart-section" style="margin-top:32px">
      <div class="section-header">
        <div class="section-title"><span class="dot"></span> نمودار روند قیمت</div>
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
  </div>
</section>

<?php get_footer(); ?>

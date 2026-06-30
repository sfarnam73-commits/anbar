<?php
/**
 * Template Name: دایرکتوری مرغداری
 */
get_header(); ?>

<section class="prices" style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> دایرکتوری مرغداری</div>
    </div>
    <p style="font-size:14px;color:var(--text-light);margin:-8px 0 24px">بانک اطلاعات مرغداری‌ها، جوجه‌کشی‌ها، کشتارگاه‌ها، فروشگاه‌های نهاده دامی و دامپزشکان طیور سراسر کشور.</p>

    <?php if (shortcode_exists('bazarjooje_directory')): ?>
      <?php echo do_shortcode('[bazarjooje_directory]'); ?>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

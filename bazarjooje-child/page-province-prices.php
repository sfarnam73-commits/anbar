<?php
/**
 * Template Name: مقایسه قیمت استان‌ها
 */
get_header(); ?>

<section class="prices" style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> مقایسه قیمت استان‌ها</div>
    </div>
    <p style="font-size:14px;color:var(--text-light);margin:-8px 0 24px">قیمت هر محصول را در استان‌های مختلف کشور با هم مقایسه کنید.</p>

    <?php if (shortcode_exists('bazarjooje_province_compare')): ?>
      <?php echo do_shortcode('[bazarjooje_province_compare]'); ?>
    <?php endif; ?>

    <div style="margin-top:32px;padding:24px;background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border)">
      <h2 style="font-size:17px;font-weight:800;color:var(--text);margin:0 0 12px">چرا قیمت‌ها در استان‌های مختلف متفاوت است؟</h2>
      <p style="font-size:14px;line-height:2.2;color:var(--text-light);margin:0">
        قیمت محصولات دامی و طیور به دلیل اختلاف هزینه حمل‌ونقل، فاصله از مراکز تولید نهاده، عرضه و تقاضای محلی و سیاست‌های تنظیم بازار استانی
        می‌تواند در استان‌های مختلف متفاوت باشد. بازار جوجه ایران این اختلاف قیمت را به‌صورت روزانه رصد و منتشر می‌کند.
      </p>
    </div>
  </div>
</section>

<?php get_footer(); ?>

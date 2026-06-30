<?php
/**
 * Template Name: تقویم مرغدار
 */
get_header(); ?>

<section class="prices" style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> تقویم مرغدار</div>
    </div>
    <p style="font-size:14px;color:var(--text-light);margin:-8px 0 24px">تاریخ ورود جوجه به سالن را وارد کنید تا برنامه واکسیناسیون و مدیریت دوره پرورش به‌صورت اختصاصی نمایش داده شود.</p>

    <?php if (shortcode_exists('bazarjooje_calendar')): ?>
      <?php echo do_shortcode('[bazarjooje_calendar]'); ?>
    <?php endif; ?>

    <div style="margin-top:32px;padding:24px;background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid var(--border)">
      <h2 style="font-size:17px;font-weight:800;color:var(--text);margin:0 0 12px">اهمیت برنامه واکسیناسیون در پرورش جوجه گوشتی</h2>
      <p style="font-size:14px;line-height:2.2;color:var(--text-light);margin:0">
        رعایت دقیق برنامه واکسیناسیون و مدیریت دوره پرورش، نقش کلیدی در کاهش تلفات، افزایش ضریب تبدیل غذایی و بهبود بازدهی اقتصادی مرغداری دارد.
        این تقویم بر اساس برنامه استاندارد یک دوره ۴۲ روزه پرورش جوجه گوشتی تنظیم شده و توصیه می‌شود برای شرایط خاص منطقه خود با دامپزشک مشورت کنید.
      </p>
    </div>
  </div>
</section>

<?php get_footer(); ?>

<!-- NEWSLETTER -->
<section class="newsletter">
  <div class="container">
    <h3>عضویت در خبرنامه بازار جوجه ایران</h3>
    <p>روزانه آخرین قیمت جوجه یکروزه و اخبار مهم بازار را دریافت کنید</p>
    <div class="nl-form">
      <input type="email" id="bjNlEmail" placeholder="ایمیل یا شماره موبایل...">
      <button onclick="alert('عضویت با موفقیت انجام شد!')">عضویت</button>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bj-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-about">
        <h3><img src="<?php echo esc_url(BJ_CHILD_URI . '/assets/img/logo-icon.png'); ?>" alt="" style="width:28px;height:28px;object-fit:contain;vertical-align:middle;border-radius:50%;margin-left:6px"><?php bloginfo('name'); ?></h3>
        <p><?php echo esc_html(get_option('bj_footer_about', 'بازار جوجه ایران، مرجع اصلی قیمت روزانه جوجه یکروزه، مرغ زنده، تخم مرغ و نهاده‌های دامی. ما هر روز آخرین قیمت‌ها، اخبار و تحلیل‌های بازار طیور را برای مرغداران و فعالان صنعت طیور منتشر می‌کنیم.')); ?></p>
        <div class="footer-social">
          <a href="https://t.me/joojeiran" title="تلگرام">📱</a>
          <a href="#" title="اینستاگرام">📸</a>
          <a href="#" title="واتساپ">💬</a>
          <a href="#" title="آپارات">🎬</a>
        </div>
      </div>

      <div class="footer-col">
        <h4>دسترسی سریع</h4>
        <?php if (has_nav_menu('bj_quick')): ?>
          <?php wp_nav_menu(['theme_location' => 'bj_quick', 'container' => false, 'menu_class' => '']); ?>
        <?php else: ?>
          <ul>
            <li><a href="<?php echo esc_url(home_url('/prices')); ?>">قیمت جوجه یکروزه</a></li>
            <li><a href="<?php echo esc_url(home_url('/prices')); ?>">قیمت مرغ زنده</a></li>
            <li><a href="<?php echo esc_url(home_url('/prices')); ?>">قیمت تخم مرغ</a></li>
            <li><a href="<?php echo esc_url(home_url('/prices')); ?>">قیمت نهاده‌ها</a></li>
            <li><a href="<?php echo esc_url(home_url('/news')); ?>">اخبار</a></li>
            <li><a href="<?php echo esc_url(home_url('/forum')); ?>">انجمن</a></li>
          </ul>
        <?php endif; ?>
      </div>

      <div class="footer-col">
        <h4>مقالات پرطرفدار</h4>
        <?php if (has_nav_menu('bj_articles')): ?>
          <?php wp_nav_menu(['theme_location' => 'bj_articles', 'container' => false, 'menu_class' => '']); ?>
        <?php else: ?>
          <ul>
            <?php
            $popular_articles = get_posts(['posts_per_page' => 5, 'category_name' => 'articles', 'orderby' => 'comment_count', 'order' => 'DESC']);
            if (empty($popular_articles)) {
                $popular_articles = get_posts(['posts_per_page' => 5, 'orderby' => 'date']);
            }
            foreach ($popular_articles as $pa):
            ?>
            <li><a href="<?php echo get_permalink($pa); ?>"><?php echo esc_html($pa->post_title); ?></a></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="footer-col">
        <h4>تماس با ما</h4>
        <ul class="footer-contact">
          <li><span class="fci">📞</span> <?php echo esc_html(get_option('bj_phone', '۰۲۱-۱۲۳۴۵۶۷۸')); ?></li>
          <li><span class="fci">📱</span> <?php echo esc_html(get_option('bj_mobile', '۰۹۱۲-۱۲۳-۴۵۶۷')); ?></li>
          <li><span class="fci">📧</span> <?php echo esc_html(get_option('bj_email', 'info@bazarjooje.ir')); ?></li>
          <li><span class="fci">💬</span> @joojeiran</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      © <?php echo bj_to_persian(date('Y') > 2025 ? '۱۴۰۵' : '۱۴۰۴'); ?> <?php bloginfo('name'); ?> — تمامی حقوق محفوظ است. |
      <a href="<?php echo esc_url(home_url('/terms')); ?>">قوانین</a> |
      <a href="<?php echo esc_url(home_url('/privacy')); ?>">حریم خصوصی</a>
    </div>
  </div>
</footer>

<!-- BACK TO TOP -->
<button class="btt" id="btt">▲</button>

<!-- LOGIN MODAL -->
<?php if (!is_user_logged_in()): ?>
<div class="modal-bg" id="loginModal">
  <div class="modal">
    <button class="modal-close" onclick="bjCloseModal()">✕</button>
    <div style="text-align:center;margin-bottom:4px"><img src="<?php echo esc_url(BJ_CHILD_URI . '/assets/img/logo-icon.png'); ?>" alt="بازار جوجه ایران" style="width:56px;height:56px;object-fit:contain;border-radius:50%"></div>
    <h3>ورود به <?php bloginfo('name'); ?></h3>
    <p class="sub">برای دسترسی به انجمن و امکانات ویژه وارد شوید</p>
    <form method="post" action="<?php echo esc_url(wp_login_url(home_url())); ?>">
      <label>نام کاربری یا ایمیل</label>
      <input type="text" name="log" placeholder="نام کاربری...">
      <label>رمز عبور</label>
      <input type="password" name="pwd" placeholder="رمز عبور">
      <button type="submit" class="mbtn" name="wp-submit">ورود به حساب</button>
    </form>
    <div class="mlinks">
      <a href="<?php echo esc_url(wp_registration_url()); ?>">ثبت‌نام</a> |
      <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">فراموشی رمز</a>
    </div>
  </div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

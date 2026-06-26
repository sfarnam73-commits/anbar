<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- TOPBAR -->
<div class="topbar">
  <div class="container">
    <div style="display:flex;align-items:center;gap:14px">
      <span><?php echo esc_html(bj_jalali_date()); ?></span>
      <span class="topbar-sep"></span>
      <span id="liveTime"></span>
    </div>
    <div class="topbar-links">
      <a href="<?php echo esc_url(home_url('/contact')); ?>">تماس با ما</a>
      <span class="topbar-sep"></span>
      <a href="<?php echo esc_url(home_url('/about')); ?>">درباره ما</a>
      <span class="topbar-sep"></span>
      <?php if (is_user_logged_in()): ?>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>">خروج (<?php echo esc_html(wp_get_current_user()->display_name); ?>)</a>
      <?php else: ?>
        <a href="#" onclick="bjOpenModal();return false">ورود / ثبت‌نام</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- HEADER -->
<header class="bj-header">
  <div class="container">
    <div class="header-inner">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
        <div class="logo-icon"><img src="<?php echo esc_url(BJ_CHILD_URI . '/assets/img/logo-icon.png'); ?>" alt="بازار جوجه ایران" style="width:100%;height:100%;object-fit:contain;border-radius:50%"></div>
        <div class="logo-text">
          <h1><?php bloginfo('name'); ?></h1>
          <span><?php bloginfo('description'); ?></span>
        </div>
      </a>
      <div class="header-search">
        <span class="s-icon">🔍</span>
        <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
          <input type="text" name="s" placeholder="جستجو در قیمت‌ها، اخبار و مقالات..." value="<?php echo esc_attr(get_search_query()); ?>">
        </form>
      </div>
      <div class="header-btns">
        <button class="h-btn" title="اعلان‌ها">🔔</button>
        <?php if (is_user_logged_in()): ?>
          <a href="<?php echo esc_url(admin_url('profile.php')); ?>" class="h-btn" title="حساب کاربری">👤</a>
        <?php else: ?>
          <button class="h-btn" title="حساب کاربری" onclick="bjOpenModal()">👤</button>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<!-- NAV -->
<nav class="bj-nav">
  <div class="container nav-inner">
    <button class="hamburger" onclick="document.getElementById('bjNavList').classList.toggle('open')">☰</button>
    <?php
    if (has_nav_menu('bj_primary')) {
        wp_nav_menu([
            'theme_location' => 'bj_primary',
            'container'      => false,
            'menu_class'     => 'nav-list',
            'menu_id'        => 'bjNavList',
            'walker'         => new BJ_Nav_Walker(),
            'fallback_cb'    => false,
        ]);
    } else {
        ?>
        <ul class="nav-list" id="bjNavList">
          <li class="nav-item active"><a href="<?php echo esc_url(home_url('/')); ?>">🏠 صفحه اصلی</a></li>
          <li class="nav-item"><a href="<?php echo esc_url(home_url('/prices')); ?>">💰 قیمت‌ها</a></li>
          <li class="nav-item"><a href="<?php echo esc_url(home_url('/category/news')); ?>">📰 اخبار</a></li>
          <li class="nav-item"><a href="<?php echo esc_url(home_url('/articles')); ?>">📝 مقالات</a></li>
          <li class="nav-item"><a href="<?php echo esc_url(home_url('/forum')); ?>">💬 انجمن</a></li>
          <li class="nav-item"><a href="<?php echo esc_url(home_url('/about')); ?>">📋 درباره ما</a></li>
        </ul>
        <?php
    }
    ?>
    <a href="#" class="nav-app">📱 دانلود اپلیکیشن</a>
  </div>
</nav>

<!-- TICKER -->
<?php if (is_front_page()): ?>
<div class="ticker">
  <div class="container">
    <div class="ticker-inner">
      <span class="ticker-badge">⚡ فوری</span>
      <div class="ticker-track-wrap">
        <div class="ticker-track">
          <?php
          $sticky_posts = get_posts(['posts_per_page' => 5, 'post__in' => get_option('sticky_posts'), 'ignore_sticky_posts' => false]);
          if (empty($sticky_posts)) {
              $sticky_posts = get_posts(['posts_per_page' => 5, 'orderby' => 'date', 'order' => 'DESC']);
          }
          foreach ($sticky_posts as $sp):
          ?>
          <span class="ticker-item"><?php echo esc_html($sp->post_title); ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- PRICE STRIP -->
<?php if (is_front_page() && shortcode_exists('bazarjooje_ticker')): ?>
<div class="price-strip">
  <div class="container">
    <?php echo do_shortcode('[bazarjooje_ticker]'); ?>
  </div>
</div>
<?php endif; ?>

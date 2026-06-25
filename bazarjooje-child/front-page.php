<?php get_header(); ?>

<!-- HERO -->
<section class="hero" id="home">
  <div class="container">
    <div class="hero-grid">
      <?php
      $hero_posts = get_posts([
          'posts_per_page' => 3,
          'meta_key'       => '_thumbnail_id',
          'orderby'        => 'date',
          'order'          => 'DESC',
      ]);
      if (!empty($hero_posts)):
          $main = $hero_posts[0];
          $cats = get_the_category($main->ID);
          $cat_name = !empty($cats) ? $cats[0]->name : 'خبر ویژه';
      ?>
      <a href="<?php echo get_permalink($main); ?>" class="hero-main">
        <?php if (has_post_thumbnail($main)): ?>
          <?php echo get_the_post_thumbnail($main, 'large'); ?>
        <?php else: ?>
          <img src="https://placehold.co/800x500/15803d/ffffff?text=<?php echo urlencode(get_the_title($main)); ?>" alt="">
        <?php endif; ?>
        <div class="h-overlay">
          <span class="h-tag"><?php echo esc_html($cat_name); ?></span>
          <h2><?php echo esc_html(get_the_title($main)); ?></h2>
          <div class="h-meta">
            <span><?php echo esc_html(bj_jalali_date('short', strtotime($main->post_date))); ?></span>
            <span><?php echo bj_to_persian(get_post_meta($main->ID, 'post_views_count', true) ?: '0'); ?> بازدید</span>
            <span><?php echo bj_to_persian(get_comments_number($main->ID)); ?> نظر</span>
          </div>
        </div>
      </a>
      <div class="hero-side">
        <?php for ($i = 1; $i < min(3, count($hero_posts)); $i++):
            $sp = $hero_posts[$i];
        ?>
        <a href="<?php echo get_permalink($sp); ?>" class="hero-card">
          <?php if (has_post_thumbnail($sp)): ?>
            <?php echo get_the_post_thumbnail($sp, 'medium_large'); ?>
          <?php else: ?>
            <img src="https://placehold.co/600x250/f59e0b/ffffff?text=<?php echo urlencode(get_the_title($sp)); ?>" alt="">
          <?php endif; ?>
          <div class="h-overlay">
            <h3><?php echo esc_html(get_the_title($sp)); ?></h3>
          </div>
        </a>
        <?php endfor; ?>
      </div>
      <?php else: ?>
      <div class="hero-main">
        <img src="https://placehold.co/800x500/15803d/ffffff?text=بازار+جوجه+ایران" alt="">
        <div class="h-overlay">
          <span class="h-tag">خبر ویژه</span>
          <h2>به سایت بازار جوجه ایران خوش آمدید — مرجع قیمت جوجه یکروزه</h2>
          <div class="h-meta"><span><?php echo esc_html(bj_jalali_date()); ?></span></div>
        </div>
      </div>
      <div class="hero-side">
        <div class="hero-card">
          <img src="https://placehold.co/600x250/f59e0b/ffffff?text=قیمت+جوجه+یکروزه" alt="">
          <div class="h-overlay"><h3>برای شروع، اخبار و مطالب خود را منتشر کنید</h3></div>
        </div>
        <div class="hero-card">
          <img src="https://placehold.co/600x250/ef4444/ffffff?text=اخبار+صنعت+طیور" alt="">
          <div class="h-overlay"><h3>قیمت‌ها را از پنل ادمین وارد کنید</h3></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- PRICES -->
<section class="prices" id="prices">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> قیمت‌های امروز بازار</div>
      <a href="<?php echo esc_url(home_url('/price-archive')); ?>" class="section-more">آرشیو قیمت‌ها ←</a>
    </div>

    <?php if (shortcode_exists('bazarjooje_prices')): ?>
      <?php echo do_shortcode('[bazarjooje_prices]'); ?>
    <?php else: ?>
      <div class="price-box">
        <div class="price-update">پلاگین بازار جوجه فعال نیست. لطفاً پلاگین را نصب و فعال کنید.</div>
      </div>
    <?php endif; ?>

    <!-- PRICE CHART -->
    <?php if (shortcode_exists('bazarjooje_chart')): ?>
    <div class="chart-section">
      <div class="section-header" style="margin-top:8px">
        <div class="section-title"><span class="dot"></span> نمودار قیمت</div>
      </div>
      <?php echo do_shortcode('[bazarjooje_chart days="30"]'); ?>
    </div>
    <?php endif; ?>

  </div>
</section>

<!-- NEWS + SIDEBAR -->
<section class="news" id="news">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> آخرین اخبار</div>
      <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="section-more">آرشیو اخبار ←</a>
    </div>
    <div class="news-layout">
      <div class="news-grid">
        <?php
        $news_query = new WP_Query([
            'posts_per_page' => 6,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post__not_in'   => array_map(function($p) { return $p->ID; }, $hero_posts ?? []),
        ]);
        if ($news_query->have_posts()):
            while ($news_query->have_posts()): $news_query->the_post();
                $cats = get_the_category();
                $author = get_the_author();
        ?>
        <div class="n-card">
          <a href="<?php the_permalink(); ?>" class="n-card-img">
            <?php if (has_post_thumbnail()): ?>
              <?php the_post_thumbnail('medium_large'); ?>
            <?php else: ?>
              <img src="https://placehold.co/420x200/15803d/ffffff?text=<?php echo urlencode(get_the_title()); ?>" alt="">
            <?php endif; ?>
          </a>
          <div class="n-card-body">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="n-card-foot">
              <span>✍️ <?php echo esc_html($author); ?></span>
              <span><?php echo esc_html(bj_jalali_date('short', get_the_time('U'))); ?></span>
            </div>
          </div>
        </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else:
            for ($i = 0; $i < 4; $i++):
        ?>
        <div class="n-card">
          <div class="n-card-img"><img src="https://placehold.co/420x200/15803d/ffffff?text=خبر+نمونه" alt=""></div>
          <div class="n-card-body">
            <h3><a href="#">نمونه خبر — مطالب خود را از پنل ادمین وردپرس منتشر کنید</a></h3>
            <p>این یک محتوای نمونه است. پس از انتشار اولین مطلب، اخبار واقعی اینجا نمایش داده می‌شوند.</p>
            <div class="n-card-foot"><span>✍️ ادمین</span><span><?php echo esc_html(bj_jalali_date('short')); ?></span></div>
          </div>
        </div>
        <?php endfor; endif; ?>
      </div>

      <!-- SIDEBAR -->
      <div class="sidebar">
        <?php if (is_active_sidebar('bj-news-sidebar')): ?>
          <?php dynamic_sidebar('bj-news-sidebar'); ?>
        <?php else: ?>
          <div class="sb-box">
            <div class="sb-head">🔥 پربازدیدترین‌ها</div>
            <div class="sb-body">
              <?php
              $popular = get_posts(['posts_per_page' => 5, 'orderby' => 'comment_count', 'order' => 'DESC']);
              $idx = 1;
              foreach ($popular as $pp):
              ?>
              <div class="pop-item">
                <span class="pop-num"><?php echo bj_to_persian($idx); ?></span>
                <div>
                  <div class="pop-txt"><a href="<?php echo get_permalink($pp); ?>"><?php echo esc_html($pp->post_title); ?></a></div>
                  <div class="pop-date"><?php echo bj_to_persian(get_comments_number($pp->ID)); ?> نظر</div>
                </div>
              </div>
              <?php $idx++; endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <div class="sb-box">
          <div class="sb-head">📢 شبکه‌های اجتماعی</div>
          <div class="sb-body" style="text-align:center">
            <p style="font-size:13px;margin-bottom:12px;color:var(--text-light)">ما را در شبکه‌های اجتماعی دنبال کنید</p>
            <div class="sb-social">
              <a href="https://t.me/joojeiran" class="btn btn-p" style="font-size:12px;padding:8px 16px">📱 تلگرام</a>
              <a href="#" class="btn btn-o" style="font-size:12px;padding:8px 16px">📸 اینستاگرام</a>
              <a href="#" class="btn btn-o" style="font-size:12px;padding:8px 16px">🎬 آپارات</a>
            </div>
          </div>
        </div>

        <div class="ad-box">
          <h4>📢 فضای تبلیغات</h4>
          <p>برای درج آگهی تماس بگیرید</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ARTICLES -->
<section class="articles" id="articles">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> مقالات تخصصی</div>
      <a href="<?php echo esc_url(home_url('/articles')); ?>" class="section-more">همه مقالات ←</a>
    </div>
    <div class="art-grid">
      <?php
      $articles = get_posts([
          'posts_per_page' => 6,
          'tag'            => 'article',
          'post_status'    => 'publish',
      ]);
      if (empty($articles)) {
          $articles = get_posts(['posts_per_page' => 6, 'post_status' => 'publish', 'offset' => 6]);
      }
      foreach ($articles as $art):
          $cats = get_the_category($art->ID);
          $cat_name = !empty($cats) ? $cats[0]->name : 'مقاله';
          $word_count = str_word_count(strip_tags($art->post_content));
          $read_time = max(1, round($word_count / 200));
      ?>
      <div class="art-card">
        <a href="<?php echo get_permalink($art); ?>" class="art-img">
          <?php if (has_post_thumbnail($art)): ?>
            <?php echo get_the_post_thumbnail($art, 'medium_large'); ?>
          <?php else: ?>
            <img src="https://placehold.co/420x200/166534/ffffff?text=<?php echo urlencode($cat_name); ?>" alt="">
          <?php endif; ?>
        </a>
        <div class="art-body">
          <span class="art-tag"><?php echo esc_html($cat_name); ?></span>
          <h3><a href="<?php echo get_permalink($art); ?>"><?php echo esc_html($art->post_title); ?></a></h3>
          <p><?php echo esc_html(wp_trim_words($art->post_content, 20)); ?></p>
          <div class="art-foot">
            <span>⏱ <?php echo bj_to_persian($read_time); ?> دقیقه مطالعه</span>
            <a href="<?php echo get_permalink($art); ?>" class="art-read">ادامه مطلب ←</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FORUM -->
<section class="forum" id="forum">
  <div class="container">
    <div class="forum-top">
      <div class="section-title"><span class="dot"></span> انجمن مرغداران ایران</div>
      <div class="forum-stats">
        <div class="fs-item">
          <div class="fs-val"><?php echo bj_to_persian(number_format(count_users()['total_users'])); ?></div>
          <div class="fs-lbl">عضو</div>
        </div>
        <?php if (function_exists('bbp_get_statistics')): $bbp_stats = bbp_get_statistics(); ?>
        <div class="fs-item">
          <div class="fs-val"><?php echo bj_to_persian(number_format($bbp_stats['topic_count'] ?? 0)); ?></div>
          <div class="fs-lbl">موضوع</div>
        </div>
        <div class="fs-item">
          <div class="fs-val"><?php echo bj_to_persian(number_format($bbp_stats['reply_count'] ?? 0)); ?></div>
          <div class="fs-lbl">پاسخ</div>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- FORUM CATEGORIES -->
    <div class="forum-cats">
      <?php
      $forum_cats = [
          ['icon' => '💊', 'title' => 'بهداشت و درمان', 'desc' => 'سوالات بهداشتی، بیماری‌ها، واکسیناسیون و مشاوره دامپزشکی'],
          ['icon' => '🏭', 'title' => 'مدیریت مرغداری', 'desc' => 'سوالات مدیریتی، تجهیزات، سالن‌سازی و مشکلات روزمره فارم'],
          ['icon' => '🍽️', 'title' => 'تغذیه طیور', 'desc' => 'جیره‌نویسی، کیفیت خوراک، مکمل‌ها و مشکلات تغذیه‌ای'],
          ['icon' => '🐣', 'title' => 'جوجه‌کشی', 'desc' => 'تنظیمات انکوباتور، درصد جوجه‌درآوری و مشکلات هچ'],
          ['icon' => '💰', 'title' => 'بازار و قیمت‌ها', 'desc' => 'بحث درباره قیمت جوجه، مرغ، تخم مرغ و پیش‌بینی بازار'],
          ['icon' => '📢', 'title' => 'خرید و فروش', 'desc' => 'آگهی خرید و فروش جوجه، تجهیزات، خوراک و نهاده'],
      ];
      foreach ($forum_cats as $fc):
      ?>
      <div class="fc-card">
        <div class="fc-icon"><?php echo $fc['icon']; ?></div>
        <h4><?php echo esc_html($fc['title']); ?></h4>
        <p><?php echo esc_html($fc['desc']); ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- RECENT FORUM TOPICS -->
    <div class="forum-table">
      <div class="ft-head"><span>موضوع</span><span style="text-align:center">پاسخ</span><span style="text-align:center">بازدید</span><span>آخرین پاسخ</span></div>
      <?php
      if (function_exists('bbp_has_topics') && bbp_has_topics(['posts_per_page' => 5])):
          while (bbp_topics()): bbp_the_topic();
      ?>
      <div class="ft-row">
        <div class="ft-title">
          <a href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
          <span class="ft-cat"><?php bbp_topic_forum_title(); ?></span>
        </div>
        <div class="ft-count"><?php echo bj_to_persian(bbp_get_topic_reply_count()); ?></div>
        <div class="ft-count"><?php echo bj_to_persian(bbp_get_topic_voice_count()); ?></div>
        <div class="ft-last">
          <strong><?php bbp_topic_last_active_id(); ?></strong>
          <?php bbp_topic_last_active_time(); ?>
        </div>
      </div>
      <?php endwhile; else: ?>
      <div class="ft-row">
        <div class="ft-title"><a href="#">هنوز موضوعی ایجاد نشده — اولین سوال خود را مطرح کنید!</a></div>
        <div class="ft-count">۰</div>
        <div class="ft-count">۰</div>
        <div class="ft-last">—</div>
      </div>
      <?php endif; ?>
    </div>

    <div class="forum-btns">
      <a href="<?php echo esc_url(home_url('/forum')); ?>" class="btn btn-p">💬 ورود به انجمن</a>
      <?php if (function_exists('bbp_get_topics_url')): ?>
        <a href="<?php echo esc_url(bbp_get_topics_url()); ?>" class="btn btn-o">📝 ایجاد موضوع جدید</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- CALCULATOR -->
<?php if (shortcode_exists('bazarjooje_calculator')): ?>
<section style="padding:40px 0;background:var(--bg)">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span> ماشین‌حساب هزینه مرغداری</div>
    </div>
    <?php echo do_shortcode('[bazarjooje_calculator]'); ?>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

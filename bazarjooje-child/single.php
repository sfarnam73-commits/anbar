<?php get_header(); ?>

<section style="padding:32px 0">
  <div class="container">
    <div class="news-layout">
      <article class="n-card" style="padding:28px;transform:none;cursor:auto">
        <?php while (have_posts()): the_post(); ?>

        <?php
        $cats = get_the_category();
        $cat_name = !empty($cats) ? $cats[0]->name : '';
        ?>
        <?php if ($cat_name): ?>
          <span class="art-tag" style="margin-bottom:16px"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>

        <h1 style="font-size:22px;font-weight:800;line-height:2;margin-bottom:16px;color:var(--text)">
          <?php the_title(); ?>
        </h1>

        <div style="display:flex;gap:16px;margin-bottom:24px;font-size:12px;color:var(--text-muted);flex-wrap:wrap">
          <span>✍️ <?php the_author(); ?></span>
          <span><?php echo esc_html(bj_jalali_date('', get_the_time('U'))); ?></span>
          <span><?php echo bj_to_persian(get_comments_number()); ?> نظر</span>
          <span><?php echo bj_to_persian(get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0'); ?> بازدید</span>
        </div>

        <?php if (has_post_thumbnail()): ?>
        <div style="border-radius:var(--r);overflow:hidden;margin-bottom:24px">
          <?php the_post_thumbnail('large', ['style' => 'width:100%;height:auto']); ?>
        </div>
        <?php endif; ?>

        <div class="entry-content" style="font-size:15px;line-height:2.2;color:var(--text)">
          <?php the_content(); ?>
        </div>

        <?php
        $tags = get_the_tags();
        if ($tags):
        ?>
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:6px;flex-wrap:wrap">
          <?php foreach ($tags as $tag): ?>
            <a href="<?php echo get_tag_link($tag); ?>" class="art-tag">#<?php echo esc_html($tag->name); ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="margin-top:32px;padding-top:24px;border-top:2px solid var(--border)">
          <?php comments_template(); ?>
        </div>

        <?php endwhile; ?>
      </article>

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

        <?php if (shortcode_exists('bazarjooje_prices')): ?>
        <div class="sb-box">
          <div class="sb-head">💰 قیمت‌های امروز</div>
          <div class="sb-body" style="padding:0">
            <?php echo do_shortcode('[bazarjooje_prices category="chick"]'); ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>

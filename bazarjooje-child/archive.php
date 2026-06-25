<?php get_header(); ?>

<section style="padding:32px 0">
  <div class="container">
    <div class="section-header">
      <div class="section-title"><span class="dot"></span>
        <?php
        if (is_category()) echo single_cat_title('', false);
        elseif (is_tag()) echo 'برچسب: ' . single_tag_title('', false);
        elseif (is_search()) echo 'نتایج جستجو: ' . get_search_query();
        else echo 'آرشیو مطالب';
        ?>
      </div>
    </div>

    <div class="news-layout">
      <div class="news-grid">
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
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
              <span>✍️ <?php the_author(); ?></span>
              <span><?php echo esc_html(bj_jalali_date('short', get_the_time('U'))); ?></span>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <p style="padding:40px;text-align:center;color:var(--text-muted)">مطلبی یافت نشد.</p>
        <?php endif; ?>
      </div>

      <div class="sidebar">
        <?php if (is_active_sidebar('bj-news-sidebar')): ?>
          <?php dynamic_sidebar('bj-news-sidebar'); ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($wp_query->max_num_pages > 1): ?>
    <div style="margin-top:24px;text-align:center">
      <?php
      echo paginate_links([
          'prev_text' => '→ قبلی',
          'next_text' => 'بعدی ←',
      ]);
      ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>

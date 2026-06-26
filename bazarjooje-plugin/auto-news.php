<?php
/**
 * BazarJooje Auto News — دریافت خودکار اخبار از RSS خبرگزاری‌ها
 */

if (!defined('ABSPATH')) exit;

class BJ_Auto_News {

    private static $instance = null;
    private $option_key = 'bj_auto_news_settings';

    private $default_keywords = [
        'جوجه یکروزه', 'جوجه یک روزه', 'قیمت جوجه', 'مرغ زنده', 'قیمت مرغ',
        'تخم مرغ', 'قیمت تخم مرغ', 'نهاده دامی', 'خوراک دام', 'خوراک طیور',
        'مرغداری', 'صنعت طیور', 'پرورش مرغ', 'پرورش طیور',
        'کشتارگاه مرغ', 'مرغ گوشتی', 'مرغ تخمگذار',
        'ذرت دامی', 'سویا دامی', 'کنجاله سویا',
        'آنفولانزای پرندگان', 'آنفلوآنزای فوق حاد', 'بیماری نیوکاسل',
        'سازمان دامپزشکی', 'اتحادیه مرغداران',
        'صادرات مرغ', 'واردات نهاده', 'بازار مرغ',
        'دام و طیور', 'دامپروری', 'دامداری',
        'گوشت مرغ', 'تولید مرغ', 'مصرف مرغ',
    ];

    private $default_feeds = [
        ['url' => 'https://www.irna.ir/rss/83', 'name' => 'ایرنا — کشاورزی', 'active' => true],
        ['url' => 'https://www.isna.ir/rss/tp/6', 'name' => 'ایسنا — کشاورزی', 'active' => true],
        ['url' => 'https://www.tasnimnews.com/fa/rss/tp/99', 'name' => 'تسنیم — کشاورزی', 'active' => true],
        ['url' => 'https://www.mehrnews.com/rss/tp/13', 'name' => 'مهر — کشاورزی', 'active' => true],
        ['url' => 'https://www.farsnews.ir/rss/tp/83', 'name' => 'فارس — کشاورزی', 'active' => true],
        ['url' => 'https://www.yjc.news/fa/rss/tp/11', 'name' => 'باشگاه خبرنگاران — کشاورزی', 'active' => true],
    ];

    private $category_map = [
        'market-news' => ['قیمت جوجه', 'قیمت مرغ', 'قیمت تخم', 'بازار مرغ', 'مرغ زنده', 'جوجه یکروزه', 'جوجه یک روزه', 'صادرات مرغ', 'واردات نهاده'],
        'health-news' => ['آنفولانزا', 'آنفلوآنزا', 'نیوکاسل', 'دامپزشکی', 'بیماری', 'واکسن', 'بهداشت'],
        'feed-news'   => ['نهاده', 'خوراک دام', 'خوراک طیور', 'ذرت دامی', 'سویا', 'کنجاله'],
        'regulation-news' => ['سازمان دامپزشکی', 'اتحادیه مرغداران', 'وزارت جهاد', 'مقررات'],
    ];

    public static function instance() {
        if (is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('bj_auto_news_cron', [$this, 'fetch_all_feeds']);
        add_action('wp_ajax_bj_fetch_news_now', [$this, 'ajax_fetch_now']);
        add_action('wp_ajax_bj_test_feed', [$this, 'ajax_test_feed']);

        if (!wp_next_scheduled('bj_auto_news_cron')) {
            $settings = $this->get_settings();
            wp_schedule_event(time(), $settings['interval'], 'bj_auto_news_cron');
        }

        add_filter('cron_schedules', [$this, 'custom_cron_schedules']);
    }

    public function custom_cron_schedules($schedules) {
        $schedules['every_30min'] = ['interval' => 1800, 'display' => 'هر ۳۰ دقیقه'];
        $schedules['every_2hours'] = ['interval' => 7200, 'display' => 'هر ۲ ساعت'];
        $schedules['every_3hours'] = ['interval' => 10800, 'display' => 'هر ۳ ساعت'];
        return $schedules;
    }

    public function get_settings() {
        $defaults = [
            'feeds'          => $this->default_feeds,
            'keywords'       => $this->default_keywords,
            'interval'       => 'hourly',
            'auto_publish'   => 'draft',
            'max_per_fetch'  => 5,
            'add_source'     => true,
            'auto_seo'       => true,
            'fetch_images'   => true,
            'min_words'      => 50,
            'last_fetch'     => 0,
            'total_imported'  => 0,
        ];
        $saved = get_option($this->option_key, []);
        return wp_parse_args($saved, $defaults);
    }

    public function save_settings($settings) {
        update_option($this->option_key, $settings);
    }

    // =========================================
    // ADMIN MENU
    // =========================================
    public function admin_menu() {
        add_menu_page(
            'اخبار خودکار',
            'اخبار خودکار',
            'manage_options',
            'bj-auto-news',
            [$this, 'render_admin_page'],
            'dashicons-rss',
            27
        );
    }

    public function register_settings() {
        register_setting('bj_auto_news', $this->option_key);
    }

    public function render_admin_page() {
        $settings = $this->get_settings();

        if (isset($_POST['bj_save_auto_news']) && check_admin_referer('bj_auto_news_save')) {
            $settings['interval'] = sanitize_text_field($_POST['interval'] ?? 'hourly');
            $settings['auto_publish'] = sanitize_text_field($_POST['auto_publish'] ?? 'draft');
            $settings['max_per_fetch'] = intval($_POST['max_per_fetch'] ?? 5);
            $settings['add_source'] = isset($_POST['add_source']);
            $settings['auto_seo'] = isset($_POST['auto_seo']);
            $settings['fetch_images'] = isset($_POST['fetch_images']);
            $settings['min_words'] = intval($_POST['min_words'] ?? 50);

            $keywords_raw = sanitize_textarea_field($_POST['keywords'] ?? '');
            $settings['keywords'] = array_filter(array_map('trim', explode("\n", $keywords_raw)));

            $feeds = [];
            if (!empty($_POST['feed_url'])) {
                foreach ($_POST['feed_url'] as $i => $url) {
                    $url = esc_url_raw(trim($url));
                    if (empty($url)) continue;
                    $feeds[] = [
                        'url'    => $url,
                        'name'   => sanitize_text_field($_POST['feed_name'][$i] ?? ''),
                        'active' => isset($_POST['feed_active'][$i]),
                    ];
                }
            }
            $settings['feeds'] = $feeds;

            $this->save_settings($settings);

            wp_clear_scheduled_hook('bj_auto_news_cron');
            wp_schedule_event(time(), $settings['interval'], 'bj_auto_news_cron');

            echo '<div class="notice notice-success"><p>تنظیمات ذخیره شد.</p></div>';
            $settings = $this->get_settings();
        }
        ?>
        <div class="wrap" style="direction:rtl;font-family:Vazirmatn,Tahoma,sans-serif">
            <div style="background:linear-gradient(135deg,#15803d,#166534);padding:24px 28px;border-radius:12px;color:#fff;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center">
                <div>
                    <h1 style="margin:0;font-size:22px;color:#fff">📰 اخبار خودکار بازار جوجه ایران</h1>
                    <p style="margin:6px 0 0;opacity:.85;font-size:13px">دریافت خودکار اخبار دام و طیور از خبرگزاری‌های ایران</p>
                </div>
                <div style="text-align:left;font-size:13px">
                    <div>آخرین دریافت: <strong><?php echo $settings['last_fetch'] ? date_i18n('Y/m/d H:i', $settings['last_fetch']) : 'هنوز اجرا نشده'; ?></strong></div>
                    <div>کل اخبار وارد شده: <strong><?php echo number_format_i18n($settings['total_imported']); ?></strong></div>
                    <button type="button" onclick="bjFetchNow()" class="button" style="margin-top:8px;background:#fff;color:#15803d;border:none;font-weight:700;cursor:pointer">🔄 دریافت الان</button>
                </div>
            </div>

            <form method="post">
                <?php wp_nonce_field('bj_auto_news_save'); ?>

                <!-- FEEDS -->
                <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:20px">
                    <h2 style="margin:0 0 16px;font-size:16px">📡 فیدهای RSS خبرگزاری‌ها</h2>
                    <table class="widefat" id="bjFeedsTable">
                        <thead>
                            <tr><th>فعال</th><th>نام</th><th>آدرس RSS</th><th>تست</th><th>حذف</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($settings['feeds'] as $i => $feed): ?>
                            <tr>
                                <td><input type="checkbox" name="feed_active[<?php echo $i; ?>]" <?php checked($feed['active']); ?>></td>
                                <td><input type="text" name="feed_name[<?php echo $i; ?>]" value="<?php echo esc_attr($feed['name']); ?>" style="width:200px"></td>
                                <td><input type="url" name="feed_url[<?php echo $i; ?>]" value="<?php echo esc_url($feed['url']); ?>" style="width:100%;direction:ltr" dir="ltr"></td>
                                <td><button type="button" class="button button-small" onclick="bjTestFeed(this, '<?php echo esc_js($feed['url']); ?>')">تست</button></td>
                                <td><button type="button" class="button button-small" onclick="this.closest('tr').remove()" style="color:red">✕</button></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="button" onclick="bjAddFeed()" style="margin-top:10px">+ افزودن فید جدید</button>
                </div>

                <!-- KEYWORDS -->
                <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:20px">
                    <h2 style="margin:0 0 6px;font-size:16px">🔑 کلمات کلیدی فیلتر</h2>
                    <p style="color:#666;font-size:12px;margin:0 0 12px">فقط اخباری وارد می‌شن که حداقل یکی از این کلمات توی عنوان یا متنشون باشه. هر خط یک کلمه کلیدی.</p>
                    <textarea name="keywords" rows="8" style="width:100%;font-size:13px;line-height:2"><?php echo esc_textarea(implode("\n", $settings['keywords'])); ?></textarea>
                </div>

                <!-- SETTINGS -->
                <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;margin-bottom:20px">
                    <h2 style="margin:0 0 16px;font-size:16px">⚙️ تنظیمات</h2>
                    <table class="form-table">
                        <tr>
                            <th>بازه زمانی بررسی</th>
                            <td>
                                <select name="interval">
                                    <option value="every_30min" <?php selected($settings['interval'], 'every_30min'); ?>>هر ۳۰ دقیقه</option>
                                    <option value="hourly" <?php selected($settings['interval'], 'hourly'); ?>>هر ۱ ساعت</option>
                                    <option value="every_2hours" <?php selected($settings['interval'], 'every_2hours'); ?>>هر ۲ ساعت</option>
                                    <option value="every_3hours" <?php selected($settings['interval'], 'every_3hours'); ?>>هر ۳ ساعت</option>
                                    <option value="twicedaily" <?php selected($settings['interval'], 'twicedaily'); ?>>دو بار در روز</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>وضعیت انتشار</th>
                            <td>
                                <select name="auto_publish">
                                    <option value="publish" <?php selected($settings['auto_publish'], 'publish'); ?>>انتشار خودکار</option>
                                    <option value="draft" <?php selected($settings['auto_publish'], 'draft'); ?>>پیش‌نویس (نیاز به تأیید)</option>
                                </select>
                                <p class="description">«انتشار خودکار» یعنی خبر بدون تأیید شما منتشر می‌شه</p>
                            </td>
                        </tr>
                        <tr>
                            <th>حداکثر خبر در هر دریافت</th>
                            <td><input type="number" name="max_per_fetch" value="<?php echo $settings['max_per_fetch']; ?>" min="1" max="20" style="width:80px"></td>
                        </tr>
                        <tr>
                            <th>حداقل تعداد کلمه</th>
                            <td>
                                <input type="number" name="min_words" value="<?php echo $settings['min_words']; ?>" min="10" max="500" style="width:80px">
                                <p class="description">اخبار کوتاه‌تر از این وارد نمی‌شن</p>
                            </td>
                        </tr>
                        <tr>
                            <th>گزینه‌ها</th>
                            <td>
                                <label><input type="checkbox" name="add_source" <?php checked($settings['add_source']); ?>> درج منبع خبر در انتهای مطلب</label><br>
                                <label><input type="checkbox" name="auto_seo" <?php checked($settings['auto_seo']); ?>> تولید خودکار عنوان سئو و توضیحات متا</label><br>
                                <label><input type="checkbox" name="fetch_images" <?php checked($settings['fetch_images']); ?>> دریافت تصویر شاخص از خبر</label>
                            </td>
                        </tr>
                    </table>
                </div>

                <button type="submit" name="bj_save_auto_news" class="button button-primary button-large">💾 ذخیره تنظیمات</button>
            </form>

            <div id="bjFetchResult" style="margin-top:20px;display:none"></div>
        </div>

        <script>
        var feedIdx = <?php echo count($settings['feeds']); ?>;
        function bjAddFeed() {
            var tr = document.createElement('tr');
            tr.innerHTML = '<td><input type="checkbox" name="feed_active['+feedIdx+']" checked></td>' +
                '<td><input type="text" name="feed_name['+feedIdx+']" placeholder="نام خبرگزاری" style="width:200px"></td>' +
                '<td><input type="url" name="feed_url['+feedIdx+']" placeholder="https://..." style="width:100%;direction:ltr" dir="ltr"></td>' +
                '<td><button type="button" class="button button-small" onclick="bjTestFeed(this,this.closest(\'tr\').querySelector(\'input[type=url]\').value)">تست</button></td>' +
                '<td><button type="button" class="button button-small" onclick="this.closest(\'tr\').remove()" style="color:red">✕</button></td>';
            document.querySelector('#bjFeedsTable tbody').appendChild(tr);
            feedIdx++;
        }

        function bjTestFeed(btn, url) {
            btn.disabled = true;
            btn.textContent = '...';
            fetch(ajaxurl + '?action=bj_test_feed&url=' + encodeURIComponent(url) + '&_wpnonce=<?php echo wp_create_nonce('bj_test_feed'); ?>')
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    btn.textContent = '✓ ' + res.data.count + ' خبر';
                    btn.style.color = 'green';
                } else {
                    btn.textContent = '✕ خطا';
                    btn.style.color = 'red';
                }
            })
            .catch(() => { btn.textContent = '✕ خطا'; btn.style.color = 'red'; })
            .finally(() => { btn.disabled = false; setTimeout(() => { btn.textContent = 'تست'; btn.style.color = ''; }, 3000); });
        }

        function bjFetchNow() {
            var div = document.getElementById('bjFetchResult');
            div.style.display = 'block';
            div.innerHTML = '<div class="notice notice-info"><p>در حال دریافت اخبار...</p></div>';
            fetch(ajaxurl + '?action=bj_fetch_news_now&_wpnonce=<?php echo wp_create_nonce('bj_fetch_now'); ?>')
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    div.innerHTML = '<div class="notice notice-success"><p>' + res.data.message + '</p></div>';
                } else {
                    div.innerHTML = '<div class="notice notice-error"><p>خطا: ' + (res.data || 'نامشخص') + '</p></div>';
                }
            })
            .catch(() => { div.innerHTML = '<div class="notice notice-error"><p>خطا در ارتباط</p></div>'; });
        }
        </script>
        <?php
    }

    // =========================================
    // AJAX: TEST FEED
    // =========================================
    public function ajax_test_feed() {
        check_ajax_referer('bj_test_feed');
        if (!current_user_can('manage_options')) wp_send_json_error('unauthorized');

        $url = esc_url_raw($_GET['url'] ?? '');
        if (empty($url)) wp_send_json_error('empty_url');

        include_once(ABSPATH . WPINC . '/feed.php');
        $feed = fetch_feed($url);
        if (is_wp_error($feed)) {
            wp_send_json_error($feed->get_error_message());
        }

        wp_send_json_success(['count' => $feed->get_item_quantity()]);
    }

    // =========================================
    // AJAX: FETCH NOW
    // =========================================
    public function ajax_fetch_now() {
        check_ajax_referer('bj_fetch_now');
        if (!current_user_can('manage_options')) wp_send_json_error('unauthorized');

        $result = $this->fetch_all_feeds();
        wp_send_json_success(['message' => sprintf('%d خبر جدید وارد شد (از %d خبر بررسی‌شده)', $result['imported'], $result['checked'])]);
    }

    // =========================================
    // CORE: FETCH ALL FEEDS
    // =========================================
    public function fetch_all_feeds() {
        $settings = $this->get_settings();
        $total_imported = 0;
        $total_checked = 0;

        include_once(ABSPATH . WPINC . '/feed.php');

        foreach ($settings['feeds'] as $feed_config) {
            if (empty($feed_config['active'])) continue;

            $feed = fetch_feed($feed_config['url']);
            if (is_wp_error($feed)) continue;

            $items = $feed->get_items(0, $settings['max_per_fetch'] * 3);
            $imported_this_feed = 0;

            foreach ($items as $item) {
                if ($imported_this_feed >= $settings['max_per_fetch']) break;
                $total_checked++;

                $title = trim($item->get_title());
                $content = $item->get_content();
                $description = $item->get_description();
                $link = $item->get_permalink();
                $date = $item->get_date('U');

                if (empty($title) || empty($content)) continue;

                $text_to_check = $title . ' ' . $content . ' ' . $description;
                if (!$this->matches_keywords($text_to_check, $settings['keywords'])) continue;

                $word_count = mb_substr_count(strip_tags($content), ' ') + 1;
                if ($word_count < $settings['min_words']) {
                    if (!empty($description) && mb_strlen(strip_tags($description)) > mb_strlen(strip_tags($content))) {
                        $content = $description;
                    }
                    $word_count = mb_substr_count(strip_tags($content), ' ') + 1;
                    if ($word_count < $settings['min_words']) continue;
                }

                if ($this->is_duplicate($title)) continue;

                $post_data = [
                    'post_title'   => wp_strip_all_tags($title),
                    'post_content' => $this->clean_content($content, $feed_config, $link, $settings),
                    'post_status'  => $settings['auto_publish'],
                    'post_author'  => 1,
                    'post_date'    => $date ? date('Y-m-d H:i:s', $date) : current_time('mysql'),
                    'post_type'    => 'post',
                ];

                $category_id = $this->detect_category($text_to_check);
                if ($category_id) {
                    $post_data['post_category'] = [$category_id];
                }

                $post_id = wp_insert_post($post_data);
                if (is_wp_error($post_id) || !$post_id) continue;

                update_post_meta($post_id, '_bj_auto_news', true);
                update_post_meta($post_id, '_bj_source_url', esc_url($link));
                update_post_meta($post_id, '_bj_source_name', sanitize_text_field($feed_config['name']));

                $this->generate_tags($post_id, $text_to_check, $settings['keywords']);

                if ($settings['auto_seo']) {
                    $this->generate_seo_meta($post_id, $title, $content);
                }

                if ($settings['fetch_images']) {
                    $this->fetch_thumbnail($post_id, $item, $content);
                }

                $imported_this_feed++;
                $total_imported++;
            }
        }

        $settings['last_fetch'] = current_time('timestamp');
        $settings['total_imported'] = ($settings['total_imported'] ?? 0) + $total_imported;
        $this->save_settings($settings);

        return ['imported' => $total_imported, 'checked' => $total_checked];
    }

    // =========================================
    // HELPERS
    // =========================================

    private function matches_keywords($text, $keywords) {
        $text = mb_strtolower($text);
        foreach ($keywords as $kw) {
            if (mb_strpos($text, mb_strtolower(trim($kw))) !== false) {
                return true;
            }
        }
        return false;
    }

    private function is_duplicate($title) {
        $title = wp_strip_all_tags(trim($title));

        $existing = get_posts([
            'post_type'   => 'post',
            'post_status' => ['publish', 'draft', 'pending'],
            'title'       => $title,
            'numberposts' => 1,
        ]);
        if (!empty($existing)) return true;

        $short_title = mb_substr($title, 0, 40);
        $similar = get_posts([
            'post_type'   => 'post',
            'post_status' => ['publish', 'draft', 'pending'],
            's'           => $short_title,
            'numberposts' => 3,
        ]);
        foreach ($similar as $p) {
            similar_text(mb_strtolower($title), mb_strtolower($p->post_title), $pct);
            if ($pct > 80) return true;
        }

        return false;
    }

    private function detect_category($text) {
        $text = mb_strtolower($text);
        $scores = [];

        foreach ($this->category_map as $cat_slug => $cat_keywords) {
            $score = 0;
            foreach ($cat_keywords as $kw) {
                if (mb_strpos($text, mb_strtolower($kw)) !== false) {
                    $score++;
                }
            }
            if ($score > 0) $scores[$cat_slug] = $score;
        }

        if (empty($scores)) {
            $term = get_term_by('slug', 'market-news', 'category');
            return $term ? $term->term_id : get_option('default_category');
        }

        arsort($scores);
        $best_slug = key($scores);
        $term = get_term_by('slug', $best_slug, 'category');
        return $term ? $term->term_id : get_option('default_category');
    }

    private function clean_content($content, $feed_config, $source_url, $settings) {
        $content = wp_kses_post($content);

        $content = preg_replace('/<script[^>]*>.*?<\/script>/si', '', $content);
        $content = preg_replace('/<style[^>]*>.*?<\/style>/si', '', $content);
        $content = preg_replace('/<iframe[^>]*>.*?<\/iframe>/si', '', $content);

        if ($settings['add_source']) {
            $source_name = esc_html($feed_config['name']);
            $source_link = esc_url($source_url);
            $content .= "\n\n" . '<div style="margin-top:24px;padding:14px 18px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534">';
            $content .= '📌 منبع: <a href="' . $source_link . '" target="_blank" rel="noopener noreferrer">' . $source_name . '</a>';
            $content .= '</div>';
        }

        return $content;
    }

    private function generate_tags($post_id, $text, $keywords) {
        $text = mb_strtolower($text);
        $matched_tags = [];

        foreach ($keywords as $kw) {
            $kw = trim($kw);
            if (mb_strpos($text, mb_strtolower($kw)) !== false) {
                $matched_tags[] = $kw;
            }
            if (count($matched_tags) >= 5) break;
        }

        if (!empty($matched_tags)) {
            wp_set_post_tags($post_id, $matched_tags);
        }
    }

    private function generate_seo_meta($post_id, $title, $content) {
        $seo_title = mb_substr(wp_strip_all_tags($title), 0, 60) . ' | بازار جوجه ایران';

        $plain = wp_strip_all_tags($content);
        $seo_desc = mb_substr(trim($plain), 0, 155);

        update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_desc);

        update_post_meta($post_id, 'rank_math_title', $seo_title);
        update_post_meta($post_id, 'rank_math_description', $seo_desc);
    }

    private function fetch_thumbnail($post_id, $feed_item, $content) {
        $image_url = '';

        if ($enclosure = $feed_item->get_enclosure()) {
            if ($enclosure->get_link() && strpos($enclosure->get_type() ?? '', 'image') !== false) {
                $image_url = $enclosure->get_link();
            }
        }

        if (empty($image_url)) {
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $m);
            if (!empty($m[1])) {
                $image_url = $m[1];
            }
        }

        if (empty($image_url)) return;

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $tmp = download_url($image_url);
        if (is_wp_error($tmp)) return;

        $ext = pathinfo(parse_url($image_url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'gif'])) $ext = 'jpg';

        $file_array = [
            'name'     => 'news-' . $post_id . '.' . $ext,
            'tmp_name' => $tmp,
        ];

        $attach_id = media_handle_sideload($file_array, $post_id);
        if (!is_wp_error($attach_id)) {
            set_post_thumbnail($post_id, $attach_id);
        }

        if (file_exists($tmp)) @unlink($tmp);
    }
}

BJ_Auto_News::instance();

<?php
/**
 * BazarJooje — Poultry Directory (مرغداری‌ها، جوجه‌کشی‌ها، کشتارگاه‌ها و...)
 */

if (!defined('ABSPATH')) exit;

class BJ_Directory {

    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post_bj_directory', [$this, 'save_meta']);
        add_action('admin_init', [$this, 'maybe_seed_terms']);
        add_action('admin_init', [$this, 'maybe_flush_rewrite']);

        add_shortcode('bazarjooje_directory', [$this, 'shortcode_directory']);
    }

    public function get_provinces() {
        return [
            'آذربایجان شرقی', 'آذربایجان غربی', 'اردبیل', 'اصفهان', 'البرز', 'ایلام',
            'بوشهر', 'تهران', 'چهارمحال و بختیاری', 'خراسان جنوبی', 'خراسان رضوی',
            'خراسان شمالی', 'خوزستان', 'زنجان', 'سمنان', 'سیستان و بلوچستان', 'فارس',
            'قزوین', 'قم', 'کردستان', 'کرمان', 'کرمانشاه', 'کهگیلویه و بویراحمد',
            'گلستان', 'گیلان', 'لرستان', 'مازندران', 'مرکزی', 'هرمزگان', 'همدان', 'یزد',
        ];
    }

    public function get_types() {
        return [
            'مرغداری گوشتی' => '🐔',
            'مرغداری تخمگذار' => '🥚',
            'جوجه‌کشی' => '🐣',
            'کشتارگاه طیور' => '🔪',
            'فروشگاه نهاده دامی' => '🌾',
            'دامپزشک طیور' => '🩺',
            'تعاونی مرغداران' => '🤝',
        ];
    }

    // =========================================
    // CPT + TAXONOMIES
    // =========================================
    public function register_cpt() {
        register_post_type('bj_directory', [
            'labels' => [
                'name' => 'دایرکتوری',
                'singular_name' => 'مرکز',
                'add_new' => 'افزودن مرکز جدید',
                'add_new_item' => 'افزودن مرکز جدید',
                'edit_item' => 'ویرایش مرکز',
                'all_items' => '🗂️ دایرکتوری مرغداری',
                'search_items' => 'جستجوی مرکز',
                'not_found' => 'موردی یافت نشد',
            ],
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => 'bazarjooje',
            'menu_icon' => 'dashicons-location-alt',
            'supports' => ['title', 'editor', 'thumbnail'],
            'has_archive' => false,
            'rewrite' => ['slug' => 'directory'],
            'show_in_rest' => true,
        ]);

        register_taxonomy('bj_dir_type', 'bj_directory', [
            'labels' => ['name' => 'نوع مرکز', 'singular_name' => 'نوع مرکز'],
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'directory-type'],
        ]);

        register_taxonomy('bj_dir_province', 'bj_directory', [
            'labels' => ['name' => 'استان', 'singular_name' => 'استان'],
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'directory-province'],
        ]);
    }

    public function maybe_seed_terms() {
        if (get_option('bj_dir_terms_seeded')) return;

        foreach (array_keys($this->get_types()) as $type) {
            if (!term_exists($type, 'bj_dir_type')) {
                wp_insert_term($type, 'bj_dir_type');
            }
        }
        foreach ($this->get_provinces() as $prov) {
            if (!term_exists($prov, 'bj_dir_province')) {
                wp_insert_term($prov, 'bj_dir_province');
            }
        }

        update_option('bj_dir_terms_seeded', 1);
    }

    public function maybe_flush_rewrite() {
        if (get_option('bj_dir_rewrite_flushed')) return;
        flush_rewrite_rules();
        update_option('bj_dir_rewrite_flushed', 1);
    }

    // =========================================
    // META BOX
    // =========================================
    public function add_meta_box() {
        add_meta_box('bj_dir_meta', 'اطلاعات تماس و ظرفیت', [$this, 'render_meta_box'], 'bj_directory', 'normal', 'high');
    }

    public function render_meta_box($post) {
        wp_nonce_field('bj_dir_meta_save', 'bj_dir_meta_nonce');
        $phone = get_post_meta($post->ID, '_bj_dir_phone', true);
        $city = get_post_meta($post->ID, '_bj_dir_city', true);
        $address = get_post_meta($post->ID, '_bj_dir_address', true);
        $capacity = get_post_meta($post->ID, '_bj_dir_capacity', true);
        $website = get_post_meta($post->ID, '_bj_dir_website', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="bj_dir_phone">📞 تلفن تماس</label></th>
                <td><input type="text" id="bj_dir_phone" name="bj_dir_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" placeholder="مثلاً 081xxxxxxxx"></td>
            </tr>
            <tr>
                <th><label for="bj_dir_city">🏙️ شهر</label></th>
                <td><input type="text" id="bj_dir_city" name="bj_dir_city" value="<?php echo esc_attr($city); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="bj_dir_address">📍 آدرس</label></th>
                <td><textarea id="bj_dir_address" name="bj_dir_address" class="large-text" rows="2"><?php echo esc_textarea($address); ?></textarea></td>
            </tr>
            <tr>
                <th><label for="bj_dir_capacity">📦 ظرفیت</label></th>
                <td><input type="text" id="bj_dir_capacity" name="bj_dir_capacity" value="<?php echo esc_attr($capacity); ?>" class="regular-text" placeholder="مثلاً ۲۰,۰۰۰ قطعه"></td>
            </tr>
            <tr>
                <th><label for="bj_dir_website">🌐 وبسایت / شبکه اجتماعی</label></th>
                <td><input type="text" id="bj_dir_website" name="bj_dir_website" value="<?php echo esc_attr($website); ?>" class="regular-text"></td>
            </tr>
        </table>
        <?php
    }

    public function save_meta($post_id) {
        if (!isset($_POST['bj_dir_meta_nonce']) || !wp_verify_nonce($_POST['bj_dir_meta_nonce'], 'bj_dir_meta_save')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $fields = ['phone', 'city', 'address', 'capacity', 'website'];
        foreach ($fields as $f) {
            $key = 'bj_dir_' . $f;
            if (isset($_POST[$key])) {
                $val = $f === 'address' ? sanitize_textarea_field($_POST[$key]) : sanitize_text_field($_POST[$key]);
                update_post_meta($post_id, '_bj_dir_' . $f, $val);
            }
        }
    }

    // =========================================
    // SHORTCODE
    // =========================================
    public function shortcode_directory($atts) {
        $types = $this->get_types();
        $provinces = $this->get_provinces();

        $sel_type = isset($_GET['bj_dir_type']) ? sanitize_text_field($_GET['bj_dir_type']) : '';
        $sel_province = isset($_GET['bj_dir_province']) ? sanitize_text_field($_GET['bj_dir_province']) : '';
        $search = isset($_GET['bj_dir_s']) ? sanitize_text_field($_GET['bj_dir_s']) : '';

        $tax_query = [];
        if ($sel_type) $tax_query[] = ['taxonomy' => 'bj_dir_type', 'field' => 'name', 'terms' => $sel_type];
        if ($sel_province) $tax_query[] = ['taxonomy' => 'bj_dir_province', 'field' => 'name', 'terms' => $sel_province];
        if (count($tax_query) > 1) $tax_query['relation'] = 'AND';

        $args = [
            'post_type' => 'bj_directory',
            'post_status' => 'publish',
            'posts_per_page' => 24,
            'paged' => max(1, get_query_var('paged')),
        ];
        if ($tax_query) $args['tax_query'] = $tax_query;
        if ($search) $args['s'] = $search;

        $query = new WP_Query($args);
        $current_url = strtok($_SERVER['REQUEST_URI'], '?');

        ob_start();
        ?>
        <div class="bj-directory">
            <form method="get" class="bj-dir-filters" action="<?php echo esc_url($current_url); ?>">
                <select name="bj_dir_type" class="bj-chart-select">
                    <option value="">همه انواع مراکز</option>
                    <?php foreach ($types as $t => $icon): ?>
                        <option value="<?php echo esc_attr($t); ?>" <?php selected($sel_type, $t); ?>><?php echo esc_html($icon . ' ' . $t); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="bj_dir_province" class="bj-chart-select">
                    <option value="">همه استان‌ها</option>
                    <?php foreach ($provinces as $p): ?>
                        <option value="<?php echo esc_attr($p); ?>" <?php selected($sel_province, $p); ?>><?php echo esc_html($p); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="bj_dir_s" class="bj-chart-select" placeholder="جستجوی نام مرکز..." value="<?php echo esc_attr($search); ?>">
                <button type="submit" class="bj-calc-btn" style="width:auto;padding:10px 24px">🔍 جستجو</button>
            </form>

            <?php if (!$query->have_posts()): ?>
                <div class="bj-prov-empty">موردی یافت نشد. می‌توانید فیلترها را تغییر دهید.</div>
            <?php else: ?>
                <div class="bj-dir-grid">
                    <?php while ($query->have_posts()): $query->the_post();
                        $id = get_the_ID();
                        $phone = get_post_meta($id, '_bj_dir_phone', true);
                        $city = get_post_meta($id, '_bj_dir_city', true);
                        $address = get_post_meta($id, '_bj_dir_address', true);
                        $capacity = get_post_meta($id, '_bj_dir_capacity', true);
                        $website = get_post_meta($id, '_bj_dir_website', true);
                        $type_terms = get_the_terms($id, 'bj_dir_type');
                        $prov_terms = get_the_terms($id, 'bj_dir_province');
                        $type_label = $type_terms && !is_wp_error($type_terms) ? $type_terms[0]->name : '';
                        $prov_label = $prov_terms && !is_wp_error($prov_terms) ? $prov_terms[0]->name : '';
                        $icon = $types[$type_label] ?? '📍';
                    ?>
                    <div class="bj-dir-card">
                        <div class="bj-dir-card-head">
                            <span class="bj-dir-icon"><?php echo esc_html($icon); ?></span>
                            <div>
                                <div class="bj-dir-name"><?php the_title(); ?></div>
                                <?php if ($type_label): ?><span class="bj-dir-type"><?php echo esc_html($type_label); ?></span><?php endif; ?>
                            </div>
                        </div>
                        <div class="bj-dir-meta">
                            <?php if ($prov_label || $city): ?><div>📍 <?php echo esc_html(trim($prov_label . ($city ? ' — ' . $city : ''))); ?></div><?php endif; ?>
                            <?php if ($address): ?><div>🏠 <?php echo esc_html($address); ?></div><?php endif; ?>
                            <?php if ($capacity): ?><div>📦 ظرفیت: <?php echo esc_html($capacity); ?></div><?php endif; ?>
                        </div>
                        <div class="bj-dir-actions">
                            <?php if ($phone): ?><a href="tel:<?php echo esc_attr($phone); ?>" class="bj-dir-btn">📞 <?php echo esc_html($phone); ?></a><?php endif; ?>
                            <?php if ($website): ?><a href="<?php echo esc_url($website); ?>" target="_blank" rel="noopener" class="bj-dir-btn bj-dir-btn-alt">🌐 وبسایت</a><?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <div class="bj-dir-pagination">
                    <?php
                    echo paginate_links([
                        'total' => $query->max_num_pages,
                        'current' => max(1, get_query_var('paged')),
                        'prev_text' => '« قبلی',
                        'next_text' => 'بعدی »',
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

BJ_Directory::instance();

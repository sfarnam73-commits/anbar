<?php
/**
 * Plugin Name: بازار جوجه ایران — مدیریت قیمت
 * Plugin URI: https://bazarjooje.ir
 * Description: سیستم جامع مدیریت قیمت روزانه جوجه یکروزه، مرغ، تخم مرغ و نهاده‌ها با نمودار، آرشیو و ماشین‌حساب هزینه
 * Version: 1.0.0
 * Author: بازار جوجه ایران
 * Text Domain: bazarjooje
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) exit;

define('BJ_PLUGIN_FILE', __FILE__);

class BazarJooje_Prices {

    private static $instance = null;
    private $table_prices;
    private $table_products;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        global $wpdb;
        $this->table_prices = $wpdb->prefix . 'bj_prices';
        $this->table_products = $wpdb->prefix . 'bj_products';

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'front_assets']);
        add_action('wp_ajax_bj_save_price', [$this, 'ajax_save_price']);
        add_action('wp_ajax_bj_get_chart', [$this, 'ajax_get_chart']);
        add_action('wp_ajax_nopriv_bj_get_chart', [$this, 'ajax_get_chart']);
        add_action('wp_ajax_bj_delete_price', [$this, 'ajax_delete_price']);
        add_action('wp_ajax_bj_save_product', [$this, 'ajax_save_product']);
        add_action('wp_ajax_bj_delete_product', [$this, 'ajax_delete_product']);
        add_action('wp_ajax_bj_bulk_save', [$this, 'ajax_bulk_save']);
        add_action('wp_ajax_bj_calculate_cost', [$this, 'ajax_calculate_cost']);
        add_action('wp_ajax_nopriv_bj_calculate_cost', [$this, 'ajax_calculate_cost']);

        add_shortcode('bazarjooje_prices', [$this, 'shortcode_prices']);
        add_shortcode('bazarjooje_chart', [$this, 'shortcode_chart']);
        add_shortcode('bazarjooje_calculator', [$this, 'shortcode_calculator']);
        add_shortcode('bazarjooje_ticker', [$this, 'shortcode_ticker']);
    }

    // =========================================
    // DATABASE SETUP
    // =========================================
    public function activate() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql_products = "CREATE TABLE {$this->table_products} (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            category varchar(100) NOT NULL DEFAULT 'chick',
            unit varchar(50) NOT NULL DEFAULT 'قطعه',
            sort_order int(11) NOT NULL DEFAULT 0,
            is_active tinyint(1) NOT NULL DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY category (category),
            KEY is_active (is_active)
        ) $charset;";

        $sql_prices = "CREATE TABLE {$this->table_prices} (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id bigint(20) UNSIGNED NOT NULL,
            price bigint(20) NOT NULL,
            price_date date NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY product_date (product_id, price_date),
            KEY price_date (price_date),
            KEY product_id (product_id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql_products);
        dbDelta($sql_prices);

        $this->seed_products();
    }

    private function seed_products() {
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_products}");
        if ($count > 0) return;

        $products = [
            ['جوجه یکروزه گوشتی — راس ۳۰۸', 'chick', 'قطعه', 1],
            ['جوجه یکروزه گوشتی — کاب ۵۰۰', 'chick', 'قطعه', 2],
            ['جوجه یکروزه گوشتی — آربورآیکرز', 'chick', 'قطعه', 3],
            ['جوجه یکروزه گوشتی — آرین', 'chick', 'قطعه', 4],
            ['جوجه یکروزه گوشتی — ایندین‌ریور', 'chick', 'قطعه', 5],
            ['جوجه یکروزه تخمگذار — لوهمن', 'chick', 'قطعه', 6],
            ['جوجه یکروزه تخمگذار — های‌لاین', 'chick', 'قطعه', 7],
            ['جوجه بوقلمون', 'chick', 'قطعه', 8],
            ['مرغ زنده — درب مرغداری', 'chicken', 'کیلوگرم', 1],
            ['مرغ زنده — بازار', 'chicken', 'کیلوگرم', 2],
            ['گوشت مرغ — کشتار روز', 'chicken', 'کیلوگرم', 3],
            ['ران مرغ', 'chicken', 'کیلوگرم', 4],
            ['سینه مرغ', 'chicken', 'کیلوگرم', 5],
            ['فیله مرغ', 'chicken', 'کیلوگرم', 6],
            ['تخم مرغ — شانه‌ای ۳۰ عددی', 'egg', 'شانه', 1],
            ['تخم مرغ — بسته‌بندی ۱۵ عددی', 'egg', 'بسته', 2],
            ['تخم مرغ — درب مرغداری', 'egg', 'شانه', 3],
            ['تخم مرغ — صادراتی', 'egg', 'شانه', 4],
            ['تخم مرغ — محلی', 'egg', 'شانه', 5],
            ['ذرت دامی', 'feed', 'کیلوگرم', 1],
            ['کنجاله سویا', 'feed', 'کیلوگرم', 2],
            ['پودر ماهی', 'feed', 'کیلوگرم', 3],
            ['پودر گوشت', 'feed', 'کیلوگرم', 4],
            ['سبوس گندم', 'feed', 'کیلوگرم', 5],
            ['مکمل ویتامینه', 'feed', 'کیلوگرم', 6],
            ['دی‌کلسیم فسفات (DCP)', 'feed', 'کیلوگرم', 7],
            ['متیونین', 'feed', 'کیلوگرم', 8],
            ['لیزین', 'feed', 'کیلوگرم', 9],
            ['کربنات کلسیم', 'feed', 'کیلوگرم', 10],
        ];

        foreach ($products as $p) {
            $wpdb->insert($this->table_products, [
                'name' => $p[0],
                'category' => $p[1],
                'unit' => $p[2],
                'sort_order' => $p[3],
            ]);
        }
    }

    // =========================================
    // ADMIN MENU
    // =========================================
    public function admin_menu() {
        add_menu_page(
            'بازار جوجه — قیمت‌ها',
            '🐣 بازار جوجه',
            'manage_options',
            'bazarjooje',
            [$this, 'page_prices'],
            'dashicons-chart-line',
            30
        );
        add_submenu_page('bazarjooje', 'ثبت قیمت روزانه', '💰 ثبت قیمت', 'manage_options', 'bazarjooje', [$this, 'page_prices']);
        add_submenu_page('bazarjooje', 'مدیریت محصولات', '📦 محصولات', 'manage_options', 'bazarjooje-products', [$this, 'page_products']);
        add_submenu_page('bazarjooje', 'آرشیو قیمت‌ها', '📊 آرشیو', 'manage_options', 'bazarjooje-archive', [$this, 'page_archive']);
        add_submenu_page('bazarjooje', 'راهنما', '❓ راهنما', 'manage_options', 'bazarjooje-help', [$this, 'page_help']);
    }

    // =========================================
    // ADMIN ASSETS
    // =========================================
    public function admin_assets($hook) {
        if (strpos($hook, 'bazarjooje') === false) return;

        wp_enqueue_style('bj-admin', plugin_dir_url(__FILE__) . 'admin.css', [], '1.0.0');
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [], '4.4.7', true);
        wp_enqueue_script('bj-admin', plugin_dir_url(__FILE__) . 'admin.js', ['jquery', 'chartjs'], '1.0.0', true);
        wp_localize_script('bj-admin', 'bjAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bj_nonce'),
        ]);
    }

    // =========================================
    // FRONT ASSETS
    // =========================================
    public function front_assets() {
        wp_enqueue_style('bj-front', plugin_dir_url(__FILE__) . 'front.css', [], '1.0.0');
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js', [], '4.4.7', true);
        wp_enqueue_script('bj-front', plugin_dir_url(__FILE__) . 'front.js', ['jquery', 'chartjs'], '1.0.0', true);
        wp_localize_script('bj-front', 'bjFront', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bj_front_nonce'),
        ]);
    }

    // =========================================
    // HELPERS
    // =========================================
    private function get_categories() {
        return [
            'chick' => ['label' => 'جوجه یکروزه', 'icon' => '🐣', 'color' => '#16a34a'],
            'chicken' => ['label' => 'مرغ زنده و کشتار', 'icon' => '🐔', 'color' => '#dc2626'],
            'egg' => ['label' => 'تخم مرغ', 'icon' => '🥚', 'color' => '#f59e0b'],
            'feed' => ['label' => 'نهاده‌های دامی', 'icon' => '🌾', 'color' => '#8b5cf6'],
        ];
    }

    private function get_products($category = null) {
        global $wpdb;
        $where = $category ? $wpdb->prepare("WHERE category = %s AND is_active = 1", $category) : "WHERE is_active = 1";
        return $wpdb->get_results("SELECT * FROM {$this->table_products} $where ORDER BY sort_order ASC");
    }

    private function get_today_prices($category = null) {
        global $wpdb;
        $today = current_time('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));

        $cat_join = $category ? $wpdb->prepare("AND p.category = %s", $category) : "";

        return $wpdb->get_results("
            SELECT p.id, p.name, p.category, p.unit,
                   tp.price AS today_price,
                   yp.price AS yesterday_price,
                   COALESCE(tp.price, 0) - COALESCE(yp.price, 0) AS price_change
            FROM {$this->table_products} p
            LEFT JOIN {$this->table_prices} tp ON p.id = tp.product_id AND tp.price_date = '$today'
            LEFT JOIN {$this->table_prices} yp ON p.id = yp.product_id AND yp.price_date = '$yesterday'
            WHERE p.is_active = 1 $cat_join
            ORDER BY p.category ASC, p.sort_order ASC
        ");
    }

    private function get_price_history($product_id, $days = 30) {
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare("
            SELECT price, price_date
            FROM {$this->table_prices}
            WHERE product_id = %d
            ORDER BY price_date DESC
            LIMIT %d
        ", $product_id, $days));
    }

    // =========================================
    // ADMIN PAGE: DAILY PRICE ENTRY
    // =========================================
    public function page_prices() {
        global $wpdb;
        $categories = $this->get_categories();
        $today = current_time('Y-m-d');
        $today_jalali = $this->gregorian_to_jalali_str($today);
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>🐣 ثبت قیمت روزانه</h1>
                <p class="bj-subtitle">📅 <?php echo esc_html($today_jalali); ?></p>
            </div>

            <div class="bj-date-selector">
                <label>📅 تاریخ:</label>
                <input type="date" id="bjDate" value="<?php echo esc_attr($today); ?>" max="<?php echo esc_attr($today); ?>">
                <button class="button button-primary" onclick="bjLoadPrices()">بارگذاری</button>
            </div>

            <div class="bj-tabs">
                <?php foreach ($categories as $key => $cat): ?>
                    <button class="bj-tab <?php echo $key === 'chick' ? 'active' : ''; ?>"
                            onclick="bjSwitchCat('<?php echo esc_attr($key); ?>', this)">
                        <?php echo esc_html($cat['icon'] . ' ' . $cat['label']); ?>
                    </button>
                <?php endforeach; ?>
                <button class="bj-tab bj-tab-all" onclick="bjSwitchCat('all', this)">📋 همه</button>
            </div>

            <?php foreach ($categories as $cat_key => $cat): ?>
                <div class="bj-cat-section" id="bj-cat-<?php echo esc_attr($cat_key); ?>"
                     style="<?php echo $cat_key !== 'chick' ? 'display:none' : ''; ?>">
                    <h3><?php echo esc_html($cat['icon'] . ' ' . $cat['label']); ?></h3>
                    <table class="bj-price-form">
                        <thead>
                            <tr>
                                <th>محصول</th>
                                <th>واحد</th>
                                <th>قیمت دیروز</th>
                                <th>قیمت امروز (تومان)</th>
                                <th>تغییر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $products = $this->get_products($cat_key);
                            foreach ($products as $prod):
                                $today_price = $wpdb->get_var($wpdb->prepare(
                                    "SELECT price FROM {$this->table_prices} WHERE product_id = %d AND price_date = %s",
                                    $prod->id, $today
                                ));
                                $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($today)));
                                $yesterday_price = $wpdb->get_var($wpdb->prepare(
                                    "SELECT price FROM {$this->table_prices} WHERE product_id = %d AND price_date = %s",
                                    $prod->id, $yesterday
                                ));
                            ?>
                            <tr data-product-id="<?php echo esc_attr($prod->id); ?>">
                                <td class="bj-prod-name"><?php echo esc_html($prod->name); ?></td>
                                <td><?php echo esc_html($prod->unit); ?></td>
                                <td class="bj-yesterday"><?php echo $yesterday_price ? number_format($yesterday_price) : '—'; ?></td>
                                <td>
                                    <input type="number" class="bj-price-input"
                                           value="<?php echo esc_attr($today_price ?: ''); ?>"
                                           placeholder="قیمت..."
                                           data-product="<?php echo esc_attr($prod->id); ?>"
                                           data-yesterday="<?php echo esc_attr($yesterday_price ?: 0); ?>">
                                </td>
                                <td class="bj-change">
                                    <?php
                                    if ($today_price && $yesterday_price) {
                                        $diff = $today_price - $yesterday_price;
                                        if ($diff > 0) echo '<span class="bj-up">▲ ' . number_format($diff) . '</span>';
                                        elseif ($diff < 0) echo '<span class="bj-down">▼ ' . number_format(abs($diff)) . '</span>';
                                        else echo '<span class="bj-eq">— ثابت</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>

            <div class="bj-actions">
                <button class="button button-primary button-hero" onclick="bjSaveAll()">
                    💾 ذخیره همه قیمت‌ها
                </button>
                <span class="bj-save-status" id="bjSaveStatus"></span>
            </div>

            <div class="bj-quick-chart">
                <h3>📈 نمودار سریع</h3>
                <select id="bjChartProduct" onchange="bjLoadQuickChart()">
                    <?php
                    $all_products = $this->get_products();
                    foreach ($all_products as $p) {
                        echo '<option value="' . esc_attr($p->id) . '">' . esc_html($p->name) . '</option>';
                    }
                    ?>
                </select>
                <div class="bj-chart-container">
                    <canvas id="bjAdminChart"></canvas>
                </div>
            </div>
        </div>
        <?php
    }

    // =========================================
    // ADMIN PAGE: MANAGE PRODUCTS
    // =========================================
    public function page_products() {
        $categories = $this->get_categories();
        $products = $this->get_products();
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>📦 مدیریت محصولات</h1>
            </div>

            <div class="bj-add-product">
                <h3>➕ افزودن محصول جدید</h3>
                <div class="bj-form-row">
                    <input type="text" id="bjNewName" placeholder="نام محصول..." class="regular-text">
                    <select id="bjNewCat">
                        <?php foreach ($categories as $k => $c): ?>
                            <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($c['icon'] . ' ' . $c['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" id="bjNewUnit" placeholder="واحد (مثلاً: قطعه، کیلوگرم)" value="قطعه" class="small-text">
                    <button class="button button-primary" onclick="bjAddProduct()">افزودن</button>
                </div>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr><th>نام</th><th>دسته</th><th>واحد</th><th>ترتیب</th><th>وضعیت</th><th>عملیات</th></tr>
                </thead>
                <tbody id="bjProductsList">
                    <?php foreach ($products as $p): ?>
                    <tr data-id="<?php echo esc_attr($p->id); ?>">
                        <td><strong><?php echo esc_html($p->name); ?></strong></td>
                        <td><?php echo esc_html($categories[$p->category]['icon'] ?? '') . ' ' . esc_html($categories[$p->category]['label'] ?? $p->category); ?></td>
                        <td><?php echo esc_html($p->unit); ?></td>
                        <td><?php echo esc_html($p->sort_order); ?></td>
                        <td><?php echo $p->is_active ? '<span style="color:green">✅ فعال</span>' : '<span style="color:red">❌ غیرفعال</span>'; ?></td>
                        <td><button class="button button-small button-link-delete" onclick="bjDeleteProduct(<?php echo esc_attr($p->id); ?>)">حذف</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    // =========================================
    // ADMIN PAGE: ARCHIVE
    // =========================================
    public function page_archive() {
        global $wpdb;
        $categories = $this->get_categories();
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>📊 آرشیو قیمت‌ها</h1>
            </div>
            <div class="bj-archive-filters">
                <select id="bjArchiveCat" onchange="bjLoadArchive()">
                    <option value="all">همه دسته‌ها</option>
                    <?php foreach ($categories as $k => $c): ?>
                        <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($c['icon'] . ' ' . $c['label']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" id="bjArchiveFrom" onchange="bjLoadArchive()">
                <input type="date" id="bjArchiveTo" value="<?php echo esc_attr(current_time('Y-m-d')); ?>" onchange="bjLoadArchive()">
                <button class="button" onclick="bjExportCSV()">📥 خروجی اکسل</button>
            </div>
            <div id="bjArchiveTable"></div>
        </div>
        <?php
    }

    // =========================================
    // ADMIN PAGE: HELP
    // =========================================
    public function page_help() {
        ?>
        <div class="wrap bj-wrap">
            <div class="bj-header">
                <h1>❓ راهنمای استفاده</h1>
            </div>
            <div class="bj-help-content">
                <div class="bj-help-card">
                    <h3>💰 ثبت قیمت روزانه</h3>
                    <p>هر روز وارد بخش «ثبت قیمت» بشید، قیمت‌ها رو وارد کنید و دکمه ذخیره رو بزنید. سیستم خودکار تغییرات نسبت به دیروز رو محاسبه می‌کنه.</p>
                </div>
                <div class="bj-help-card">
                    <h3>📊 شورت‌کدها</h3>
                    <ul>
                        <li><code>[bazarjooje_prices]</code> — جدول قیمت‌های روز</li>
                        <li><code>[bazarjooje_prices category="chick"]</code> — فقط قیمت جوجه</li>
                        <li><code>[bazarjooje_chart]</code> — نمودار قیمت</li>
                        <li><code>[bazarjooje_chart product_id="1"]</code> — نمودار یک محصول</li>
                        <li><code>[bazarjooje_calculator]</code> — ماشین‌حساب هزینه مرغداری</li>
                        <li><code>[bazarjooje_ticker]</code> — نوار متحرک قیمت</li>
                    </ul>
                </div>
                <div class="bj-help-card">
                    <h3>📦 مدیریت محصولات</h3>
                    <p>محصولات جدید اضافه کنید یا محصولات موجود رو ویرایش و حذف کنید.</p>
                </div>
            </div>
        </div>
        <?php
    }

    // =========================================
    // AJAX HANDLERS
    // =========================================
    public function ajax_save_price() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('دسترسی ندارید');

        global $wpdb;
        $product_id = intval($_POST['product_id']);
        $price = intval($_POST['price']);
        $date = sanitize_text_field($_POST['date']);

        if (!$product_id || !$price || !$date) {
            wp_send_json_error('اطلاعات ناقص');
        }

        $wpdb->replace($this->table_prices, [
            'product_id' => $product_id,
            'price' => $price,
            'price_date' => $date,
        ], ['%d', '%d', '%s']);

        wp_send_json_success('ذخیره شد');
    }

    public function ajax_bulk_save() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('دسترسی ندارید');

        global $wpdb;
        $date = sanitize_text_field($_POST['date']);
        $prices = json_decode(stripslashes($_POST['prices']), true);

        if (!$date || !is_array($prices)) wp_send_json_error('اطلاعات ناقص');

        $saved = 0;
        foreach ($prices as $item) {
            $pid = intval($item['product_id']);
            $price = intval($item['price']);
            if ($pid && $price) {
                $wpdb->replace($this->table_prices, [
                    'product_id' => $pid,
                    'price' => $price,
                    'price_date' => $date,
                ], ['%d', '%d', '%s']);
                $saved++;
            }
        }

        wp_send_json_success("$saved قیمت ذخیره شد");
    }

    public function ajax_delete_price() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('دسترسی ندارید');

        global $wpdb;
        $id = intval($_POST['id']);
        $wpdb->delete($this->table_prices, ['id' => $id], ['%d']);
        wp_send_json_success('حذف شد');
    }

    public function ajax_save_product() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('دسترسی ندارید');

        global $wpdb;
        $name = sanitize_text_field($_POST['name']);
        $category = sanitize_text_field($_POST['category']);
        $unit = sanitize_text_field($_POST['unit']);

        if (!$name) wp_send_json_error('نام محصول الزامی است');

        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(sort_order) FROM {$this->table_products} WHERE category = %s", $category
        ));

        $wpdb->insert($this->table_products, [
            'name' => $name,
            'category' => $category,
            'unit' => $unit ?: 'قطعه',
            'sort_order' => ($max_order ?: 0) + 1,
        ]);

        wp_send_json_success(['id' => $wpdb->insert_id, 'message' => 'محصول اضافه شد']);
    }

    public function ajax_delete_product() {
        check_ajax_referer('bj_nonce', 'nonce');
        if (!current_user_can('manage_options')) wp_send_json_error('دسترسی ندارید');

        global $wpdb;
        $id = intval($_POST['id']);
        $wpdb->update($this->table_products, ['is_active' => 0], ['id' => $id]);
        wp_send_json_success('غیرفعال شد');
    }

    public function ajax_get_chart() {
        global $wpdb;
        $product_id = intval($_GET['product_id'] ?? $_POST['product_id'] ?? 1);
        $days = intval($_GET['days'] ?? $_POST['days'] ?? 30);

        $history = $wpdb->get_results($wpdb->prepare("
            SELECT price, price_date
            FROM {$this->table_prices}
            WHERE product_id = %d
            AND price_date >= DATE_SUB(CURDATE(), INTERVAL %d DAY)
            ORDER BY price_date ASC
        ", $product_id, $days));

        $labels = [];
        $data = [];
        foreach ($history as $row) {
            $labels[] = $row->price_date;
            $data[] = intval($row->price);
        }

        $stats = $wpdb->get_row($wpdb->prepare("
            SELECT MAX(price) as high, MIN(price) as low, AVG(price) as avg
            FROM {$this->table_prices}
            WHERE product_id = %d AND price_date >= DATE_SUB(CURDATE(), INTERVAL %d DAY)
        ", $product_id, $days));

        wp_send_json_success([
            'labels' => $labels,
            'data' => $data,
            'stats' => [
                'high' => intval($stats->high ?? 0),
                'low' => intval($stats->low ?? 0),
                'avg' => intval($stats->avg ?? 0),
                'current' => end($data) ?: 0,
            ]
        ]);
    }

    public function ajax_calculate_cost() {
        $chick_count = intval($_POST['chick_count'] ?? 0);
        $chick_price = intval($_POST['chick_price'] ?? 0);
        $feed_per_bird = floatval($_POST['feed_per_bird'] ?? 4.2);
        $feed_price = intval($_POST['feed_price'] ?? 0);
        $mortality = floatval($_POST['mortality'] ?? 5);
        $avg_weight = floatval($_POST['avg_weight'] ?? 2.5);
        $other_costs = intval($_POST['other_costs'] ?? 0);

        $surviving = $chick_count * (1 - $mortality / 100);
        $total_chick_cost = $chick_count * $chick_price;
        $total_feed_cost = $chick_count * $feed_per_bird * $feed_price;
        $total_other = $chick_count * $other_costs;
        $total_cost = $total_chick_cost + $total_feed_cost + $total_other;
        $total_meat = $surviving * $avg_weight;
        $cost_per_kg = $total_meat > 0 ? round($total_cost / $total_meat) : 0;

        wp_send_json_success([
            'surviving_birds' => round($surviving),
            'total_chick_cost' => $total_chick_cost,
            'total_feed_cost' => $total_feed_cost,
            'total_other_cost' => $total_other,
            'total_cost' => $total_cost,
            'total_meat_kg' => round($total_meat, 1),
            'cost_per_kg' => $cost_per_kg,
        ]);
    }

    // =========================================
    // SHORTCODES
    // =========================================
    public function shortcode_prices($atts) {
        $atts = shortcode_atts(['category' => ''], $atts);
        $categories = $this->get_categories();
        $cat = $atts['category'];
        $prices = $this->get_today_prices($cat ?: null);
        $today_jalali = $this->gregorian_to_jalali_str(current_time('Y-m-d'));

        // Group prices by category, preserving category order
        $grouped = [];
        foreach ($categories as $k => $c) $grouped[$k] = [];
        foreach ($prices as $p) {
            $grouped[$p->category][] = $p;
        }

        ob_start();
        ?>
        <div class="bj-front-prices">
            <?php if (!$cat): ?>
            <div class="bj-front-tabs">
                <?php foreach ($categories as $k => $c): if (empty($grouped[$k])) continue; ?>
                    <a href="#bj-cat-<?php echo esc_attr($k); ?>" class="bj-ftab" style="--cat-color:<?php echo esc_attr($c['color']); ?>">
                        <?php echo esc_html($c['icon'] . ' ' . $c['label']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="bj-front-update">📅 آخرین بروزرسانی: <?php echo esc_html($today_jalali); ?></div>

            <?php foreach ($categories as $k => $c):
                if ($cat && $cat !== $k) continue;
                if (empty($grouped[$k])) continue;
            ?>
            <div class="bj-cat-section" id="bj-cat-<?php echo esc_attr($k); ?>">
                <div class="bj-cat-head" style="--cat-color:<?php echo esc_attr($c['color']); ?>">
                    <span class="bj-cat-icon"><?php echo esc_html($c['icon']); ?></span>
                    <span class="bj-cat-title"><?php echo esc_html($c['label']); ?></span>
                </div>
                <table class="bj-front-table">
                    <thead>
                        <tr><th>محصول</th><th>قیمت امروز</th><th>قیمت دیروز</th><th>تغییر</th><th>واحد</th><th>وضعیت</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grouped[$k] as $p): ?>
                        <tr class="bj-frow">
                            <td class="bj-fname"><?php echo esc_html($p->name); ?></td>
                            <td class="bj-fprice"><?php echo $p->today_price ? number_format($p->today_price) : '—'; ?></td>
                            <td><?php echo $p->yesterday_price ? number_format($p->yesterday_price) : '—'; ?></td>
                            <td>
                                <?php if ($p->today_price && $p->yesterday_price):
                                    $diff = $p->price_change;
                                    if ($diff > 0): ?>
                                        <span class="bj-fup">▲ <?php echo number_format($diff); ?></span>
                                    <?php elseif ($diff < 0): ?>
                                        <span class="bj-fdn">▼ <?php echo number_format(abs($diff)); ?></span>
                                    <?php else: ?>
                                        <span class="bj-feq">— ۰</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="bj-feq">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html($p->unit); ?></td>
                            <td>
                                <?php if ($p->today_price && $p->yesterday_price):
                                    if ($p->price_change > 0): ?>
                                        <span class="bj-badge-up">صعودی</span>
                                    <?php elseif ($p->price_change < 0): ?>
                                        <span class="bj-badge-dn">نزولی</span>
                                    <?php else: ?>
                                        <span class="bj-badge-eq">ثابت</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function shortcode_chart($atts) {
        $atts = shortcode_atts(['product_id' => '', 'days' => '30'], $atts);
        $products = $this->get_products();
        $pid = $atts['product_id'] ?: ($products[0]->id ?? 1);
        $id = 'bj_chart_' . uniqid();

        ob_start();
        ?>
        <div class="bj-front-chart" id="<?php echo esc_attr($id); ?>">
            <div class="bj-chart-controls">
                <select class="bj-chart-select" onchange="bjFrontChart('<?php echo esc_attr($id); ?>', this.value)">
                    <?php foreach ($products as $p): ?>
                        <option value="<?php echo esc_attr($p->id); ?>" <?php selected($p->id, $pid); ?>>
                            <?php echo esc_html($p->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="bj-period-btns">
                    <button class="bj-pbtn" onclick="bjFrontChartPeriod('<?php echo esc_attr($id); ?>',7)">هفتگی</button>
                    <button class="bj-pbtn active" onclick="bjFrontChartPeriod('<?php echo esc_attr($id); ?>',30)">ماهانه</button>
                    <button class="bj-pbtn" onclick="bjFrontChartPeriod('<?php echo esc_attr($id); ?>',90)">سه‌ماهه</button>
                </div>
            </div>
            <div class="bj-chart-box">
                <canvas id="<?php echo esc_attr($id); ?>_canvas"></canvas>
            </div>
            <div class="bj-chart-stats" id="<?php echo esc_attr($id); ?>_stats"></div>
        </div>
        <script>document.addEventListener('DOMContentLoaded',function(){bjFrontChart('<?php echo esc_js($id); ?>',<?php echo intval($pid); ?>)});</script>
        <?php
        return ob_get_clean();
    }

    public function shortcode_calculator($atts) {
        ob_start();
        ?>
        <div class="bj-calculator">
            <h3>🔢 ماشین‌حساب هزینه تمام‌شده مرغداری</h3>
            <div class="bj-calc-grid">
                <div class="bj-calc-field">
                    <label>🐣 تعداد جوجه</label>
                    <input type="number" id="bjCalcCount" value="10000" placeholder="مثلاً ۱۰۰۰۰">
                </div>
                <div class="bj-calc-field">
                    <label>💰 قیمت جوجه (تومان)</label>
                    <input type="number" id="bjCalcChickPrice" value="48000" placeholder="قیمت هر قطعه">
                </div>
                <div class="bj-calc-field">
                    <label>🌾 مصرف خوراک (کیلو/قطعه)</label>
                    <input type="number" id="bjCalcFeedPerBird" value="4.2" step="0.1">
                </div>
                <div class="bj-calc-field">
                    <label>💰 قیمت خوراک (تومان/کیلو)</label>
                    <input type="number" id="bjCalcFeedPrice" value="28000" placeholder="میانگین قیمت">
                </div>
                <div class="bj-calc-field">
                    <label>📉 درصد تلفات</label>
                    <input type="number" id="bjCalcMortality" value="5" step="0.5" max="100">
                </div>
                <div class="bj-calc-field">
                    <label>⚖️ میانگین وزن کشتار (کیلو)</label>
                    <input type="number" id="bjCalcWeight" value="2.5" step="0.1">
                </div>
                <div class="bj-calc-field">
                    <label>📋 سایر هزینه‌ها (تومان/قطعه)</label>
                    <input type="number" id="bjCalcOther" value="15000" placeholder="دارو، برق، گاز...">
                </div>
            </div>
            <button class="bj-calc-btn" onclick="bjCalculate()">🔢 محاسبه هزینه تمام‌شده</button>
            <div class="bj-calc-result" id="bjCalcResult" style="display:none">
                <div class="bj-calc-main">
                    <span>هزینه تمام‌شده هر کیلو مرغ زنده:</span>
                    <strong id="bjCalcCostPerKg">۰</strong>
                    <small>تومان</small>
                </div>
                <div class="bj-calc-details" id="bjCalcDetails"></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function shortcode_ticker($atts) {
        $prices = $this->get_today_prices();
        ob_start();
        ?>
        <div class="bj-ticker">
            <div class="bj-ticker-track">
                <?php foreach ($prices as $p): if (!$p->today_price) continue; ?>
                <div class="bj-ticker-item">
                    <span class="bj-ticker-name"><?php echo esc_html($p->name); ?></span>
                    <span class="bj-ticker-val"><?php echo number_format($p->today_price); ?></span>
                    <?php if ($p->yesterday_price):
                        $diff = $p->price_change;
                        $cls = $diff > 0 ? 'up' : ($diff < 0 ? 'dn' : 'eq');
                        $icon = $diff > 0 ? '▲' : ($diff < 0 ? '▼' : '—');
                    ?>
                    <span class="bj-ticker-ch bj-ticker-<?php echo $cls; ?>"><?php echo $icon . ' ' . number_format(abs($diff)); ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    // =========================================
    // DATE HELPER
    // =========================================
    private function gregorian_to_jalali_str($date) {
        $timestamp = strtotime($date);
        $d = date('d', $timestamp);
        $m = date('m', $timestamp);
        $y = date('Y', $timestamp);

        $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $gy2 = ($m > 2) ? ($y + 1) : $y;
        $days = 355666 + (365 * $y) + intval(($gy2 + 3) / 4) - intval(($gy2 + 99) / 100) + intval(($gy2 + 399) / 400) + $d + $g_d_m[$m - 1];
        $jy = -1595 + (33 * intval($days / 12053));
        $days %= 12053;
        $jy += 4 * intval($days / 1461);
        $days %= 1461;
        if ($days > 365) {
            $jy += intval(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        if ($days < 186) {
            $jm = 1 + intval($days / 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intval(($days - 186) / 30);
            $jd = 1 + (($days - 186) % 30);
        }

        $months = ['', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        $pDays = ['یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه'];
        $dayOfWeek = $pDays[date('w', $timestamp)];

        return "$dayOfWeek $jd {$months[$jm]} $jy";
    }
}

BazarJooje_Prices::instance();

require_once plugin_dir_path(__FILE__) . 'auto-news.php';
require_once plugin_dir_path(__FILE__) . 'province-prices.php';
require_once plugin_dir_path(__FILE__) . 'poultry-calendar.php';
require_once plugin_dir_path(__FILE__) . 'directory.php';

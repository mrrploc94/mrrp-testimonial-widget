WordPress Plugin
Plugin Name: MRRP Testimonial Widget
Plugin URI: https://github.com/yourusername/mrrp-testimonial-widget
Description: A professional Elementor widget for displaying testimonials with horizontal scrolling slider, full-width backgrounds, and avatar navigation.
Version: 1.0.0
Author: MRRP
Author URI: https://github.com/yourusername
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: mrrp-testimonial
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Elementor tested up to: 3.18
Elementor Pro tested up to: 3.18
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Plugin constants
define('MRRP_TESTIMONIAL_VERSION', '1.0.0');
define('MRRP_TESTIMONIAL_FILE', __FILE__);
define('MRRP_TESTIMONIAL_PATH', plugin_dir_path(__FILE__));
define('MRRP_TESTIMONIAL_URL', plugin_dir_url(__FILE__));

/**
 * Main MRRP Testimonial Widget Class
 */
final class MRRP_Testimonial_Widget {
    
    /**
     * Instance
     */
    private static $_instance = null;
    
    /**
     * Instance
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }
        
        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, '3.0.0', '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }
        
        // Check for required PHP version
        if (version_compare(PHP_VERSION, '7.0', '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }
        
        // Register widget
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        
        // Register widget category
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
        
        // Enqueue scripts and styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_frontend_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'enqueue_frontend_scripts']);
        
        // Load plugin text domain
        add_action('init', [$this, 'load_textdomain']);
    }
    
    /**
     * Admin notice for missing Elementor
     */
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
        
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'mrrp-testimonial'),
            '<strong>' . esc_html__('MRRP Testimonial Widget', 'mrrp-testimonial') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'mrrp-testimonial') . '</strong>'
        );
        
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    /**
     * Admin notice for minimum Elementor version
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
        
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'mrrp-testimonial'),
            '<strong>' . esc_html__('MRRP Testimonial Widget', 'mrrp-testimonial') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'mrrp-testimonial') . '</strong>',
            '3.0.0'
        );
        
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    /**
     * Admin notice for minimum PHP version
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
        
        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'mrrp-testimonial'),
            '<strong>' . esc_html__('MRRP Testimonial Widget', 'mrrp-testimonial') . '</strong>',
            '<strong>' . esc_html__('PHP', 'mrrp-testimonial') . '</strong>',
            '7.0'
        );
        
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    
    /**
     * Register widgets
     */
    public function register_widgets($widgets_manager) {
        require_once MRRP_TESTIMONIAL_PATH . 'includes/class-mrrp-testimonial-widget.php';
        $widgets_manager->register(new \MRRP_Testimonial_Widget_Class());
    }
    
    /**
     * Register widget category
     */
    public function register_widget_category($elements_manager) {
        $elements_manager->add_category(
            'mrrp-widgets',
            [
                'title' => esc_html__('MRRP Widgets', 'mrrp-testimonial'),
                'icon' => 'fa fa-plug',
            ]
        );
    }
    
    /**
     * Enqueue frontend styles
     */
    public function enqueue_frontend_styles() {
        wp_enqueue_style(
            'mrrp-testimonial-widget',
            MRRP_TESTIMONIAL_URL . 'assets/css/testimonial-widget.css',
            [],
            MRRP_TESTIMONIAL_VERSION
        );
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts() {
        // Swiper JS (CDN)
        wp_register_script(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [],
            '11.0.0',
            true
        );
        
        // Swiper CSS (CDN)
        wp_register_style(
            'swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.0.0'
        );
        
        // Tailwind CSS (CDN) - only load when widget is used
        wp_register_script(
            'tailwindcss',
            'https://cdn.tailwindcss.com',
            [],
            '3.4.0',
            false
        );
        
        // Custom widget script
        wp_register_script(
            'mrrp-testimonial-widget',
            MRRP_TESTIMONIAL_URL . 'assets/js/testimonial-widget.js',
            ['jquery', 'swiper'],
            MRRP_TESTIMONIAL_VERSION,
            true
        );
    }
    
    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('mrrp-testimonial', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

// Initialize the plugin
MRRP_Testimonial_Widget::instance();

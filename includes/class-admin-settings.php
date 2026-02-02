<?php
/**
 * Admin Settings Page
 * 
 * Handles plugin settings and GitHub token management
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRRP_Admin_Settings {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_post_mrrp_check_update', array($this, 'handle_check_update'));
    }
    
    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_options_page(
            'MRRP Testimonial Settings',
            'MRRP Testimonial',
            'manage_options',
            'mrrp-testimonial-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('mrrp_testimonial_settings', 'mrrp_github_token', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Get update info
        $updater = new MRRP_GitHub_Updater(
            MRRP_TESTIMONIAL_FILE,
            'mrrploc94', // Replace with actual GitHub username
            'mrrp-testimonial-widget'
        );
        
        $update_info = $updater->manual_check_update();
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form action="options.php" method="post">
                <?php
                settings_fields('mrrp_testimonial_settings');
                do_settings_sections('mrrp_testimonial_settings');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="mrrp_github_token">GitHub Token</label>
                        </th>
                        <td>
                            <input type="password" 
                                   id="mrrp_github_token" 
                                   name="mrrp_github_token" 
                                   value="<?php echo esc_attr(get_option('mrrp_github_token')); ?>" 
                                   class="regular-text">
                            <p class="description">
                                Enter your GitHub personal access token for automatic updates.
                                Token is stored securely (serialized) and NOT included in code.
                            </p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button('Save Settings'); ?>
            </form>
            
            <hr>
            
            <h2>Update Information</h2>
            <table class="form-table">
                <tr>
                    <th scope="row">Current Version</th>
                    <td><strong><?php echo esc_html(MRRP_TESTIMONIAL_VERSION); ?></strong></td>
                </tr>
                <tr>
                    <th scope="row">Latest Version</th>
                    <td>
                        <strong><?php echo esc_html($update_info['remote_version'] ?: 'Unknown'); ?></strong>
                        <?php if ($update_info['update_available']) : ?>
                            <span style="color: #d63638; margin-left: 10px;">● Update Available</span>
                        <?php else : ?>
                            <span style="color: #00a32a; margin-left: 10px;">● Up to Date</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
            
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="mrrp_check_update">
                <?php wp_nonce_field('mrrp_check_update'); ?>
                <?php submit_button('Check for Updates', 'secondary', 'check_update'); ?>
            </form>
            
            <?php if ($update_info['update_available']) : ?>
                <p>
                    <a href="<?php echo esc_url(admin_url('plugins.php')); ?>" class="button button-primary">
                        Go to Plugins Page to Update
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Handle manual update check
     */
    public function handle_check_update() {
        check_admin_referer('mrrp_check_update');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Force check for updates
        delete_site_transient('update_plugins');
        
        wp_redirect(add_query_arg(
            array('page' => 'mrrp-testimonial-settings', 'updated' => '1'),
            admin_url('options-general.php')
        ));
        exit;
    }
}

// Initialize
new MRRP_Admin_Settings();

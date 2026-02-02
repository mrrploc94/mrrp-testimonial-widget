<?php
/**
 * GitHub Updater Class
 * 
 * Handles automatic updates from GitHub releases
 */

if (!defined('ABSPATH')) {
    exit;
}

class MRRP_GitHub_Updater {
    
    private $github_token;
    private $repo_owner;
    private $repo_name;
    private $plugin_slug;
    private $plugin_file;
    
    public function __construct($plugin_file, $repo_owner, $repo_name) {
        $this->plugin_file = $plugin_file;
        $this->plugin_slug = plugin_basename($plugin_file);
        $this->repo_owner = $repo_owner;
        $this->repo_name = $repo_name;
        
        // Get serialized token from wp_options
        $this->github_token = get_option('mrrp_github_token', '');
        
        // Hooks
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_info'), 10, 3);
    }
    
    /**
     * Check for updates
     */
    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        $remote_version = $this->get_remote_version();
        
        if ($remote_version && version_compare(MRRP_TESTIMONIAL_VERSION, $remote_version, '<')) {
            $plugin_data = array(
                'slug' => $this->plugin_slug,
                'new_version' => $remote_version,
                'url' => "https://github.com/{$this->repo_owner}/{$this->repo_name}",
                'package' => $this->get_download_url($remote_version),
            );
            
            $transient->response[$this->plugin_slug] = (object) $plugin_data;
        }
        
        return $transient;
    }
    
    /**
     * Get remote version from GitHub
     */
    private function get_remote_version() {
        $api_url = "https://api.github.com/repos/{$this->repo_owner}/{$this->repo_name}/releases/latest";
        
        $args = array(
            'headers' => array(
                'Authorization' => 'token ' . $this->github_token,
                'Accept' => 'application/vnd.github.v3+json',
            ),
        );
        
        $response = wp_remote_get($api_url, $args);
        
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response));
        
        if (!empty($body->tag_name)) {
            return ltrim($body->tag_name, 'v');
        }
        
        return false;
    }
    
    /**
     * Get download URL for specific version
     */
    private function get_download_url($version) {
        return "https://github.com/{$this->repo_owner}/{$this->repo_name}/archive/refs/tags/v{$version}.zip";
    }
    
    /**
     * Plugin info for update screen
     */
    public function plugin_info($false, $action, $response) {
        if ($action !== 'plugin_information') {
            return $false;
        }
        
        if ($response->slug !== $this->plugin_slug) {
            return $false;
        }
        
        $remote_version = $this->get_remote_version();
        
        $info = array(
            'name' => 'MRRP Testimonial Widget',
            'slug' => $this->plugin_slug,
            'version' => $remote_version,
            'author' => 'MRRP',
            'homepage' => "https://github.com/{$this->repo_owner}/{$this->repo_name}",
            'download_link' => $this->get_download_url($remote_version),
            'sections' => array(
                'description' => 'A professional Elementor widget for displaying testimonials.',
            ),
        );
        
        return (object) $info;
    }
    
    /**
     * Manual update check
     */
    public function manual_check_update() {
        delete_site_transient('update_plugins');
        $remote_version = $this->get_remote_version();
        
        return array(
            'current_version' => MRRP_TESTIMONIAL_VERSION,
            'remote_version' => $remote_version,
            'update_available' => version_compare(MRRP_TESTIMONIAL_VERSION, $remote_version, '<'),
        );
    }
}

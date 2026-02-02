<?php
/**
 * MRRP Testimonial Widget Class
 *
 * @package MRRP_Testimonial_Widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class MRRP_Testimonial_Widget_Class extends \Elementor\Widget_Base {
    
    /**
     * Get widget name
     */
    public function get_name() {
        return 'mrrp-testimonial';
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return esc_html__('MRRP Testimonial', 'mrrp-testimonial');
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }
    
    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['mrrp-widgets'];
    }
    
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['testimonial', 'review', 'slider', 'carousel', 'mrrp'];
    }
    
    /**
     * Get style dependencies
     */
    public function get_style_depends() {
        return ['swiper', 'mrrp-testimonial-widget'];
    }
    
    /**
     * Get script dependencies
     */
    public function get_script_depends() {
        return ['tailwindcss', 'swiper', 'mrrp-testimonial-widget'];
    }
    
    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Tab - Testimonials
        $this->start_controls_section(
            'section_testimonials',
            [
                'label' => esc_html__('Testimonials', 'mrrp-testimonial'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new \Elementor\Repeater();
        
        $repeater->add_control(
            'testimonial_text',
            [
                'label' => esc_html__('Quote Text', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Starting my business at CoWork has been a game-changer. The supportive community and access to resources have helped me scale my startup quickly. It\'s the ideal place for any entrepreneur.', 'mrrp-testimonial'),
                'placeholder' => esc_html__('Enter testimonial quote...', 'mrrp-testimonial'),
                'rows' => 5,
            ]
        );
        
        $repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('James Gouse', 'mrrp-testimonial'),
                'placeholder' => esc_html__('Enter name...', 'mrrp-testimonial'),
            ]
        );
        
        $repeater->add_control(
            'author_title',
            [
                'label' => esc_html__('Author Title', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Entrepreneur', 'mrrp-testimonial'),
                'placeholder' => esc_html__('Enter job title...', 'mrrp-testimonial'),
            ]
        );
        
        $repeater->add_control(
            'background_image',
            [
                'label' => esc_html__('Background Image', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $repeater->add_control(
            'avatar_image',
            [
                'label' => esc_html__('Avatar Image', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        
        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonial Items', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testimonial_text' => esc_html__('Starting my business at CoWork has been a game-changer. The supportive community and access to resources have helped me scale my startup quickly. It\'s the ideal place for any entrepreneur.', 'mrrp-testimonial'),
                        'author_name' => esc_html__('James Gouse', 'mrrp-testimonial'),
                        'author_title' => esc_html__('Entrepreneur', 'mrrp-testimonial'),
                    ],
                    [
                        'testimonial_text' => esc_html__('The creative environment at CoWork has truly elevated my work. The ergonomic furniture and vibrant community inspire me daily. It\'s the perfect space for any designer looking to thrive.', 'mrrp-testimonial'),
                        'author_name' => esc_html__('Sarah Johnson', 'mrrp-testimonial'),
                        'author_title' => esc_html__('Designer', 'mrrp-testimonial'),
                    ],
                    [
                        'testimonial_text' => esc_html__('I love the professional yet relaxed atmosphere here. The amenities are top-notch, and the networking opportunities are invaluable. CoWork has become an essential part of my business growth.', 'mrrp-testimonial'),
                        'author_name' => esc_html__('Michael Chen', 'mrrp-testimonial'),
                        'author_title' => esc_html__('Developer', 'mrrp-testimonial'),
                    ],
                ],
                'title_field' => '{{{ author_name }}}',
            ]
        );
        
        $this->end_controls_section();
        
        // Content Tab - Slider Settings
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label' => esc_html__('Slider Settings', 'mrrp-testimonial'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrrp-testimonial'),
                'label_off' => esc_html__('No', 'mrrp-testimonial'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay (ms)', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Transition Speed (ms)', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 800,
                'min' => 300,
                'max' => 2000,
                'step' => 100,
            ]
        );
        
        $this->add_control(
            'loop',
            [
                'label' => esc_html__('Loop', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrrp-testimonial'),
                'label_off' => esc_html__('No', 'mrrp-testimonial'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'mrrp-testimonial'),
                'label_off' => esc_html__('No', 'mrrp-testimonial'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Tab - Overlay Box
        $this->start_controls_section(
            'section_overlay_style',
            [
                'label' => esc_html__('Overlay Box', 'mrrp-testimonial'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'overlay_background',
            [
                'label' => esc_html__('Background Color', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.75)',
            ]
        );
        
        $this->add_responsive_control(
            'overlay_width',
            [
                'label' => esc_html__('Width (%)', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 35,
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 45,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 90,
                ],
            ]
        );
        
        $this->add_control(
            'overlay_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'overlay_padding',
            [
                'label' => esc_html__('Padding', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 40,
                    'right' => 40,
                    'bottom' => 40,
                    'left' => 40,
                    'unit' => 'px',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Tab - Quote Text
        $this->start_controls_section(
            'section_quote_style',
            [
                'label' => esc_html__('Quote Text', 'mrrp-testimonial'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Text Color', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .mrrp-testimonial-quote',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Tab - Avatar Navigation
        $this->start_controls_section(
            'section_avatar_style',
            [
                'label' => esc_html__('Avatar Navigation', 'mrrp-testimonial'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'avatar_size',
            [
                'label' => esc_html__('Avatar Size', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 48,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'size' => 36,
                ],
            ]
        );
        
        $this->add_control(
            'avatar_active_color',
            [
                'label' => esc_html__('Active Border Color', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4CAF50',
            ]
        );
        
        $this->add_control(
            'avatar_border_width',
            [
                'label' => esc_html__('Active Border Width', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 3,
                ],
            ]
        );
        
        $this->add_control(
            'avatar_inactive_opacity',
            [
                'label' => esc_html__('Inactive Opacity', 'mrrp-testimonial'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.7,
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $testimonials = $settings['testimonials'];
        
        if (empty($testimonials)) {
            return;
        }
        
        $widget_id = $this->get_id();
        
        // Slider configuration
        $slider_config = [
            'autoplay' => $settings['autoplay'] === 'yes' ? [
                'delay' => $settings['autoplay_delay'],
                'disableOnInteraction' => false,
                'pauseOnMouseEnter' => $settings['pause_on_hover'] === 'yes',
            ] : false,
            'speed' => $settings['speed'],
            'loop' => $settings['loop'] === 'yes',
            'effect' => 'slide',
            'navigation' => false,
            'pagination' => false,
        ];
        
        ?>
        <div class="mrrp-testimonial-slider-wrapper" data-widget-id="<?php echo esc_attr($widget_id); ?>">
            <div class="swiper mrrp-testimonial-swiper" data-config='<?php echo esc_attr(json_encode($slider_config)); ?>'>
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $index => $item) : ?>
                        <div class="swiper-slide">
                            <?php $this->render_slide($item, $index, $settings); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Avatar Navigation -->
            <div class="mrrp-avatar-navigation">
                <?php foreach ($testimonials as $index => $item) : ?>
                    <div class="mrrp-avatar-item <?php echo $index === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo esc_attr($index); ?>">
                        <div class="mrrp-avatar-image">
                            <?php if (!empty($item['avatar_image']['url'])) : ?>
                                <img src="<?php echo esc_url($item['avatar_image']['url']); ?>" alt="<?php echo esc_attr($item['author_name']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="mrrp-avatar-name"><?php echo esc_html($item['author_name']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render individual slide
     */
    private function render_slide($item, $index, $settings) {
        ?>
        <div class="mrrp-testimonial-slide">
            <!-- Background Image -->
            <?php if (!empty($item['background_image']['url'])) : ?>
                <div class="mrrp-slide-background" style="background-image: url('<?php echo esc_url($item['background_image']['url']); ?>');"></div>
            <?php endif; ?>
            
            <!-- Overlay Box -->
            <div class="mrrp-overlay-box">
                <div class="mrrp-testimonial-content">
                    <div class="mrrp-testimonial-quote">
                        "<?php echo esc_html($item['testimonial_text']); ?>"
                    </div>
                    <div class="mrrp-testimonial-author">
                        <div class="mrrp-author-name"><?php echo esc_html($item['author_name']); ?></div>
                        <?php if (!empty($item['author_title'])) : ?>
                            <div class="mrrp-author-title"><?php echo esc_html($item['author_title']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

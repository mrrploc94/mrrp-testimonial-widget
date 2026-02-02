/**
 * MRRP Testimonial Widget JavaScript
 * 
 * Handles Swiper initialization and avatar navigation
 */

(function ($) {
    'use strict';

    /**
     * Initialize testimonial sliders
     */
    function initTestimonialSliders() {
        $('.mrrp-testimonial-swiper').each(function () {
            const $slider = $(this);
            const $wrapper = $slider.closest('.mrrp-testimonial-slider-wrapper');
            const widgetId = $wrapper.data('widget-id');
            const config = $slider.data('config');

            // Skip if already initialized
            if ($slider.hasClass('swiper-initialized')) {
                return;
            }

            // Initialize Swiper
            const swiper = new Swiper($slider[0], {
                ...config,
                on: {
                    init: function () {
                        updateActiveAvatar(0, $wrapper);
                    },
                    slideChange: function () {
                        updateActiveAvatar(this.realIndex, $wrapper);
                    }
                }
            });

            // Store swiper instance
            $slider.data('swiper', swiper);

            // Avatar click navigation
            $wrapper.find('.mrrp-avatar-item').on('click', function () {
                const slideIndex = $(this).data('slide-index');
                swiper.slideTo(slideIndex);
            });

            // Pause on hover (if enabled)
            if (config.autoplay && config.autoplay.pauseOnMouseEnter) {
                $wrapper.on('mouseenter', function () {
                    if (swiper.autoplay && swiper.autoplay.running) {
                        swiper.autoplay.stop();
                    }
                });

                $wrapper.on('mouseleave', function () {
                    if (swiper.autoplay && !swiper.autoplay.running) {
                        swiper.autoplay.start();
                    }
                });
            }
        });
    }

    /**
     * Update active avatar
     */
    function updateActiveAvatar(index, $wrapper) {
        $wrapper.find('.mrrp-avatar-item').removeClass('active');
        $wrapper.find('.mrrp-avatar-item').eq(index).addClass('active');
    }

    /**
     * Initialize on document ready
     */
    $(document).ready(function () {
        initTestimonialSliders();
    });

    /**
     * Re-initialize on Elementor preview
     */
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/mrrp-testimonial.default', function ($scope) {
            initTestimonialSliders();
        });
    });

})(jQuery);

/**
 * MRRP Testimonial Widget JavaScript
 * 
 * Handles Swiper initialization, avatar navigation, and progress bars
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
            const $progressBars = $wrapper.find('.mrrp-progress-bar');

            // Skip if already initialized
            if ($slider.hasClass('swiper-initialized')) {
                return;
            }

            // Store progress duration for animations
            const progressDuration = config.autoplay ? config.autoplay.delay : 5000;
            $wrapper[0].style.setProperty('--progress-duration', progressDuration + 'ms');

            // Initialize Swiper
            const swiper = new Swiper($slider[0], {
                ...config,
                on: {
                    init: function () {
                        updateActiveStates(0, $wrapper);
                        if (config.autoplay) {
                            startProgressAnimation(0, $wrapper, progressDuration);
                        }
                    },
                    slideChange: function () {
                        const index = this.realIndex;
                        updateActiveStates(index, $wrapper);
                        if (config.autoplay) {
                            startProgressAnimation(index, $wrapper, progressDuration);
                        }
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

            // Progress bar click navigation
            $progressBars.on('click', function () {
                const slideIndex = $(this).data('slide-index');
                swiper.slideTo(slideIndex);
            });

            // Pause on hover (if enabled)
            if (config.autoplay && config.autoplay.pauseOnMouseEnter) {
                $wrapper.on('mouseenter', function () {
                    if (swiper.autoplay && swiper.autoplay.running) {
                        swiper.autoplay.stop();
                        pauseProgressAnimation($wrapper);
                    }
                });

                $wrapper.on('mouseleave', function () {
                    if (swiper.autoplay && !swiper.autoplay.running) {
                        swiper.autoplay.start();
                        resumeProgressAnimation($wrapper, progressDuration);
                    }
                });
            }
        });
    }

    /**
     * Update active states for both avatars and progress bars
     */
    function updateActiveStates(index, $wrapper) {
        // Update avatar active state
        $wrapper.find('.mrrp-avatar-item').removeClass('active');
        $wrapper.find('.mrrp-avatar-item').eq(index).addClass('active');

        // Update progress bar states
        $wrapper.find('.mrrp-progress-bar').each(function (i) {
            const $bar = $(this);
            if (i < index) {
                // Completed bars
                $bar.removeClass('active').addClass('completed');
            } else if (i === index) {
                // Active bar
                $bar.removeClass('completed').addClass('active');
            } else {
                // Upcoming bars
                $bar.removeClass('active completed');
                $bar.find('.mrrp-progress-fill').css('width', '0%');
            }
        });
    }

    /**
     * Start progress bar animation
     */
    function startProgressAnimation(index, $wrapper, duration) {
        const $activeBar = $wrapper.find('.mrrp-progress-bar').eq(index);
        const $fill = $activeBar.find('.mrrp-progress-fill');

        // Reset animation
        $fill.css({
            'width': '0%',
            'transition': 'none'
        });

        // Force reflow to restart animation
        $fill[0].offsetHeight;

        // Start animation with slight delay
        setTimeout(() => {
            $fill.css({
                'width': '100%',
                'transition': `width ${duration}ms linear`
            });
        }, 50);
    }

    /**
     * Pause progress animation (on hover)
     */
    function pauseProgressAnimation($wrapper) {
        const $activeFill = $wrapper.find('.mrrp-progress-bar.active .mrrp-progress-fill');

        if ($activeFill.length === 0) return;

        // Get current width
        const currentWidth = $activeFill[0].getBoundingClientRect().width;
        const parentWidth = $activeFill.parent()[0].getBoundingClientRect().width;
        const percentage = (currentWidth / parentWidth) * 100;

        // Store current percentage
        $activeFill.data('paused-at', percentage);

        // Stop animation
        $activeFill.css({
            'width': percentage + '%',
            'transition': 'none'
        });
    }

    /**
     * Resume progress animation (on mouse leave)
     */
    function resumeProgressAnimation($wrapper, totalDuration) {
        const $activeFill = $wrapper.find('.mrrp-progress-bar.active .mrrp-progress-fill');

        if ($activeFill.length === 0) return;

        // Get paused percentage
        const pausedAt = $activeFill.data('paused-at') || 0;
        const remainingPercentage = 100 - pausedAt;
        const remainingDuration = (remainingPercentage / 100) * totalDuration;

        // Resume animation
        $activeFill.css({
            'width': '100%',
            'transition': `width ${remainingDuration}ms linear`
        });
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

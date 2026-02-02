/**
 * MRRP Testimonial Widget JavaScript
 * 
 * Handles Swiper initialization, avatar navigation with expansion, and progress bars inside slides
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

            // Store progress duration for animations
            const progressDuration = config.autoplay ? config.autoplay.delay : 5000;

            // Initialize Swiper
            const swiper = new Swiper($slider[0], {
                ...config,
                on: {
                    init: function () {
                        updateAvatarStates(0, $wrapper);
                        if (config.autoplay) {
                            startSlideProgress(0, $slider, progressDuration);
                        }
                    },
                    slideChange: function () {
                        const index = this.realIndex;
                        updateAvatarStates(index, $wrapper);
                        if (config.autoplay) {
                            // Reset all progress bars
                            $slider.find('.mrrp-slide-progress-fill').css({
                                'width': '0%',
                                'transition': 'none'
                            });
                            // Start new slide's progress
                            startSlideProgress(index, $slider, progressDuration);
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

            // Pause on hover (if enabled)
            if (config.autoplay && config.autoplay.pauseOnMouseEnter) {
                $wrapper.on('mouseenter', function () {
                    if (swiper.autoplay && swiper.autoplay.running) {
                        swiper.autoplay.stop();
                        pauseSlideProgress($slider);
                    }
                });

                $wrapper.on('mouseleave', function () {
                    if (swiper.autoplay && !swiper.autoplay.running) {
                        swiper.autoplay.start();
                        resumeSlideProgress($slider, progressDuration);
                    }
                });
            }
        });
    }

    /**
     * Update avatar active states (with expansion animation)
     */
    function updateAvatarStates(index, $wrapper) {
        $wrapper.find('.mrrp-avatar-item').each(function (i) {
            if (i === index) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        });
    }

    /**
     * Start progress bar animation for specific slide
     */
    function startSlideProgress(slideIndex, $slider, duration) {
        const $slide = $slider.find('.swiper-slide').eq(slideIndex);
        const $progress = $slide.find('.mrrp-slide-progress-fill');

        if ($progress.length === 0) return;

        // Reset
        $progress.css({
            'width': '0%',
            'transition': 'none'
        });

        // Force reflow
        $progress[0].offsetHeight;

        // Start animation with slight delay
        setTimeout(() => {
            $progress.css({
                'width': '100%',
                'transition': `width ${duration}ms linear`
            });
        }, 50);
    }

    /**
     * Pause progress animation (on hover)
     */
    function pauseSlideProgress($slider) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $progress = $activeSlide.find('.mrrp-slide-progress-fill');

        if ($progress.length === 0) return;

        // Get current width
        const currentWidth = $progress[0].getBoundingClientRect().width;
        const parentWidth = $progress.parent()[0].getBoundingClientRect().width;
        const percentage = (currentWidth / parentWidth) * 100;

        // Store paused position
        $progress.data('paused-at', percentage);

        // Stop animation
        $progress.css({
            'width': percentage + '%',
            'transition': 'none'
        });
    }

    /**
     * Resume progress animation (on mouse leave)
     */
    function resumeSlideProgress($slider, totalDuration) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $progress = $activeSlide.find('.mrrp-slide-progress-fill');

        if ($progress.length === 0) return;

        // Get paused percentage
        const pausedAt = $progress.data('paused-at') || 0;
        const remainingPercentage = 100 - pausedAt;
        const remainingDuration = (remainingPercentage / 100) * totalDuration;

        // Resume animation
        $progress.css({
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

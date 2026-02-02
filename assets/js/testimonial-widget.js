/**
 * MRRP Testimonial Widget JavaScript
 * 
 * Handles Swiper initialization, avatar navigation with expansion, and segmented progress bars inside slides
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
                        console.log('Swiper initialized, initial index:', this.realIndex);
                        updateAvatarStates(0, $wrapper);
                        updateProgressSegments(0, $slider, progressDuration);
                    },
                    slideChange: function () {
                        const index = this.realIndex;
                        console.log('Slide changed to index:', index);
                        updateAvatarStates(index, $wrapper);
                        updateProgressSegments(index, $slider, progressDuration);
                    }
                }
            });

            // Store swiper instance
            $slider.data('swiper', swiper);

            // Avatar click navigation
            $wrapper.find('.mrrp-avatar-item').on('click', function () {
                const slideIndex = parseInt($(this).data('slide-index'));
                console.log('Avatar clicked, navigating to index:', slideIndex);

                // Immediate visual feedback
                updateAvatarStates(slideIndex, $wrapper);

                // Navigate
                swiper.slideTo(slideIndex);
            });

            // Progress segment click navigation (delegate to wrapper since segments are inside slides)
            $wrapper.on('click', '.mrrp-progress-segment', function () {
                const segmentIndex = parseInt($(this).data('segment-index'));
                console.log('Segment clicked, navigating to index:', segmentIndex);

                // Navigate
                swiper.slideTo(segmentIndex);
            });

            // Pause on hover (if enabled)
            if (config.autoplay && config.autoplay.pauseOnMouseEnter) {
                $wrapper.on('mouseenter', function () {
                    if (swiper.autoplay && swiper.autoplay.running) {
                        swiper.autoplay.stop();
                        pauseProgressSegment($slider);
                    }
                });

                $wrapper.on('mouseleave', function () {
                    if (swiper.autoplay && !swiper.autoplay.running) {
                        swiper.autoplay.start();
                        resumeProgressSegment($slider, progressDuration);
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
     * Update progress segments in active slide
     */
    function updateProgressSegments(activeIndex, $slider, duration) {
        // Get active slide
        const $activeSlide = $slider.find('.swiper-slide').eq(activeIndex);

        // Update segments in active slide
        $activeSlide.find('.mrrp-progress-segment').each(function (index) {
            const $segment = $(this);
            const $fill = $segment.find('.mrrp-progress-segment-fill');

            if (index < activeIndex) {
                // Completed segments
                $segment.removeClass('active').addClass('completed');
                $fill.css({
                    'width': '100%',
                    'transition': 'width 0.3s ease'
                });
            } else if (index === activeIndex) {
                // Active segment - animate
                $segment.removeClass('completed').addClass('active');
                animateSegment($fill, duration);
            } else {
                // Future segments
                $segment.removeClass('active completed');
                $fill.css({
                    'width': '0%',
                    'transition': 'width 0.3s ease'
                });
            }
        });
    }

    /**
     * Animate a single progress segment
     */
    function animateSegment($fill, duration) {
        // Reset
        $fill.css({
            'width': '0%',
            'transition': 'none'
        });

        // Force reflow
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
     * Pause progress segment animation (on hover)
     */
    function pauseProgressSegment($slider) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $activeSegment = $activeSlide.find('.mrrp-progress-segment.active');
        const $fill = $activeSegment.find('.mrrp-progress-segment-fill');

        if ($fill.length === 0) return;

        // Get current width
        const currentWidth = $fill[0].getBoundingClientRect().width;
        const parentWidth = $fill.parent()[0].getBoundingClientRect().width;
        const percentage = (currentWidth / parentWidth) * 100;

        // Store paused position
        $fill.data('paused-at', percentage);

        // Stop animation
        $fill.css({
            'width': percentage + '%',
            'transition': 'none'
        });
    }

    /**
     * Resume progress segment animation (on mouse leave)
     */
    function resumeProgressSegment($slider, totalDuration) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $activeSegment = $activeSlide.find('.mrrp-progress-segment.active');
        const $fill = $activeSegment.find('.mrrp-progress-segment-fill');

        if ($fill.length === 0) return;

        // Get paused percentage
        const pausedAt = $fill.data('paused-at') || 0;
        const remainingPercentage = 100 - pausedAt;
        const remainingDuration = (remainingPercentage / 100) * totalDuration;

        // Resume animation
        $fill.css({
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

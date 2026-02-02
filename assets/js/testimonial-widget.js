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

            // Set CSS custom property for progress bar animation
            $wrapper[0].style.setProperty('--autoplay-delay', `${progressDuration}ms`);

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

            // Progress segment click navigation (segments are inside slides now)
            $slider.on('click', '.mrrp-progress-segment', function (e) {
                e.stopPropagation(); // Prevent slide click
                const segmentIndex = parseInt($(this).data('segment-index'));
                console.log('✅ Progress segment clicked, navigating to index:', segmentIndex);

                // Immediate visual feedback
                updateAvatarStates(segmentIndex, $wrapper);

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
     * Update progress segments across ALL slides using Web Animations API
     */
    function updateProgressSegments(activeIndex, $slider, duration) {
        // Find ALL progress segments across ALL slides
        const $allSegments = $slider.find('.mrrp-progress-segment');

        // Reset ALL segments first
        $allSegments.each(function () {
            const $segment = $(this);
            const $fill = $segment.find('.mrrp-progress-segment-fill');
            const fillElement = $fill[0];

            $segment.removeClass('active completed');

            // Cancel existing animations
            if (fillElement) {
                fillElement.getAnimations().forEach(anim => anim.cancel());
            }

            // Reset to 0%
            $fill.css('width', '0%');
        });

        // Then animate ONLY segments matching activeIndex across ALL slides
        $slider.find(`.mrrp-progress-segment[data-segment-index="${activeIndex}"]`).each(function () {
            const $segment = $(this);
            const $fill = $segment.find('.mrrp-progress-segment-fill');
            const fillElement = $fill[0];

            $segment.addClass('active');

            // Animate using Web Animations API
            const animation = fillElement.animate([
                { width: '0%' },
                { width: '100%' }
            ], {
                duration: duration,
                easing: 'linear',
                fill: 'forwards'
            });

            console.log('✅ Web Animations API started for segment', activeIndex);

            // When animation completes, mark as completed
            animation.onfinish = () => {
                $segment.removeClass('active').addClass('completed');
            };
        });
    }

    /**
     * Pause progress segment animation (on hover)
     */
    function pauseProgressSegment($slider) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $activeSegment = $activeSlide.find('.mrrp-progress-segment.active');
        const $fill = $activeSegment.find('.mrrp-progress-segment-fill');

        if ($fill.length === 0) return;

        const fillElement = $fill[0];
        const animations = fillElement.getAnimations();

        if (animations.length > 0) {
            animations.forEach(anim => anim.pause());
        }
    }

    /**
     * Resume progress segment animation (on mouse leave)
     */
    function resumeProgressSegment($slider, totalDuration) {
        const $activeSlide = $slider.find('.swiper-slide-active');
        const $activeSegment = $activeSlide.find('.mrrp-progress-segment.active');
        const $fill = $activeSegment.find('.mrrp-progress-segment-fill');

        if ($fill.length === 0) return;

        const fillElement = $fill[0];
        const animations = fillElement.getAnimations();

        if (animations.length > 0) {
            animations.forEach(anim => anim.play());
        }
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

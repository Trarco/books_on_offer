define(['jquery'], function ($) {
    return {
        init: function () {
            $('.carousel-wrapper').each(function () {
                const $wrapper = $(this);
                const $carousel = $wrapper.find('.books-carousel');
                const $cards = $carousel.find('.book-card');
                const $btnPrev = $wrapper.find('.carousel-prev');
                const $btnNext = $wrapper.find('.carousel-next');

                if ($cards.length === 0) return;

                const cardWidth = $cards.outerWidth(true); // include margin
                let currentIndex = 0;
                const maxIndex = $cards.length - 4;

                $btnNext.on('click', function () {
                    currentIndex++;
                    if (currentIndex > maxIndex) currentIndex = 0;
                    $carousel.animate({ scrollLeft: cardWidth * currentIndex }, 300);
                });

                $btnPrev.on('click', function () {
                    currentIndex--;
                    if (currentIndex < 0) currentIndex = maxIndex;
                    $carousel.animate({ scrollLeft: cardWidth * currentIndex }, 300);
                });
            });
        }
    };
});

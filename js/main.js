const loader = '<div class="text-center h2 mb-0 loader"><i class="fa-solid fa-spinner fa-spin"></i></div>';

/**
 * Convert a string with key-pair values to an object
 * @param {String} string 
 */
function stringToObject(string) {
    var obj = {};
    string.split('&').forEach(function(pair) {
        let index = pair.indexOf('=');
        let name = decodeURIComponent(pair.substring(0, index)); // name
        let value = decodeURIComponent(pair.substring(index + 1)).replaceAll('+', ' '); // value

        obj[name] = value;
    });

    return obj;
}

/**
 * Check if an element is within viewport
 * @param {HTMLElement} e 
 */
function isInViewport(e) {
    var elementTop = $(e).offset().top;
    var elementBottom = elementTop + $(e).outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
}

$(document).ready(function() {
    $('.gallery').on('click', '.img-view', function(e) {
        e.preventDefault();
        var images = $('.gallery .img-item');

        let i = images.index($(this).closest('.img-item'));
        console.log(i, images);
        $(`.image-modal [data-slide-to="${i}"]`).click();
        $('.image-modal').modal('show');
    });

    $('body').on('click', '.media-btn', function() {
        $(this).parent().find('video').attr('controls', 'controls');
        $(this).remove();
    })
});

/**
 * Load an image gallery from database
 */
function loadImageGallery(data, url) {
    $.post(url, data, function(d, s) {
        try {
            if (s !== 'success') throw new Exception('image gallery load failed; status: ' + s);

            if (d === 'false') throw new Exception('image gallery load failed; unknown error');

            $('.gallery .loader').replaceWith(d);

            var length = $('.image-modal .carousel-indicators>li').length;

            const target = $('.image-modal .carousel').attr('id');

            $(d).find('.img-item').each(function() {
                var $this = $(this);
                var img = $this.find('.bg-img img, .bg-img video').clone().removeClass('sr-only');
                if (img.is('video')) img.attr('controls', 'true');
                var text = $this.find('.img-text').text();

                $('.image-modal .carousel-indicators').append(`<li data-target="#${target}" class="d-flex justify-content-center align-items-center" data-slide-to="${length}"></li>`);
                $('.image-modal .carousel-inner').append(`<div class="carousel-item">${img.prop('outerHTML')}<div class="carousel-caption heading font-weight-light pb-0 position-relative text-center text-white font-sm">${text}</div></div>`);
                length++;
            });

            $('.image-modal .carousel-indicators>li').removeClass('active').first().addClass('active');
            $('.image-modal .carousel-item').removeClass('active').first().addClass('active');
        } catch (error) {
            console.error(error);
        }
    })
}
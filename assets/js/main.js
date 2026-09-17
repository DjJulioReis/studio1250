/* assets/js/main.js - Studio 1250 Main Frontend JS */

$(document).ready(function() {

    // Initialize Main Banner Slick Slider
    if ($('.hero-slider').length) {
        $('.hero-slider').slick({
            dots: true,
            infinite: true,
            speed: 600,
            fade: true,
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: true,
            adaptiveHeight: true
        });
    }

    // Initialize Videos Slider
    if ($('.video-slider').length) {
        $('.video-slider').slick({
            dots: false,
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 4000,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    // Fancybox initialization
    if (typeof Fancybox !== "undefined") {
        Fancybox.bind("[data-fancybox]", {
            // Options
        });
    }

    // Global Audio Mini Player Logic
    var audio = document.getElementById('globalAudioPlayer');
    var isPlaying = false;

    $('#btnMiniPlay').on('click', function() {
        if (!audio) return;

        if (isPlaying) {
            audio.pause();
            $(this).html('<i class="fa-solid fa-play"></i>');
            isPlaying = false;
        } else {
            audio.play().then(function() {
                $('#btnMiniPlay').html('<i class="fa-solid fa-pause"></i>');
                isPlaying = true;
            }).catch(function(err) {
                console.log("Play blocked by browser:", err);
            });
        }
    });

    if (audio) {
        audio.ontimeupdate = function() {
            if (audio.duration) {
                var pct = (audio.currentTime / audio.duration) * 100;
                $('#miniPlayerProgressBar').css('width', pct + '%');
            }
        };

        audio.onended = function() {
            isPlaying = false;
            $('#btnMiniPlay').html('<i class="fa-solid fa-play"></i>');
            $('#miniPlayerProgressBar').css('width', '0%');
        };

        $('#miniPlayerProgress').on('click', function(e) {
            var offset = $(this).offset();
            var relX = e.pageX - offset.left;
            var width = $(this).width();
            if (audio.duration) {
                audio.currentTime = (relX / width) * audio.duration;
            }
        });
    }

    // Track play button in music page
    $('.btn-play-track').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        var title = $(this).data('title');
        var artist = $(this).data('artist');

        if (audio) {
            audio.src = url;
            $('#miniPlayerTitle').text(title);
            $('#miniPlayerArtist').text(artist || 'Studio 1250 Track');
            audio.play().then(function() {
                $('#btnMiniPlay').html('<i class="fa-solid fa-pause"></i>');
                isPlaying = true;
            });
        }
    });

    // Smooth Scroll to Top
    $('.back-to-top').on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    // AJAX Contact Form Submit
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var alertBox = $('#contactAlert');

        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>Enviando...');

        $.ajax({
            url: '/ajax_contact.php',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i>Enviar Mensagem');
                if (response.success) {
                    alertBox.removeClass('d-none alert-danger').addClass('alert-success').html('<i class="fa-solid fa-check-circle me-2"></i>' + response.message);
                    form[0].reset();
                } else {
                    alertBox.removeClass('d-none alert-success').addClass('alert-danger').html('<i class="fa-solid fa-circle-exclamation me-2"></i>' + response.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-2"></i>Enviar Mensagem');
                alertBox.removeClass('d-none alert-success').addClass('alert-danger').html('<i class="fa-solid fa-triangle-exclamation me-2"></i>Erro ao enviar mensagem. Tente novamente.');
            }
        });
    });

});

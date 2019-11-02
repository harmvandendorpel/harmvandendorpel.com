$(document).ready(function () {
    $('.floating-logo').each(function () {
        var $logo = $(this);
        var width = Math.max(300, $(window).width() - 200);

        var left = Math.max(
            0,
            (width - $logo.width()) * Math.random()
        );

        var top = Math.random() * Math.max(0, $(window).height() - 200) + 50;

        console.log(left);
        $logo.css({
            left: left,
            top:  top
        });

        setTimeout(function() {
            $logo.fadeIn(3000);
        }, Math.random() * 5000 + 100);
    });

    $('*[data-link]').each(function () {
        $(this).bind('click touch', function () {
            location.href = $(this).data().link;
        });
    });

    var mailinglistFormVisible = false;
    var $form = $('.mailing-list');

    $('.btn-mailinglist').bind('touchstart mousedown', function (e) {
        showForm(e);
    });

    $('.btn-close').bind('touchstart mousedown', function (e) {
        hideForm(e);
    });

    function showForm(e) {
        $('.to-blur').addClass('blurry');
        console.log('xxx')
        $form.addClass('visible');
        mailinglistFormVisible = true;
        if (e) {
            e.preventDefault();
            return false;
        }
    }

    function hideForm(e) {
        if (mailinglistFormVisible) {
            $form.removeClass('visible');
            $('.to-blur').removeClass('blurry');
            mailinglistFormVisible = false;
            if (e) {
                e.preventDefault();
                return false;
            }
        }
    }

    if(window.location.hash) {
        var hash = window.location.hash.substring(1);
        if (hash === 'subscribe') {
            showForm();
        }
    }
});

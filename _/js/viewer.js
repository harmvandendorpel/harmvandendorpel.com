String.prototype.replaceAll = function(search, replace) {
    if (replace === undefined) {
        return this.toString();
    }
    return this.split(search).join(replace);
}

function GalleryViewer(options) {
    var clickEvent = 'ontouchend' in window ? 'touchend':'click',
        currentIndex = options.index,
        $prevImg = null,
        $img = null,
        $caption = null,
        $prevCaption = null,
        mouseMovedTimeoutId = null,
        scaleStyle = null,
        busy = false;

    this.showPic = function (currentIndex) {
        if (busy) return;
        var currentImage = options.imageData.images[currentIndex],
            fullFilename= '/img/slideshows/' + options.imageData.path + currentImage.filename,
            caption = currentImage.caption,
            that = this;

        $img = $('<img>').addClass('image');

        if (!caption || caption == '') {
            $caption = null;
        } else {
            caption = caption.replaceAll(' x ', ' &times; ')
            $caption = $('<div>').addClass('caption').html(caption);
        }

        if ($prevImg) {
            $prevImg.fadeOut(function () {
                $(this).remove();
            });
        }

        if ($prevCaption) {
            $prevCaption.fadeOut(function () {
                $(this).remove();
            });
        }

        scaleStyle = currentImage.scale;

        busy = true;
        this.$node.addClass('loading');
        $img.load(function () {
            that.$node.find('.images').append($img).append($caption);
            $(this).fadeIn();
            if ($caption) {
                $caption.fadeIn(function () {
                    that.mouseMovedHandler();
                });
            }
            that.scalePositionImg();
            $prevImg = $img;
            $prevCaption = $caption;
            busy = false;
            that.$node.removeClass('loading');
        });

        $img.prop('src', fullFilename);
    };


    this.viewPort = function () {
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0),
            h = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);

        return [w, h];
    }

    this.scalePositionImg = function () {
        var vp = this.viewPort(),
            w = vp[0],
            h = vp[1],
            iw = $img.width(),
            ih = $img.height(),
            ratio = w / h,
            iratio = iw / ih,
            niw = null,
            nih = null;

        niw = w;
        nih = w / iratio;

        if(scaleStyle == 'cover') {
            if (nih < h) {
                nih = h;
                niw = h * iratio;
            }
        } else {
            if (nih > h) {
                nih = h;
                niw = h * iratio;
            }
        }

        $img.css({
            width: niw,
            height: nih,
            left: (w - niw) / 2,
            top: (h - nih) / 2
        });

        if ($caption) {
            $caption.css({
                left: (w - $caption.outerWidth()) / 2
            });
        }
    };

    this.prevImg = function () {
        currentIndex--;

        if (currentIndex < 0) {
            currentIndex = options.imageData.images.length-1;
        }
        this.showPic(currentIndex);
    };

    this.nextImg = function () {
        currentIndex++;

        if (currentIndex >= options.imageData.images.length) {
            currentIndex = 0;
        }
        this.showPic(currentIndex);
    };

    this.closeViewer = function () {
        var that = this;
          $(window).unbind('.viewer');
        $(document).unbind('.viewer');

        $('body').removeClass('no-scroll');
        this.$node.fadeOut(function () {
            that.$node.remove();
        });
    };

    this.mouseMovedHandler = function () {
        if (mouseMovedTimeoutId) {
            clearTimeout(mouseMovedTimeoutId);
        }

        if ($caption) {
            $caption.show();
        }

        mouseMovedTimeoutId = setTimeout(function () {
            console.log('hide caption');
            if ($caption) {
                $caption.fadeOut();
            }
        }, 2500);
    };

    this.initEvents = function () {
        var that = this;
        $('body').keydown(function(e) {
            switch (e.keyCode) {
                case 27:
                    that.closeViewer();
                    break;

                case 37:
                    that.prevImg();
                    break;

                case 39:
                    that.nextImg();
                    break;
            }
        });

        this.$node.bind(clickEvent, function () {
            that.nextImg();
        });

        $(window).bind('resize.viewer', function () {
           that.scalePositionImg();
        });

        $(window).bind('mousemove.viewer', function () {
            that.mouseMovedHandler();
        });

        $(document).bind('touchmove.viewer', function(e) {
            e.preventDefault();
        });

        $('.btn-close-viewer').bind(clickEvent, function (e) {
            that.closeViewer();
            e.preventDefault();
            return false;
        });
    };

    this.init = function () {
        $('body').addClass('no-scroll');
        this.$node = $('<div><div class="images"></div></div>').addClass("viewer");
        $('body').append(this.$node);
        var $btnClose = $('<div>&times;</div>').addClass('btn-close-viewer noselect');

        this.$node.append($btnClose);
        this.$node.fadeIn();

        this.initEvents();
        this.showPic(currentIndex);
    }

    this.init();
}
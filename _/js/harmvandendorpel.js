(function () {
    var STORAGE_KEY = 'harmvandendorpel-history';

    var editmode = localStorage.getItem('edit') == '1' ? true: false,
        clickEvent = 'ontouchend' in window ? 'touchend' : 'click';


    window.pushHistory = function (url, caption, type) {
        return;
        var history = getHistory();
        var lastState = history[history.length - 1];
        var newState = {url:url, caption:caption, type:type};

        if (JSON.stringify(lastState) == JSON.stringify(newState)) {
            console.log('page refresh: do not add to history');
            return;
        }

        history.push(newState);

        if (history.length > 1) {
            console.log('remove one ' + history.length);
            history.shift();
        }
        localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    }

    window.popHistory = function () {
        return;
        var history = getHistory();

        if (history.length) {
            history.pop();
        }
        localStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    };

    window.previousHistory = function () {
        return;
        var history = getHistory();

        if (history.length > 0) {
            var last = history[history.length - 1];
        } else {
            var last = {
                url: '/',
                caption: 'Index'
            };
        }
        return last;
    }

    window.clearHistory = function () {
        localStorage.setItem(STORAGE_KEY, '');
    }

    this.init = function () {
        var initialHeightLastElement = null;

        function setDimensionLastElement() {
            if (!initialHeightLastElement) {
                initialHeightLastElement = $('.main-category:last-child').height()
            }

            $('.main-category:last-child').height(
                $(window).height() - initialHeightLastElement
            );
        }

        $(document).ready(setDimensionLastElement);
        $(window).resize(setDimensionLastElement);

        //(function (i, s, o, g, r, a, m) {
        //    i['GoogleAnalyticsObject'] = r;
        //    i[r] = i[r] || function () {
        //        (i[r].q = i[r].q || []).push(arguments)
        //    }, i[r].l = 1 * new Date();
        //    a = s.createElement(o),
        //        m = s.getElementsByTagName(o)[0];
        //    a.async = 1;
        //    a.src = g;
        //    m.parentNode.insertBefore(a, m)
        //})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
        //
        //ga('create', 'UA-54420633-2', 'auto');
        //ga('send', 'pageview');

        this.initRouter();

        if (editmode) {
            this.initDragging();
        } else {
            this.initGalleryViewer();
        }

        //$('.back-button').bind('click touch', function (e) {
        //    e.preventDefault();
        //    var lastHistoryState = window.popHistory();
        //    location.href = lastHistoryState.url;
        //    return false;
        //});
        var previousHistory = window.previousHistory();

        //$('.back-button')
        //    .attr('href', previousHistory.url)
        //    //.text(previousHistory.type == 'post' ? 'back' : previousHistory.caption)
        //    .bind('click touch', function (e) {
        //        e.preventDefault();
        //        window.popHistory();
        //        location.href = previousHistory.url;
        //        return false;
        //    });
    };

    this.initGalleryViewer = function () {
        $('.gallery-launcher').each(function (index, el) {
            var $el = $(el);

            $el.bind('click', function (e) {
                if (dragging) {
                    e.preventDefault();
                    return false;
                }

                var slideshowId = $el.data().galleryId,
                    index = $el.data().index,
                    galleryViewer = new GalleryViewer({
                        imageData:window.data['slideshows'][slideshowId],
                        selector: '.viewer',
                        index: index
                    });

                e.preventDefault();
                return false;
            });
        });
    };

    var dragging = false,
        startPos = null,
        clickPos = null,
        screenStart = null;

    this.initDragging = function () {

        $('.thumb').mousedown(function (e) {
            if (dragging) return;
            var $el = $(e.currentTarget);
            dragging = true;

            startPos = $el.position();
            clickPos = {
                left: e.offsetX,
                top: e.offsetY
            };

            screenStart = {
                left: e.screenX,
                top: e.screenY
            }
        });

        $('.thumb').mousemove(function (e) {

            if (dragging) {
                dragging = true;
                var $el = $(e.currentTarget);
                console.log(e);


                var newPos = [
                    e.screenX - screenStart.left + startPos.left,
                    e.screenY - screenStart.top  + startPos.top
                ];

                $el.css({
                    left: newPos[0],
                    top:  newPos[1]
                });
            }
        });

        $('.thumb').mouseup(function (e) {
            dragging = false;
            e.preventDefault();
            return false;
        });
    };

    this.initRouter = function () {
       /* var AppRouter = Backbone.Router.extend({
                routes: {
                    "posts/:id": "getPost",
                    "*actions": "defaultRoute"
                }
            }),
            app_router = new AppRouter;

        app_router.on('route:getPost', function (id) {
            alert( "Get post number " + id );
        });
        app_router.on('route:defaultRoute', function (actions) {
            alert( actions );
        });
        Backbone.history.start();*/
    };

    this.init();

    window.getData = function () {

        $('.thumb').each(function (index, thumb) {
           var $thumb = $(thumb),
               $img = $thumb.find('img'),
               galleryId = $img.data().galleryId,
               imageIndex =  $img.data().index;


            //console.log(galleryId + ' ' + imageIndex);

            var imageData = window.data.slideshows[galleryId].images[imageIndex],
                pos = $thumb.position();


            imageData.left = pos.left;
            imageData.top = pos.top;
        });

        console.clear();
        return JSON.stringify(window.data.slideshows);
    };

    function getHistory () {
        return;
        var historyJSON = localStorage.getItem(STORAGE_KEY);

        if (!historyJSON || historyJSON == '') {
            return [];
        } else {
            return JSON.parse(historyJSON);
        }
    }


})();
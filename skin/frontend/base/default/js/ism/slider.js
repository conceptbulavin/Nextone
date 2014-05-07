(function($){
    window.ISM = window.ISM || {};

    (function() {
        var lastTime = 0;
        var vendors = ['ms', 'moz', 'webkit', 'o'];
        for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
            window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame']||
                                        window[vendors[x]+'CancelRequestAnimationFrame'];
        }

        if (!window.requestAnimationFrame)
            window.requestAnimationFrame = function(callback, element) {
                var currTime = new Date().getTime(),
                    timeToCall = Math.max(0, 16 - (currTime - lastTime));

                lastTime = currTime + timeToCall;
                return window.setTimeout(function() { callback(currTime + timeToCall); },
                  timeToCall);
            };

        if (!window.cancelAnimationFrame)
            window.cancelAnimationFrame = function(id) {
                clearTimeout(id);
            };
    }());


    ISM.slider = function(element, slides, options){
        this.el = $(element);
        this.slides = slides;
        this.initialize(options);
    };

    ISM.slider.prototype = {

        initialize: function(options){
            this.settings = $.extend({
                prefix: 'ism',
                duration: 1000,
                width: 960,
                height:  400,
                frequency:  3000,
                controls: true,
                pagination: true,
                autoSlide: true,
                startIndx: 0,
                effect: 'fade',
                cl: {
                    item:         this.el.find('.b-ism-slider-item'),
                    pagination:   this.el.find('.b-ism-slider-pagination-item'),
                    leftControl:  this.el.find('.b-ism-slider-control-left'),
                    rightControl: this.el.find('.b-ism-slider-control-right'),
                    preloader:    this.el.find('.b-ism-slider-preloader'),
                    progress:     this.el.find('.b-ism-slider-progress')
                }
            }, options || {});

            this.size = this.settings.cl.item.length;
            this.startTime = null;

            if (this.settings.pagination){
                this.settings.cl.pagination.on(
                    'click', $.proxy(this.paginationClickHandler, this)
                ).parent().show();
            }

            if (this.settings.controls){
                this.settings.cl.rightControl.show().on(
                    'click', $.proxy(this.nextSlide, this)
                );
                this.settings.cl.leftControl.show().on(
                    'click', $.proxy(this.prevSlide, this)
                );
            }

            this.getSlide(this.settings.startIndx);

            if (this.settings.autoSlide){
                this.runTimer();
                this.el.on({
                    'mouseover': $.proxy(this.stopTimer, this),
                    'mouseout': $.proxy(this.runTimer, this)
                });
            }
        },

        runTimer: function () {
            this.isProgressBlocked = false;
            this.runProgressSlider();
            this.timer =  window.setInterval(
                $.proxy(this.nextSlide, this),
                this.settings.frequency
            );
        },

        stopTimer: function () {
            this.isProgressBlocked = true;
            this.stopProgressSlider();
            window.clearInterval(this.timer);
        },

        getTime: function () {
            return new Date().getTime();
        },

        runProgressSlider: function () {
            this.progressId = requestAnimationFrame(
                this.updateProgressSlider.bind(this)
            );
        },

        stopProgressSlider: function () {
            this.startTime = null;
            window.cancelAnimationFrame(this.proggressId);
            this.settings.cl.progress.css({
                'width': 0
            });
        },

        updateProgressSlider: function () {
            var progress, timeDiff;

            if (this.isProgressBlocked) {
                return false;
            }

            if (this.startTime === null) {
                this.startTime = this.getTime();
            }

            timeDiff = this.getTime() - this.startTime;
            progress = timeDiff * 100 / this.settings.frequency;

            this.settings.cl.progress.css({
                'width': Math.min(progress, 100) + "%"
            });

            if (progress < 100) {
                 this.progressId = requestAnimationFrame(
                    this.updateProgressSlider.bind(this)
                );
            }
        },

        getSlide: function(page){
            if (typeof page !== 'number' || page > this.size - 1 || page < 0) {
                return false;
            }
            var self = this,
                curSlide = this.slides[page],
                callback = function(){
                    if (self.settings.pagination){
                        self.settings.cl.pagination
                            .removeClass('current')
                            .eq(page)
                            .addClass('current');
                    }

                    self.settings.cl.item
                        .siblings()
                        .removeClass('current')
                        .end()
                        .eq(page)
                        .addClass('current')
                        .fadeIn(
                        self.settings.duration,
                        function(){
                            $(this).siblings().hide(0);
                        });

                    self.runProgressSlider();
                };

            this.stopProgressSlider();

            if (!curSlide.loaded){
                var img = ISM.loadImage(
                    curSlide.src,
                    {
                        beforeLoad: function(img){
                            self.settings.cl.preloader.show();
                            $(img).css({
                                    width: self.settings.width,
                                    height: self.settings.height
                                })
                                .addClass([
                                    'b-ism-slider-item-img ',
                                    self.settings.prefix, '-item-img'
                                ].join(''));
                        },
                        load: function(){
                            curSlide.loaded = true;
                            callback();
                            self.settings.cl.preloader.hide();
                        }
                    });
                self.settings.cl.item
                    .eq(page)
                    .find('.b-ism-slider-item-link')
                    .append(img);
            } else {
                callback();
            }
        },

        paginationClickHandler: function(event){
            if(this.settings.cl.item.is(':animated') || !event) {
                return false;
            }

            var pageIndx = $(event.currentTarget).index();
            this.getSlide(pageIndx);
        },

        nextSlide: function(){
            if (this.settings.cl.item.is(':animated')) {
                return false;
            }

            var current = this.settings.cl.item.filter('.current').index() + 1;

            if (this.settings.autoSlide && current >= this.size){
                current = 0;
            }

            this.getSlide(current);
        },

        prevSlide: function(){
            if (this.settings.cl.item.is(':animated')) {
                return false;
            }

            var current = this.settings.cl.item.filter('.current').index() - 1;

            if (this.settings.autoSlide && current < 0){
                current = this.size - 1;
            }

            this.getSlide(current);
        }
    };
})(jQuery);

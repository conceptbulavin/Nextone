(function($){
    window.S2 = window.S2 || {};

    S2.slider = function(element, slides, options){
            this.el = $(element);
            this.slides = slides;
            this.init(options);
    };

    S2.slider.prototype = {

        init : function(options){
            this.settings = $.extend({
                prefix: 's2',
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
                    item :         this.el.find('.b-s2-slider-item'),
                    pagination :   this.el.find('.b-s2-slider-pagination-item'),
                    leftControl :  this.el.find('.b-s2-slider-control-left'),
                    rightControl : this.el.find('.b-s2-slider-control-right'),
                    preloader :    this.el.find('.b-s2-slider-preloader')
                }
            }, options || {});

            this.size = this.settings.cl.item.length;

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
            this.timer =  window.setInterval(
                $.proxy(this.nextSlide, this),
                this.settings.frequency
            );
        },

        stopTimer: function () {
            window.clearInterval(this.timer);
        },

        getSlide : function(page){
            if (typeof page !== 'number' || page > this.size - 1 || page < 0) {
                return fasle;
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
                };

            if (!curSlide.loaded){
                var img = S2.loadImage(
                    curSlide.src,
                    {
                        beforeLoad: function(img){
                            self.settings.cl.preloader.show();
                            $(img).css({
                                    width: self.settings.width,
                                    height: self.settings.height
                                })
                                .addClass([
                                    'b-s2-slider-item-img ',
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
                    .find('.b-s2-slider-item-link')
                    .append(img);
            }else{
                callback();
            }
        },

         paginationClickHandler : function(event){
            if(this.settings.cl.item.is(':animated') || !event) {
                return false;
            }

            var pageIndx = $(event.currentTarget).index();
            this.getSlide(pageIndx);
        },

        nextSlide : function(){
            if (this.settings.cl.item.is(':animated')) {
                return false;
            }

            var current = this.settings.cl.item.filter('.current').index() + 1;

            if (this.settings.autoSlide && current >= this.size){
                current = 0;
            }

            this.getSlide(current);
        },

        prevSlide : function(){
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

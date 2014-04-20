S2 = window.S2 || {};

S2.imageZoom = {
    init : function(options){
        if (!window.izJson) return;
        S2.imageZoom.settings = jQuery.extend(true, {
            classes : {
                prefix :         'iz-',
                btnDisabled :    'iz-btn-disabled',
                btnHidden :      'iz-btn-hidden',
                itemCurrent :    'iz-item-current',
                disabled :       'iz-disabled',
                thumbsEnabled :  'iz-thumbs-enabled'
            },
            obj : {
                el :             jQuery('.image-zoom'),
                notice :         jQuery('.iz-notice'),
                preloader :      jQuery('.iz-preloader'),
                thumbsHolder :   jQuery('.more-views'),
                thumbsUl :       jQuery('.more-views > ul'),
                thumbs :         jQuery('.more-views li'),
                holder :         jQuery('.cloud-zoom'),
                btns :           jQuery('.iz-btn-controls'),
                btnPrev :        jQuery('.iz-btn-prev'),
                btnNext :        jQuery('.iz-btn-next')
            },
            thumbs :             false,
            slideTime :          200,
            thumbSlideTime :     400,
            startIndx :          0,
            shiftCount :         6,
            onInit :             null,
            onDestoy :           null,
            onAfterImgDisplayed :   null,
            onBeforeImgDisplayed :  null,
            onThumbClick :       null
        }, options || {});

        // Initialization
        S2.imageZoom.settings.thumbs && S2.imageZoomThumbs.init();
        S2.imageZoom.settings.obj.thumbs.bind('click', jQuery.proxy(S2.imageZoom.clickThHandler, S2.imageZoom));
        S2.imageZoom.settings.onInit && S2.imageZoom.settings.onInit.apply(S2.imageZoom, []);
    },
    destroy : function(callback){
        S2.imageZoom.settings.obj.holder.data('zoom') && S2.imageZoom.settings.obj.holder.data('zoom').destroy();
        callback && S2.imageZoom.settings.onDestoy && S2.imageZoom.settings.onDestoy.apply(S2.imageZoom, []);
    },
    clickThHandler : function(event){
        event.preventDefault();
        var _el = jQuery(event.currentTarget);
        _el.addClass(S2.imageZoom.settings.classes.itemCurrent).siblings().removeClass(S2.imageZoom.settings.classes.itemCurrent);
        S2.imageZoom.getImage(_el.index());
        S2.imageZoom.settings.onThumbClick && S2.imageZoom.settings.onThumbClick.apply(S2.imageZoom, []);
    },
    getImage : function(indx){
        S2.imageZoom.settings.onBeforeImgDisplayed && S2.imageZoom.settings.onBeforeImgDisplayed.apply(S2.imageZoom, []);
        var slide = window.izJson[indx],
            img,
            currentImg;
        S2.imageZoom.settings.obj.holder.attr('href', slide.image);
        if (!slide.loaded){
            S2.imageZoom.settings.obj.preloader.show();
            img = jQuery('<img />', {
                'class' :       S2.imageZoom.settings.classes.prefix + 'current ' + S2.imageZoom.settings.classes.prefix + indx,
                'alt'   :       slide.alt,
                'src'   :       slide.thumb + '?' + (new Date()).getTime()
            });
            img.load(jQuery.proxy(function(){
                S2.imageZoom.settings.obj.preloader.hide();
                slide.loaded = true;
                S2.imageZoom.displayImage(img, slide);
            }, S2.imageZoom));
            S2.imageZoom.settings.obj.holder.append(img);
        } else{
            currentImg = jQuery('.' + S2.imageZoom.settings.classes.prefix + indx, S2.imageZoom.settings.obj.holder);
            if (currentImg.hasClass(S2.imageZoom.settings.classes.prefix + 'current')) return;
            S2.imageZoom.displayImage(currentImg, slide);
        };
    },
    displayImage : function(elm, img){
        if (elm.is(':animated')) return;
        elm
            .addClass(S2.imageZoom.settings.classes.prefix + 'current')
            .stop()
            .animate({'opacity' : 1}, S2.imageZoom.settings.slideTime, jQuery.proxy(function(){
            S2.imageZoom.destroy(true);
            if (img.image != 'javascript: void(0)'){
                S2.imageZoom.settings.obj.el.removeClass(S2.imageZoom.settings.classes.disabled);
                S2.imageZoom.settings.obj.holder.CloudZoom()
            } else{
                S2.imageZoom.settings.obj.el.addClass(S2.imageZoom.settings.classes.disabled);
            };
            elm.siblings('img').css({'opacity' : 0});
            S2.imageZoom.settings.onAfterImgDisplayed && S2.imageZoom.settings.onAfterImgDisplayed.apply(S2.imageZoom, []);
        }, S2.imageZoom)).siblings('img').removeClass(S2.imageZoom.settings.classes.prefix + 'current');
    }
}; // <!>



S2.imageZoomThumbs =  {
    init : function(){
        // Initialization
        S2.imageZoom.settings.obj.el.addClass(S2.imageZoom.settings.classes.thumbsEnabled);

        var thumbW = S2.imageZoom.settings.obj.thumbs.outerWidth(true);
        S2.imageZoomThumbs.shift = S2.imageZoom.settings.shiftCount*thumbW;
        S2.imageZoomThumbs.obj = {
            thumb : {
                width :         thumbW,
                length :        thumbW*S2.imageZoom.settings.obj.thumbs.length
            },
            thumbsHolder : {
                width :         S2.imageZoom.settings.obj.thumbsHolder.outerWidth()
            }
        };

        jQuery.data(document.body, 'thumbShift', 0);
        S2.imageZoom.settings.obj.btnPrev.bind( 'click', jQuery.proxy(S2.imageZoomThumbs.getPrev, S2.imageZoomThumbs));
        S2.imageZoom.settings.obj.btnNext.bind( 'click', jQuery.proxy(S2.imageZoomThumbs.getNext, S2.imageZoomThumbs));

        S2.imageZoom.settings.obj.thumbsUl.width(S2.imageZoomThumbs.obj.thumb.length);
        if (S2.imageZoomThumbs.obj.thumb.length <= S2.imageZoomThumbs.obj.thumbsHolder.width){
            S2.imageZoomThumbs.hideControls();
        }
        else if (S2.imageZoom.settings.startIndx == 0) S2.imageZoomThumbs.hideControls(-1)
        else if(S2.imageZoom.settings.startIndx == S2.imageZoomThumbs.obj.thumb.length) S2.imageZoomThumbs.hideControls(1);

        S2.imageZoom.settings.obj.btns.removeClass(S2.imageZoom.settings.classes.btnHidden);

    },
    move : function(o){
        if (typeof o !== 'number' || jQuery(S2.imageZoom.settings.obj.thumbsUl).is(':animated')) return;
        var pos = jQuery.data(document.body, 'thumbShift') || 0,
            shift,
            maxShift;

        if (o > 0){
            shift = pos - S2.imageZoomThumbs.shift,
                maxShift = S2.imageZoomThumbs.obj.thumbsHolder.width - S2.imageZoomThumbs.obj.thumb.length;

            if (pos - S2.imageZoomThumbs.shift < maxShift){
                if (Math.abs(maxShift - pos) >= S2.imageZoom.settings.obj.thumbsHolder.width) return
                else {
                    shift = maxShift;
                }
            };
            (shift <= maxShift) ? S2.imageZoomThumbs.hideControls(1) : S2.imageZoom.settings.obj.btns.removeClass(S2.imageZoom.settings.classes.btnDisabled);
        } else{
            shift = pos + S2.imageZoomThumbs.shift;
            if (pos + S2.imageZoomThumbs.shift > 0){
                if (Math.abs(pos) >= S2.imageZoomThumbs.shift) return;
                else {
                    shift = 0;
                }
            };
            (shift == 0) ? S2.imageZoomThumbs.hideControls(-1) : S2.imageZoom.settings.obj.btns.removeClass(S2.imageZoom.settings.classes.btnDisabled);
        };
        jQuery(S2.imageZoom.settings.obj.thumbsUl).animate({'left' : shift}, S2.imageZoom.settings.thumbSlideTime);
        jQuery.data(document.body, 'thumbShift', shift);
    },
    hideControls : function(o){
        if (S2.imageZoomThumbs.obj.thumb.length <= S2.imageZoomThumbs.obj.thumbsHolder.width){
            o = 0;
        };
        S2.imageZoom.settings.obj.btnNext.add(S2.imageZoom.settings.obj.btnPrev).removeClass(S2.imageZoom.settings.classes.btnDisabled)
        if (o > 0){
            S2.imageZoom.settings.obj.btnNext.addClass(S2.imageZoom.settings.classes.btnDisabled);
        } else if(o < 0){
            S2.imageZoom.settings.obj.btnPrev.addClass(S2.imageZoom.settings.classes.btnDisabled);
        } else{
            S2.imageZoom.settings.obj.btnNext.add(S2.imageZoom.settings.obj.btnPrev).addClass(S2.imageZoom.settings.classes.btnDisabled);
        };
    },
    getPrev : function(){
        S2.imageZoomThumbs.move(0);
    },
    getNext : function(){
        S2.imageZoomThumbs.move(1);
    }
}; // <!>
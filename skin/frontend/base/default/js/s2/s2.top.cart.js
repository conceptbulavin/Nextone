window.topCart = {
    initialize: function(container){
        thatCart = this;
        thatCart.container = jQuery(container);
        thatCart.element = thatCart.container.parent();
        thatCart.elementHeader = thatCart.container.prev();
        thatCart.isiPad = /ipad|iphone|android|blackberry/.test(navigator.userAgent.toLowerCase());
        thatCart.delay = 0;
        thatCart.delayInterval = null;
        thatCart.interval = null;
        thatCart.timePeriod = 0;
        if (thatCart.isiPad === true){
            thatCart.element.bind('click', function(e){
                thatCart.onClick();
                e.stopPropagation();
            });
            thatCart.container.bind('click', function(e){
                e.stopPropagation();
            });
            jQuery(document).bind('click', function(e){
                thatCart.hideCart();
                e.stopPropagation();
            });
        } else {
            thatCart.element.bind('mouseenter', thatCart.onMouseOver);
            thatCart.element.bind('mouseleave', thatCart.onMouseOut);
        }
    },
    onMouseOver: function(){
        if (!$(thatCart.elementHeader).hasClass('expanded')){
            clearTimeout(thatCart.delayInterval);
            if (thatCart.delay !== null){
                thatCart.delayInterval = setTimeout(thatCart.showCart, thatCart.delay);
            }
        }
    },
    onMouseOut: function(){
        if ($(thatCart.elementHeader).hasClass('expanded')){
            clearTimeout(thatCart.delayInterval);
            if (thatCart.delay !== null){
                thatCart.delayInterval = setTimeout(thatCart.hideCart, thatCart.delay);
            }
        }
    },
    onClick: function(){
        if (!$(thatCart.elementHeader).hasClass('expanded')){
            thatCart.showCart();
        } else {
            thatCart.hideCart();
        }
    },
    showCart: function(timePeriod){
        thatCart.elementHeader.addClass('expanded');
        thatCart.container.show();
        if(timePeriod) {
            thatCart.timePeriod = timePeriod*1000;
            thatCart.interval = setTimeout(thatCart.hideCart, thatCart.timePeriod);
        }
    },
    hideCart: function(){
        thatCart.elementHeader.removeClass('expanded');
        thatCart.container.hide();
        if (thatCart.interval !== null) {
            clearTimeout(thatCart.interval);
            thatCart.interval = null;
        }
    }
};

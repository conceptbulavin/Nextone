jQuery(document).ready(function(){
    new S2.tabs('.b-tabs');
});

window.S2 = window.S2 || {};

S2.tabs = function(el, options){
    if (!el) return;
    this.el = jQuery(el);
    this.settings = jQuery.extend(true, {
        startIndex : 0
    }, options || {});

    this.init();
}; // <!>

S2.tabs.prototype = {
    init : function(){
        this.headers = jQuery('li.b-th-item', this.el);
        this.items = jQuery('.b-tab-item', this.el);
        this.count = this.headers.length;

        if(window.location.hash && /^[A-Za-z]+[A-Za-z0-9\-\_\:\.]*$/gi.test(window.location.hash.substring(1))){
            this.get(window.location.hash, true);
        } else {
            this.get(this.settings.startIndex);
        }

        var self = this;
        this.headers.delegate('a', 'click', function(e){
            self.get(this.hash);
            e.preventDefault();
        });
    },
    get : function(id, scroll){
        if (typeof id === 'number' && id >= 0 && id < this.count){
            this.headers.eq(id).addClass('b-th-item-active').siblings('.b-th-item').removeClass('b-th-item-active');
            this.items.eq(id).addClass('b-tab-item-active').siblings('.b-tab-item').removeClass('b-tab-item-active');
            if (scroll) this.scrollTo(id);
            return false;
        } else if(typeof id === 'string'){
            var item = this.items.filter(id),
                index = (typeof item === 'undefined') ? this.settings.startIndex  : item.index() - 1; // We have to subtract 1 because of first element is <ul> with tab-headers

            scroll = scroll ? scroll : void(0);
            this.get(index, scroll);
            return false;
        }

        return false;
    },
    scrollTo : function(id){
        jQuery(document.documentElement).add(document.body).scrollTop(this.headers.eq(id).offset().top);
    }

}; // <!>

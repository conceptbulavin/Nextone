function filterItems(url) {
    jQuery.noConflict();
    jQuery('#loadingmask').show();
    jQuery.post(url, {},function(data) {
        jQuery('.main').html(data);
        jQuery('#loadingmask').hide();
        jQuery('.block-layered-nav .m-selected-ln-item')
            .closest('dd').addClass('is-expanded')
            .prev('dt').addClass('is-expanded');
    },'html');
}

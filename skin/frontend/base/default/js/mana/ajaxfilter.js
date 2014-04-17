function filterItems(url) {
    jQuery.noConflict();
    jQuery('#loadingmask').show();
    jQuery.post(url,{},function(data)
    {
        jQuery('.main').html(data);
        jQuery('#loadingmask').hide();
    },'html');
}

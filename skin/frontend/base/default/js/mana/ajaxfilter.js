function filterItems(url)
{
    jQuery.noConflict();
    Element.show('loadingmask');
    jQuery.post(url,{},function(data)
    {
        jQuery('.main').html(data);
        Element.hide('loadingmask');
    },'html');
}

(function () {
    jQuery(document).ready(function () {
        jQuery(document.body).on('click', '.block .filter-name',
            function (event) {
                jQuery(event.currentTarget)
                    .parent()
                    .toggleClass('is-expanded')
                    .next()
                    .toggleClass('is-expanded');
            });
    });
}());

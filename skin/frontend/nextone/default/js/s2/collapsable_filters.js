(function () {
    jQuery(document).ready(function () {
        jQuery(document.body).on('click', '.block .filter-name',
            function (event) {
                jQuery(event.currentTarget)
                    .parent()
                    .toggleClass('is-expanded')
                    .next()
                    .slideToggle(200);
            });

        jQuery('.b-tabs').on('click', '.b-pdp-tabs-label',
            function (event) {
                jQuery(event.currentTarget)
                    .toggleClass('is-expanded')
                    .next()
                    .slideToggle(200);
            });
    });
}());

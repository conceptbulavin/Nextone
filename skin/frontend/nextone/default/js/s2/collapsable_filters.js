(function () {
    jQuery(document).ready(function () {
        var layeredNav = jQuery('.block-layered-nav'),
            collapseLink = layeredNav.find('.collapse-filter'),
            filtersHeader = layeredNav.find('dt');

        layeredNav.on('click', 'dt', function (event) {
            jQuery(event.currentTarget) // dt element
                .toggleClass('is-expanded')
                .next() // next dd element with list of filter items
                .toggleClass('is-expanded');
        });
    });
}());

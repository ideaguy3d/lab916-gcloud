jQuery(document).ready(function () {
    jQuery("#L9-footer-h1").on("mouseover", function (el) {
        console.log(el);
        $( this ).css( "color", "red" );
    });
});
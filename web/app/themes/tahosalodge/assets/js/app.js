jQuery(document).ready( function($) {
    $('.product_cat-cuboree').on( 'change', '.do-you-have-any-dietary-restrictions input', function() {
        var $requiredItem = $(this).closest('.form-row').siblings('.dietary-restrictions');
        $requiredItem.toggle();
        $requiredItem.children('input').prop('required', this.checked );
    });

    $('[type="number"]').keypress( function(e) {
        var a = [];
        var k = e.which;
        var i = 48;

        for ( i = 48; i < 58; i++ ) {
            a.push(i);
        }

        if ( !( $.inArray(k,a) >= 0 )) {
            e.preventDefault();
        }
    });

});

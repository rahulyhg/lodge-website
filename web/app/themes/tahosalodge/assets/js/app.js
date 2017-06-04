jQuery(function($){
$(document).ready(function() {

$('.product_cat-cuboree').on( 'change', '.do-you-have-any-dietary-restrictions input', function() {
    var $requiredItem = $(this).closest('.form-row').siblings('.dietary-restrictions')
    $requiredItem.toggle();
    $requiredItem.children('input').prop('required', this.checked );
});

});
});

jQuery(document).ready(($) => {
  $('.product_cat-cuboree').on('change', '.do-you-have-any-dietary-restrictions input', () => {
    const $requiredItem = $(this).closest('.form-row').siblings('.dietary-restrictions');
    $requiredItem.toggle();
    $requiredItem.children('input').prop('required', this.checked);
  });

  $('[type="number"]').keypress((e) => {
    const a = [];
    const k = e.which;
    let i = 48;

    for (i = 48; i < 58; i += 1) {
      a.push(i);
    }

    if (!($.inArray(k, a) >= 0)) {
      e.preventDefault();
    }
  });
});

import Cookie from 'js-cookie';

export default function($) {
  $('.product').on(
    'change',
    '.do-you-have-any-allergies-or-dietary-restrictions input',
    event => {
      const $this = $(event.currentTarget);
      const $requiredItem = $this
        .closest('.form-row')
        .siblings('.dietary-restriction-details');
      $requiredItem.toggle();
      $requiredItem.children('input').prop('required', $this.checked);
    }
  );

  $('[type="number"]').keypress(e => {
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

  const cuboreeBar = Cookie.get('cuboreeBar');
  if ($('body').hasClass('page-cuboree') && cuboreeBar !== 'hide') {
    Cookie.set('cuboreeBar', 'show');
    $('.cuboree-guide').addClass('show');
  }
  if (cuboreeBar === 'show') {
    $('.cuboree-guide').addClass('show');
  }
  $('.cuboree-guide .close').click(() => {
    $('.cuboree-guide').removeClass('show');
    Cookie.set('cuboreeBar', 'hide');
  });
}

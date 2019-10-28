jQuery(document).ready(function ($) {
  /* getweb form submission */
  $('#getwebContactForm').on('submit', function (e) {

    e.preventDefault();
    $('.has-error-contact').removeClass('has-error-contact');
    $('.js-show-feedback').removeClass('js-show-feedback');

    var form = $(this),
      name = form.find('#name').val(),
      email = form.find('#email').val(),
      message = form.find('#message').val(),
      mobile = form.find('#mobile').val(),
      ajaxurl = form.data('url');

    if (name === '') {
      $('#name').parent('.contract-from-group').addClass('has-error-contact');
      return;
    }

    if (email === '') {
      $('#email').parent('.contract-from-group').addClass('has-error-contact');
      return;
    }
    if (mobile === '') {
      $('#mobile').parent('.contract-from-group').addClass('has-error-contact');
      return;
    }
    if (message === '') {
      $('#message').parent('.contract-from-group').addClass('has-error-contact');
      return;
    }


    form.find('input, button, textarea').attr('disabled', 'disabled');
    $('.js-form-submission').addClass('js-show-feedback');

    $.ajax({

      url: ajaxurl,
      type: 'post',
      data: {

        name: name,
        email: email,
        message: message,
        mobile: mobile,
        action: 'getweb_save_user_contact_form'

      },
      error: function (response) {
        $('.js-form-submission').removeClass('js-show-feedback');
        $('.js-form-error').addClass('js-show-feedback');
        form.find('input, button, textarea').removeAttr('disabled');
      },
      success: function (response) {
        if (response == 0) {
          setTimeout(function () {
            $('.js-form-submission').removeClass('js-show-feedback');
            $('.js-form-error').addClass('js-show-feedback');
            form.find('input, button, textarea').removeAttr('disabled');
          }, 1500);
        } else {
          setTimeout(function () {
            $('.js-form-submission').removeClass('js-show-feedback');
            $('.js-form-success').addClass('js-show-feedback');
            $('#contactPopUp').css('display','block');
            form.find('input, button, textarea').removeAttr('disabled').val('');
            $('#getwebContactForm')[0].reset();
          }, 1500);

        }
      }

    });

  });
});

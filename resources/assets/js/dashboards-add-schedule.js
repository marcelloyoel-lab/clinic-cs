'use strict';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

console.log(flatpickr);

$(function () {
  $('input[name="booking_type"]').on('change', function () {
    const type = $(this).val();

    if (type === 'consultation') {
      $('#doctor-wrapper').removeClass('d-none');
      $('#treatment-wrapper').addClass('d-none');
    } else {
      $('#doctor-wrapper').addClass('d-none');
      $('#treatment-wrapper').removeClass('d-none');
    }
  });

  if ($('.flatpickr-date').length) {
    $('.flatpickr-date').flatpickr({
      dateFormat: 'd/m/Y'
    });
  }

  if ($('.flatpickr-time').length) {
    $('.flatpickr-time').flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: 'H:i'
    });
  }
});

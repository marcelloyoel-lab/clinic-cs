'use strict';

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

/* ==========================================
   HELPERS
========================================== */

$(document).on('keydown', '.flatpickr-date', function (e) {
  e.preventDefault();
});

/* ==========================================
   PAGE
========================================== */

$(function () {
  /*
  |--------------------------------------------------------------------------
  | Flatpickr
  |--------------------------------------------------------------------------
  */

  if ($('.flatpickr-date').length) {
    $('.flatpickr-date').flatpickr({
      altInput: true,
      altInputClass: 'form-control',
      altFormat: 'd/m/Y',
      dateFormat: 'Y-m-d',
      allowInput: false,
      disableMobile: true,

      onChange: function (selectedDates, dateStr, instance) {
        validateField($(instance.input));
      },

      onClose: function (selectedDates, dateStr, instance) {
        validateField($(instance.input));
      }
    });
  }

  /*
  |--------------------------------------------------------------------------
  | Booking Type
  |--------------------------------------------------------------------------
  | Consultation is the only supported type for now.
  | Keep Treatment disabled until implemented.
  */

  // $('input[name="booking_type"]').prop('disabled', true);

  /*
  |--------------------------------------------------------------------------
  | Required Fields
  |--------------------------------------------------------------------------
  */

  const requiredFields = [
    {
      selector: 'input[name="name"]',
      label: 'Full Name'
    },
    {
      selector: 'input[name="dob"]',
      label: 'Date of Birth'
    },
    {
      selector: 'select[name="gender"]',
      label: 'Gender'
    },
    {
      selector: 'input[name="phone"]',
      label: 'Phone Number'
    },
    {
      selector: 'select[name="doctor_id"]',
      label: 'Attending Doctor'
    },
    {
      selector: 'input[name="date"]',
      label: 'Appointment Date'
    },
    {
      selector: 'input[name="time"]',
      label: 'Appointment Time'
    },
    {
      selector: 'textarea[name="chief_complaint"]',
      label: 'Reason for Visit'
    }
  ];

  /*
  |--------------------------------------------------------------------------
  | Validation Helper
  |--------------------------------------------------------------------------
  */

  function validateField($field) {
    if (!$field.length) {
      return true;
    }

    const value = ($field.val() ?? '').toString().trim();

    const picker = $field[0]?._flatpickr;

    if (!value) {
      $field.addClass('is-invalid');

      if (picker) {
        $(picker.altInput).addClass('is-invalid');
      }

      return false;
    }

    $field.removeClass('is-invalid');

    if (picker) {
      $(picker.altInput).removeClass('is-invalid');
    }

    return true;
  }

  /*
  |--------------------------------------------------------------------------
  | Validate On Blur / Change
  |--------------------------------------------------------------------------
  */

  $('input, textarea').on('blur', function () {
    validateField($(this));
  });

  $('select').on('change', function () {
    validateField($(this));
  });

  /*
  |--------------------------------------------------------------------------
  | Validate On Submit
  |--------------------------------------------------------------------------
  */

  $('form').on('submit', function (e) {
    let firstInvalidField = null;

    for (const field of requiredFields) {
      const $field = $(field.selector);

      if (!validateField($field) && !firstInvalidField) {
        firstInvalidField = $field;
      }
    }

    if (!firstInvalidField) {
      return;
    }

    e.preventDefault();

    const picker = firstInvalidField[0]?._flatpickr;

    if (picker) {
      picker.altInput.focus();
    } else {
      firstInvalidField.trigger('focus');
    }

    alert('Please complete all required fields.');
  });
});

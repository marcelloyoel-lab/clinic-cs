'use strict';

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

import select2 from 'select2';
select2(window, $);

import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css';

$(document).on('keydown', '.flatpickr-date', function (e) {
  e.preventDefault();
});

/* ==========================================
   HELPERS
========================================== */

function setPatientEditable(editable) {
  $('.patient-input').each(function () {
    const $field = $(this);

    if ($field.is('select')) {
      $field.prop('disabled', !editable);
      return;
    }

    if ($field.hasClass('flatpickr-date')) {
      const picker = $field[0]._flatpickr;

      if (picker) {
        picker.altInput.disabled = !editable;
        picker.config.clickOpens = editable;
      }

      $field.prop('disabled', false);
      return;
    }

    $field.prop('readonly', !editable);
  });
}

function clearPatientForm() {
  $('#patient-name').val('');

  const dobPicker = $('#patient-dob')[0]._flatpickr;
  if (dobPicker) {
    dobPicker.clear();
  }

  $('#patient-gender').val('');
  $('#patient-phone').val('');
  $('#patient-phone-alt').val('');
  $('#patient-email').val('');
}

// function fillPatient(patient) {
//   $('#patient-name').val(patient.name ?? '');
//   $('#patient-dob').val(patient.dob ?? '');
//   $('#patient-gender').val(patient.gender ?? '');
//   $('#patient-phone').val(patient.phone ?? '');
//   $('#patient-phone-alt').val(patient.phone_alternative ?? '');
//   $('#patient-email').val(patient.email ?? '');

//   setPatientEditable(true);
// }

function fillPatient(patient) {
  $('#patient-name').val(patient.name ?? '');

  const dobPicker = $('#patient-dob')[0]._flatpickr;
  if (dobPicker) {
    dobPicker.setDate(patient.dob ?? null);
  }

  $('#patient-gender').val(patient.gender ?? '');
  $('#patient-phone').val(patient.phone ?? '');
  $('#patient-phone-alt').val(patient.phone_alternative ?? '');
  $('#patient-email').val(patient.email ?? '');

  setPatientEditable(true);
}

function prepareNewPatient() {
  clearPatientForm();
  setPatientEditable(true);
}

const patients = JSON.parse(document.getElementById('patients-data').textContent);

const patientsById = Object.fromEntries(patients.map(patient => [patient.id, patient]));

/* ==========================================
   PAGE EVENTS
========================================== */

$(function () {
  /*
  |--------------------------------------------------------------------------
  | Initial State
  |--------------------------------------------------------------------------
  */
  console.log($);
  console.log($.fn.select2);
  $('#patient-search').select2({
    theme: 'bootstrap-5',
    placeholder: 'Search patient...',
    allowClear: true,
    width: '100%'
  });

  // setPatientEditable(false);
  $('#patient-search').on('change', function () {
    const patientId = $(this).val();

    // Create new patient
    if (patientId == -1) {
      prepareNewPatient();
      return;
    }

    // Reset form
    if (!patientId) {
      clearPatientForm();
      setPatientEditable(false);
      return;
    }

    // Existing patient
    const patient = patientsById[patientId];

    if (!patient) {
      return;
    }

    fillPatient(patient);
  });

  /*
  |--------------------------------------------------------------------------
  | Booking Type
  |--------------------------------------------------------------------------
  */

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

  /*
  |--------------------------------------------------------------------------
  | Flatpickr
  |--------------------------------------------------------------------------
  */

  if ($('.flatpickr-date').length) {
    // $('.flatpickr-date').flatpickr({
    //   altInput: true,
    //   altInputClass: 'form-control',
    //   altFormat: 'd/m/Y', // user sees
    //   dateFormat: 'Y-m-d', // backend receives
    //   allowInput: false,
    //   disableMobile: true,
    //   clickOpens: false
    // });
    $('.flatpickr-date').flatpickr({
      altInput: true,
      altInputClass: 'form-control',
      altFormat: 'd/m/Y',
      dateFormat: 'Y-m-d',
      allowInput: false,
      disableMobile: true
    });
  }

  setPatientEditable(false);

  if ($('.flatpickr-time').length) {
    $('.flatpickr-time').flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: 'H:i'
    });
  }

  /*
  |--------------------------------------------------------------------------
  | Example Usage
  |--------------------------------------------------------------------------
  */

  // Existing patient selected
  // fillPatient({
  //   name: 'John Doe',
  //   dob: '15/08/1995',
  //   gender: 'male',
  //   phone: '08123456789',
  //   phone_alternative: '08987654321',
  //   email: 'john@example.com'
  // });

  // Add new patient
  // prepareNewPatient();
});

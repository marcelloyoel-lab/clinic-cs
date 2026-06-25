'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const diagnosisContainer = document.getElementById('diagnosisContainer');
  const medicineContainer = document.getElementById('medicineContainer');
  const treatmentContainer = document.getElementById('treatmentContainer');

  const medicines = window.medicines ?? [];
  const treatments = window.treatments ?? [];

  const medicineOptions = medicines
    .map(
      medicine => `
        <option value="${medicine.id}">
          ${medicine.name}
        </option>
      `
    )
    .join('');

  const treatmentOptions = treatments
    .map(
      treatment => `
        <option value="${treatment.id}">
          ${treatment.name}
        </option>
      `
    )
    .join('');

  document.getElementById('addDiagnosisBtn')?.addEventListener('click', () => {
    diagnosisContainer.insertAdjacentHTML(
      'beforeend',
      `
      <div class="diagnosis-row">
        <div class="input-group">
          <input type="text"
                 name="diagnoses[]"
                 class="form-control"
                 placeholder="Enter diagnosis">

          <button type="button"
                  class="btn btn-icon btn-outline-secondary remove-diagnosis">
            <i class="bx bx-trash"></i>
          </button>
        </div>
      </div>
      `
    );
  });

  document.getElementById('addMedicineBtn')?.addEventListener('click', () => {
    medicineContainer.insertAdjacentHTML(
      'beforeend',
      `
      <div class="medicine-row">
        <div class="row g-3 align-items-end">

          <div class="col-12 col-md-4">
            <label class="form-label">Medicine Name</label>
            <select class="form-select" name="medicine_id[]">
              ${medicineOptions}
            </select>
          </div>

          <div class="col-12 col-md-2">
            <label class="form-label">Quantity</label>
            <input type="number"
                   class="form-control"
                   value="1"
                   name="quantity[]">
          </div>

          <div class="col-12 col-md-5">
            <label class="form-label">Instructions</label>
            <input type="text"
                   class="form-control"
                   name="instruction[]">
          </div>

          <div class="col-12 col-md-1">
            <button type="button"
                    class="btn btn-icon btn-outline-secondary remove-medicine">
              <i class="bx bx-trash"></i>
            </button>
          </div>

        </div>
      </div>
      `
    );
  });

  document.getElementById('addTreatmentBtn')?.addEventListener('click', () => {
    treatmentContainer.insertAdjacentHTML(
      'beforeend',
      `
      <div class="row g-3 treatment-row align-items-end">

        <div class="col-8">
          <select class="form-select"
                  name="treatment_id[]">
            ${treatmentOptions}
          </select>
        </div>

        <div class="col-3">
          <input type="number"
                 class="form-control"
                 value="1"
                 name="treatment_qty[]">
        </div>

        <div class="col-1">
          <button type="button"
                  class="btn btn-icon btn-outline-secondary remove-treatment">
            <i class="bx bx-x"></i>
          </button>
        </div>

      </div>
      `
    );
  });

  document.addEventListener('click', e => {
    if (e.target.closest('.remove-diagnosis')) {
      e.target.closest('.diagnosis-row')?.remove();
    }

    if (e.target.closest('.remove-medicine')) {
      e.target.closest('.medicine-row')?.remove();
    }

    if (e.target.closest('.remove-treatment')) {
      e.target.closest('.treatment-row')?.remove();
    }
  });

  const cancelBtn = document.getElementById('confirmCancelConsultation');
  const validationAlert = document.getElementById('validationAlert');

  cancelBtn?.addEventListener('click', () => {
    const reason = document.getElementById('cancelReason');

    if (!reason.value.trim()) {
      reason.classList.add('is-invalid');
      reason.focus();
      return;
    }

    reason.classList.remove('is-invalid');

    // TODO:
    // submit cancel request here

    console.log('Cancel Reason:', reason.value);
  });

  const submitBtn = document.getElementById('submitConsultationBtn');

  submitBtn?.addEventListener('click', e => {
    let isValid = true;

    document.querySelectorAll('.is-invalid').forEach(el => {
      el.classList.remove('is-invalid');
    });

    // Consultation Notes (Required)
    const notes = document.getElementById('consultationNotes');

    if (!notes.value.trim()) {
      notes.classList.add('is-invalid');
      isValid = false;
    }

    // Diagnoses
    document.querySelectorAll('.diagnosis-row').forEach(row => {
      const diagnosis = row.querySelector('input[name="diagnoses[]"]');

      if (!diagnosis.value.trim()) {
        diagnosis.classList.add('is-invalid');
        isValid = false;
      }
    });

    // Medicines
    document.querySelectorAll('.medicine-row').forEach(row => {
      const quantity = row.querySelector('input[name="quantity[]"]');
      const instruction = row.querySelector('input[name="instruction[]"]');

      if (!quantity.value.trim()) {
        quantity.classList.add('is-invalid');
        isValid = false;
      }

      if (!instruction.value.trim()) {
        instruction.classList.add('is-invalid');
        isValid = false;
      }
    });

    // Treatments
    document.querySelectorAll('.treatment-row').forEach(row => {
      const qty = row.querySelector('input[name="treatment_qty[]"]');

      if (!qty.value.trim()) {
        qty.classList.add('is-invalid');
        isValid = false;
      }
    });

    if (!isValid) {
      e.preventDefault();
      validationAlert.classList.remove('d-none');
      validationAlert.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });
      return;
    }
    validationAlert.classList.add('d-none');
    submitBtn.closest('form').submit();
  });

  // Supaya ga teurs2an muncul error invalid ketika user input di field yang sudah diisi
  document.addEventListener('input', e => {
    if (
      e.target.matches(
        'input[name="diagnoses[]"], input[name="quantity[]"], input[name="instruction[]"], input[name="treatment_qty[]"], textarea[name="notes"]'
      )
    ) {
      e.target.classList.remove('is-invalid');
    }
  });
});

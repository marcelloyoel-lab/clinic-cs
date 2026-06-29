'use strict';

document.addEventListener('DOMContentLoaded', () => {
  // ======================================================
  // ELEMENTS
  // ======================================================

  const processPaymentBtn = document.getElementById('processPaymentBtn');
  const paymentMethodForm = document.getElementById('paymentMethodForm');

  const cashRadio = document.getElementById('paymentCash');
  const midtransRadio = document.getElementById('paymentMidtrans');

  const cashSection = document.getElementById('cashSection');
  const midtransSection = document.getElementById('midtransSection');

  const paymentModal = new bootstrap.Modal(document.getElementById('paymentMethodModal'));

  // ======================================================
  // OPEN MODAL
  // ======================================================

  processPaymentBtn.addEventListener('click', () => {
    paymentMethodForm.reset();

    cashSection.classList.add('d-none');
    midtransSection.classList.add('d-none');

    paymentModal.show();
  });

  // ======================================================
  // PAYMENT METHOD CHANGE
  // ======================================================

  function updatePaymentMethod() {
    cashSection.classList.add('d-none');
    midtransSection.classList.add('d-none');

    if (cashRadio.checked) {
      cashSection.classList.remove('d-none');
    }

    if (midtransRadio.checked) {
      midtransSection.classList.remove('d-none');
    }
  }

  cashRadio.addEventListener('change', updatePaymentMethod);
  midtransRadio.addEventListener('change', updatePaymentMethod);

  // ======================================================
  // FORM SUBMIT
  // ======================================================

  paymentMethodForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked');

    if (!paymentMethod) {
      alert('Please select a payment method.');

      return;
    }

    switch (paymentMethod.value) {
      case 'cash':
        console.log('Cash payment selected.');

        // TODO:
        // Submit Cash Payment

        break;

      case 'midtrans':
        console.log('Midtrans payment selected.');

        // TODO:
        // Request Snap Token
        // snap.pay(token);

        break;
    }
  });
});

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

  const continueBtn = document.getElementById('continuePaymentBtn');

  const paymentModalElement = document.getElementById('paymentMethodModal');
  const paymentModal = new bootstrap.Modal(paymentModalElement);

  let paymentInProgress = false;

  // ======================================================
  // OPEN MODAL
  // ======================================================

  processPaymentBtn.addEventListener('click', () => {
    if (paymentInProgress) {
      return;
    }

    paymentMethodForm.reset();

    cashSection.classList.add('d-none');
    midtransSection.classList.add('d-none');

    paymentModal.show();
  });

  // ======================================================
  // PAYMENT METHOD
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
  // SUBMIT
  // ======================================================

  paymentMethodForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

    if (!selectedPayment) {
      alert('Please select a payment method.');

      return;
    }

    continueBtn.disabled = true;

    const originalHtml = continueBtn.innerHTML;

    continueBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Processing...
        `;

    try {
      const response = await fetch(paymentMethodForm.action, {
        method: paymentMethodForm.method,

        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,

          Accept: 'application/json'
        },

        body: new FormData(paymentMethodForm)
      });

      const result = await response.json();

      if (!response.ok) {
        throw new Error(result.message ?? 'Something went wrong.');
      }

      // ======================================================
      // CASH
      // ======================================================

      if (result.payment_method === 'cash') {
        paymentModal.hide();

        alert(result.message);

        return;
      }

      // ======================================================
      // MIDTRANS
      // ======================================================

      paymentModal.hide();

      paymentInProgress = true;

      processPaymentBtn.disabled = true;
      continueBtn.disabled = true;

      paymentModal.hide();

      snap.pay(result.snap_token, {
        onSuccess: function (result) {
          paymentInProgress = false;
          alert('Payment Success');

          setTimeout(() => {
            window.location.href = '/booking-list';
          }, 1500);
        },

        onPending: function (result) {
          paymentInProgress = false;

          processPaymentBtn.disabled = false;
          continueBtn.disabled = false;

          alert('Waiting for customer payment.');
        },

        onError: function (result) {
          paymentInProgress = false;

          processPaymentBtn.disabled = false;
          continueBtn.disabled = false;

          alert('Payment Failed.');
        },

        onClose: function () {
          paymentInProgress = false;

          processPaymentBtn.disabled = false;
          continueBtn.disabled = false;

          alert('Payment popup was closed.');
        }
      });
    } catch (error) {
      console.error(error);

      alert(error.message);
    } finally {
      continueBtn.disabled = false;

      continueBtn.innerHTML = originalHtml;
    }

    window.addEventListener('beforeunload', function (e) {
      if (!paymentInProgress) {
        return;
      }

      e.preventDefault();
      e.returnValue = '';
    });
  });
});

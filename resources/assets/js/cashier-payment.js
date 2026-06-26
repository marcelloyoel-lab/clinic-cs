'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const processBtn = document.getElementById('processPaymentBtn');

  if (!processBtn) return;

  processBtn.addEventListener('click', () => {
    processBtn.disabled = true;

    const originalHtml = processBtn.innerHTML;

    processBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Processing...
        `;

    setTimeout(() => {
      processBtn.disabled = false;
      processBtn.innerHTML = originalHtml;

      alert('Payment processed successfully.');
    }, 1200);
  });
});

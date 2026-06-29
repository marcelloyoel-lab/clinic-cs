@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Schedule')

@section('page-script')
  @vite('resources/assets/js/cashier-payment.js')
@endsection

@section('page-style')
  @vite('resources/assets/css/cashier-payment.css')
@endsection

@section('content')

<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-1">
          <li class="breadcrumb-item">Billing</li>
          <li class="breadcrumb-item active">Process Payment</li>
        </ol>
      </nav>
      <h3 class="mb-0"><b>Cashier Payment</b></h3>
    </div>

    <span class="badge bg-label-primary fs-6 px-3 py-2">
      {{ $invoiceNumber }}
    </span>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <div class="row align-items-center gy-3">
      <div class="col-lg-4 d-flex align-items-center">
        <div class="avatar avatar-xl me-3">
          <span class="avatar-initial rounded-circle bg-label-primary">
            <i class="bx bx-user fs-3"></i>
          </span>
        </div>
        <div>
          <small class="text-muted d-block">PATIENT NAME</small>
          <h4 class="mb-0"><b>{{ $consultation->patient->name }}</b></h4>
        </div>
      </div>

      <div class="col-lg-2">
        <small class="text-muted d-block">BOOKING DATE</small>
        <div><i class="bx bx-calendar text-primary me-1"></i> {{ $consultation->booking->date->format('d M Y') }}</div>
      </div>

      <div class="col-lg-2">
        <small class="text-muted d-block">BOOKING TIME</small>
        <div><i class="bx bx-time text-primary me-1"></i>{{ \Carbon\Carbon::parse($consultation->booking->time)->format('h:i A') }}</div>
      </div>

      <div class="col-lg-4 text-lg-end">
        <span class="badge bg-label-success px-3 py-2">
          <i class="bx bx-badge-check me-1"></i>Verified Patient
        </span>
      </div>
    </div>
  </div>
</div>

<div class="row mb-5">
  <div class="col-lg-9">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-receipt text-primary me-2"></i>Billing Items</h5>
      </div>

      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Item</th>
              <th>Type</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Price</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><strong>Consultation Fee</strong></td>
              <td>
                <span class="item-type consultation">
                    <i class="bx bx-user-voice"></i>
                    Consultation
                </span>
              </td>
              <td class="text-center">1</td>
              <td class="text-end">{{ number_format($consultationFee) }}</td>
              <td class="text-end fw-bold">
                  {{ number_format($consultationFee) }}
              </td>
            </tr>

            @foreach($consultation->consultationPrescription as $prescription)
              <tr>
                  <td>{{ $loop->iteration + 1 }}</td>
                  <td>{{ $prescription->medicine->name }}</td>
                  <td>
                      <span class="item-type medicine">
                        <i class="bx bx-capsule"></i>
                        Medicine
                      </span>
                  </td>
                  <td class="text-center">
                      {{ $prescription->quantity }}
                  </td>
                  <td class="text-end">
                      {{ number_format($prescription->medicine->price) }}
                  </td>
                  <td class="text-end fw-bold">
                      {{ number_format(
                          $prescription->medicine->price * $prescription->quantity
                      ) }}
                  </td>
              </tr>
            @endforeach

            @foreach($consultation->consultationTreatment as $consul_result)
              <tr>
                  <td>{{ $loop->iteration + 1 }}</td>
                  <td>{{ $consul_result->treatment->name }}</td>
                  <td>
                      <span class="item-type treatment">
                        <i class="bx bx-plus-medical"></i>
                        Treatment
                      </span>
                  </td>
                  <td class="text-center">
                      {{ $consul_result->quantity }}
                  </td>
                  <td class="text-end">
                      {{ number_format($consul_result->treatment->price) }}
                  </td>
                  <td class="text-end fw-bold">
                      {{ number_format(
                          $consul_result->treatment->price * $consul_result->quantity
                      ) }}
                  </td>
              </tr>
              @endforeach
          </tbody>
        </table>
      </div>

      <div class="card-footer text-muted">
        <em>Note: Taxes are calculated based on local regional standards.</em>
      </div>
    </div>
  </div>

  <div class="col-lg-3 mt-4 mt-lg-0">
    <div class="card payment-summary">
      <div class="card-header">
        <h4 class="mb-0"><b>Payment Summary</b></h4>
      </div>

      
      <div class="card-body">
        <hr class="payment-divider">
        <div class="d-flex justify-content-between mb-3">
          <span>Subtotal</span>
          <strong>{{ number_format($grandTotal) }} IDR</strong>
        </div>

        <div class="d-flex justify-content-between mb-3">
          <span>Tax (0%)</span>
          <strong>0 IDR</strong>
        </div>

        <div class="d-flex justify-content-between mb-3">
          <span>Discount</span>
          <strong class="text-danger">-0 IDR</strong>
        </div>

        <hr>

        <small class="text-primary fw-semibold">GRAND TOTAL</small>

        <h2 class="text-primary fw-bold mb-0">
          {{ number_format($grandTotal) }} <small class="fs-6 text-muted"></small>
        </h2>
      </div>
    </div>
  </div>
</div>

{{-- <div class="d-flex justify-content-end gap-3 mt-5 flex-wrap">
  <button class="btn btn-outline-primary">
    <i class="bx bx-edit-alt me-1"></i>
    Change Request
  </button>

  <button class="btn btn-primary" id="processPaymentBtn">
    <i class="bx bx-credit-card me-1"></i>
    Process Payment
  </button>
</div> --}}

<div class="payment-actions">
    <div class="d-flex justify-content-end gap-3 flex-wrap">
      <button class="btn btn-outline-primary">
        <i class="bx bx-edit-alt me-1"></i>
        Change Request
      </button>

      <button class="btn btn-primary" type="button" id="processPaymentBtn">
        <i class="bx bx-credit-card me-1"></i>
        Process Payment
      </button>
    </div>
</div>

<!-- ==========================================================
    PROCESS PAYMENT MODAL
=========================================================== -->
<div class="modal fade"
     id="paymentMethodModal"
     tabindex="-1"
     aria-labelledby="paymentMethodModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="paymentMethodModalLabel">
                    <i class="bx bx-credit-card me-2 text-primary"></i>
                    Process Payment
                </h4>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <form id="paymentMethodForm">

                <div class="modal-body">

                    <!-- ===============================
                        PAYMENT SUMMARY
                    ================================ -->

                    <div class="payment-modal-summary">

                        <div class="summary-row">
                            <span>Invoice</span>
                            <strong>
                                {{ $invoiceNumber }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Patient</span>
                            <strong>
                                {{ $consultation->patient->name }}
                            </strong>
                        </div>

                        <div class="summary-row">
                            <span>Total Payment</span>

                            <h4 class="mb-0 text-primary fw-bold">
                                Rp {{ number_format($grandTotal) }}
                            </h4>
                        </div>

                    </div>

                    <hr class="my-4">

                    <!-- ===============================
                        PAYMENT METHOD
                    ================================ -->

                    <h6 class="fw-bold mb-3">
                        Select Payment Method
                    </h6>

                    <div class="row g-3">

                        <!-- CASH -->

                        <div class="col-md-6">

                            <label class="payment-option w-100">

                                <input
                                    id="paymentCash"
                                    type="radio"
                                    name="payment_method"
                                    value="cash"
                                    class="payment-radio">

                                <div class="payment-card">

                                    <div class="payment-icon bg-label-success">
                                        <i class="bx bx-money"></i>
                                    </div>

                                    <div>

                                        <h5 class="mb-1">
                                            Cash
                                        </h5>

                                        <small class="text-muted">
                                            Customer pays directly at the cashier.
                                        </small>

                                    </div>

                                </div>

                            </label>

                        </div>

                        <!-- MIDTRANS -->

                        <div class="col-md-6">

                            <label class="payment-option w-100">

                                <input
                                    id="paymentMidtrans"
                                    type="radio"
                                    name="payment_method"
                                    value="midtrans"
                                    class="payment-radio">

                                <div class="payment-card">

                                    <div class="payment-icon bg-label-primary">
                                        <i class="bx bx-mobile-alt"></i>
                                    </div>

                                    <div>

                                        <h5 class="mb-1">
                                            Midtrans
                                        </h5>

                                        <small class="text-muted">
                                            QRIS, GoPay, Credit Card,
                                            ShopeePay & Bank Transfer.
                                        </small>

                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>

                    <!-- ===============================
                        CASH INFORMATION
                    ================================ -->

                    <div
                        id="cashSection"
                        class="payment-method-detail mt-4 d-none">

                        <div class="alert alert-success mb-0">

                            <div class="d-flex">

                                <i class="bx bx-check-circle fs-3 me-3"></i>

                                <div>

                                    <h6 class="mb-1">
                                        Cash Payment
                                    </h6>

                                    <small>
                                        The payment will be recorded as
                                        <strong>Cash</strong>.
                                        Press Continue to complete the payment.
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ===============================
                        MIDTRANS INFORMATION
                    ================================ -->

                    <div
                        id="midtransSection"
                        class="payment-method-detail mt-4 d-none">

                        <div class="alert alert-primary mb-0">

                            <div class="d-flex">

                                <i class="bx bx-wallet fs-3 me-3"></i>

                                <div>

                                    <h6 class="mb-2">
                                        Midtrans Payment
                                    </h6>

                                    <small class="d-block mb-2">
                                        Customer will complete payment through Midtrans.
                                    </small>

                                    <div class="d-flex flex-wrap gap-2">

                                        <span class="badge bg-label-primary">
                                            QRIS
                                        </span>

                                        <span class="badge bg-label-primary">
                                            GoPay
                                        </span>

                                        <span class="badge bg-label-primary">
                                            ShopeePay
                                        </span>

                                        <span class="badge bg-label-primary">
                                            Credit Card
                                        </span>

                                        <span class="badge bg-label-primary">
                                            Bank Transfer
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="continuePaymentBtn">

                        <i class="bx bx-right-arrow-alt me-1"></i>

                        Continue

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>
  
@endsection

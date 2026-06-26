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
      INVOICE #MF-2023-10-1209
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
          <h4 class="mb-0"><b>Eleanor Pena</b></h4>
        </div>
      </div>

      <div class="col-lg-2">
        <small class="text-muted d-block">BOOKING DATE</small>
        <div><i class="bx bx-calendar text-primary me-1"></i> Oct 12, 2023</div>
      </div>

      <div class="col-lg-2">
        <small class="text-muted d-block">BOOKING TIME</small>
        <div><i class="bx bx-time text-primary me-1"></i>09:00 AM</div>
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
              <td>01</td>
              <td><strong>Consultation Fee</strong></td>
              <td><span class="badge bg-label-info">CONSULTATION</span></td>
              <td class="text-center">1</td>
              <td class="text-end">50,000 IDR</td>
              <td class="text-end text-primary fw-bold">50,000 IDR</td>
            </tr>

            <tr>
              <td>02</td>
              <td><strong>Amoxicillin 500mg</strong></td>
              <td><span class="badge bg-label-secondary">MEDICINE</span></td>
              <td class="text-center">20</td>
              <td class="text-end">1,500 IDR</td>
              <td class="text-end text-primary fw-bold">30,000 IDR</td>
            </tr>

            <tr>
              <td>03</td>
              <td><strong>Nebulization Therapy</strong></td>
              <td><span class="badge bg-label-primary">TREATMENT</span></td>
              <td class="text-center">1</td>
              <td class="text-end">45,000 IDR</td>
              <td class="text-end text-primary fw-bold">45,000 IDR</td>
            </tr>
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
          <strong>125,000 IDR</strong>
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
          125,000 <small class="fs-6 text-muted">IDR</small>
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

      <button class="btn btn-primary" id="processPaymentBtn">
        <i class="bx bx-credit-card me-1"></i>
        Process Payment
      </button>
    </div>
</div>
  
  
@endsection

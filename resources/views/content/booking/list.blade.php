@extends('layouts/contentNavbarLayout')

@section('title', 'Booking - List')

@section('page-style')
  @vite('resources/assets/css/booking-list.css')
@endsection

@section('page-script')
  @vite('resources/assets/js/booking-list.js')
@endsection

@section('content')

<div class="row g-4">

  {{-- Total Bookings --}}
  <div class="col-xl-3 col-md-6">
    <div class="card booking-summary-card">
      <div class="card-body d-flex align-items-center gap-4">

        <div class="booking-icon booking-icon-primary">
          <i class="bx bx-calendar-check"></i>
        </div>

        <div>
          <div class="summary-title">
            TOTAL BOOKINGS
          </div>

          <div id="totalBooking" class="summary-number">
            0
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Pending --}}
  <div class="col-xl-3 col-md-6">
    <div class="card booking-summary-card">
      <div class="card-body d-flex align-items-center gap-4">

        <div class="booking-icon booking-icon-info">
          <i class="bx bx-calendar-exclamation"></i>
        </div>

        <div>
          <div class="summary-title">
            PENDING
          </div>

          <div id="pendingBooking" class="summary-number">
            0
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Completed --}}
  <div class="col-xl-3 col-md-6">
    <div class="card booking-summary-card">
      <div class="card-body d-flex align-items-center gap-4">

        <div class="booking-icon booking-icon-success">
          <i class="bx bx-check-circle"></i>
        </div>

        <div>
          <div class="summary-title">
            COMPLETED
          </div>

          <div id="completedBooking" class="summary-number">
            0
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Cancelled --}}
  <div class="col-xl-3 col-md-6">
    <div class="card booking-summary-card">
      <div class="card-body d-flex align-items-center gap-4">

        <div class="booking-icon booking-icon-danger">
          <i class="bx bx-x-circle"></i>
        </div>

        <div>
          <div class="summary-title">
            CANCELLED
          </div>

          <div id="cancelledBooking" class="summary-number">
            0
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- Main Table --}}
  <div class="col-lg-9 order-2 order-lg-1">
    @if (session('success'))
      <div class="alert alert-success text-dark alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
        </button>
      </div>
    @endif
    <div class="card">

      <div
        class="card-header border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

        <div>
          <h4 class="mb-1 fw-bold">
            All Bookings
          </h4>

          <p class="mb-0 text-muted">
            Manage and monitor today's patient workflow
          </p>
        </div>

        <div class="d-flex gap-2">

          <div class="input-group">
            <span class="input-group-text bg-white">
              <i class="bx bx-search"></i>
            </span>

            <input
              id="searchBooking"
              type="text"
              class="form-control"
              placeholder="Search patients..." />
          </div>

          {{-- <button class="btn btn-outline-secondary">
            <i class="bx bx-filter-alt me-1"></i>
            Filter
          </button> --}}
          <div class="dropdown">
            <button
              class="btn btn-outline-secondary dropdown-toggle"
              type="button"
              data-bs-toggle="dropdown"
              data-bs-auto-close="outside">

              <i class="bx bx-filter-alt me-1"></i>
              Filter
            </button>

            <div class="dropdown-menu dropdown-menu-end p-3" style="width: 300px">

              <h6 class="mb-2">Type</h6>

              @foreach (\App\Enums\BookingType::cases() as $type)
                <div class="form-check">
                  <input
                    class="form-check-input filter-type"
                    type="checkbox"
                    value="{{ $type->value }}"
                    id="type-{{ $type->value }}">

                  <label
                    class="form-check-label"
                    for="type-{{ $type->value }}">

                    {{ $type->label() }}
                  </label>
                </div>
              @endforeach

              <hr>

              <h6 class="mb-2">Status</h6>

              @foreach (\App\Enums\BookingStatus::cases() as $status)
                <div class="form-check">
                  <input
                    class="form-check-input filter-status"
                    type="checkbox"
                    value="{{ $status->value }}"
                    id="status-{{ $status->value }}">

                  <label
                    class="form-check-label"
                    for="status-{{ $status->value }}">

                    {{ $status->label() }}
                  </label>
                </div>
              @endforeach

              <hr>

              <button
                id="clearFilter"
                class="btn btn-sm btn-outline-secondary w-100">

                Clear Filter
              </button>

            </div>
          </div>

        </div>

      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

          <thead>
            <tr>
              <th>Booking</th>
              <th>Patient</th>
              <th>Type</th>
              <th>Date & Time</th>
              <th>Doctor</th>
              <th>Status</th>
              <th width="100"></th>
            </tr>
          </thead>

          <tbody id="bookingTableBody">
          </tbody>

        </table>
      </div>
      <div
        id="paginationContainer"
        class="card-footer d-flex justify-content-end">
      </div>
    </div>
  </div>

  {{-- Quick View --}}
  <div class="col-lg-3 order-1 order-lg-2">
    <div class="card quick-view-card">
      <div class="card-body p-4">

        <h4 class="fw-bold mb-4">
          Quick View
        </h4>

        <div class="d-flex flex-column gap-3">

          <div class="quick-view-item" data-view="all">
            <span>All</span>
            <span id="allCount" class="quick-view-badge">0</span>
          </div>

          <div class="quick-view-item active" data-view="today">
            <span>Today</span>
            <span id="todayCount" class="quick-view-badge">0</span>
          </div>

          <div class="quick-view-item" data-view="upcoming">
            <span>Upcoming</span>
            <span id="upcomingCount" class="quick-view-badge">0</span>
          </div>

        </div>

      </div>
    </div>
  </div>

</div>
<div
    class="modal fade"
    id="cancelConsultationModal"
    tabindex="-1">

    <div class="modal-dialog">

        <form
            action="{{ route('consultation-cancel') }}"
            method="POST"
            class="modal-content">

            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="consultation_id"
                id="cancelConsultationId">

            <div class="modal-header">
                <h5 class="modal-title">
                    Cancel Consultation
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    Please provide a cancellation reason.
                </div>

                <div>
                    <label class="form-label">
                        Reason
                    </label>

                    <textarea required
                        name="cancel_reason"
                        class="form-control"
                        maxlength="300"
                        rows="4"
                        required></textarea>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-label-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>

                <button
                    type="submit"
                    class="btn btn-danger">
                    Confirm Cancellation
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
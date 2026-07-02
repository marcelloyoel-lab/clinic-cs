@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Appointment')

@section('page-script')
  @vite('resources/assets/js/dashboards-edit-schedule.js')
@endsection

@section('page-style')
  @vite('resources/assets/css/dashboards-edit-schedule.css')
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-end mb-4">
  <div>
    <h4 class="mb-1">Edit Schedule</h4>
    <p class="text-muted mb-0">
      Update patient information and appointment details
    </p>
  </div>

  <div>

    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>

        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="alert">
        </button>
      </div>
    @endif

    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show mb-4">
        {{ session('error') }}

        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="alert">
        </button>
      </div>
    @endif

    <a
      href="{{ route('dashboard-schedule') }}"
      class="btn btn-text-secondary p-0">

      <i class="bx bx-arrow-back me-1"></i>
      Back to Appointments

    </a>

  </div>
</div>

<form
    action="{{ route('booking-schedule-update', $booking) }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        {{-- ============================= --}}
        {{-- SECTION 1 --}}
        {{-- ============================= --}}

        <div class="card-body section-divider">

            <div class="section-title">

                <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        1
                    </span>
                </div>

                <h5 class="mb-0">
                    Patient Identity
                </h5>

            </div>

            {{-- Booking Summary --}}
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card summary-card border-0 bg-label-primary h-100">
                        <div class="card-body">
                            <small class="text-uppercase text-muted d-block mb-1">
                                Booking Code
                            </small>

                            <h6 class="mb-0 fw-semibold">
                                {{ $booking->booking_code }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 bg-label-info shadow-none h-100">
                        <div class="card-body">
                            <small class="text-uppercase text-muted d-block mb-1">
                                Patient ID
                            </small>

                            <h6 class="mb-0 fw-semibold">
                                #{{ str_pad($booking->consultation->patient->id, 5, '0', STR_PAD_LEFT) }}
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 bg-label-success shadow-none h-100">
                        <div class="card-body">
                            <small class="text-uppercase text-muted d-block mb-1">
                                Status
                            </small>

                            <h6 class="mb-0 fw-semibold">
                                {{ $booking->consultation->status->label() }}
                            </h6>
                        </div>
                    </div>
                </div>

            </div>

            <div class="patient-box">
              <div class="row g-3">

                  <div class="col-md-4">

                      <label class="form-label">
                          Full Name
                      </label>

                      <input
                          type="text"
                          name="name"
                          class="form-control"
                          value="{{ old('name', $booking->consultation->patient->name) }}"
                          required>
                      <div class="invalid-feedback">
                          Full Name is required.
                      </div>
                  </div>

                  <div class="col-md-4">

                      <label class="form-label">
                          Date of Birth
                      </label>

                      <input
                          type="text"
                          name="dob"
                          class="form-control flatpickr-date"
                          value="{{ old('dob', optional($booking->consultation->patient->dob)->format('Y-m-d')) }}"
                          required>

                      <div class="invalid-feedback">
                          DOB is required.
                      </div>

                  </div>

                  <div class="col-md-4">

                      <label class="form-label">
                          Gender
                      </label>

                      <select
                          name="gender"
                          class="form-select"
                          required>

                          <option disabled value="">
                              Select Gender
                          </option>

                          <option
                              value="0"
                              @selected(old('gender', $booking->consultation->patient->gender?->value) == 0)>
                              Male
                          </option>

                          <option
                              value="1"
                              @selected(old('gender', $booking->consultation->patient->gender?->value) == 1)>
                              Female
                          </option>

                      </select>

                       <div class="invalid-feedback">
                          Gender is required.
                      </div>

                  </div>

                  <div class="col-md-4">

                      <label class="form-label">
                          Phone Number
                      </label>

                      <input
                          type="text"
                          name="phone"
                          class="form-control"
                          value="{{ old('phone', $booking->consultation->patient->phone) }}"
                          required>

                      <div class="invalid-feedback">
                          Phone Number is required.
                      </div>

                  </div>

                  <div class="col-md-4">

                      <label class="form-label">
                          Alternative Phone Number
                      </label>

                      <input
                          type="text"
                          name="phone_alternative"
                          class="form-control"
                          value="{{ old('phone_alternative', $booking->consultation->patient->phone_alternative) }}">

                  </div>

                  <div class="col-md-4">

                      <label class="form-label">
                          Email
                      </label>

                      <input
                          type="email"
                          name="email"
                          class="form-control"
                          value="{{ old('email', $booking->consultation->patient->email) }}">
                      <div class="invalid-feedback">
                          Email is required.
                      </div>
                  </div>

              </div>

          </div>

        </div>

        {{-- ============================= --}}
        {{-- SECTION 2 --}}
        {{-- ============================= --}}

        <div class="card-body">

            <div class="section-title">

                <div class="avatar avatar-sm">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        2
                    </span>
                </div>

                <h5 class="mb-0">
                    Booking Detail
                </h5>

            </div>

            <div class="row g-4 align-items-stretch mb-4">

                {{-- Consultation --}}
                <div class="col-12 col-lg-6">

                    <label class="booking-card w-100">

                        <input
                            type="radio"
                            checked
                            disabled>

                        <div class="booking-card-body">

                            <div class="booking-icon bg-label-primary">
                                <i class="bx bx-plus-medical"></i>
                            </div>

                            <div class="mt-4">

                                <h5 class="mb-2">
                                    Consultation
                                </h5>

                                <p class="text-muted mb-0">
                                    Schedule a doctor consultation appointment to discuss symptoms and diagnosis.
                                </p>

                            </div>

                            <i class="bx bxs-check-circle booking-check"></i>

                        </div>

                    </label>

                    <input
                        type="hidden"
                        name="booking_type"
                        value="consultation">

                </div>

                {{-- Treatment --}}
                <div class="col-12 col-lg-6">

                    <label class="booking-card w-100">

                        <input
                            type="radio"
                            disabled>

                        <div class="booking-card-body">

                            <div class="booking-icon bg-label-secondary">
                                <i class="bx bx-clinic"></i>
                            </div>

                            <div class="mt-4">

                                <h5 class="mb-2">
                                    Treatment
                                </h5>

                                <p class="text-muted mb-0">
                                    Coming Soon
                                </p>

                            </div>

                        </div>

                    </label>

                </div>

            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">
                    Attending Doctor
                </label>

                <select
                    name="doctor_id"
                    class="form-select"
                    required>

                    <option value="" disabled>
                        Select Doctor
                    </option>

                    @foreach ($doctors as $doctor)

                        <option
                            value="{{ $doctor->id }}"
                            @selected(old('doctor_id', $booking->consultation->doctor_id) == $doctor->id)>

                            {{ $doctor->name }}

                        </option>

                    @endforeach

                </select>

                <div class="invalid-feedback">
                  Attending Doctor is required.
                </div>

              </div>

              <div class="col-md-3">

                  <label class="form-label">
                      Date
                  </label>

                  <input
                      type="text"
                      name="date"
                      class="form-control flatpickr-date"
                      value="{{ old('date', $booking->date->format('Y-m-d')) }}"
                      required>

                    <div class="invalid-feedback">
                        Appointment Date is required.
                    </div>

              </div>

              <div class="col-md-3">

                  <label class="form-label">
                      Time
                  </label>

                  <input
                      type="time"
                      name="time"
                      class="form-control"
                      value="{{ old('time', substr($booking->time, 0, 5)) }}"
                      required>
                    <div class="invalid-feedback">
                        Appointment Time is required.
                    </div>
              </div>

              <div class="col-12">

                  <label class="form-label">
                      Reason for Visit
                  </label>

                  <textarea
                      rows="4"
                      class="form-control"
                      name="chief_complaint"
                      placeholder="Briefly describe the patient's symptoms or reason for the consultation..."
                      required>{{ old('chief_complaint', $booking->consultation->chief_complaint) }}</textarea>
                   <div class="invalid-feedback">
                        Reason for Visit is required.
                  </div>
              </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2 pt-2">

                <a
                    href="{{ route('dashboard-schedule') }}"
                    class="btn btn-outline-secondary">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bx bx-save me-1"></i>
                    Update Schedule

                </button>

            </div>

        </div>

    </div>

</form>
@endsection
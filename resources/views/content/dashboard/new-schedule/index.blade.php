@extends('layouts/contentNavbarLayout')

@section('title', 'New Appointment')

@section('page-script')
  @vite('resources/assets/js/dashboards-add-schedule.js')
@endsection

@section('page-style')
  @vite('resources/assets/css/dashboards-add-schedule.css')
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-end mb-4">
  <div>
    <h4 class="mb-1">Create New Schedule</h4>
    <p class="text-muted mb-0">
      Book a new appointment or consultation
    </p>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
      <strong>Please fix the following errors:</strong>

      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>

      <button type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close">
      </button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger text-dark alert-dismissible fade show mb-4" role="alert">
      {{ session('error') }}

      <button type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close">
      </button>
    </div>
  @endif

  <a href="{{ route('dashboard-schedule') }}"
     class="btn btn-text-secondary p-0">
    <i class="bx bx-arrow-back me-1"></i>
    Back to Appointments
  </a>
</div>

<form action="{{ route('dashboard-schedule-store') }}" method="post">
  @csrf
  <div class="card">
  
    {{-- SECTION 1 --}}
    <div class="card-body section-divider">
  
      <div class="section-title">
        <div class="avatar avatar-sm">
          <span class="avatar-initial rounded-circle bg-label-primary">
            1
          </span>
        </div>
  
        <h5 class="mb-0">Patient Information</h5>
      </div>
  
      <div class="mb-4">
        <label class="form-label">
          Find Patient
        </label>
  
        <select id="patient-search" name="patient_id" class="form-select">
            <option></option>
  
            <optgroup label="Actions">
              <option value="-1">
                + Add New Patient
              </option>
            </optgroup>
  
            <optgroup label="Patients">
              @foreach ($patients as $patient)
                <option value="{{ $patient->id }}">
                  {{ $patient->name }} ({{ $patient->phone }})
                </option>
              @endforeach
            </optgroup>
        </select>
        <script type="application/json" id="patients-data">
          @json($patients)
        </script>
      </div>
  
      <div class="patient-box">
  
        <div class="row g-3">
  
          <div class="col-md-4">
            <label class="form-label">
              Full Name
            </label>
  
            <input
              type="text"
              id="patient-name"
              name="name"
              class="form-control patient-input"
              readonly>
          </div>
  
          <div class="col-md-4">
            <label class="form-label">
              Date of Birth
            </label>
  
            <input
              type="text"
              id="patient-dob"
              name="dob"
              class="form-control patient-input flatpickr-date"
              disabled>
          </div>
  
          <div class="col-md-4">
            <label class="form-label">
              Gender
            </label>
  
            <select
              id="patient-gender"
              name="gender"
              class="form-select patient-input"
              disabled>
              <option value="">
                Select Gender
              </option>
              <option value="0">
                Male
              </option>
              <option value="1">
                Female
              </option>
            </select>
          </div>
  
          <div class="col-md-4">
            <label class="form-label">
              Phone Number
            </label>
  
            <input
              type="text"
              id="patient-phone"
              name="phone"
              class="form-control patient-input"
              readonly>
          </div>
  
          <div class="col-md-4">
            <label class="form-label">
              Alternative Phone Number
            </label>
  
            <input
              type="text"
              id="patient-phone-alt"
              name="phone_alternative"
              class="form-control patient-input"
              readonly>
          </div>
  
          <div class="col-md-4">
            <label class="form-label">
              Email
            </label>
  
            <input
              type="email"
              id="patient-email"
              name="email"
              class="form-control patient-input"
              readonly>
          </div>
  
        </div>
  
      </div>
  
      <div class="text-end mt-3">
        <button disabled class="btn btn-label-primary">
          View Profile
        </button>
      </div>
  
    </div>
  
    {{-- SECTION 2 --}}
    <div class="card-body section-divider">
  
      <div class="section-title">
        <div class="avatar avatar-sm">
          <span class="avatar-initial rounded-circle bg-label-primary">
            2
          </span>
        </div>
  
        <h5 class="mb-0">Booking Type</h5>
      </div>
  
      <div class="row g-4 align-items-stretch">
  
        {{-- Consultation --}}
        <div class="col-12 col-lg-6">
          <label class="booking-card w-100">
  
            <input
              type="radio"
              name="booking_type"
              value="consultation"
              checked>
  
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
        </div>
  
        {{-- Treatment --}}
        <div class="col-12 col-lg-6">
          <label class="booking-card w-100">
  
            <input
              type="radio"
              name="booking_type"
              value="treatment">
  
            <div class="booking-card-body">
  
              <div class="booking-icon bg-label-secondary">
                <i class="bx bx-clinic"></i>
              </div>
  
              <div class="mt-4">
                <h5 class="mb-2">
                  Treatment
                </h5>
  
                <p class="text-muted mb-0">
                  Schedule a specific clinic treatment or procedure appointment.
                </p>
              </div>
  
            </div>
  
          </label>
        </div>
  
      </div>
  
    </div>
  
    {{-- SECTION 3 --}}
    <div class="card-body">
  
      <div class="section-title">
        <div class="avatar avatar-sm">
          <span class="avatar-initial rounded-circle bg-label-primary">
            3
          </span>
        </div>
  
        <h5 class="mb-0">
          Appointment Details
        </h5>
      </div>
  
      <div class="row g-3 mb-4">
  
        <div
          class="col-md-6"
          id="doctor-wrapper">
  
          <label class="form-label">
            Attending Doctor
          </label>
  
          <select name="doctor_id" class="form-select" required>
            <option value="" selected disabled>
              Select Doctor
            </option>
            @foreach ($doctors as $doctor)
              <option value="{{ $doctor->id }}">
                {{ $doctor->name }}
              </option>
            @endforeach
          </select>
  
        </div>
  
        <div
          class="col-md-6 d-none"
          id="treatment-wrapper">
  
          <label class="form-label">
            Treatment
          </label>
  
          <select class="form-select">
            <option>
              Select Treatment
            </option>
          </select>
  
        </div>
  
        <div class="col-md-3">
  
          <label class="form-label">
            Date
          </label>
  
          <input
            type="text"
            name="date"
            class="form-control flatpickr-date" required>
  
        </div>
  
        <div class="col-md-3">
  
          <label class="form-label">
            Time
          </label>
  
          <input
            type="time"
            name="time"
            class="form-control" required>
  
        </div>
  
        <div class="col-12">
  
          <label class="form-label">
            Reason for Visit
          </label>
  
          <textarea
            rows="4"
            class="form-control"
            name="chief_complaint"
            placeholder="Briefly describe the patient's symptoms or reason for the consultation..."></textarea>
  
        </div>
  
      </div>
  
      <hr>
  
      <div class="d-flex justify-content-end gap-2 pt-2">
  
        <a
          href=""
          class="btn btn-outline-secondary">
  
          Cancel
  
        </a>
  
        <button type="submit"
          class="btn btn-primary">
  
          <i class="bx bx-calendar-check me-1"></i>
          Create Schedule
  
        </button>
  
      </div>
  
    </div>
  
  </div>
</form>

@endsection
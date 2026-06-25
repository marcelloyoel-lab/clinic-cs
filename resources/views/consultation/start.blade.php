@extends('layouts/contentNavbarLayout')

@section('title', 'Consultation - Start')

@section('page-script')
  @vite('resources/assets/js/start-consultation.js')
@endsection

@section('page-style')
  @vite('resources/assets/css/start-consultation.css')
@endsection

@section('content')

<script>
    window.medicines = @json($medicines);
    window.treatments = @json($treatments);
</script>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
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
  <div>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
          <a href="{{ route('booking-list') }}">Consultations</a>
        </li>
        <li class="breadcrumb-item active">
          <b>Start Consultation</b>
        </li>
      </ol>
    </nav>

    <h3 class="mb-0">
      <b>Start Consultation</b>
    </h3>
  </div>

  <div>
    <span class="badge rounded-pill bg-label-info px-3 py-2 page-header-badge">
      <span class="status-dot me-2"></span>
      Active Session
    </span>
  </div>

</div>

<div id="validationAlert"
     class="alert alert-danger d-none mb-4">
  Please complete all required fields.
</div>

<form action="{{ route('input-consultation') }}" method="POST">
  @csrf
  <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
  
  <div class="row g-4">
  
    {{-- Patient Summary --}}
    <div class="col-12 col-lg-4">
      <div class="card patient-card">
        <div class="card-header">
          <h5 class="mb-0">Patient Summary</h5>
        </div>
  
        <div class="card-body">
          <div class="text-center mb-4">
            <div class="avatar avatar-xl mx-auto mb-3">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="bx bx-user"></i>
              </span>
            </div>
  
            <h5 class="mb-1">{{ $consultation->patient->name }}</h5>
            <small class="text-muted">
              Booking Code: {{ $consultation->booking->code }}
            </small>
          </div>
  
          <div class="row g-3">
  
            <div class="col-6">
              <label class="form-label">Age</label>
              <input type="text"
                     class="form-control"
                     value="{{ $consultation->patient->age }} Years"
                     readonly>
            </div>
  
            <div class="col-6">
              <label class="form-label">Gender</label>
              <input type="text"
                     class="form-control"
                     value="{{ $consultation->patient->gender->name }}"
                     readonly>
            </div>
  
            <div class="col-12">
              <label class="form-label">Last Visit</label>
              <input type="text"
                     class="form-control"
                     value="{{ $consultation->patient->last_visit ?? '-'}}"
                     readonly>
            </div>
  
          </div>
        </div>
      </div>
    </div>
  
    {{-- Diagnoses --}}
    <div class="col-12 col-lg-8">
      <div class="card diagnosis-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Diagnoses</h5>
  
          <button type="button"
                  class="btn btn-sm btn-primary"
                  id="addDiagnosisBtn">
            <i class="bx bx-plus"></i>
            Add Diagnosis
          </button>
        </div>
  
        <div class="card-body">
  
          <div id="diagnosisContainer">
  
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
  
          </div>
  
          {{-- <button type="button"
                  id="addDiagnosisBtn"
                  class="btn btn-sm btn-label-primary mt-3">
            <i class="bx bx-plus"></i>
            Add More
          </button> --}}
  
        </div>
      </div>
    </div>
  
    {{-- Prescription --}}
    <div class="col-12">
      <div class="card medicine-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Prescription / Medicines</h5>
  
          <button type="button"
                  class="btn btn-primary btn-sm"
                  id="addMedicineBtn">
            <i class="bx bx-plus"></i>
            Add Medicine
          </button>
        </div>
  
        <div class="card-body">
  
          <div id="medicineContainer">
  
            <div class="medicine-row">
  
              <div class="row g-3 align-items-end">
  
                <div class="col-12 col-md-4">
                  <label class="form-label">Medicine Name</label>
  
                  <select name="medicine_id[]"
                          class="form-select">
                    @foreach ($medicines as $medicine)
                      <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                    @endforeach
                  </select>
                </div>
  
                <div class="col-12 col-md-2">
                  <label class="form-label">Quantity</label>
  
                  <input type="number"
                        class="form-control"
                        name="quantity[]"
                        value="1">
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
  
          </div>
  
          {{-- <button type="button"
                  id="addMedicineBtn"
                  class="btn btn-label-secondary mt-3">
            <i class="bx bx-plus-circle"></i>
            Add Medicine
          </button> --}}
  
        </div>
      </div>
    </div>
  
    {{-- Treatment --}}
    <div class="col-12 col-lg-6">
      <div class="card treatment-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Treatment</h5>
  
          <button type="button"
                  class="btn btn-primary btn-sm"
                  id="addTreatmentBtn">
            <i class="bx bx-plus"></i>
            Add Treatment
          </button>
        </div>
  
        <div class="card-body">
  
          <div id="treatmentContainer">
  
            <div class="row g-3 treatment-row align-items-end">
  
              <div class="col-8">
                <label class="form-label">Treatment Type</label>
  
                <select name="treatment_id[]"
                        class="form-select">
                  @foreach ($treatments as $treatment)
                    <option value="{{ $treatment->id }}">{{ $treatment->name }}</option>
                  @endforeach
                </select>
              </div>
  
              <div class="col-3">
                <label class="form-label">Qty</label>
  
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
  
          </div>
  
          {{-- <button type="button"
                  id="addTreatmentBtn"
                  class="btn btn-sm btn-label-primary mt-3">
            <i class="bx bx-plus"></i>
            Add Treatment
          </button> --}}
  
        </div>
      </div>
    </div>
  
    {{-- Consultation Notes --}}
    <div class="col-12 col-lg-6">
      <div class="card notes-card">
        <div class="card-header">
          <h5 class="mb-0">Consultation Notes</h5>
        </div>
  
        <div class="card-body">
          <textarea id="consultationNotes"
            class="form-control h-100"
            name="notes"
            placeholder="Detailed consultation results, observations, and summary..."></textarea>
        </div>
      </div>
    </div>
  
    {{-- Footer Actions --}}
    <div class="col-12">
      <div class="d-flex justify-content-end gap-3">
  
        <button type="button"
                class="btn btn-label-secondary"
                data-bs-toggle="modal"
                data-bs-target="#cancelConsultationModal">
          Cancel Consultation
        </button>
  
        <button type="button"
          id="submitConsultationBtn"
          class="btn btn-primary px-5">
          <i class="bx bx-check-circle me-1"></i>
          Submit Consultation
        </button>
  
      </div>
    </div>
  
  </div>

</form>


<!-- Cancel Consultation Modal -->
<form action="{{ route('consultation-cancel', ['consultation' => $consultation->id]) }}"
      method="POST"
      id="cancelConsultationForm">
  @csrf
  @method('PUT')
  <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
  <div class="modal fade"
       id="cancelConsultationModal"
       tabindex="-1"
       aria-hidden="true">
  
    <div class="modal-dialog">
      <div class="modal-content">
  
        <div class="modal-header">
          <h5 class="modal-title">
            Cancel Consultation
          </h5>
  
          <button type="button"
                  class="btn-close"
                  data-bs-dismiss="modal">
          </button>
        </div>
  
        <div class="modal-body">
  
          <div class="alert alert-warning mb-3">
            Please provide a reason before cancelling this consultation.
          </div>
  
          <div>
            <label class="form-label">
              Cancellation Reason <span class="text-danger">*</span>
            </label>
  
            <textarea required
              id="cancelReason"
              name="cancel_reason"
              class="form-control"
              rows="4"
              placeholder="Example: Patient did not attend the consultation"></textarea>
  
            <div class="invalid-feedback">
              Cancellation reason is required.
            </div>
          </div>
  
        </div>
  
        <div class="modal-footer">
  
          <button type="button"
                  class="btn btn-label-secondary"
                  data-bs-dismiss="modal">
            Close
          </button>
  
          <button type="submit"
                  class="btn btn-danger"
                  id="confirmCancelConsultation">
            Confirm Cancellation
          </button>
  
        </div>
  
      </div>
    </div>
  
  </div>
</form>
@endsection

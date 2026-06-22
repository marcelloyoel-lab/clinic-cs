@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Schedule')

<script>
  window.todaySchedules = @json($todaySchedules);
  window.upcomingSchedules = @json($upcomingSchedules);
</script>
@section('page-script')
  @vite('resources/assets/js/dashboards-schedule.js')
@endsection

@section('page-style')
  @vite('resources/assets/css/dashboards.css')
@endsection

@section('content')


<div class="dashboard-schedule">

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

  {{-- PAGE HEADER --}}
  <div class="dashboard-header">

    <div>
      <h2 class="dashboard-title">
        Customer Service Dashboard
      </h2>

      <p class="dashboard-subtitle">
        Manage today's clinical flow and upcoming appointments.
      </p>
    </div>

    <div class="dashboard-date-card">
      <i class="bx bx-calendar"></i>
      <span>{{ now()->format('F d, Y') }}</span>
    </div>

  </div>

  <div class="row g-4">

    {{-- LEFT COLUMN --}}
    <div class="col-lg-4">

      {{-- TOTAL PATIENTS --}}
      <div class="glass-card metric-card mb-4">

        <div class="metric-icon">
          <i class="bx bx-group"></i>
        </div>

        <div>

          <div class="metric-label">
            Total Patients Today
          </div>

          <div class="metric-content">

            <h2 class="metric-value">
              {{ $totalPatients ?? 42 }}
            </h2>

            <span class="metric-badge">
              <i class="bx bx-trending-up"></i>
              12%
            </span>

          </div>

        </div>

      </div>

      {{-- CALENDAR --}}
      <div class="glass-card calendar-card">

        <div class="card-top">

          <h5>
            {{ now()->format('F Y') }}
          </h5>

          <div class="calendar-nav">

            <button class="btn btn-icon">
              <i class="bx bx-chevron-left"></i>
            </button>

            <button class="btn btn-icon">
              <i class="bx bx-chevron-right"></i>
            </button>

          </div>

        </div>

        <div id="dashboard-calendar"></div>

      </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div class="col-lg-8">

      {{-- TODAY SCHEDULE --}}
      <div class="glass-card table-card mb-4">

        <div class="card-header-custom">

          <div class="header-left">

            <div class="header-icon primary">
              <i class="bx bx-clipboard"></i>
            </div>

            <h5>
              Today's Schedule
            </h5>

          </div>

          <div class="header-actions">

            <input
              id="today-schedule-search"
              type="text"
              class="form-control search-input"
              placeholder="Filter patients..."
            >

            {{-- <a href="{{ route('dashboard-new-schedule') }}" class="btn btn-primary">
              <i class="bx bx-plus"></i>
              New Booking
            </a> --}}
            <div class="d-flex gap-2">
              <a href="{{ route('booking-list', ['view' => 'today']) }}"
                class="btn btn-label-secondary">
                View All
              </a>
              @role('CS')
              <a href="{{ route('booking-new') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i>
                New Booking
              </a>
              @endrole
            </div>

          </div>

        </div>

        <div class="table-responsive">

          <table class="table schedule-table align-middle mb-0">

            <thead>
              <tr>
                <th>No</th>
                <th>Patient Name</th>
                <th>Status</th>
                <th>Type</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>

            <tbody id="today-schedule-table-body"></tbody>

          </table>

        </div>

      </div>

      {{-- UPCOMING --}}
      <div class="glass-card table-card">

        <div class="card-header-custom">

          <div class="header-left">

            <div class="header-icon secondary">
              <i class="bx bx-history"></i>
            </div>

            <h5>
              Upcoming (Tomorrow)
            </h5>

          </div>

          <div class="header-actions">

            <a href="{{ route('booking-list', ['view' => 'upcoming']) }}"
              class="btn btn-label-secondary">
              View All
            </a>

          </div>

        </div>

        <div class="table-responsive">

          <table class="table schedule-table align-middle mb-0">

            <thead>
              <tr>
                <th>Time</th>
                <th>Patient Name</th>
                <th>Type</th>
                <th>Provider</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>

            <tbody id="upcoming-schedule-table-body"></tbody>

          </table>

        </div>

      </div>

    </div>

  </div>

</div>

@endsection
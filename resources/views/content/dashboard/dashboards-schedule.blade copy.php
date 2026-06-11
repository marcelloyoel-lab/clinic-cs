@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Schedule')

@section('page-script')
{{-- Point this to where your template compiles dashboards-schedule.js --}}
@vite('resources/assets/js/dashboards-schedule.js')
@endsection

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap');

  .mediflow-dashboard {
    font-family: 'Public Sans', sans-serif !important;
    --mf-primary: #4546da;
    --mf-primary-fixed: #e1e0ff;
    --mf-secondary: #535f6f;
    --mf-tertiary: #00657b;
    --mf-tertiary-fixed: #b5ebff;
    --mf-tertiary-fixed-dim: #43d6ff;
  }

  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    vertical-align: middle;
  }
  .material-symbols-outlined.fill {
    font-variation-settings: 'FILL' 1;
  }

  /* Glass Bento Card Layout Utilities */
  .bento-card {
    background: rgba(255, 255, 255, 0.75) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    border-radius: 1rem !important;
    transition: all 0.25s ease-in-out;
  }
  .bento-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
  }

  /* Interactive items handling layout table animations */
  .custom-table tbody tr {
    transition: background-color 0.15s ease;
    border-radius: 0.5rem;
  }
  .custom-table tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.5) !important;
  }
  .custom-table tbody tr:hover .action-btn-trigger {
    opacity: 1 !important;
  }
  .action-btn-trigger {
    opacity: 0;
    transition: opacity 0.15s ease;
  }
  
  /* Calendar grid spacing mechanics */
  .calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 0.5rem 0.25rem;
  }
  .calendar-day-node {
    padding: 0.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.15s ease;
  }
  .calendar-day-node:hover:not(.active-day) {
    background-color: rgba(67, 214, 255, 0.15);
    color: var(--mf-primary);
  }
</style>

<div class="container-xxl flex-grow-1 container-p-y mediflow-dashboard position-relative">
  
  <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3 mb-4">
    <div>
      <h4 class="fw-bold mb-1" style="color: #1b1b23; font-size: 26px; letter-spacing: -0.02em;">Customer Service Dashboard</h4>
      <p class="text-muted mb-0" style="font-size: 15px;">Manage today's clinical flow and upcoming appointments.</p>
    </div>
    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 bento-card text-muted shadow-sm" style="font-size: 14px;">
      <span class="material-symbols-outlined text-secondary" style="font-size: 18px;">today</span>
      <span class="fw-medium">October 15, 2023</span>
    </div>
  </div>

  <div class="row g-4">
    
    <div class="col-12 col-lg-4 d-flex flex-column gap-4">
      
      <div class="bento-card p-4 d-flex align-items-center gap-3 shadow-sm">
        <div class="d-flex align-items-center justify-content-center text-dark shadow-sm" 
             style="width: 56px; height: 56px; border-radius: 1rem; background: linear-gradient(135deg, var(--mf-tertiary-fixed), var(--mf-tertiary-fixed-dim)); border: 1px solid rgba(255,255,255,0.5);">
          <span class="material-symbols-outlined text-dark" style="font-size: 28px; color: var(--mf-tertiary) !important;">groups</span>
        </div>
        <div>
          <p class="text-muted mb-1 fw-medium" style="font-size: 13px;">Total Patients Today</p>
          <div class="d-flex align-items-baseline gap-2">
            <h3 class="mb-0 fw-bold" style="color: #1b1b23; font-size: 28px;">42</h3>
            <span class="badge rounded-pill d-inline-flex align-items-center px-2 py-1" style="background-color: #e6f4ea; color: #1e8e3e; font-size: 11px;">
              <span class="material-symbols-outlined me-0.5" style="font-size: 12px;">arrow_upward</span>12%
            </span>
          </div>
        </div>
      </div>

      <div class="bento-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="mb-0 fw-bold" style="color: #1b1b23; font-size: 18px;">October 2023</h5>
          <div class="d-inline-flex gap-1 p-1 bg-light rounded border border-white">
            <button class="btn btn-sm p-1 text-muted border-0 bg-transparent hover:bg-white" type="button">
              <span class="material-symbols-outlined" style="font-size: 18px;">chevron_left</span>
            </button>
            <button class="btn btn-sm p-1 text-muted border-0 bg-transparent hover:bg-white" type="button">
              <span class="material-symbols-outlined" style="font-size: 18px;">chevron_right</span>
            </button>
          </div>
        </div>

        <div class="calendar-grid text-center text-muted fw-bold mb-2 text-uppercase" style="font-size: 11px; letter-spacing: 0.05em;">
          <div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div class="text-light-muted">Sa</div><div class="text-light-muted">Su</div>
        </div>

        <div class="calendar-grid text-center">
          <div class="calendar-day-node text-muted opacity-25">25</div><div class="calendar-day-node text-muted opacity-25">26</div><div class="calendar-day-node text-muted opacity-25">27</div>
          <div class="calendar-day-node text-muted opacity-25">28</div><div class="calendar-day-node text-muted opacity-25">29</div><div class="calendar-day-node text-muted opacity-25">30</div>
          <div class="calendar-day-node text-dark">1</div><div class="calendar-day-node text-dark">2</div><div class="calendar-day-node text-dark">3</div>
          <div class="calendar-day-node text-dark">4</div><div class="calendar-day-node text-dark">5</div><div class="calendar-day-node text-dark">6</div>
          <div class="calendar-day-node text-dark">7</div><div class="calendar-day-node text-dark">8</div><div class="calendar-day-node text-dark">9</div>
          <div class="calendar-day-node text-dark">10</div>
          <div class="calendar-day-node text-dark position-relative">11<span class="position-absolute bottom-0 start-50 translate-middle-x rounded-circle" style="width: 5px; height: 5px; background-color: var(--mf-primary);"></span></div>
          <div class="calendar-day-node text-dark">12</div><div class="calendar-day-node text-dark">13</div><div class="calendar-day-node text-dark">14</div>
          <div class="calendar-day-node active-day text-white fw-bold shadow-sm" style="background: linear-gradient(135deg, var(--mf-primary), #5f61f4); transform: scale(1.05);">15</div>
          <div class="calendar-day-node text-dark position-relative">16<span class="position-absolute bottom-0 start-50 translate-middle-x rounded-circle" style="width: 5px; height: 5px; background-color: var(--mf-tertiary);"></span></div>
          <div class="calendar-day-node text-dark">17</div><div class="calendar-day-node text-dark">18</div><div class="calendar-day-node text-dark">19</div>
          <div class="calendar-day-node text-dark">20</div><div class="calendar-day-node text-dark">21</div><div class="calendar-day-node text-dark">22</div>
          <div class="calendar-day-node text-dark">23</div><div class="calendar-day-node text-dark">24</div><div class="calendar-day-node text-dark">25</div>
          <div class="calendar-day-node text-dark">26</div><div class="calendar-day-node text-dark">27</div><div class="calendar-day-node text-dark">28</div>
          <div class="calendar-day-node text-dark">29</div><div class="calendar-day-node text-dark">30</div><div class="calendar-day-node text-dark">31</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8 d-flex flex-column gap-4">
      
      <div class="bento-card d-flex flex-column shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-bottom border-white d-flex justify-content-between align-items-center" style="background-color: rgba(255, 255, 255, 0.2);">
          <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background-color: rgba(69, 70, 218, 0.1);">
              <span class="material-symbols-outlined" style="font-size: 18px; color: var(--mf-primary);">clinical_notes</span>
            </div>
            <h5 class="mb-0 fw-bold" style="color: #1b1b23; font-size: 18px;">Today's Schedule</h5>
          </div>
          <button class="btn btn-sm px-3 fw-medium text-primary border border-transparent hover:border-primary-subtle" style="font-size: 14px; background-color: transparent;">View All</button>
        </div>
        
        <div class="table-responsive p-2">
          <table class="table custom-table table-borderless align-middle mb-0">
            <thead>
              <tr style="font-size: 11px; letter-spacing: 0.05em; color: var(--mf-secondary);">
                <th class="text-uppercase fw-semibold py-2">No</th>
                <th class="text-uppercase fw-semibold py-2">Patient Name</th>
                <th class="text-uppercase fw-semibold py-2">Status</th>
                <th class="text-uppercase fw-semibold py-2">Type</th>
                <th class="text-uppercase fw-semibold py-2 text-end">Action</th>
              </tr>
            </thead>
            <tbody style="font-size: 15px; color: #1b1b23;" id="today-schedule-table-body">
              </tbody>
          </table>
        </div>
      </div>

      <div class="bento-card d-flex flex-column shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-bottom border-white d-flex justify-content-between align-items-center" style="background-color: rgba(255, 255, 255, 0.2);">
          <div class="d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background-color: rgba(83, 95, 111, 0.1);">
              <span class="material-symbols-outlined" style="font-size: 18px; color: var(--mf-secondary);">update</span>
            </div>
            <h5 class="mb-0 fw-bold" style="color: #1b1b23; font-size: 18px;">Upcoming (Tomorrow)</h5>
          </div>
          <button class="btn btn-sm btn-icon border border-white bg-transparent rounded-lg shadow-sm text-secondary" type="button">
            <span class="material-symbols-outlined" style="font-size: 20px;">filter_list</span>
          </button>
        </div>
        
        <div class="table-responsive p-2">
          <table class="table custom-table table-borderless align-middle mb-0">
            <thead>
              <tr style="font-size: 11px; letter-spacing: 0.05em; color: var(--mf-secondary);">
                <th class="text-uppercase fw-semibold py-2">Time</th>
                <th class="text-uppercase fw-semibold py-2">Patient Name</th>
                <th class="text-uppercase fw-semibold py-2">Type</th>
                <th class="text-uppercase fw-semibold py-2">Provider</th>
                <th class="text-uppercase fw-semibold py-2 text-end">Action</th>
              </tr>
            </thead>
            <tbody style="font-size: 15px; color: #1b1b23;" id="upcoming-schedule-table-body">
              </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
/**
 * MediFlow CMS - Dashboard Schedule
 */

document.addEventListener('DOMContentLoaded', () => {
  // ------------------------------------------------------------------
  // DATA SOURCE
  // ------------------------------------------------------------------

  // const todaysSchedule = [
  //   {
  //     id: '#1042',
  //     name: 'Sarah Jenkins',
  //     status: 'Arrived',
  //     type: 'Consultation',
  //     badgeStyle: 'background-color:#e6f4ea;color:#1e8e3e;border:1px solid rgba(30,142,62,.2);'
  //   },
  //   {
  //     id: '#1043',
  //     name: 'Michael Chang',
  //     status: 'Waiting',
  //     type: 'Follow-up',
  //     badgeStyle: 'background-color:#fef7e0;color:#f29900;border:1px solid rgba(242,153,0,.2);'
  //   },
  //   {
  //     id: '#1044',
  //     name: 'Emily Davis',
  //     status: 'Scheduled',
  //     type: 'Vaccination',
  //     badgeStyle: 'background-color:#d6e4f6;color:#101d2a;border:1px solid rgba(186,200,218,.3);'
  //   },
  //   {
  //     id: '#1045',
  //     name: 'Robert Wilson',
  //     status: 'Delayed',
  //     type: 'Consultation',
  //     badgeStyle: 'background-color:#fce8e6;color:#d93025;border:1px solid rgba(217,48,37,.2);'
  //   }
  // ];

  const todaysSchedule = window.todaySchedules ?? [];
  const upcomingSchedule = window.upcomingSchedules ?? [];

  // ------------------------------------------------------------------
  // DOM
  // ------------------------------------------------------------------

  const todayContainer = document.getElementById('today-schedule-table-body');

  const upcomingContainer = document.getElementById('upcoming-schedule-table-body');

  const searchInput = document.getElementById('today-schedule-search');

  const calendarContainer = document.getElementById('dashboard-calendar');

  // ------------------------------------------------------------------
  // INIT
  // ------------------------------------------------------------------

  renderTodaySchedule();
  renderUpcomingSchedule();
  generateCalendar();

  // ------------------------------------------------------------------
  // SEARCH
  // ------------------------------------------------------------------

  if (searchInput) {
    searchInput.addEventListener('input', e => {
      const keyword = e.target.value.toLowerCase();

      const filtered = todaysSchedule.filter(row => row.name.toLowerCase().includes(keyword));

      renderTodaySchedule(filtered);
    });
  }

  // ------------------------------------------------------------------
  // TODAY SCHEDULE
  // ------------------------------------------------------------------

  function renderTodaySchedule(data = todaysSchedule) {
    if (!todayContainer) return;

    todayContainer.innerHTML = data
      .map(
        row => `
        <tr class="align-middle">

          <td class="py-3 text-muted fw-medium">
            ${row.booking_code}
          </td>

          <td class="py-3 fw-semibold text-dark">
            ${row.name}
          </td>

          <td class="py-3">
            <span
              class="badge ${row.status_class} px-2 py-1 fw-semibold"
            >
              ${row.status}
            </span>
          </td>

          <td class="py-3 text-muted">
            ${row.type}
          </td>

          <td class="py-3 text-end">
            ${renderActions(row.actions)}
          </td>

        </tr>
      `
      )
      .join('');
  }

  // ------------------------------------------------------------------
  // UPCOMING SCHEDULE
  // ------------------------------------------------------------------

  function renderUpcomingSchedule(data = upcomingSchedule) {
    if (!upcomingContainer) return;

    upcomingContainer.innerHTML = data
      .map(
        row => `
        <tr class="align-middle">

          <td class="py-3 fw-semibold text-dark">
            ${row.time}
          </td>

          <td class="py-3 text-dark">
            ${row.patient_name}
          </td>

          <td class="py-3 text-muted">
            ${row.type}
          </td>

          <td class="py-3 text-muted">
            ${row.doctor_name}
          </td>

          <td class="py-3 text-end">

            <button
              type="button"
              class="btn btn-sm btn-icon row-action"
            >
              <i class="bx bx-calendar-edit"></i>
            </button>

          </td>

        </tr>
      `
      )
      .join('');
  }

  // ------------------------------------------------------------------
  // ACTION DROPDOWN
  // ------------------------------------------------------------------

  function renderActions(actions = []) {
    if (!actions.length) {
      return '';
    }

    const items = actions
      .map(action => {
        if (action.key === 'cancel-consultation') {
          return `
            <li>
              <button
                type="button"
                class="dropdown-item cancel-consultation-btn"
                data-consultation-id="${action.id}">
                <i class="bx ${action.icon} me-2"></i>
                ${action.label}
              </button>
            </li>
          `;
        }

        return `
          <li>
            <a
              href="${action.url}"
              class="dropdown-item">
              <i class="bx ${action.icon} me-2"></i>
              ${action.label}
            </a>
          </li>
        `;
      })
      .join('');

    return `
      <div class="dropdown">
        <button
          type="button"
          class="btn btn-sm btn-icon"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end">
          ${items}
        </ul>
      </div>
    `;
  }

  // ------------------------------------------------------------------
  // CALENDAR
  // ------------------------------------------------------------------

  function generateCalendar() {
    if (!calendarContainer) return;

    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    const currentDate = today.getDate();

    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);

    const totalDays = lastDay.getDate();

    let startingDay = firstDay.getDay();
    startingDay = startingDay === 0 ? 6 : startingDay - 1;

    const eventDays = [11, 16];

    let html = `
      <div class="calendar-weekdays">
        <div>Mo</div>
        <div>Tu</div>
        <div>We</div>
        <div>Th</div>
        <div>Fr</div>
        <div>Sa</div>
        <div>Su</div>
      </div>

      <div class="calendar-grid">
    `;

    for (let i = 0; i < startingDay; i++) {
      html += `<div></div>`;
    }

    for (let day = 1; day <= totalDays; day++) {
      html += `
        <div
          class="calendar-day ${day === currentDate ? 'active' : ''}"
        >
          ${day}

          ${eventDays.includes(day) ? `<span class="calendar-dot"></span>` : ''}
        </div>
      `;
    }

    html += `
      </div>
    `;

    calendarContainer.innerHTML = html;
  }

  document.addEventListener('click', e => {
    const button = e.target.closest('.cancel-consultation-btn');

    if (!button) {
      return;
    }

    document.querySelector('#cancelConsultationId').value = button.dataset.consultationId;

    const modal = new bootstrap.Modal(document.getElementById('cancelConsultationModal'));

    modal.show();
  });
});

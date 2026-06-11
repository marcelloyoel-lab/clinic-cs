/**
 * MediFlow CMS - Dashboard Schedule Front End Script Engine
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Core Front-End State Array Engine (Dummy Data)
  const todaysSchedule = [
    {
      id: '#1042',
      name: 'Sarah Jenkins',
      status: 'Arrived',
      type: 'Consultation',
      badgeStyle: 'background-color: #e6f4ea; color: #1e8e3e; border: 1px solid rgba(30,142,62,0.2);'
    },
    {
      id: '#1043',
      name: 'Michael Chang',
      status: 'Waiting',
      type: 'Follow-up',
      badgeStyle: 'background-color: #fef7e0; color: #f29900; border: 1px solid rgba(242,153,0,0.2);'
    },
    {
      id: '#1044',
      name: 'Emily Davis',
      status: 'Scheduled',
      type: 'Vaccination',
      badgeStyle: 'background-color: #d6e4f6; color: #101d2a; border: 1px solid rgba(186,200,218,0.3);'
    },
    {
      id: '#1045',
      name: 'Robert Wilson',
      status: 'Delayed',
      type: 'Consultation',
      badgeStyle: 'background-color: #fce8e6; color: #d93025; border: 1px solid rgba(217,48,37,0.2);'
    }
  ];

  const upcomingSchedule = [
    { time: '09:00 AM', name: 'Amanda Lee', type: 'Annual Physical', provider: 'Dr. Smith' },
    { time: '09:30 AM', name: 'David Miller', type: 'Follow-up', provider: 'Dr. Smith' },
    { time: '10:15 AM', name: 'Jessica Brown', type: 'Consultation', provider: 'Dr. Adams' }
  ];

  // 2. DOM Rendering Handlers
  const todayContainer = document.getElementById('today-schedule-table-body');
  const upcomingContainer = document.getElementById('upcoming-schedule-table-body');

  if (todayContainer) {
    todayContainer.innerHTML = todaysSchedule
      .map(
        row => `
      <tr class="align-middle border-bottom border-light">
        <td class="py-3 text-muted fw-medium">${row.id}</td>
        <td class="py-3 fw-bold text-dark table-patient-title">${row.name}</td>
        <td class="py-3">
          <span class="badge font-weight-bold d-inline-flex align-items-center px-2.5 py-1 text-uppercase" style="${row.badgeStyle} font-size: 11px; font-weight:700;">
            ${row.status}
          </span>
        </td>
        <td class="py-3 text-muted">${row.type}</td>
        <td class="py-3 text-end">
          <button class="btn btn-sm btn-icon p-1 rounded-lg text-secondary action-btn-trigger" type="button" style="background-color: transparent;">
            <span class="material-symbols-outlined" style="font-size: 20px;">more_vert</span>
          </button>
        </td>
      </tr>
    `
      )
      .join('');
  }

  if (upcomingContainer) {
    upcomingContainer.innerHTML = upcomingSchedule
      .map(
        row => `
      <tr class="align-middle border-bottom border-light">
        <td class="py-3 fw-bold text-dark">${row.time}</td>
        <td class="py-3 fw-medium text-dark">${row.name}</td>
        <td class="py-3 text-muted">${row.type}</td>
        <td class="py-3 text-muted">${row.provider}</td>
        <td class="py-3 text-end">
          <button class="btn btn-sm btn-icon p-1 rounded-lg text-secondary action-btn-trigger" type="button" style="background-color: transparent;">
            <span class="material-symbols-outlined" style="font-size: 18px;">edit_calendar</span>
          </button>
        </td>
      </tr>
    `
      )
      .join('');
  }
});

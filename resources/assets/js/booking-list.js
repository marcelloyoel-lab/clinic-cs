'use strict';

const ENDPOINTS = {
  bookings: '/bookings/data'
};

const urlParams = new URLSearchParams(window.location.search);

const state = {
  page: 1,
  search: '',
  view: urlParams.get('view') ?? 'today',
  types: [],
  statuses: []
};

const tableBody = document.querySelector('#bookingTableBody');
const paginationContainer = document.querySelector('#paginationContainer');
const searchInput = document.querySelector('#searchBooking');

const typeFilters = document.querySelectorAll('.filter-type');
const statusFilters = document.querySelectorAll('.filter-status');
const clearFilterButton = document.querySelector('#clearFilter');

document.addEventListener('DOMContentLoaded', () => {
  bindEvents();
  setActiveQuickView();
  loadBookings();
});

function bindEvents() {
  if (!searchInput) return;

  searchInput.addEventListener(
    'input',
    debounce(e => {
      state.page = 1;
      state.search = e.target.value.trim();

      loadBookings();
    }, 500)
  );

  typeFilters.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      state.page = 1;

      state.types = [...typeFilters].filter(x => x.checked).map(x => Number(x.value));

      loadBookings();
    });
  });

  statusFilters.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      state.page = 1;

      state.statuses = [...statusFilters].filter(x => x.checked).map(x => Number(x.value));

      loadBookings();
    });
  });

  clearFilterButton?.addEventListener('click', () => {
    state.page = 1;
    state.types = [];
    state.statuses = [];

    typeFilters.forEach(x => (x.checked = false));
    statusFilters.forEach(x => (x.checked = false));

    loadBookings();
  });
}

function renderStats(stats) {
  document.querySelector('#totalBooking').textContent = stats.total;

  document.querySelector('#pendingBooking').textContent = stats.pending;

  document.querySelector('#completedBooking').textContent = stats.completed;

  document.querySelector('#cancelledBooking').textContent = stats.cancelled;
}

async function loadBookings() {
  try {
    // const response = await fetch(
    //   `${ENDPOINTS.bookings}?page=${state.page}&search=${encodeURIComponent(state.search)}&view=${state.view}`
    // );

    const params = new URLSearchParams({
      page: state.page,
      search: state.search,
      view: state.view
    });

    state.types.forEach(type => {
      params.append('types[]', type);
    });

    state.statuses.forEach(status => {
      params.append('statuses[]', status);
    });

    const response = await fetch(`${ENDPOINTS.bookings}?${params.toString()}`);

    if (!response.ok) {
      throw new Error('Failed to load bookings');
    }

    const data = await response.json();

    renderCounts(data.counts);
    renderStats(data.stats);
    renderTable(data.data);
    renderPagination(data);
  } catch (error) {
    console.error(error);

    if (tableBody) {
      tableBody.innerHTML = `
        <tr>
          <td colspan="7" class="text-center py-5 text-danger">
            Failed to load bookings.
          </td>
        </tr>
      `;
    }
  }
}

function renderTable(bookings) {
  if (!tableBody) return;

  if (!bookings.length) {
    tableBody.innerHTML = `
      <tr>
        <td colspan="7" class="text-center py-5 text-muted">
          No bookings found.
        </td>
      </tr>
    `;

    return;
  }

  tableBody.innerHTML = bookings
    .map(
      booking => `
        <tr>
          <td>
            <span class="fw-semibold">
              ${booking.code}
            </span>
          </td>

          <td>
            <div>
              <h6 class="mb-0">
                ${booking.patient ?? '-'}
              </h6>

              <small class="text-muted">
                ${booking.phone ?? '-'}
              </small>
            </div>
          </td>

          <td>
            <span class="badge ${booking.type_badge}">
              ${booking.type}
            </span>
          </td>

          <td>
            <div>${booking.date}</div>

            <small class="text-muted">
              ${booking.time}
            </small>
          </td>

          <td>
            ${booking.doctor ?? '-'}
          </td>

          <td>
            <span class="badge ${booking.status_badge}">
              ${booking.status}
            </span>
          </td>

          <td class="text-end">
            ${renderActions(booking.actions)}
          </td>
        </tr>
      `
    )
    .join('');
}

function renderActions(actions = []) {
  if (!actions.length) {
    return '';
  }

  const items = actions
    .map(
      action => `
        <li>
          <a
            href="${action.url}"
            class="dropdown-item"
            data-action="${action.key}"
            data-id="${action.id}"
          >
            <i class="bx ${action.icon} me-2"></i>
            ${action.label}
          </a>
        </li>
      `
    )
    .join('');

  return `
    <div class="dropdown">
      <button
        type="button"
        class="btn p-0"
        data-bs-toggle="dropdown"
        aria-expanded="false"
      >
        <i class="bx bx-dots-vertical-rounded"></i>
      </button>

      <ul class="dropdown-menu dropdown-menu-end">
        ${items}
      </ul>
    </div>
  `;
}

function renderPagination(data) {
  if (!paginationContainer) return;

  if (data.last_page <= 1) {
    paginationContainer.innerHTML = '';
    return;
  }

  let html = `
    <nav>
      <ul class="pagination mb-0">
  `;

  html += `
    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
      <button
        class="page-link"
        data-page="${data.current_page - 1}">
        Previous
      </button>
    </li>
  `;

  for (let page = 1; page <= data.last_page; page++) {
    html += `
      <li class="page-item ${page === data.current_page ? 'active' : ''}">
        <button
          class="page-link"
          data-page="${page}">
          ${page}
        </button>
      </li>
    `;
  }

  html += `
    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
      <button
        class="page-link"
        data-page="${data.current_page + 1}">
        Next
      </button>
    </li>
  `;

  html += `
      </ul>
    </nav>
  `;

  paginationContainer.innerHTML = html;

  paginationContainer.querySelectorAll('[data-page]').forEach(button => {
    button.addEventListener('click', () => {
      const page = Number(button.dataset.page);

      if (page < 1 || page > data.last_page || page === state.page) {
        return;
      }

      state.page = page;
      loadBookings();
    });
  });
}

function debounce(fn, delay) {
  let timer;

  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

const quickViewItems = document.querySelectorAll('.quick-view-item');

quickViewItems.forEach(item => {
  item.addEventListener('click', () => {
    state.view = item.dataset.view;
    state.page = 1;

    setActiveQuickView();
    loadBookings();
  });
});

function setActiveQuickView() {
  quickViewItems.forEach(item => {
    item.classList.toggle('active', item.dataset.view === state.view);
  });
}

function renderCounts(counts) {
  document.querySelector('#allCount').textContent = counts.all;

  document.querySelector('#todayCount').textContent = counts.today;

  document.querySelector('#upcomingCount').textContent = counts.upcoming;
}

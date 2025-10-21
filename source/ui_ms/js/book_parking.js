// ../js/dropdown_searchbar.js
document.addEventListener('DOMContentLoaded', function () {
  const searchBtn = document.getElementById('searchBtn');
  const searchSection = document.getElementById('searchSection');
  const parkingList = document.getElementById('parkingList');
  const activePills = document.getElementById('activePills');

  // Dropdown generic handlers
  const dropdowns = document.querySelectorAll('.dropdown');
  dropdowns.forEach(drop => {
    const toggle = drop.querySelector('.dropdown-toggle');
    const panel = drop.querySelector('.dropdown-panel');

    // Toggle click
    toggle.addEventListener('click', function (e) {
      const isOpen = drop.classList.contains('open');
      // Close any other open dropdowns first
      document.querySelectorAll('.dropdown.open').forEach(d => {
        if (d !== drop) {
          d.classList.remove('open');
          const t = d.querySelector('.dropdown-toggle');
          if (t) t.setAttribute('aria-expanded', 'false');
        }
      });

      if (isOpen) {
        drop.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      } else {
        drop.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
      }
      e.stopPropagation();
    });

    // Prevent clicks inside panel from closing via document listener
    if (panel) {
      panel.addEventListener('click', function (e) {
        e.stopPropagation();
      });
    }
  });

  // Close dropdowns if click outside
  document.addEventListener('click', function () {
    document.querySelectorAll('.dropdown.open').forEach(d => {
      d.classList.remove('open');
      const t = d.querySelector('.dropdown-toggle');
      if (t) t.setAttribute('aria-expanded', 'false');
    });
  });

  // Update pills when filters (checkboxes) change
  function refreshPills() {
    if (!activePills) return;
    activePills.innerHTML = ''; // reset

    // checked filter checkboxes
    const checkedFilters = Array.from(document.querySelectorAll('.dropdown#filtersDropdown input[type="checkbox"]:checked'));
    checkedFilters.forEach(inp => {
      const val = inp.value;
      const labelText = inp.closest('label')?.querySelector('.filter-label-text')?.textContent || val;
      const pill = document.createElement('div');
      pill.className = 'pill';
      pill.textContent = labelText;
      activePills.appendChild(pill);
    });

    // selected distance (radio)
    const selectedDistance = document.querySelector('.dropdown#distanceDropdown input[name="distance"]:checked');
    if (selectedDistance) {
      const labelText = selectedDistance.closest('label')?.querySelector('.filter-label-text')?.textContent || selectedDistance.value;
      const pill = document.createElement('div');
      pill.className = 'pill';
      pill.textContent = `Distance: ${labelText}`;
      activePills.appendChild(pill);
    }

    // hide pills container if empty (aria-hidden already set; keep for visual)
    activePills.setAttribute('aria-hidden', activePills.children.length === 0 ? 'true' : 'false');
  }

  // attach listeners to filter checkboxes
  const filterCheckboxes = document.querySelectorAll('#filtersDropdown input[type="checkbox"]');
  filterCheckboxes.forEach(cb => cb.addEventListener('change', refreshPills));

  // attach listeners to distance radios
  const distanceRadios = document.querySelectorAll('#distanceDropdown input[name="distance"]');
  distanceRadios.forEach(r => r.addEventListener('change', function () {
    // Optionally close the distance dropdown after selection for convenience
    const dd = document.getElementById('distanceDropdown');
    if (dd) {
      dd.classList.remove('open');
      const t = dd.querySelector('.dropdown-toggle');
      if (t) t.setAttribute('aria-expanded', 'false');
    }
    refreshPills();
  }));

  // --- Existing Search behaviour: solleva la search bar e mostra parkingList ---
  function findNavElement() {
    return document.querySelector('nav') || document.querySelector('[role="navigation"]') || null;
  }

  if (searchBtn && searchSection && parkingList) {
    searchBtn.addEventListener('click', function () {
      // chiudi tutti i dropdown
      document.querySelectorAll('.dropdown.open').forEach(d => {
        d.classList.remove('open');
        const t = d.querySelector('.dropdown-toggle');
        if (t) t.setAttribute('aria-expanded', 'false');
      });

      if (searchSection.classList.contains('is-raised')) return;

      // se preferisci calcolo dinamico commenta la linea con is-raised CSS e usa translate dinamico
      searchSection.classList.add('is-raised');

      // mostra le card con una piccola delay per effetto
      setTimeout(() => {
        parkingList.style.display = 'grid';
        parkingList.classList.add('visible');
      }, 280);
    });
  }

  // init: refresh pill display in case pre-checked controls exist
  refreshPills();
});



    /* Transition Part*/
document.addEventListener('DOMContentLoaded', function () {
  const searchBtn = document.getElementById('searchBtn');
  const searchSection = document.getElementById('searchSection');
  const parkingList = document.getElementById('parkingList');

  if (!searchBtn || !searchSection || !parkingList) return;

  searchBtn.addEventListener('click', function () {
    // Se già attiva, non rifare l'animazione
    if (searchSection.classList.contains('is-raised')) return;

    // Solleva la search bar
    searchSection.classList.add('is-raised');

    // Mostra le card dopo un breve delay per un effetto fluido
    setTimeout(() => {
      parkingList.style.display = 'grid';
      parkingList.classList.add('visible');
    }, 300);
  });
});


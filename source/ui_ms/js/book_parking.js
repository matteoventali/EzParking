document.addEventListener('DOMContentLoaded', function () {
  const searchBtn = document.getElementById('searchBtn');
  const searchSection = document.getElementById('searchSection');
  const parkingList = document.getElementById('parkingList');
  const activePills = document.getElementById('activePills');
  const loader = document.querySelector('.loader-section');

  // --- Generic dropdown logic  ---
  const dropdowns = document.querySelectorAll('.dropdown');
  dropdowns.forEach(drop => {
    const toggle = drop.querySelector('.dropdown-toggle');
    const panel = drop.querySelector('.dropdown-panel');

    toggle.addEventListener('click', function (e) {
      const isOpen = drop.classList.contains('open');
      // Close other open dropdowns
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

    if (panel) panel.addEventListener('click', e => e.stopPropagation());
  });

  document.addEventListener('click', function () {
    document.querySelectorAll('.dropdown.open').forEach(d => {
      d.classList.remove('open');
      const t = d.querySelector('.dropdown-toggle');
      if (t) t.setAttribute('aria-expanded', 'false');
    });
  });

  // --- Pills refresh ---
  function refreshPills() {
    if (!activePills) return;
    activePills.innerHTML = '';

    const checkedFilters = Array.from(document.querySelectorAll('.dropdown#filtersDropdown input[type="checkbox"]:checked'));
    checkedFilters.forEach(inp => {
      const labelText = inp.closest('label')?.querySelector('.filter-label-text')?.textContent || inp.value;
      const pill = document.createElement('div');
      pill.className = 'pill';
      pill.textContent = labelText;
      activePills.appendChild(pill);
    });

    const selectedDistance = document.querySelector('.dropdown#distanceDropdown input[name="distance"]:checked');
    if (selectedDistance) {
      const labelText = selectedDistance.closest('label')?.querySelector('.filter-label-text')?.textContent || selectedDistance.value;
      const pill = document.createElement('div');
      pill.className = 'pill';
      pill.textContent = `Distance: ${labelText}`;
      activePills.appendChild(pill);
    }

    activePills.setAttribute('aria-hidden', activePills.children.length === 0 ? 'true' : 'false');
  }

  document.querySelectorAll('#filtersDropdown input[type="checkbox"]').forEach(cb => cb.addEventListener('change', refreshPills));
  document.querySelectorAll('#distanceDropdown input[name="distance"]').forEach(r => {
    r.addEventListener('change', function () {
      const dd = document.getElementById('distanceDropdown');
      if (dd) {
        dd.classList.remove('open');
        const t = dd.querySelector('.dropdown-toggle');
        if (t) t.setAttribute('aria-expanded', 'false');
      }
      refreshPills();
    });
  });

  refreshPills(); // init

  // --- Search / loader logic---
  if (!searchBtn || !searchSection || !parkingList) return;

  let busy = false;
  let loaderTimeoutId = null;
  let showCardsTimeoutId = null;

  searchBtn.addEventListener('click', function () {
    // do not refresh again if already loaded
    if (searchSection.classList.contains('is-raised') || busy) return;

    busy = true;

    // close open dropdowns
    document.querySelectorAll('.dropdown.open').forEach(d => {
      d.classList.remove('open');
      const t = d.querySelector('.dropdown-toggle');
      if (t) t.setAttribute('aria-expanded', 'false');
    });

    // show loader
    if (loader) {
      // solleva la search bar
      searchSection.classList.add('is-raised');
      loader.style.display = 'block';
      loader.setAttribute('aria-hidden', 'false');
    }

    // clean old timeouts
    if (loaderTimeoutId) clearTimeout(loaderTimeoutId);
    if (showCardsTimeoutId) clearTimeout(showCardsTimeoutId);

    // after four seconds close loader and show results
    loaderTimeoutId = setTimeout(() => {
      if (loader) {
        loader.style.display = 'none';
        loader.setAttribute('aria-hidden', 'true');
      }
  
      showCardsTimeoutId = setTimeout(() => {
        parkingList.style.display = 'grid';
        parkingList.classList.add('visible');

        busy = false;
      }, 280);
    }, 2500);
  });
});

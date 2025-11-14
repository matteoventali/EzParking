document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("searchForm");
    const searchSection = document.getElementById("searchSection");
    const parkingList = document.getElementById("parkingList");
    const searchBtn = document.getElementById("searchBtn");
    const loaderSection = document.querySelector(".loader-section");

    const MIN_LOADER_TIME = 1000;

    function applyPostSearchIfCards() {
        const hasCard = parkingList && parkingList.querySelector(".parking-card") !== null;
        if (!hasCard) return false;
        searchSection.classList.add("is-raised");
        parkingList.classList.add("visible");
        if (loaderSection) loaderSection.style.display = "none";
        return true;
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        if (form.dataset.animating === "1") return;

        form.dataset.animating = "1";
        if (searchBtn) {
            searchBtn.disabled = true;
            searchBtn.setAttribute("aria-busy", "true");
        }

        searchSection.classList.add("is-raised");
        if (loaderSection) loaderSection.style.display = "block";
        parkingList.classList.remove("visible");

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        const startTime = performance.now();

        xhr.open("POST", "../php/perform_parking_search.php", true);

        xhr.onload = function () {
            const elapsed = performance.now() - startTime;
            const remaining = Math.max(MIN_LOADER_TIME - elapsed, 0);

            setTimeout(() => {
                if (loaderSection) loaderSection.style.display = "none";

                if (xhr.status === 200) {
                    parkingList.innerHTML = xhr.responseText;
                    parkingList.classList.add("visible");
                    applyPostSearchIfCards();
                } else {
                    parkingList.innerHTML = `<p class="error">Search error (${xhr.status})</p>`;
                }

                form.dataset.animating = "0";
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.removeAttribute("aria-busy");
                }
            }, remaining);
        };

        xhr.onerror = function () {
            if (loaderSection) loaderSection.style.display = "none";
            parkingList.innerHTML = `<p class="error">Network error</p>`;
            form.dataset.animating = "0";
            if (searchBtn) {
                searchBtn.disabled = false;
                searchBtn.removeAttribute("aria-busy");
            }
        };

        xhr.send(formData);
    });
});



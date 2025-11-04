document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("searchForm");
            const searchSection = document.getElementById("searchSection");
            const parkingList = document.getElementById("parkingList");
            const searchBtn = document.getElementById("searchBtn");
            const loaderSection = document.querySelector(".loader-section");
      
            const LOADER_MS = 3000;  
            const POST_SHOW_DELAY = 250; 

         
            function applyPostSearchIfCards() {
                const hasCard = parkingList && parkingList.querySelector(".parking-card") !== null;
                if (!hasCard) return false;
                searchSection.classList.add("is-raised");
                parkingList.classList.add("visible");
                
                if (loaderSection) loaderSection.style.display = "none";
                return true;
            }

            
            if (sessionStorage.getItem("searchAnimated") === "1") {
                window.requestAnimationFrame(() => {
                    const applied = applyPostSearchIfCards();
                    
                    sessionStorage.removeItem("searchAnimated");
                   
                });
            }

            
            form.addEventListener("submit", (e) => {
             
                if (form.dataset.animating === "1") return;

                e.preventDefault();

             
                sessionStorage.setItem("searchAnimated", "1");

                form.dataset.animating = "1";
                if (searchBtn) {
                    searchBtn.disabled = true;
                    searchBtn.setAttribute("aria-busy", "true");
                }

                
                searchSection.classList.add("is-raised");

                
                if (loaderSection) {
                    loaderSection.style.display = "block";
                }
         
                parkingList.classList.remove("visible");

               
                setTimeout(() => {
                    if (loaderSection) loaderSection.style.display = "none";

                  
                    parkingList.classList.add("visible");

                 
                    setTimeout(() => {
                       
                        form.submit();
                    }, POST_SHOW_DELAY);

                }, LOADER_MS);
            });

        });
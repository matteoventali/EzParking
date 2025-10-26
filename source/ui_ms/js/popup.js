(function () {

       
        const deleteBtns = document.querySelectorAll('.delete-booking-btn');
        const modal = document.getElementById('deleteModal');
        const modalBackdrop = document.querySelector('.modal-backdrop');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function handleKeydown(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        }

        
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', openModal);
        });

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', closeModal);
        }

        document.addEventListener('keydown', handleKeydown);

        window.DeleteModal = {
            open: openModal,
            close: closeModal
        };

    })();
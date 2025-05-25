document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('toggleEditProfileBtn');
    const cancelButton = document.getElementById('cancelEditBtn');
    const displaySection = document.getElementById('profileDisplaySection');
    const formSection = document.getElementById('profileEditFormSection');
    const cardHeader = toggleButton ? toggleButton.closest('.card-header').querySelector('h4') : null;

    if (!toggleButton || !displaySection || !formSection || !cardHeader) {
        return;
    }

    let isEditMode = false;

    function toggleMode(event) {
        if (event) {
            event.preventDefault();
        }
        isEditMode = !isEditMode;
        if (isEditMode) {
            displaySection.style.display = 'none';
            formSection.style.display = 'block';
            toggleButton.innerHTML = '<i class="bi bi-x-circle me-1"></i> Anuluj Edycję';
            toggleButton.classList.remove('btn-outline-primary');
            toggleButton.classList.add('btn-outline-danger');
            cardHeader.textContent = 'Edytuj Swój Profil';
        } else {
            displaySection.style.display = 'block';
            formSection.style.display = 'none';
            toggleButton.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Edytuj Profil';
            toggleButton.classList.remove('btn-outline-danger');
            toggleButton.classList.add('btn-outline-primary');
            cardHeader.textContent = 'Twój Profil';
        }
    }

    toggleButton.addEventListener('click', toggleMode);

    if (cancelButton) {
        cancelButton.addEventListener('click', toggleMode);
    }

    const hasErrors = formSection.dataset.hasErrors === 'true';
    if (hasErrors) {
        isEditMode = false;
        toggleMode();
    }
});
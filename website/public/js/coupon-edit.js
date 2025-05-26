document.addEventListener('DOMContentLoaded', function () {
    const codeTypeSelect = document.getElementById('code_type');
    const manualCodeField = document.getElementById('manual_code_field');
    const codeInput = document.getElementById('code');

    function toggleManualCodeField() {
        if (codeTypeSelect.value === 'manual') {
            manualCodeField.style.display = 'block';
            codeInput.required = true;
        } else {
            manualCodeField.style.display = 'none';
            codeInput.required = false;
            codeInput.value = '';
        }
    }

    if(codeTypeSelect) {
        codeTypeSelect.addEventListener('change', toggleManualCodeField);
        toggleManualCodeField();
    }
});
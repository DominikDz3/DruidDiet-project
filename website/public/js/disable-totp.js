function confirmDisableTotp() {
    if (confirm('Czy na pewno chcesz wyłączyć uwierzytelnianie dwuskładnikowe? Zmniejszy to bezpieczeństwo Twojego konta.')) {
        document.getElementById('disableTotpForm').submit();
    }
}
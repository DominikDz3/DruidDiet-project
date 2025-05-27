function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Klucz skopiowany do schowka!');
        }, function(err) {
            prompt('Nie udało się automatycznie skopiować. Skopiuj ręcznie:', text);
        });
    } else {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            alert('Klucz skopiowany do schowka!');
        } catch (err) {
            prompt('Nie udało się automatycznie skopiować. Skopiuj ręcznie:', text);
        }
        document.body.removeChild(textArea);
    }
}
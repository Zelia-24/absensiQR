function validateForm() {
    const inputs = document.querySelectorAll("input[required], textarea[required]");
    for (let i = 0; i < inputs.length; i++) {
        if (!inputs[i].value.trim()) {
            alert("Form tidak boleh kosong");
            inputs[i].focus();
            return false;
        }
    }
    return true;
}

function confirmAction(msg) {
    return confirm(msg || "Yakin ingin melanjutkan?");
}

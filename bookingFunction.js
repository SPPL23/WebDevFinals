function showPassword() {
    const pw = document.getElementById("showPW");
    if (pw.type === "password") {
        pw.type = "text";
    } else {
        pw.type = "password";
    }
}

function showConfirmPassword() {
    const cpw = document.getElementById("showCPW");
    if (cpw.type === "password") {
        cpw.type = "text";
    } else {
        cpw.type = "password";
    }
}
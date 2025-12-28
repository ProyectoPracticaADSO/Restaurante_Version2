document.getElementById('perfil').addEventListener('change', function() {
    var contrasenaDiv = document.getElementById('contrasenaDiv');
    if (this.value == '1') {
        contrasenaDiv.style.display = 'block';
        document.getElementById('contrasena').required = true;
    } else {
        contrasenaDiv.style.display = 'none';
        document.getElementById('contrasena').required = false;
    }
});
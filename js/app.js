
document.addEventListener("DOMContentLoaded", () => {

    const password = document.getElementById("password");
    const toggle = document.getElementById("togglePassword");
    const mensaje = document.getElementById("mensajeLogin");

    if (toggle && password) {
        toggle.addEventListener("click", () => {
            const visible = password.type === "text";

            password.type = visible ? "password" : "text";

            toggle.innerHTML = visible
                ? '<i class="fa-regular fa-eye"></i>'
                : '<i class="fa-regular fa-eye-slash"></i>';
        });
    }

    document.getElementById("btnGoogle")
        ?.addEventListener("click", () => {
            mensaje.textContent =
                "El acceso con Google todavía no está disponible.";
        });

    document.getElementById("recuperarPassword")
        ?.addEventListener("click", (evento) => {
            evento.preventDefault();
            mensaje.textContent =
                "La recuperación de contraseña estará disponible próximamente.";
        });

});

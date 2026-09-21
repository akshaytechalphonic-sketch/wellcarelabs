import './bootstrap';
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".button-toggle-menu");

    if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
            document.body.classList.toggle("sidebar-collapsed");
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const navToggleBtn = document.getElementById("navToggleBtn");
    const mobileNavMenu = document.getElementById("mobileNavMenu");
    const mobileNavOverlay = document.getElementById("mobileNavOverlay");

    function toggleMobileMenu() {
        if (mobileNavMenu) {
            const isOpen = mobileNavMenu.classList.toggle("open");
            if (mobileNavOverlay) {
                mobileNavOverlay.classList.toggle("active", isOpen);
            }
        }
    }

    function closeMobileMenu() {
        if (mobileNavMenu) mobileNavMenu.classList.remove("open");
        if (mobileNavOverlay) mobileNavOverlay.classList.remove("active");
    }

    if (navToggleBtn) {
        navToggleBtn.addEventListener("click", toggleMobileMenu);
    }

    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener("click", closeMobileMenu);
    }

    window.addEventListener("resize", () => {
        if (window.innerWidth > 860) {
            closeMobileMenu();
        }
    });
});

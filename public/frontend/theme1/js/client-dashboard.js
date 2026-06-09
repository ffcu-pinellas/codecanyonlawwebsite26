(function ($){
    "use strict";
    
    const toggleBtn = document.querySelector('#clientDashboardMenuBtn');
    const closeBtn = document.querySelector('#mobileCloseSidebar');
    const overlay = document.querySelector('#sidebarOverlay');
    const logoutBtn = document.querySelector('#logOut');

    const closeSidebar = () => {
        const toggleEl = document.querySelector('.toggle');
        if (toggleEl) toggleEl.classList.remove('active');
        
        const navEl = document.querySelector('.client-navigation');
        if (navEl) navEl.classList.remove('active');
        
        const mainEl = document.querySelector('.main');
        if (mainEl) mainEl.classList.remove('active');
        
        if (overlay) overlay.classList.remove('active');
    };

    const toggleMenu = () => {
        const toggleEl = document.querySelector('.toggle');
        const navEl = document.querySelector('.client-navigation');
        const mainEl = document.querySelector('.main');
        
        if (toggleEl) toggleEl.classList.toggle('active');
        if (navEl) navEl.classList.toggle('active');
        if (mainEl) mainEl.classList.toggle('active');
        if (overlay) overlay.classList.toggle('active');
    };

    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleMenu);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    }
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = logoutBtn.querySelector('#logOutForm');
            if (form) {
                form.submit();
            } else {
                const globalForm = document.getElementById('logOutForm');
                if (globalForm) globalForm.submit();
            }
        });
    }
})(jQuery);


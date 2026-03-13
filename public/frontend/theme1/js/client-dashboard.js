(function ($){
    "use stripe";
    document.querySelector('#clientDashboardMenuBtn').addEventListener('click', toggleMenu);
    
    const closeSidebar = () => {
        document.querySelector('.toggle').classList.remove('active');
        document.querySelector('.client-navigation').classList.remove('active');
        document.querySelector('.main').classList.remove('active');
        document.querySelector('#sidebarOverlay').classList.remove('active');
    };

    function toggleMenu(){
        let toggleBtn = document.querySelector('.toggle');
        let navigation = document.querySelector('.client-navigation');
        let main = document.querySelector('.main');
        let overlay = document.querySelector('#sidebarOverlay');
        
        toggleBtn.classList.toggle('active');
        navigation.classList.toggle('active');
        main.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Mobile specific close events
    const overlay = document.querySelector('#sidebarOverlay');
    const closeBtn = document.querySelector('#mobileCloseSidebar');
    
    if(overlay) overlay.addEventListener('click', closeSidebar);
    if(closeBtn) closeBtn.addEventListener('click', closeSidebar);

    document.querySelector('#logOut').addEventListener('click', logout);
    function logout(e){
      e.preventDefault();
      document.querySelector('#logOut').querySelector('#logOutForm').submit();
    }
})(jQuery)

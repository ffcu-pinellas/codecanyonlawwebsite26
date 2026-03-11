(function ($){
    "use stripe";
    document.querySelector('#clientDashboardMenuBtn').addEventListener('click', toggleMenu);
    function toggleMenu(){
        let toggleMenu = document.querySelector('.toggle');
        let navigation = document.querySelector('.client-navigation');
        let main = document.querySelector('.main');
        toggleMenu.classList.toggle('active');
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    }

    document.querySelector('#logOut').addEventListener('click', logout);
    function logout(e){
      e.preventDefault();
      document.querySelector('#logOut').querySelector('#logOutForm').submit();
    }
})(jQuery)

(function($) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.expand-btn').on('click', showCardContent);

    function showCardContent(){
        const content = this.parentElement;
        const form = content.querySelector('form');
        const page = form.querySelector('input[name="page"]');
        const group = form.querySelector('input[name="group"]');
        if ($(this).parent().find('form').hasClass('d-none')){
            $(this).parent().find('form').removeClass('d-none');
            getinputFilds(form, page.value, group.value);
            if (!form.getAttribute('action')) {
                form.addEventListener('submit', getInputs);
            }
        }else {
            $(this).parent().find('form').addClass('d-none');
        }
    }

    function showForm(){
        const content = this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
        const form = content.parentElement
        const formInputs = content.getElementsByClassName('row')[1];
        const footer = content.parentElement.querySelector('.card-footer');
        if ($(this).prop('checked')){
            formInputs.classList.remove('d-none');
            footer.classList.remove('d-none');
            if (!form.getAttribute('action')) {
                form.addEventListener('submit', getInputs);
            }
        }else {
            formInputs.classList.add('d-none');
            footer.classList.add('d-none');
            if (!form.getAttribute('action')) {
                deleteInputs(form);
            }else {
                form.querySelector("input[name=show]").value = null
                form.submit();
            }

        }
    }

    function getInputs(e){
        e.preventDefault();
        const inputs = this.getElementsByTagName('input');
        const inputedData = [];
        for (let i = 0; i < inputs.length; i++){
            inputedData.push({
                name:inputs[i].getAttribute('name'),
                value:inputs[i].value,

            })
        }
        if (this.querySelector('textarea')){
            inputedData.push({
                name:this.querySelector('textarea').getAttribute('name'),
                value:this.querySelector('textarea').value,

            })
        }
        $.ajax({
            type: 'post',
            url: '/admin/page-settings/store-page',
            data: inputedData,
            success:function (data){
                toastr.warning(data.msg);
                // toastr.success('Showen Successfully');
            }
        })
    }

    function deleteInputs(form){
        const inputs = form.getElementsByTagName('input');
        const inputedData = [];
        for (let i = 0; i < inputs.length; i++){
            if (inputs[i].getAttribute('name') == 'show'){
                inputedData.push({
                    name:inputs[i].getAttribute('name'),
                    value: null,

                })
            }else {
                inputedData.push({
                    name:inputs[i].getAttribute('name'),
                    value:inputs[i].value,

                })
            }
        }
        if (form.querySelector('textarea')){
            inputedData.push({
                name:form.querySelector('textarea').getAttribute('name'),
                value:form.querySelector('textarea').value,

            })
        }
        $.ajax({
            type: 'post',
            url: '/admin/page-settings/store-page',
            data: inputedData,
            success:function (data){
                toastr.warning(data.msg);
                // toastr.success('Hidden Successfully');
            }
        })
    }

    function getinputFilds(form, page, group){
        console.log(group)
        $.ajax({
            type:"get",
            url:'/admin/page-settings/input-fields',
            data:{
                page:page,
                group:group
            },
            success:function (data){
                form.innerHTML = data.html;
                action(data.section);
            }
        })
    }

    function action(data){

        $('.content-show').on('change', showForm);
        if (data == 'faq_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'client_dashboard_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'teams_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'blogs_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'cases_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'services_breadcumb_bg_img') {
            $('.breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'left_about_img') {
            $('.left-about-img').imageUploader({
                imagesInputName: 'fnt_img',
                maxFiles: 1,
            });
        }else if (data == 'about_left_img'){
            $('.home-page-about-img').imageUploader({
                imagesInputName: 'fnt_img',
                maxFiles: 1,
            });
        }else if (data == 'home_page_service'){
            $('.home-page-service-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'contact_breadcumb_bg_img'){
            $('.contact-page-breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }else if (data == 'about_breadcumb_bg_img'){
            $('.about-page-breadcumb-bg-img').imageUploader({
                imagesInputName: 'bg_img',
                maxFiles: 1,
            });
        }


    }
})(jQuery);

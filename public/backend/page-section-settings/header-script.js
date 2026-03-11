(function($) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.expand-btn').on('click', showCardContent);
    $('.content-show').on('change', showForm);

    let showBtn = document.getElementsByClassName('content-show');
    for (let c = 0; c < showBtn.length; c++){
        const content = showBtn[c].parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
        const form = content.parentElement;
        const formInputs = content.getElementsByClassName('row')[1];
        const footer = content.parentElement.querySelector('.card-footer');
        if (showBtn[c].checked){
            formInputs.classList.remove('d-none');
            footer.classList.remove('d-none');
            if (!form.getAttribute('action')) {
                form.addEventListener('submit', getInputs);
            }
        }
    }

    function showCardContent(){
        const content = this.parentElement;
        const form = content.querySelector('form');
        if ($(this).parent().find('form').hasClass('d-none')){
            $(this).parent().find('form').removeClass('d-none');
            if (!form.getAttribute('action')) {
                form.addEventListener('submit', getInputs);
            }
        }else {
            $(this).parent().find('form').addClass('d-none');
        }
    }

    function showForm(){
        const content = this.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
        const form = content.parentElement;
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
                form.querySelector("input[name=show]").value = null;
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

        $.ajax({
            type: 'post',
            url: '/admin/page-settings/store-page',
            data: inputedData,
            success:function (data){
                toastr.warning(data.msg);
                // toastr.success('Shown Successfully');
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

})(jQuery);

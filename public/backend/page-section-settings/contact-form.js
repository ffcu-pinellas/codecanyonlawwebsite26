(function($) {
    "use strict";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const form = document.getElementById('contact_form');
    const inputFields = form.getElementsByTagName('input');
    const submit = form.querySelector('.btn_the');
    submit.addEventListener('click', getInputs);

    function getInputs() {

        let request = [];
        for (let i = 0; i < inputFields.length; i++){
            request.push({
                name:inputFields[i].getAttribute('name'),
                value:inputFields[i].value
            });
            if (inputFields[i].getAttribute('name') != 'website') {
                if (!inputFields[i].value) {
                    inputFields[i].setAttribute('placeholder', 'This ' + inputFields[i].getAttribute('name') + ' field is required');
                } else {
                    inputFields[i].setAttribute('placeholder', 'Your ' + inputFields[i].getAttribute('name'));
                    inputFields[i].value = '';
                }
            }
        }

        if (form.querySelector('textarea')){
            request.push({
                name:form.querySelector('textarea').getAttribute('name'),
                value:form.querySelector('textarea').value,
            });
            if (!form.querySelector('textarea').value){
                form.querySelector('textarea').setAttribute('placeholder', 'This '+form.querySelector('textarea').getAttribute('name')+' field is required');
            }else{
                form.querySelector('textarea').setAttribute('placeholder', 'Your '+form.querySelector('textarea').getAttribute('name'));
                form.querySelector('textarea').value = '';
            }

        }
        for (let r = 0; r < request.length; r++){
            if (request[r].name != 'website') {
                if (!request[r].value) {
                    return;
                }
            }
        }

        $.ajax({
            type: 'post',
            url: '/contacts',
            data: request,
            success:function (data){
                react(data);
            }
        })
    }

    function react(data){
        if (data = 'success'){
            form.getElementsByClassName('form-row')[1].querySelector('.form-group').innerHTML = '<p class="text-black">\'Our consultant will reply you soon.\'</p>';
            setTimeout(function(){
                form.getElementsByClassName('form-row')[1].querySelector('.form-group').innerHTML = '';
            }, 3000);
        }
    }

})(jQuery);

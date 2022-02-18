function addRemoveClass1(type,id){
    if(type){
        $(id).closest('.form-group').removeClass('has-success');
        $(id).closest('.form-group').addClass('has-error');
    }else{
        $("#language_dir").closest('.form-group').removeClass('has-error');
        $("#language_dir").closest('.form-group').addClass('has-success');
    }
}
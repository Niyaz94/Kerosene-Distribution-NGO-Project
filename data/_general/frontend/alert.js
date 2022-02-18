


// Prompt
$('#sweet_prompt').on('click', function() {
    swal({
        title: "An input!",
        text: "Write something interesting:",
        type: "input",
        showCancelButton: true,
        confirmButtonColor: "#2196F3",
        closeOnConfirm: false,
        animation: "slide-from-top",
        inputPlaceholder: "Write something"
    },
    function(inputValue){
        if (inputValue === false) return false;
        if (inputValue === "") {
            swal.showInputError("You need to write something!");
            return false
        }
        swal({
            title: "Nice!",
            text: "You wrote: " + inputValue,
            type: "success",
            confirmButtonColor: "#2196F3"
        });
    });
});
// AJAX loader
$('#sweet_loader').on('click', function() {
    swal({
        title: "Ajax request example",
        text: "Submit to run ajax request",
        type: "info",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonColor: "#2196F3",
        showLoaderOnConfirm: true
    },
    function() {
        setTimeout(function() {
            swal({
                title: "Ajax request finished!",
                confirmButtonColor: "#2196F3"
            });
        }, 2000);
    });
});
$('#sweet_combine').on('click', function() {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#EF5350",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel pls!",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm){
        if (isConfirm) {
            swal({
                title: "Deleted!",
                text: "Your imaginary file has been deleted.",
                confirmButtonColor: "#66BB6A",
                type: "success"
            });
        }
        else {
            swal({
                title: "Cancelled",
                text: "Your imaginary file is safe :)",
                confirmButtonColor: "#2196F3",
                type: "error"
            });
        }
    });
});

function oneAlert(type="info",title="This Info Alert",text="This Info Alert",timer,showCancelButton=false,confirmButtonText=""){
    var color="#2196F3";
    if(type=="info"){
        color="#2196F3";
    }else if(type=="warning"){
        color="#FF7043";
    }else if(type=="error"){
        color="#EF5350";
    }else if(type=="success"){
        color="#66BB6A";
    }
    swal({
        title: Translation(title),
        text: Translation(text),
        confirmButtonColor:color,
        type: type,
        html: true,
        showCancelButton: showCancelButton,
        ...(confirmButtonText.length>0 ? {confirmButtonText:confirmButtonText} : {}),
        ...(timer!=null ? {timer:timer} : {})
    });
}

function secondAlert(type="info",title="Are you sure ?",text="What you want ?",confirmButtonText="Yes",cancelButtonText="Cancel"){
    var color="#2196F3";
    if(type=="info"){
        color="#2196F3";
    }else if(type=="warning"){
        color="#FF7043";
    }else if(type=="error"){
        color="#EF5350";
    }else if(type=="success"){
        color="#66BB6A";
    }
    return {
            title: Translation(title),
            text: Translation(text),
            type: type,
            showCancelButton: true,
            confirmButtonColor:color,
            confirmButtonText: Translation(confirmButtonText),
            cancelButtonText: Translation(cancelButtonText),
            closeOnConfirm: true,
            closeOnCancel: true
        }
    ;
}
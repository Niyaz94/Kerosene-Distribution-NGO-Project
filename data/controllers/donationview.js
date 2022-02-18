
$(document).ready(function () {
    addingExtenton();
    table = $("#datatabledonationviewView").DataTable({
        buttons: {
            buttons: dtButtons()
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ["10", "25","50","100"]
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_donationview.php",
            "data": function (d) {
                d.type = "load";
                d.FMYID=$("#FMYID").val();
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                        {},
                        {
                            "DOTID":row[0],
                            "FMYID":row[4],
                        },
                        "table"
                    );
                }
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
        "order": [
            [0, "desc"]
        ],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatabledonationviewView");
    generalConfig(); 
});
function deletedonation(DOTID,FMYID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_donationview.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "DOTID_UIZP": DOTID,
                        "DOTDeleted_UIZN":1,
                        "FMYID":FMYID
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatabledonationviewView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            setTimeout(function(){
                                oneAlert("error","Error!!!",res.data);
                            },500);
                        }
                    }
                }); 
            }
        }
    );
}
function viewdonation(DOTID) {
    $.ajax({
        url: "models/_donationview.php",
        type: "POST",
        dataType: "JSON",
        data:{
            "type":"getData",
            "PageName":$("#PageName").val(),
            "DOTID_UIZP": DOTID,
        },
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            if (res.is_success == true) {
                addToTable("#donationviewTable",res.data);
            } else {
                setTimeout(function(){
                    oneAlert("error","Error!!!",res.data);
                },500);
            }
        }
    });
    $("#editdonationviewModal").modal("toggle");
} 
function addToTable(id,res){
    resLen=res.length;
    $(id).empty();
    for(i=0;i<resLen;i++){
        //PROBLEM
        $(id).append(`
            <tr>
                <td>${res[i]["DDTID"]}</td>
                <td>${res[i]["PDUName"]}</td>
                <td>${res[i]["DDTType"]}</td>
                <td>${Number(res[i]["DDTQty"])}</td>
            </tr>
        `);
    }
} 
    
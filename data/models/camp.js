
$(document).ready(function () {
    $("#addcampForm").on("submit", function (e) {
        e.preventDefault();
        $("#savecampFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("CMPNote_IAZN",CKEDITOR.instances["CMPNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_camp.php",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                res=JSON.parse(res);
                if(res.is_success == true){
                    $("#addcampForm")[0].reset();
                    deselectSelect2();
                    CKEDITOR.instances["CMPNote_IAZN"].setData(""); 
                    $("#datatablecampView").DataTable().ajax.reload(null, false);
                    $("#addcampCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#savecampFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savecampFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editcampForm").on("submit", function (e) {
        e.preventDefault();
        $("#savecampFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("CMPNote_UARN",CKEDITOR.instances["CMPNote_UARN"].getData());
        $.ajax({
            url: "models/_camp.php",
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            dataType: "json",
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if(res.is_success == true){
                    $("#editcampForm")[0].reset();
                    CKEDITOR.instances["CMPNote_IAZN"].setData(""); 
                    $("#datatablecampView").DataTable().ajax.reload(null, false);
                    $("#editcampModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#savecampFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savecampFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatablecampView").DataTable({
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
            "url": "models/_camp.php",
            "data": function (d) {
                d.type = "load";
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
                    console.log(row[3]);
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                        {
                            "familyTotal":(row[3]>0?1:0)
                        },
                        {
                            "CMPID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 2,
                "render": function (data, type, row) {
                    return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Round ${data}</span>`;
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
    generalConfigDatatable(table,"#datatablecampView");
    generalConfig(); 
});
function deletecamp(CMPID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_camp.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "CMPID_UIZP": CMPID,
                        "PageName":$("#PageName").val()
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatablecampView").DataTable().ajax.reload(null, false);
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
function editcamp(CMPID) {
    $("#CMPID_UIZP").val(Number(CMPID));
    getDataFromServer("editcampForm","'camp'");  
    $("#editcampModal").modal("toggle");
}  
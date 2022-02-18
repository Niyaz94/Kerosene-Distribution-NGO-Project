$(document).ready(function () {
    $("#addroundForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveroundFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("RNDNote_IAZN",CKEDITOR.instances["RNDNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_round.php",
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
                    $("#addroundForm")[0].reset();
                    CKEDITOR.instances["RNDNote_IAZN"].setData(""); 
                    $("#datatableroundView").DataTable().ajax.reload(null, false);
                    $("#addroundCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#saveroundFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveroundFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editroundForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveroundFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","update");
        formData.append("RNDNote_UARN",CKEDITOR.instances["RNDNote_UARN"].getData());
        $.ajax({
            url: "models/_round.php",
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
                    $("#editroundForm")[0].reset();
                    CKEDITOR.instances["RNDNote_UARN"].setData(""); 
                    $("#datatableroundView").DataTable().ajax.reload(null, false);
                    $("#editroundModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveroundFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveroundFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableroundView").DataTable({
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
            "url": "models/_round.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            tooltip1("");
        },
        "columnDefs": [
            {
                "targets": 6,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                        {},
                        {
                            "RNDID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 1,
                "render": function (data, type, row) {
                    return "Round "+data;
                }
            }/*,{
                "targets": 6,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Active</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Not Active</span>`;
                    }
                }
            }*/
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
    generalConfigDatatable(table,"#datatableroundView");
    generalConfig(); 
});
function deleteround(RNDID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_round.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "RNDID_UIZP": RNDID,
                        "PageName":$("#PageName").val()
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableroundView").DataTable().ajax.reload(null, false);
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
function editround(RNDID) {
    $("#RNDID_UIZP").val(Number(RNDID));
    getDataFromServer("editroundForm","'round'");  
    $("#editroundModal").modal("toggle");
    setTimeout(function(){
        startHour=$("#RNDStartTime_UDRN").val().split(":")[0];
        startHour=(startHour%12 || 12)  +(startHour>=12?" PM":" AM");
        $("#RNDStartTime_UDRN").val(startHour);
        endHour=$("#RNDEndTime_UDRN").val().split(":")[0];
        endHour=(endHour%12 || 12)  +(endHour>=12?" PM":" AM");
        $("#RNDEndTime_UDRN").val(endHour);

    },1000);
}  
    
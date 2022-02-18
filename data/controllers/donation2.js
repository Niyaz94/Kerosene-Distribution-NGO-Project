$(document).ready(function () {
    addingExtenton();
    table = $("#datatabledonationView").DataTable({
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
            "url": "models/_donation2.php",
            "data": function (d) {
                d.type = "load";
                d.ADMCampID = $("#ADMCampID").val();
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
                        {
                        },{
                            "FMYID":row[0],
                        },
                        "table"
                    );
                }
            },{
                "targets": 4,
                "render": function (data, type, row) {
                    return `
                        <div id="${row[0]}" class="readingBarcode" style="padding:0px;margin:0px">
                            <canvas id="canvas_${row[0]}""></canvas>
                            <div style="color:white;font-size:1px;padding:0px;margin:0px">${data}</div>
                        </div>
                        <script>readBarcode(${row[0]},${data});</script>
                    `;
                }
            },{
                "targets": 5,
                "render": function (data, type, row) {
                    if(data==2){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Not Received</span>`;
                    }else if(data==0){
                        return `<span class="label label-block label-flat border-warning text-slate-800" style="padding:6%">Peding</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Received</span>`;
                    }
                }
            }
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            if(Number(aData[5])>0){
                $(nRow).addClass('changeRowColor');
            }
        },
        "order": [
            [0, "desc"]
        ],
        "displayLength": 25,
        initComplete: function () {
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),{},{},"header"));
        }
    });
    generalConfigDatatable(table,"#datatabledonationView");
    generalConfig(); 
    $(document).scannerDetection({
        preventDefault: true,
        ignoreIfFocusOn: 'input',  
        onComplete:function(barcode){
            if(barcode>100000 && barcode<1000000){
                sendBarcode(barcode);
            }else{
                oneAlert("error","Error !!! ","Barcode Length Must be 6 digits");
            }
        },
    });
    $("#insertManualySave").on("click",function(){
        if(Number($("#barcodeNumber").val())>100000 && Number($("#barcodeNumber").val())<1000000){
            sendBarcode(Number($("#barcodeNumber").val()));
        }else{
            oneAlert("error","Error !!! ","Barcode Length Must be 6 digits");
        }
        $("#barcodeNumber").val("");
        $("#insertManualyModal").modal("toggle");
    });
    $("#insertCSVForm").on("submit", function (e) {
        e.preventDefault();
        $("#insertCSVSave").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","acceptMultiDonation");
        $.ajax({
            url: "models/_donation2.php",
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
               // location.reload();

                if(res.is_success == true){
                    $("#insertCSVForm")[0].reset();
                    $("#insertCSVModal").modal("toggle");
                    $("#datatabledonationView").DataTable().ajax.reload(null, false);
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",`${res.data} rows not inserted Please Check Data Again`);
                }
                $("#insertCSVSave").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#insertCSVSave").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
});
function acceptdonation(FMYID,CMPID,currentRound) {
    $.ajax({
        url: "models/_donation2.php",
        type: "POST",
        data: {
            "type":"acceptDonation",
            "PageName":$("#PageName").val(),
            "FMYID_UIZP": FMYID,
            "currentRound": currentRound,
            "ADMCampID":$("#ADMCampID").val(),
            "CMPID":CMPID
        },
        dataType: "json",
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            if (res.is_success == true) {
                $("#datatabledonationView").DataTable().ajax.reload(null, false);
                oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            } else {
                setTimeout(function(){
                    oneAlert("error","Error !!!",res.data);
                },500);
            }
        },
        fail: function (err){},
        always:function(){}
    });
}
function insertManualy(){
    $("#insertManualyModal").modal("toggle");
}
function sendBarcode(barcode){
    $.ajax({
        url: "models/_donation2.php",
        type: "POST",
        dataType:"json",
        data: {
            "type": "searchByBarcode",
            "barcode": barcode
        },
        complete: function () {
            //hideLoader();
        },
        beforeSend: function () {
        },
        success: function (res) {
            if (res.is_success == true) {
                if(res.data.FMYActive==1){
                    oneAlert("error","Error !!!",res.data.FMYNote);
                }else{
                    arabic_title="هل انت موافق؟";
                    english_title="Are you Agree?";
                    english=`The Family Entitled to receive ${res.data.amount} liter of kerosene !!!`;
                    arabic=`!!!العائلة مستحقة لاستلام ${res.data.amount} لتر من النفط الأبيض`;
                    swal(
                        secondAlert("warning",arabic_title+"\n"+english_title,arabic+"\n"+english),
                        function (isConfirm) {
                            if (isConfirm) {
                                acceptdonation(res.data.FMYID,res.data.FMYCMPFORID,res.data.currentRound);
                            }
                        }
                    );
                }
            } else {
                oneAlert("error","Error !!! ",res.data);
            }
        }
    });
}
function insertCSVFile(){
    $("#insertCSVModal").modal("toggle");
}

$(document).ready(function () {
    $("#addfamilyForm").on("submit", function (e) {
        e.preventDefault();
        $("#savefamilyFormCollapse").attr("disabled", true);       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("FMYNote_IAZN",CKEDITOR.instances["FMYNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_family.php",
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
                    $("#addfamilyForm")[0].reset();
                    deselectSelect2();
                    CKEDITOR.instances["FMYNote_IAZN"].setData("");
                    createBarcode(); 
                    $("#datatablefamilyView").DataTable().ajax.reload(null, false);
                    $("#addfamilyCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#savefamilyFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savefamilyFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editfamilyForm").on("submit", function (e) {
        e.preventDefault();
        $("#savefamilyFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("FMYNote_UARN",CKEDITOR.instances["FMYNote_UARN"].getData());
        formData.append("type","update");
        $.ajax({
            url: "models/_family.php",
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
                    $("#editfamilyForm")[0].reset();
                    CKEDITOR.instances["FMYNote_UARN"].setData(""); 
                    $("#datatablefamilyView").DataTable().ajax.reload(null, false);
                    $("#editfamilyModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#savefamilyFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#savefamilyFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editfamilymaxamountForm").on("submit", function (e) {
        e.preventDefault();
        $("#editfamilymaxamountSave").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","setAmount");
        $.ajax({
            url: "models/_family.php",
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
                    $("#editfamilymaxamountForm")[0].reset();
                    $("#editfamilymaxamountModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#editfamilymaxamountSave").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editfamilymaxamountSave").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#insertCSVForm").on("submit", function (e) {
        e.preventDefault();
        $("#insertCSVSave").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","addCSV");
        $.ajax({
            url: "models/_family.php",
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
                    $("#datatablefamilyView").DataTable().ajax.reload(null, false);
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
    $("#insertCSVDeactiveForm").on("submit", function (e) {
        e.preventDefault();
        $("#insertCSVDeactiveSave").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","addCSVDeactive");
        $.ajax({
            url: "models/_family.php",
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
                    $("#insertCSVDeactiveForm")[0].reset();
                    $("#insertCSVDeactiveModal").modal("toggle");
                    $("#datatablefamilyView").DataTable().ajax.reload(null, false);
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",`${res.data} rows not Deactive Please Check Data Again`);
                }
                $("#insertCSVDeactiveSave").attr("disabled",false);
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
    $("#insertCSVActiveForm").on("submit", function (e) {
        e.preventDefault();
        $("#insertCSVActiveSave").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","addCSVActive");
        $.ajax({
            url: "models/_family.php",
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
                    $("#insertCSVActiveForm")[0].reset();
                    $("#insertCSVActiveModal").modal("toggle");
                    $("#datatablefamilyView").DataTable().ajax.reload(null, false);
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",`${res.data} rows not Deactive Please Check Data Again`);
                }
                $("#insertCSVActiveSave").attr("disabled",false);
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
    addingExtenton();
    table = $("#datatablefamilyView").DataTable({
        buttons: {
            buttons:  [
                {
                    extend: 'copyHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: [0, ':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    },
                    /*customize: function(doc) {

                        doc.images = doc.images || {};

                        doc.images['myGlyph'] = getBase64Image(myGlyph);
                        
                        for (var i=1;i<doc.content[1].table.body.length;i++) {
                            if (doc.content[1].table.body[i][0].text == '<img src="myglyph.png">') {
                                delete doc.content[1].table.body[i][0].text;
                                doc.content[1].table.body[i][0].image = 'myGlyph';
                            }
                        }
                        var tblBody = doc.content[1].table.body;

                        $('#datatablefamilyView').find('tr').each(function (ix, row) {
                            var index = ix;
                            $(row).find('td').each(function (ind, elt) {
                                //.toDataURL("image/png")
                                console.log($($(elt).find("canvas")));
                                if(tblBody[index] !== undefined && tblBody[index][ind] !== undefined && tblBody[index][ind]["text"] !== undefined){
                                    console.log(tblBody[index][ind]);
                                    tblBody[index][ind]["text"]="11111";

                                }
                                tblBody[index][ind].border
                                if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {
                                    delete tblBody[index][ind].style;
                                    tblBody[index][ind].fillColor = '#FFF9C4';
                                }
                                else
                                {
                                    if (tblBody[index][2].text == '') {
                                        delete tblBody[index][ind].style;
                                        tblBody[index][ind].fillColor = '#FFFDE7';
                                    }
                                }
                            });
                        });
                    }*/ 
                },
                {
                    extend: 'print',
                    className: 'btn btn-default',
                    exportOptions: {
                        columns: ':visible'
                    },
                    /*customize: function(doc) {
                        doc.styles.title = {
                          color: 'red',
                          fontSize: '40',
                          background: 'blue',
                          alignment: 'center'
                        }
                        var tblBody = doc.content[1].table.body;
                        console.log(tblBody);
                        doc.content[1].layout = {
                        hLineWidth: function(i, node) {
                            return (i === 0 || i === node.table.body.length) ? 2 : 1;},
                        vLineWidth: function(i, node) {
                            return (i === 0 || i === node.table.widths.length) ? 2 : 1;},
                        hLineColor: function(i, node) {
                            return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';},
                        vLineColor: function(i, node) {
                            return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';}
                        };
                        $('#gridID').find('tr').each(function (ix, row) {
                            var index = ix;
                            var rowElt = row;
                            $(row).find('td').each(function (ind, elt) {
                                tblBody[index][ind].border
                                if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {
                                    delete tblBody[index][ind].style;
                                    tblBody[index][ind].fillColor = '#FFF9C4';
                                }
                                else
                                {
                                    if (tblBody[index][2].text == '') {
                                        delete tblBody[index][ind].style;
                                        tblBody[index][ind].fillColor = '#FFFDE7';
                                    }
                                }
                            });
                        });
                    } */
                },
                {
                    extend: 'colvis',
                    text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                    className: 'btn bg-blue btn-icon'
                }
            ]
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ["10", "25","50","100"]
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_family.php",
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
                        {},
                        {
                            "FMYID":row[0]
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
                    if(data==0){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Active</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Not Active</span>`;
                    }
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
    generalConfigDatatable(table,"#datatablefamilyView");
    generalConfig();
    createBarcode(); 
    $("#showFamilyBarcodeSave").on("click",function(event){
        window.open(`pdf/familyBarcode.php?campid=${$("#campid").val()}&start=${$("#start_date").val()}&end=${$("#end_date").val()}&startNumber=${$("#startNumber").val()}&endNumber=${$("#endNumber").val()}`);
        $("#showFamilyBarcodeModal").modal("toggle");
    });
});
function insertCSVFile(){
    $("#insertCSVModal").modal("toggle");
}
function editfamily(FMYID) {
    $("#FMYID_UIZP").val(Number(FMYID));
    getDataFromServer("editfamilyForm","'family'");  
    $("#editfamilyModal").modal("toggle");
}  
function createBarcode(){
    $.ajax({
        url: "models/_family.php",
        type: "POST",
        data: {
            "type":"createBarcode"
        },
        dataType: "json",
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            $("#FMYBarcode_IIZN").val(res);
        },
        fail: function (err){},
        always:function(){}
    }); 
}
function showingFamilyBarcode(){
    $("#showFamilyBarcodeModal").modal("toggle");
}
function amountfamily(FMYID) {
    $.ajax({
        url: "models/_family.php",
        type: "POST",
        dataType: "JSON",
        data:{
            "type":"getAmount",
            "FMYID": FMYID
        },
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            $("#editfamilymaxamountBody").empty();
            for (let index = 0; index < res.data.length; index++) {
                $("#editfamilymaxamountBody").append(`
                    <div class="form-group">
                        <label class="control-label col-lg-2" style="font-size:20px;text-align:right;">${res.data[index]["PDUName"]}</label>
                        <div class="col-lg-10">
                            <input type="hidden" id="old_${res.data[index]["FPMID"]}" name="old_${res.data[index]["FPMID"]}" value="${res.data[index]["FPMMaxAmount"]}">
                            <input type="hidden" id="oldRemain_${res.data[index]["FPMID"]}" name="oldRemain_${res.data[index]["FPMID"]}" value="${res.data[index]["FPMRemainAmount"]}">
                            <input 
                                type="number"
                                min=${res.data[index]["FPMRemainAmount"]}
                                class="form-control" 
                                id="${res.data[index]["FPMID"]}" 
                                name="row_${res.data[index]["FPMID"]}" 
                                placeholder="Please Enter ${res.data[index]["PDUName"]} Amount" 
                                value="${res.data[index]["FPMMaxAmount"]}">
                        </div>
                    </div>
                `);
            }
            $("#editfamilymaxamountModal").modal("toggle");
        }
    }); 
}
function deletefamily(FMYID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_family.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "FMYID_UIZP": FMYID,
                        "PageName":$("#PageName").val()
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatablefamilyView").DataTable().ajax.reload(null, false);
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
function changeBarcode(FMYID){
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this Barcode Again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_family.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"barcodeChange",
                        "FMYID": FMYID,
                        "PageName":$("#PageName").val()
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatablefamilyView").DataTable().ajax.reload(null, false);
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
function activeCSVFamily(){
    $("#insertCSVActiveModal").modal("toggle");
}
function deActiveCSVFamily(){
    $("#insertCSVDeactiveModal").modal("toggle");
}

$(document).ready(function () {
    $("#addproductForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveproductFormCollapse").attr("disabled", true);   
       
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("PDUNote_IAZN",CKEDITOR.instances["PDUNote_IAZN"].getData());
        formData.append("type","create");
        $.ajax({
            url: "models/_product.php",
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
                    $("#addproductForm")[0].reset();
                    deselectSelect2();
                    $("#datatableproductView").DataTable().ajax.reload(null, false);
                    $("#addproductCollapse").collapse("hide");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#saveproductFormCollapse").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveproductFormCollapse").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editproductForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveproductFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("PDUNote_UARN",CKEDITOR.instances["PDUNote_UARN"].getData());
        formData.append("type","update");
        $.ajax({
            url: "models/_product.php",
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
                    $("#editproductForm")[0].reset();
                    $("#datatableproductView").DataTable().ajax.reload(null, false);
                    $("#editproductModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveproductFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveproductFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    $("#editproductAmountForm").on("submit", function (e) {
        e.preventDefault();
        $("#saveproductAmountFormModal").attr("disabled", true);
        var formData = new FormData($(this)[0]);
        formData.append("PageName",$("#PageName").val());
        formData.append("type","setAmount");
        $.ajax({
            url: "models/_product.php",
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
                    $("#editproductAmountForm")[0].reset();
                    $("#editproductAmountModal").modal("toggle");
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                  oneAlert("error","Error!!!",res.data);
                }
                $("#saveproductAmountFormModal").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#saveproductFormModal").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
    addingExtenton();
    table = $("#datatableproductView").DataTable({
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
            "url": "models/_product.php",
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
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                        {},
                        {
                            "PDUID":row[0]
                        },
                        "table"
                    );
                }
            },{
                "targets": 3,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-primary text-slate-800" style="padding:6%">Active</span>`;
                    }else{
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">Not Active</span>`;
                    }
                }
            },{
                "targets": 2,
                "render": function (data, type, row) {
                    if(data==0){
                        return `<span class="label label-block label-flat border-info text-slate-800" style="padding:6%">Qty</span>`;
                    }else if(data==1){
                        return `<span class="label label-block label-flat border-warning text-slate-800" style="padding:6%">Meter</span>`;
                    }else if(data==2){
                        return `<span class="label label-block label-flat border-brown text-slate-800" style="padding:6%">Liter</span>`;
                    }else if(data==3){
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">Packet</span>`;
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
    generalConfigDatatable(table,"#datatableproductView");
    generalConfig(); 

    $('.multiselect').multiselect({
        nonSelectedText: 'Please choose',
        maxHeight: 250,
        enableFiltering: true,
        disableIfEmpty: true,
        onChange: function(element, checked) {
            id=element.parent().prop("id");
            if(id=="PDDCMPFORID_INZN"){
                if($("#PDDCMPFORID_INZN").val()==null){  
                    oneAlert("error","Error!!!","Please At Least Select One Camp !!!")
                    $('#PDDCMPFORID_INZN option').prop('selected',true);
                    $('#PDDCMPFORID_INZN').multiselect('refresh');
                }
            }
            if(id=="PDDCMPFORID_UNRN"){
                if($("#PDDCMPFORID_UNRN").val()==null){
                    oneAlert("error","Error!!!","Please At Least Select One Camp !!!")
                    $('#PDDCMPFORID_UNRN option').prop('selected',true);
                    $('#PDDCMPFORID_UNRN').multiselect('refresh');
                }
            }


            
        },
    });  
});
function editproduct(PDUID) {
    $("#PDUID_UIZP").val(Number(PDUID));
    getDataFromServer("editproductForm",["'product'","'product_detail'"],"",[{"columnName":"PDDDeleted","operation":"=","value":0,"link":"and"}]);  
    $("#editproductModal").modal("toggle");
}  

function deleteproduct(PDUID) {
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_product.php",
                    type: "POST",
                    dataType: "JSON",
                    data:{
                        "type":"delete",
                        "PageName":$("#PageName").val(),
                        "PDUID_UIZP": PDUID
                    },
                    complete: function () {},
                    beforeSend: function () {},
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableproductView").DataTable().ajax.reload(null, false);
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
function amountproduct(PDUID) {
    $.ajax({
        url: "models/_product.php",
        type: "POST",
        dataType: "JSON",
        data:{
            "type":"getAmount",
            "PDUID": PDUID
        },
        complete: function () {},
        beforeSend: function () {},
        success: function (res) {
            $("#addToAmountBody").empty();
            for (let index = 0; index < res.data.length; index++) {
                $("#addToAmountBody").append(`
                    <div class="form-group">
                        <label class="control-label col-lg-2" style="font-size:20px;text-align:right;">${res.data[index]["CMPName"]}</label>
                        <div class="col-lg-10">
                            <input 
                                type="number"
                                min=0 
                                class="form-control" 
                                id="${res.data[index]["PDDID"]}" 
                                name="row_${res.data[index]["PDDID"]}" 
                                placeholder="Please Enter ${res.data[index]["CMPName"]} Amount" 
                                value="${res.data[index]["PDDItemNumber"]}">
                        </div>
                    </div>
                `);
            }
            $("#editproductAmountModal").modal("toggle");
        }
    }); 
}
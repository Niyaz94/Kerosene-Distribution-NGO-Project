var table,newTable, block = $(".content-wrapper").parent(),languageInfo=languageInfo();

var LOGID_for_dataTable=1;
function save_items(LOGID) {

    $("#show_detail_modal").modal("show");
    LOGID_for_dataTable=LOGID;  
    newTable.ajax.reload(/*function(json){
        console.log(json.data);
    }*/);
}
function update_items(LOGID){

    updateData={};
    updateData["LOGID"]=LOGID;
    tr=$("#"+LOGID).parents("tr");
    $(" td input",tr).each(function(index,value){
        updateData[$(value).attr("name")]=$(value).val().trim();
    });
    $.ajax({
        url: "models/_systemlog.php",
        type: "POST",
        dataType:"json",
        data: {
            "type":"changeSystemLog",
            "data":JSON.stringify(updateData)
        },
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            if(res.is_success == true){
                oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            }else{
                oneAlert("error","Error!!!",res.data)
            }
        },
        fail: function (err){
            oneAlert("error","Error!!!",res.data)
        },
        always:function(){
            console.log("complete");
        }
    });
}
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return 'not';
}
var query_id = getQueryVariable("tables");


$(function () {
    addingExtenton(); 
    newTable=$('#showData').DataTable({ 
        buttons: {
            buttons: []
        },
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10', '25', '50', '100', 'All']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_systemlog.php",
            "data": function (d) {
                d.type = "loadModule";
                d.id = LOGID_for_dataTable;
            }
        },
        "columnDefs": [
            {
                "render": function (data, type, row) {
                    return `
                        <input type="text" id="LogColumnName" name="LogColumnName" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                "targets": 1
            }, {
                "render": function (data, type, row) {
                    return `
                        <input type="text" id="SLDOldValue" name="SLDOldValue" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                "targets": 2
            }, {
                "render": function (data, type, row) {
                    return `
                        <input type="text" id="SLDNewValue" name="SLDNewValue" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                "targets": 3
            }, {
                "render": function (data, type, row) {
                    return `
                        <input type="text" id="SLDCreateAt" name="SLDCreateAt" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                "targets": 4
            },
        ],
        
        "order": [
            [0, 'desc']
        ],
        'drawCallback': function( settings ) {
           
         },
        "displayLength": 25,
    });
    table = $('.datatable-column-search-inputs').DataTable({
        buttons: {
            buttons: dtButtons()
        },
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10', '25', '50', '100', 'All']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_systemlog.php",
            "data": function (d) {
                d.type = "load";
                if (query_id != 'not') {
                    d.tables = query_id;
                }
            }
        },
        drawCallback: function () {
            tooltip1("");
            datatableAfterInit();
        },
        "columnDefs": [
            {
                "targets": 8,
                "data": null,
                "render": function (data, type, row) {
                    var info="";
                    if(Number(data[8])>0){
                       info=`
                            <li class='text-primary-600'>
                                <button  class='btn btn-info text-slate-800 btn-flat' onclick="save_items(${row[0]})" data-popup='tooltip' data-original-title='` + Translation('Save') + `'>
                                    <i class='icon-eye-plus'></i>
                                    <i id="icon_` + row[0] + `" class=""></i>
                                </button>
                            </li>
                        `;
                        
                    }
                    edit=`
                        <li class='text-danger-600'>
                            <button  id="${row[0]}" class='btn btn-info text-brown-800 btn-flat' onclick="update_items(${row[0]})" data-popup='tooltip' data-original-title='` + Translation('Save') + `'>
                                <i class='icon-pencil'></i>
                                <i id="icon_` + row[0] + `" class=""></i>
                            </button>
                        </li>
                    `;
                    return `
                        <ul class='icons-list pull-right'>
                            ${info}
                            ${edit}
                        </ul>
                    `;
                }
            },
           /* {
               //Have Problem
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGCreateBY" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 1
            },*/{
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="dateAndTimeStyle_${row[0]}" name="LOGCreateAt" value="${data}" class="form-control multi_lang dateAndTimeStyle" placeholder="">
                    `;
                },
                targets:2
            }, {
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGPage" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 3
            }, {
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGTable" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 4
            }, {
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGRowID" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 5
            }, {
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGAction" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 6
            }, {
                "render": function (data, type, row,index) {
                    return `
                        <input type="text" id="" name="LOGNote" value="${data}" class="form-control multi_lang" placeholder="">
                    `;
                },
                targets: 7
            }           
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[9] == 0) {
                $(nRow).children().each(function (index, td) {
                    $(this).addClass('warning-active');
                });
            }
        },
        "order": [
            [0, 'desc']
        ],
        "displayLength": 25,
        initComplete: function () {

            $("#systemLogDatatable_wrapper .datatable-header").append(`
                <div class="btn-group">
                    <button 
                        id="inv_filter_btn" 
                        type="button" 
                        class="btn bg-teal-400 btn-labeled dropdown-toggle" 
                        data-toggle="dropdown"
                    >
                        <b><i class="icon-filter4"></i></b>
                        <span id='inv_filter'>` + Translation('System Log Filter') + `</span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" id="language_filter">
                    </ul>
                </div>
            `);
           

            //adding language to buttons
            $.ajax({
                url: 'models/_systemlog.php',
                type: 'POST',
                data: {
                    type: 'returnTablesNane'
                },
                dataType: 'json',
                success: function (response) {
                    var attach = '';
                    for (var i = response.length - 1; i >= 0; i--) {
                        attach = attach + '<li><a href="index.php?p=systemlog&tables=' + response[i]["table_name"] + '"><i class="icon-book3  text-success"></i> ' + response[i]["table_name"] + '</a></li>';
                    }
                    $("#language_filter").html(attach);
                },
                complete: function () {
                }
            }); 
        }
    });
    generalConfigDatatable(table,".datatable-column-search-inputs");
    generalConfig();
    $("#editSystemlogFormButton").on("click",function(event){
        systemLogData=[];
        $("#showData tbody tr").each(function(indexTr,dataTr){
            row={};
            $("td",dataTr).each(function(indexTd,dataTd){
                if(indexTd==0){
                   row["SLDID"]=$(dataTd).text();
                }else{
                    row[$(dataTd).find("input").attr("id")]=$(dataTd).find("input").val();
                }
            });
            systemLogData.push(row);
        });
        $("#editSystemlogFormButton").attr("disabled",false);
        $.ajax({
            url: "models/_systemlog.php",
            type: "POST",
            dataType:"json",
            data: {
                "type":"changeDetail",
                "data":JSON.stringify(systemLogData)
            },
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if(res.is_success == true){
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                }else{
                    oneAlert("error","Error!!!",res.data)
                }
                $("#show_detail_modal").modal("toggle");
                $("#editSystemlogFormButton").attr("disabled",false);
            },
            fail: function (err){
                oneAlert("error","Error!!!",res.data)
                $("#editSystemlogFormButton").attr("disabled",false);
            },
            always:function(){
                console.log("complete");
            }
        });
    });
});
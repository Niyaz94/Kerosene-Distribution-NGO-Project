
var languageInfo=languageInfo(),userPermissionFromDatabase, table;
$(document).ready(function () {
    $(".switch").bootstrapSwitch();
    $('#add_form').on('submit', function (e) {
        e.preventDefault();
        Check1=true,Check2=true;
        if ($("#ADMPassword_IWZ").val() != $("#ADMPassword_IWW").val()) {
            oneMessege("","Passwords Does Not Matches!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            Check2 = false;
        }
        if ($("#ADMPassword_IWZ").val().length < 6) {
            oneMessege("","Passwords Should Be at least 6 Characters!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            Check2 = false;
        }
        if ($("#ADMUsername_ISZ").val().length < 3) {
            oneMessege("","UserName Should Be at least 3 Characters!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            Check1 = false;
        }
        if (Check1 && Check2) {
            $("#submit_add_btn").attr('disabled', true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("type","create");
            $.ajax({
                url: "models/_admin.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){
                        $("#add_form")[0].reset();
                        $("#datatableAdminView").DataTable().ajax.reload(null, false);
                        $("#addadminCollapse").collapse('hide');
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                      oneAlert("error","Error!!!",res.data)
                    }
                    $("#submit_add_btn").attr('disabled',false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#submit_add_btn").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        } else {
            oneMessege("","Enter Required Fields!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        } // if check
    });
    $('#edit_form').on('submit', function (e) {
        e.preventDefault();
        Check1=true;
        if ($("#ADMUsername_USZ").val().length < 3) {
            oneMessege("","UserName Should Be at least 3 Characters!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
            Check1 = false;
        }
        if (Check1) {
            $("#submit_edit_btn").attr('disabled', true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("ADMID_UIZP",$("#ADMID_UIZP").val());
            formData.append("type","update");

            $.ajax({
                url: "models/_admin.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    if(res.is_success == true){
                        $("#edit_form")[0].reset();
                        $("#datatableAdminView").DataTable().ajax.reload(null, false);

                        $("#edit_form_cont").modal('toggle');
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                      oneAlert("error","Error!!!",res.data);
                    }
                    $("#submit_edit_btn").attr('disabled',false);
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#submit_edit_btn").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        } else {
            oneMessege("","Enter Required Fields!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
        } // if check
       
    });
    $('#campAdminForm').on('submit', function (e) {
        e.preventDefault();
        //if($("#ADMCampID").val()==null){
            $("#campAdminSubmit").attr('disabled', true);
            var formData = new FormData($(this)[0]);
            formData.append("PageName",$("#PageName").val());
            formData.append("ADMID_UIZP",$("#ADMID_UIZP").val());
            formData.append("type","campAdd");
            $.ajax({
                url: "models/_admin.php",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType: 'json',
                complete: function () {
                    oneCloseLoader("#"+$(this).parent().id,"self");
                },
                beforeSend: function () {
                    oneOpenLoader("#"+$(this).parent().id,"self","dark");
                },
                success: function (res) {
                    window.location.href=`models/logout.php?`;
                    /*if(res.is_success == true){
                        $("#campAdminForm")[0].reset();
                        $("#campAdminModal").modal('toggle');
                        oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                    }else{
                      oneAlert("error","Error!!!",res.data);
                    }
                    $("#campAdminSubmit").attr('disabled',false);*/
                },
                fail: function (err){
                    oneAlert("error","Error!!!",res.data)
                    $("#campAdminSubmit").attr('disabled',false);
                },
                always:function(){
                    console.log("complete");
                }
            });
        //}else{
            //oneMessege("","Please Select At Least one Camp!!!","danger",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
       // }
            
    });
    $('#edit_form_role').on('submit', function (e) {
        e.preventDefault();
        $("#save_roles").attr('disabled', true);
        $.ajax({
            url: "models/_admin.php",
            type: "POST",
            dataType: "JSON",
            data: {
                "type": "role",
                "PageName":$("#PageName").val(),
                "ADMID_UIZP": $("#ADMID_UIZP").val(),
                "ADMProfilePermission_UIZ":JSON.stringify(userPermissionFromDatabase)
            },
            complete: function () {
                oneCloseLoader("#"+$(this).parent().id,"self");
            },
            beforeSend: function () {
                oneOpenLoader("#"+$(this).parent().id,"self","dark");
            },
            success: function (res) {
                if (res.is_success == true) {
                    $("#edit_role").modal('toggle');
                    $("#datatableAdminView").DataTable().ajax.reload(null, false);
                    oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                } else {
                    oneAlert("error",res.data,res.data);
                }
                $("#save_roles").attr('disabled', false);
            }
        });         
    });
    $('.multiselect').multiselect({
        nonSelectedText: 'Please choose',
        maxHeight: 250,
        enableFiltering: true,
        onChange: function(element, checked) {
           id=element.parent().prop("id").split("_")[1];
           optionValue=$(element).val();
           if(checked){
                changeFromArray(id,"add",optionValue);
           }else{
                changeFromArray(id,"remove",optionValue);
           }
          
        },
    });
    $('.switch').on('switchChange.bootstrapSwitch', function (event, state) {
        switch_id=event.target.id;
        select_id="select_"+switch_id.split("_")[1];

        $('#'+select_id).siblings('div').removeClass("open");
        if(!state){
            $('option',$('#'+select_id)).each(function(element) {
                $('#'+select_id).multiselect('select', $(this).val());
            });
            $('#'+select_id).multiselect('enable');
            changeFromArray(switch_id.split("_")[1],"addAll");
        }else{
            $('option',$('#'+select_id)).each(function(element) {
                $('#'+select_id).multiselect('deselect', $(this).val());
            });
            $('#'+select_id).multiselect('disable');
            changeFromArray(switch_id.split("_")[1],"removeAll");
        }
    });  
    addingExtenton();
    table = $("#datatableAdminView").DataTable({
        buttons: {
            buttons: dtButtons()
        },
        lengthMenu: [
            [10, 25, 50, 100],
            ['10', '25', '50', '100']
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "models/_admin.php",
            "data": function (d) {
                d.type = "load";
            }
        },
        drawCallback: function () {
            $('[data-popup=tooltip]').tooltip();
            $('[data-popup=popover-custom]').popover({
                template: '<div class="popover  border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
            });
        },
        "columnDefs": [
            {
                "targets": 9,
                "data": null,
                "render": function (data, type, row) {
                    return returnTablButtons(
                        JSON.parse($("#pageInfo").val()),
                        JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),
                        {
                            "ADMProfileType":row[5],
                            "ADMDeleted":row[8]
                        },{
                            "ADMID":row[0],
                            "ADMUsername":row[1],
                            "ADMFullname":row[2],
                            "ADMEmail":row[3],
                            "ADMPhoneNumber":row[4],
                            "ADMProfileType":row[5]
                        },
                        "table"
                    );
                }
            },
            {
                "render": function (data, type, row) {

                    if (data == 0) {
                        return `<span class="label label-block label-flat border-success text-slate-800" style="padding:6%">${Translation('Active')}</span>`;
                    } else {
                        return `<span class="label label-block label-flat border-danger text-slate-800" style="padding:6%">${Translation('De Active')}</span>`;
                    }

                },
                "targets": 8
            },
            {
                "render": function (data, type, row) {
                    if (data == 1) {
                        return `<span class="label label-block label-flat border-warning text-slate-800" style="padding:6%">${Translation('Admin')}</span>`;
                    } else if (data == 0) {
                        return `
                        <span class="label label-block label-flat border-info text-slate-800" style="padding:6%">${Translation('Permission')}</span>`;
                    }
                },
                "targets": 5
            },
        ],
        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData[8] == 1) {
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
            $("div.datatable-header").append(returnTablButtons(JSON.parse($("#pageInfo").val()),JSON.parse($("#userPermission").val()),$("#ADMProfileType").val(),{},{},"header"));
        }
    });
    generalConfig(table,"#datatableAdminView");
    $(".select").select2();
    $('.camplist').bootstrapDualListbox({
        preserveSelectionOnMove: 'moved',
        moveOnSelect: true
    });
});
//change by niyaz
function changeFromArray(id,tyep,optionID=0){
    for(var key in userPermissionFromDatabase){
        if(id==key){
            buttons=userPermissionFromDatabase[key]["buttons"];
            if(tyep=="addAll"){
                userPermissionFromDatabase[key]["active"]=1;
                for(keyButton in buttons){
                    userPermissionFromDatabase[key]["buttons"][keyButton]=1;
                }
            }else if(tyep=="add"){
                for(keyButton in buttons){
                    if(keyButton==optionID){
                        userPermissionFromDatabase[key]["buttons"][keyButton]=1;
                    }
                }
            }else if(tyep=="removeAll"){
                userPermissionFromDatabase[key]["active"]=0;
                for(keyButton in buttons){
                    userPermissionFromDatabase[key]["buttons"][keyButton]=0;
                }
            }else if(tyep=="remove"){
                for(keyButton in buttons){
                    if(keyButton==optionID){
                        userPermissionFromDatabase[key]["buttons"][keyButton]=0;
                    }
                }
            }
        }
    }
}
//change by niyaz
function editAdmin(ADMID, itemName, fulname, email, phone,ADMProfileType) {
    ADMID=Number(ADMID);
    $("#ADMID_UIZP").val(ADMID);
    $("#ADMUsername_USZ").val(decodeURIComponent(itemName));
    $("#ADMFullname_USZ").val(decodeURIComponent(fulname));
    $("#ADMEmail_UEZ").val(decodeURIComponent(email));
    $("#ADMPhoneNumber_UPZ").val(decodeURIComponent(phone));
    $("#ADMProfileType_UCZ").val(decodeURIComponent(ADMProfileType));
    $('#edit_form_cont').modal('toggle');
}
//change by niyaz
function deactiveAdmin(ADMID) {
    ADMID=Number(ADMID);
    block = $("#list_of_items_tbl");
    swal(
        secondAlert("warning","Are you sure?","You will deactivate this user!"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_admin.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "type": "deactive",
                        "PageName":$("#PageName").val(),
                        "LogType":"Deactive User",
                        "ADMID_UIZP": ADMID,
                        "ADMDeleted_UIZ":2
                    },
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                           $("#datatableAdminView").DataTable().ajax.reload(null, false);

                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            oneAlert("error",res.data,res.data);
                        }
                    }
                });
            }
        }
    );
}
//change by niyaz
function activeAdmin(ADMID) {
    ADMID=Number(ADMID);
    block = $("#list_of_items_tbl");
    swal(
        secondAlert("warning","Are you sure?","You will Activate this user!"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_admin.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "type": "active",
                        "PageName":$("#PageName").val(),
                        "LogType":"Active User",
                        "ADMID_UIZP": ADMID,
                        "ADMDeleted_UIZ":0
                    },
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableAdminView").DataTable().ajax.reload(null, false);

                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            oneAlert("error",res.data,res.data);
                        }
                    }
                }); 
            }
        }
    );
}
//change by niyaz
function deleteAdmin(ADMID) {
    ADMID=Number(ADMID);
    block = $("#list_of_items_tbl");
    swal(
        secondAlert("warning","Are you sure?","You will not be able to recover this record again"),
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "models/_admin.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        "type": "delete",
                        "PageName":$("#PageName").val(),
                        "ADMID_UIZP": ADMID,
                        "ADMDeleted_UIZ":1
                    },
                    complete: function () {
                        oneCloseLoader("#"+$(this).parent().id,"self");
                    },
                    beforeSend: function () {
                        oneOpenLoader("#"+$(this).parent().id,"self","dark");
                    },
                    success: function (res) {
                        if (res.is_success == true) {
                            $("#datatableAdminView").DataTable().ajax.reload(null, false);
                            oneMessege("",res.data,"success",languageInfo["DIR"]=="ltr"?"right":"left",languageInfo["DIR"]=="ltr"?"topright":"topleft",false)
                        } else {
                            setTimeout(function(){
                                oneAlert("error","Error !!!",res.data);
                            },500);
                        }
                    }
                }); 
            }
        }
    );
}
//change by niyaz
function roleAdmin(ADMID) {
    ADMID=Number(ADMID);
    $("#ADMID_UIZP").val(ADMID);
    $.ajax({
        url: "models/_admin.php",
        type: "POST",
        dataType: "JSON",
        data: {
            "type": "getRoles",
            "ADMID": ADMID,
        },
        complete: function () {
            oneCloseLoader("#"+$(this).parent().id,"self");
        },
        beforeSend: function () {
            oneOpenLoader("#"+$(this).parent().id,"self","dark");
        },
        success: function (res) {
            if (res.is_success == true) {
                userPermissionFromDatabase=(Object.keys(JSON.parse(res.data)).length>0)?JSON.parse(res.data):JSON.parse($("#userActionData").val());
                for (const key in userPermissionFromDatabase) {
                    if (Array.isArray(userPermissionFromDatabase[key]["buttons"])) { 
                        userPermissionFromDatabase[key]["buttons"]={};                       
                    }
                }
                //console.log(userPermissionFromDatabase);
                correctWithCurrent();
                for(var key in userPermissionFromDatabase){
                    if(userPermissionFromDatabase[key]["active"]==0){
                        $('#switch_'+key).bootstrapSwitch('state', true);
                        $('#select_'+key).multiselect('disable');
                    }else if(userPermissionFromDatabase[key]["active"]==1){
                        $('#switch_'+key).bootstrapSwitch('state', false);
                        for(var keyButton in userPermissionFromDatabase[key]["buttons"]){
                            if(userPermissionFromDatabase[key]["buttons"][keyButton]==0){
                                $('#select_'+key).multiselect('deselect',keyButton);
                            }else if(userPermissionFromDatabase[key]["buttons"][keyButton]==1){
                                $('#select_'+key).multiselect('select',keyButton);
                            }
                        }
                    }
                }
            } else {
                oneAlert("error",res.data,res.data);
            }
        }
    });
    $('#edit_role').modal('toggle');
}
function correctWithCurrent(){
    /*
        userPermissionFromJSON :- coming from json file
        userPermissionFromDatabase:- coming from database
    */
    userPermissionFromJSON=JSON.parse($("#userActionData").val());
    for (const key in userPermissionFromJSON) {
        if (Array.isArray(userPermissionFromJSON[key]["buttons"])) { 
            userPermissionFromJSON[key]["buttons"]={};                       
        }
    }
    userPermissionFromDatabase=RemoveFromObject(userPermissionFromDatabase,userPermissionFromJSON);
    userPermissionFromDatabase=AddToObject(userPermissionFromDatabase,userPermissionFromJSON,"page");
    //if the data coming from json contain in data coming from database, then may be buttons change now check for buttons change
    for(var key in userPermissionFromDatabase){
        userPermissionFromDatabase[key]["buttons"]=RemoveFromObject(userPermissionFromDatabase[key]["buttons"],userPermissionFromJSON[key]["buttons"]);
        userPermissionFromDatabase[key]["buttons"]=AddToObject(     userPermissionFromDatabase[key]["buttons"],userPermissionFromJSON[key]["buttons"],"button");
    } 
}
//remove all rows that contain in object1 and not contain in object2
function RemoveFromObject(object1,object2){
    object2key=Object.keys(object2);
    for(var key in object1){//search in data coming from database, remove any row that not contain in current data
        if(!object2key.includes(`${key}`)){
            delete object1[`${key}`];
        }
    }
    return object1;
}
//add all rows that contain in object2 and not contain in object1
function AddToObject(object1,object2,type){
    object1Key=Object.keys(object1);
    for(var key in object2){//search in data coming from json file,add any row that not contain in current data
        if(!object1Key.includes(`${key}`)){//if the data coming from json not contain in data coming from database
           
            if(type=="page"){
                object1[`${key}`]=object2[`${key}`];
                object1[`${key}`]["active"]=0;
                for(keyButton in object1[`${key}`]["buttons"]){
                    object1[`${key}`]["buttons"][keyButton]=0;
                }
            }else if(type="button"){
                object1[`${key}`]=0;
            }
            
        }
    }
    return object1;
}
function campAdmin(ADMID){
    $("#ADMID_UIZP").val(ADMID);
    $("#campAdminForm").append(`<input id="ADMID_UIZP" value="${ADMID}">`);
    getDataFromServer("campAdminForm","'admin'"); 
    $("#campAdminForm").find("#ADMID_UIZP").remove();

    $("#campAdminModal").modal("toggle");
}
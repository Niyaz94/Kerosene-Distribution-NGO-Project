function addingExtenton(){
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [],
        dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>' + 'search' + ':</span> _INPUT_',
            searchPlaceholder: 'search' + '...',
            lengthMenu: '<span>' + 'Display' + ':</span> _MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': '&rarr;',
                'previous': '&larr;'
            }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
}
function generalConfigDatatable(table,id=""){
    //adding row selecting
    $(id+' tbody').on('click', 'tr', function () {
        if ($(this).hasClass('danger')) {
            $(this).removeClass('danger');
        } else {
            table.$('tr.danger').removeClass('danger');
            $(this).addClass('danger');
        }
    });
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $(id+' tfoot td').not(':last-child').each(function () {
        var title = $(id+' thead th').eq($(this).index()).text();
        $(this).html('<input type="text" class="form-control input-sm" placeholder="' + Translation('Search for') + ' ' + title + '" />');
    });
    $(id+' tfoot tr').insertAfter($(id+' thead tr'));

    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            that.search(this.value).draw();
        });
    });
}
function dtButtons(){
    return [
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
            }
        },
        {
            extend: 'print',
            className: 'btn btn-default',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'colvis',
            text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
            className: 'btn bg-blue btn-icon'
        }
    ];
}
function returnTablButtons(pagePermission,userPermission,pageType,condition,data,type){
    if(type=="table"){
        returnTable=`<ul class="icons-list pull-right">`;
        var pageLen=pagePermission["buttons"].length;
        for(var i=0;i<pageLen;i++){ 
            splitID=pagePermission["buttons"][i]["id"].split("_");
            if(typeof splitID[1] !== "undefined" && splitID[1]=="TB"){
                if(pageType==1 || pageType==2 ){
                    pageCondition=pagePermission["buttons"][i]["condition"];
                    if(pageCondition.length>0){
                        checkCondition=0;
                        for(var k=0;k<pageCondition.length;k++){
                            /*
                                pageCondition[k][0] => column name
                                pageCondition[k][1] => operation
                                pageCondition[k][2] => value
                            */
                            if(pageCondition[k][1]=="eq" && Object.keys(condition).includes(pageCondition[k][0]) && condition[pageCondition[k][0]]==pageCondition[k][2]){
                                ++checkCondition;
                            }else if(pageCondition[k][1]=="gr" && Object.keys(condition).includes(pageCondition[k][0]) && Number(condition[pageCondition[k][0]])>Number(pageCondition[k][2])){
                                ++checkCondition;
                            }
                        }
                        if(pageCondition.length==checkCondition){
                            functionName=pagePermission["buttons"][i]["functionName"]+'(';
                            for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                                functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                            }
                            if(pagePermission["buttons"][i]["value"].length>0){
                                functionName=functionName.substr(0,functionName.length-1);
                            }
                            functionName+=')';
                            
                            returnTable+=addTableButton(pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName);
                        }
                    }else{
                        functionName=pagePermission["buttons"][i]["functionName"]+'(';
                        for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                            functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                        }
                        if(pagePermission["buttons"][i]["value"].length>0){
                            functionName=functionName.substr(0,functionName.length-1);
                        }
                        functionName+=')';
                        
                        returnTable+=addTableButton(pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName); 
                    }   
                }else if(pageType==0){
                    if(userPermission[pagePermission["id"]]["active"]==1 && userPermission[pagePermission["id"]]["buttons"][pagePermission["buttons"][i]["id"]]==1){
                        pageCondition=pagePermission["buttons"][i]["condition"];
                        if(pageCondition.length>0){
                            for(var k=0;k<pageCondition.length;k++){
                                /*
                                    pageCondition[k][0] => column name
                                    pageCondition[k][1] => operation
                                    pageCondition[k][2] => value
                                */
                                if(pageCondition[k][1]="eq" && Object.keys(condition).includes(pageCondition[k][0]) && condition[pageCondition[k][0]]==pageCondition[k][2]){
                                    functionName=pagePermission["buttons"][i]["functionName"]+'(';
                                    for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                                        functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                                    }
                                    if(pagePermission["buttons"][i]["value"].length>0){
                                        functionName=functionName.substr(0,functionName.length-1);
                                    }
                                    functionName+=')';
                                    
                                    returnTable+=addTableButton(pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName); 
                                }
                            }
                        }else{
                            functionName=pagePermission["buttons"][i]["functionName"]+'(';
                            for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                                functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                            }
                            if(pagePermission["buttons"][i]["value"].length>0){
                                functionName=functionName.substr(0,functionName.length-1);
                            }
                            functionName+=')';
                            
                            returnTable+=addTableButton(pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName); 
                        }
                    }
                }
            }
        }
        returnTable+=`</ul>`;
        return returnTable;
    }else if(type=="header"){
        returnHeader=``;
        var pageLen=pagePermission["buttons"].length;
        for(var i=0;i<pageLen;i++){ 
            splitID=pagePermission["buttons"][i]["id"].split("_");
            if(typeof splitID[1] !== "undefined" && splitID[1]=="HD"){
                if(pageType==1 || pageType==2 ){
                    if(pagePermission["buttons"][i]["functionName"]=="addingButton"){
                        returnHeader+=addingButton(pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],pagePermission["page"]); 
                    }else if(pagePermission["buttons"][i]["functionName"]=="button3"){
                        returnHeader+=button3(pagePermission["buttons"][i]["id"],"button",pagePermission["buttons"][i]["style"],pagePermission["buttons"][i]["text"],pagePermission["buttons"][i]["icon"]); 
                    }else{
                        functionName=pagePermission["buttons"][i]["functionName"]+'(';
                        for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                            functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                        }
                        if(pagePermission["buttons"][i]["value"].length>0){
                            functionName=functionName.substr(0,functionName.length-1);
                        }
                        functionName+=')';
                        returnHeader+=generalButton("button",pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName);
                    }
                }else if(pageType==0){
                    if(userPermission[pagePermission["id"]]["active"]==1 && userPermission[pagePermission["id"]]["buttons"][pagePermission["buttons"][i]["id"]]==1){
                        if(pagePermission["buttons"][i]["functionName"]=="addingButton"){
                            returnHeader+=addingButton(pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],pagePermission["page"]); 
                        }else if(pagePermission["buttons"][i]["functionName"]=="button3"){
                            returnHeader+=button3(pagePermission["buttons"][i]["id"],"button",pagePermission["buttons"][i]["style"],pagePermission["buttons"][i]["text"],pagePermission["buttons"][i]["icon"]); 
                        }else{
                            functionName=pagePermission["buttons"][i]["functionName"]+'(';
                            for(var j=0;j<pagePermission["buttons"][i]["value"].length;j++){
                                functionName+='"'+data[pagePermission["buttons"][i]["value"][j]]+'",';
                            }
                            if(pagePermission["buttons"][i]["value"].length>0){
                                functionName=functionName.substr(0,functionName.length-1);
                            }
                            functionName+=')';
                            returnHeader+=generalButton("button",pagePermission["buttons"][i]["color"],pagePermission["buttons"][i]["icon"],pagePermission["buttons"][i]["text"],functionName);
                        }
                    }
                }
            }
        }
        return returnHeader; 
    }
}
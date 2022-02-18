function addingButton(bIcon,bText,collapseID){
    return `
        <a data-toggle="collapse"
            style="margin-right:10px;"
            data-target="#add${collapseID}Collapse"
            href="javascript:;"
            aria-controls="add${collapseID}Collapse"
            aria-expanded="false"
            data-popup='tooltip' 
            title='${Translation(bText)}'
            class="btn btn-primary"
            id="add_Item_Btn">
            <i class="${bIcon}"></i> ${Translation(bText)}
        </a>
    `;
}
function addTableButton(color,icon,text,functionName){
    return `
        <li class='text-${color}-600'>
            <a href='#' class='text-${color}' onclick='${functionName}' data-popup='tooltip' data-original-title='${Translation(text)}'>
                <i class='${icon}'></i>
            </a>
        </li>
    `;
}

function generalButton(type="button",cssClass,icon,text){
    return `
        <button type="${type}" class="${cssClass}" onclick='${functionName}'>
            <span class="multi_lang">${Translation(text)}</span> 
            <b><i class="${icon}"></i></b>
        </button>
    `;
}

function button3(id,type,cssClass,text,icon,extra=""){
    return `
        <button type="${type}" class="${cssClass}" id="${id}" ${extra}>
            <span class="multi_lang">${Translation(text)}</span> 
            <b><i class="${icon}"></i></b>
        </button>
    `;
}
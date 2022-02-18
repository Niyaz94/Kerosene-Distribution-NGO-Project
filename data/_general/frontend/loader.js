    
    


    function oneOpenLoader(id,type,colorType,spinnerType="sync"){
        /* spinnerType=,2,3,4,6,9,10,11,sync */
        if(type!="self"){
            id = $(id).parent(); 
        }

        if(colorType=="light"){
            overlayCSS= {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            };
            css={
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            };

        }else if(colorType=="dark"){
            overlayCSS= {
                backgroundColor: '#1B2024',
                opacity: 0.85,
                cursor: 'wait'
            };
            css= {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            };
        }
        $(id).block({
            message: `<i class="icon-spinner spinner"></i>`,
            overlayCSS:overlayCSS,
            css:css
        });
    }
    function oneCloseLoader(id,type){

        if(type!="self"){
            id = $(id).parent(); 
        }
        $(id).unblock();
    }

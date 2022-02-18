
/* noty({
    width: 200,
    text: Translation(res.data),
    type: 'success',
    dismissQueue: true,
    timeout: 3000,
    layout: 'topRight',
});*/

function oneMessege(title,text,type,direction,pageDirection="topright",showIcon=true){

    var addclass="";
    if(type=="default"){
        addclass="bg-default";
    }else if(type=="danger"){
        addclass="bg-danger";
    }else if(type=="success"){
        addclass="bg-success";
    }else if(type=="info"){
        addclass="bg-info";
    }else if(type=="primary"){
        addclass="bg-primary";
    }else if(type=="warning"){
        addclass="bg-warning";
    }

    if(direction=="center"){
        addclass+="";
    }else if(direction=="left" && showIcon){
        addclass+=" alert alert-styled-left alert-arrow-left";
    }else if(direction=="right" && showIcon){
        addclass+=" alert alert-styled-right alert-arrow-right";
    }

    if(pageDirection=="topright"){
        stack={};
    }else if(pageDirection=="topleft"){
        addclass+=" stack-top-left";
        stack = {
            stack:{"dir1": "down", "dir2": "right", "push": "top"}
        };
    }else if(pageDirection=="bottomleft"){
        addclass+=" stack-bottom-left";
        stack = {
            stack:{"dir1": "right", "dir2": "up", "push": "top"}
        };
    }else if(pageDirection=="bottomright"){
        addclass+=" stack-bottom-right";
        stack = {
            stack:{"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25}
        };
    }else if(pageDirection=="customleft"){
        addclass+=" stack-custom-left";
        stack = {
            stack:{"dir1": "right", "dir2": "down"}
        };
    }else if(pageDirection=="customright"){
        addclass+=" stack-custom-right";
        stack = {
            stack:{"dir1": "left", "dir2": "up", "push": "top"}
        };
    }else if(pageDirection=="top"){
        addclass+=" stack-custom-top";
        stack={
            stack : {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 1},
            width: "100%",
            cornerclass: "no-border-radius",
        };
    }else if(pageDirection=="bottom"){
        addclass+=" stack-custom-bottom";
        stack={
            stack : {"dir1": "up", "dir2": "right", "spacing1": 1},
            width: "100%",
            cornerclass: "no-border-radius",
        };
    }

    new PNotify({
        ...(title.length>0?{title: Translation(title)}:{}),
        text: Translation(text),
        delay: 1000,
        addclass:addclass,
        hide: true,
        buttons: {
            closer: false,
            sticker: false
        },
        ...(Object.keys(stack).length>0?stack:{}),
    });
}




$(function() {

    // Close on click
    $('#pnotify-click').on('click', function () {
        var notice = new PNotify({
            title: 'Close on click',
            text: 'Click me anywhere to dismiss me.',
            addclass: 'bg-warning',
            hide: false,
            buttons: {
                closer: false,
                sticker: false
            }
        });
        notice.get().click(function() {
            notice.remove();
        });
    });



    // Sticky notice
    $('#pnotify-sticky').on('click', function () {
        new PNotify({
            title: 'Sticky notice',
            text: 'Check me out! I\'m a sticky notice. You\'ll have to close me yourself.',
            addclass: 'bg-primary',
            hide: false
        });
    });

    // Sticky buttons
    $('#pnotify-sticky-buttons').on('click', function () {
        new PNotify({
            title: 'No sticky button notice',
            text: 'I\'m a sticky notice with no unsticky button. You\'ll have to close me yourself.',
            addclass: 'bg-primary',
            hide: false,
            buttons: {
                sticker: false
            }
        });
    });


    // Progress loader
    $('#pnotify-progress').on('click', function () {
        var cur_value = 1,
        progress;

        // Make a loader.
        var loader = new PNotify({
            title: "Creating series of tubes",
            text: '<div class="progress progress-striped active" style="margin:0">\
            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">\
            <span class="sr-only">0%</span>\
            </div>\
            </div>',
            addclass: 'bg-primary',
            icon: 'icon-spinner4 spinner',
            hide: false,
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            },
            before_open: function(PNotify) {
                progress = PNotify.get().find("div.progress-bar");
                progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");

                // Pretend to do something.
                var timer = setInterval(function() {
                    if (cur_value >= 100) {

                        // Remove the interval.
                        window.clearInterval(timer);
                        loader.remove();
                        return;
                    }
                    cur_value += 1;
                    progress.width(cur_value + "%").attr("aria-valuenow", cur_value).find("span").html(cur_value + "%");
                }, 65);
            }
        });
    });
    // Dynamic loader
    $('#pnotify-dynamic').on('click', function () {
        var percent = 0;
        var notice = new PNotify({
            text: "Please wait",
            addclass: 'bg-primary',
            type: 'info',
            icon: 'icon-spinner4 spinner',
            hide: false,
            buttons: {
                closer: false,
                sticker: false
            },
            opacity: .9,
            width: "170px"
        });

        setTimeout(function() {
        notice.update({
            title: false
        });
        var interval = setInterval(function() {
            percent += 2;
            var options = {
                text: percent + "% complete."
            };
            if (percent == 80) options.title = "Almost There";
            if (percent >= 100) {
                window.clearInterval(interval);
                options.title = "Done!";
                options.addclass = "bg-success";
                options.type = "success";
                options.hide = true;
                options.buttons = {
                    closer: true,
                    sticker: true
                };
                options.icon = 'icon-checkmark3';
                options.opacity = 1;
                options.width = PNotify.prototype.options.width;
            }
            notice.update(options);
            }, 120);
        }, 2000);
    });



});

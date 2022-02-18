function tooltip1(type){
    $('[data-popup=tooltip]').tooltip({
        template: `
            <div class="tooltip">
                <div class="${type}">
                    <div class="tooltip-arrow"></div>
                    <div class="tooltip-inner"></div>
                </div>
            </div>
        `
    });
    $('[data-popup=popover]').popover({
      template: `
            <div class="popover  border-teal-400">
                <div class="arrow"></div>
                <h3 class="popover-title bg-teal-400"></h3>
                <div class="popover-content"></div>
            </div>
        `
    });
}
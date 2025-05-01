("use strict");

var certificateListTable = (function () {
    var _tableGen = function () {
    let queryStr = $('#query').val() != '' ? $('#query').val() : '';
    
    axios({
        method: 'get',
        url: route('records-and-drafts.list', {queryStr: queryStr}),
        data: {queryStr: queryStr},
        headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
    }).then(response => {
        if (response.status == 200) {
            $('#certificateListTable tbody').html(response.data.html);

            createIcons({
                icons,
                attrs: { "stroke-width": 1.5 },
                nameAttr: "data-lucide",
            });
        }
    }).catch(error =>{
        console.log(error)
    });


};
return {
    init: function () {
        _tableGen();
    },
};
})();

(function () {
    if ($("#certificateListTable").length) {
        certificateListTable.init();

        // On submit filter form
        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                certificateListTable.init();
                console.log('enter')
            }
        });

        $("#certificateListTable").on('click', '.recordRow', function(e){
            let $theRow = $(this);
            let theUrl = $theRow.attr('data-url');
            window.location.href = theUrl;
        });
    }

    

})();

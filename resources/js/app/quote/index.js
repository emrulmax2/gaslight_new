("use strict");

var quoteListTable = (function () {
    var _tableGen = function () {
        let queryStr = $('#query').val() != '' ? $('#query').val() : '';
        
        axios({
            method: 'get',
            url: route('quotes.list', {queryStr: queryStr}),
            data: {queryStr: queryStr},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#quoteListTable tbody').html(response.data.html);

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
    if ($("#quoteListTable").length) {
        quoteListTable.init();

        // On submit filter form
        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                quoteListTable.init();
                console.log('enter')
            }
        });

        $("#quoteListTable").on('click', '.quoteRow', function(e){
            let $theRow = $(this);
            let theUrl = $theRow.attr('data-url');
            window.location.href = theUrl;
        });
    }

    
    localStorage.clear();
})();
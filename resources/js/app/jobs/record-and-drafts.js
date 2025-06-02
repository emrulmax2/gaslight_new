("use strict");

var certificateListTable = (function () {
    var _tableGen = function () {
        let queryStr = $('#query').val() != '' ? $('#query').val() : '';
        let job_id = $('#certificateListTable').attr('data-jobid') ? $('#certificateListTable').attr('data-jobid') : '';
        
        axios({
            method: 'get',
            url: route('jobs.record.and.drafts.list', {job : job_id, queryStr : queryStr}),
            data: {
                job_id : job_id,
                queryStr: queryStr
            },
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
    }

    function filtercertificateListTable() {
        certificateListTable.init();
    }

    // On submit filter form
    $("#query").on("keypress", function (e) {
        var key = e.keyCode || e.which;
        if(key === 13){
            e.preventDefault();

            certificateListTable.init();
        }
    });

    $("#certificateListTable").on('click', '.recordRow', function(e){
        let $theRow = $(this);
        let theUrl = $theRow.attr('data-url');
        window.location.href = theUrl;
    });


})();

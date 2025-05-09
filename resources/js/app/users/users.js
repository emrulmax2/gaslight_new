("use strict");
const updatesignatureModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#updatesignature-modal"));
const confirmModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#confirmModal"));
const succModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#successModal"));
let confModalDelTitle = 'Are you sure?';

var usersListTable = (function () {
    var _tableGen = function () {

        let querystr = $("#query").val() != "" ? $("#query").val() : "";

        axios({
            method: 'get',
            url: route('users.list', {querystr: querystr}),
            data: {querystr: querystr, status : status},
            headers: {'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')},
        }).then(response => {
            if (response.status == 200) {
                $('#usersListTable').html(response.data.html);

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
    if ($("#usersListTable").length) {
        usersListTable.init();

        // On submit filter form
        $("#query").on("keypress", function (e) {
            var key = e.keyCode || e.which;
            if(key === 13){
                e.preventDefault();
    
                usersListTable.init();
            }
        });
    }

})();

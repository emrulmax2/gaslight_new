(function () {
    "use strict";

    // Show modal
    $("#add-new").on("click", function () {
        const el = document.querySelector("#addnew-modal");
        const modal = tailwind.Modal.getOrCreateInstance(el);
        modal.toggle();
    });

    // Hide modal
    // $("#programmatically-hide-modal").on("click", function () {
    //     const el = document.querySelector("#programmatically-modal");
    //     const modal = tailwind.Modal.getOrCreateInstance(el);
    //     modal.hide();
    // });

    // // Toggle modal
    // $("#programmatically-toggle-modal").on("click", function () {
    //     const el = document.querySelector("#programmatically-modal");
    //     const modal = tailwind.Modal.getOrCreateInstance(el);
    //     modal.toggle();
    // });
})();

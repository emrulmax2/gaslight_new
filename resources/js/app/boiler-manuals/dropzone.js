(function () {
    "use strict";

    const warningModal = tailwind.Modal.getOrCreateInstance(document.querySelector("#warningModal"));
    //Dropzone
    Dropzone.autoDiscover = false;
    $(".dropzone").each(function () {
        let options = {
            accept: (file, done) => {
                console.log(file);
                done();
            },
            maxFilesize: 2,
        };

        if ($(this).data("single")) {
            options.maxFiles = 1;
        }

        if ($(this).data("file-types")) {
            options.accept = (file, done) => {
                if (
                    $(this).data("file-types").split("|").indexOf(file.type) ===
                    -1
                ) {
                    alert("Error! Files of this type are not accepted");
                    done("Error! Files of this type are not accepted");
                } else {
                    console.log("Uploaded");
                    done();
                }
            };
        }

        let dz = new Dropzone(this, {
           ...options,
           
        success: function (file, response) {
            let fileId = response.id;
            let filePath = response.filePath;
            
            console.log(filePath);
            $('#fileUploadForm input[name="file_path"]').val(filePath);
            $('#fileUploadForm input[name="file_id"]').val(fileId);
        },
        });

        dz.on("maxfilesexceeded", (file) => {
            alert("No more files please!");
        });

        dz.on("error", (file, response) => {

            let errorMessage = "An error occurred during the upload.";
            if (response && response.message) {
                errorMessage = response.message;
            } else if (typeof response === "string") {
                errorMessage = response;
            } else if (file.size > this.options.maxFilesize * 1024 * 1024) {
                errorMessage = `File is too large. Maximum file size is ${this.options.maxFilesize}MB.`;
            }
            warningModal.show();
            document.getElementById("warningModal").addEventListener("shown.tw.modal", function (event) {
                $("#warningModal .warningModalTitle").html("Error On Given File!");
                $("#warningModal .warningModalDesc").html(errorMessage);
            });
        });

        dz.on("addedfile", (file) => {
            // Create the remove button
            let removeButton = Dropzone.createElement("<button class='btn btn-danger btn-sm mt-3'>Remove file</button>");

            // Listen to the click event
            removeButton.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Remove the file from Dropzone
                dz.removeFile(file);
            });

            // Add the button to the file preview element
            file.previewElement.appendChild(removeButton);
        });
    });
})();

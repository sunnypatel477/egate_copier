"use strict";

var flexform_files = [];

function flexform_init_dropzone(limit = 1){
    if ($('#dropzoneDragArea').length > 0) {
        //prevent dropzon already initialized
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function(instance) {
                instance.destroy();
            });
        }
        const form = $('#dropzoneDragArea').closest('form');
        const formAction = $(form).attr('action');
        const removeButton = $('#flexform-remove-files');
        Dropzone.autoDiscover = false;
        // Initialize Dropzone
        // Initialize Dropzone
        var myDropzone = new Dropzone("#dropzoneDragArea", {
            url: formAction,
            autoProcessQueue: false,
            maxFiles: limit,
            previewsContainer: '.dropzone-previews',
            init: function() {
                var myDropzone = this; // Closure

                // Remove button event
                removeButton.on("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.removeAllFiles(true); // Remove all files from the Dropzone
                    // Hide the remove button
                    $(removeButton).hide();
                    // Clear the global array
                    flexform_files = [];
                });

                this.on("addedfile", function(file) {
                    //add the file to the global array
                    flexform_files.push(file);
                    $(removeButton).show();
                });

                this.on("maxfilesexceeded", function(file) {
                    this.removeFile(file); // Remove the file that exceeds the limit
                });

                this.on("complete", function(file) {
                    // Handle the response after file upload is complete
                });
            }
        })
    }
}
function flexform_append_files_to_form(){
    if(flexform_files.length === 0) return false;
    var hiddenInputsContainer = $('#flexform-files-input');
    flexform_files.forEach(function(file) {
        var reader = new FileReader();
        reader.onload = function(event) {
            var base64String = event.target.result.replace(/^data:[a-zA-Z0-9]+\/[a-zA-Z0-9]+;base64,/, '');
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'files[]';
            input.value = base64String;
            $(hiddenInputsContainer).append(input);

            var nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'file_names[]';
            nameInput.value = file.name;
            hiddenInputsContainer.append(nameInput);

            var typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = 'file_types[]';
            typeInput.value = file.type;
            hiddenInputsContainer.append(typeInput);
        };
        reader.readAsDataURL(file);
    });
}
$(document).on('click', '.flexform-client-block-container .ff-submit-button', function() {
    //check if there are files to upload
    const obj = $(this);
    //delay the form submission to allow the files to be appended to the form
    if(flexform_files.length > 0) {
        flexform_append_files_to_form();
        setTimeout(function() {
            flexform_submit_form_data(obj);
        }, 1000);
    }else{
        flexform_submit_form_data(obj);
    }
    return false;
});

function flexform_submit_form_data(obj){
    var form = $(obj).closest('form');
    $.ajax({
        url: $(form).attr('action'),
        type: 'POST',
        data: new FormData(form[0]),
        contentType: false,
        processData: false,
        success: function(response) {
            //update the middle panel
            const r = JSON.parse(response);
            if(r.status === 'error') {
                alert_float('danger', r.message);
                return false;
            }
            if(r.status === 'info') {
                alert_float('info', r.message);
                //shake the form
                return false;
            }
            flexform_update_client_ui(r);
        },
        error: function(xhr, status, error) {
        }
    });
}

//footer navigation
$(document).on('click', '.flexform-footer-actions button', function() {
    const type = $(this).data('type');
    const id = $(this).data('id');
    const url = $(this).data('url');

    //post the data
    $.ajax({
        url: url,
        type: 'GET',
        data: {
            type: type,
            current: id
        },
        success: function(response) {
            const r = JSON.parse(response);
            if(r.status === 'error') {
                alert_float('danger', r.message);
                return false;
            }
            flexform_update_client_ui(r);
        },
        error: function(xhr, status, error) {
        }
    });
    return false;
});

function flexform_update_client_ui(r){
    //check if is_submit is returned
    if(r.is_submit) {
        //we are updating the button text to show that the form is being submitted
        $('.flexform-sumbit-button-wrapper').html(r.html);
        return false;
    }

    $('.flexform-client-block-container').fadeOut(400, function() {
        $('.flexform-client-block-container').html(r.html);
        $('.flexform-client-block-container').fadeIn(400);
        const upload_limit = r.upload_limit ? parseInt(r.upload_limit) : 1;
        flexform_client_inits(upload_limit);
    });
    //update the nav_footer_link
    $('.flexform-footer-actions').html(r.nav_footer_link);
    const percentage_complete = r.current_percentage_completed;
    //remove the previous progress bar and animate the new one
    $('body').find('.flexform-progress-bar').remove();
    //animate the progress bar
    const $progressBar = $('<div class="flexform-progress-bar"></div>').css({width: '0%', backgroundColor: '#000'});
    $('body').append($progressBar);
    $progressBar.animate({ width: percentage_complete + '%' }, 1000);
}

//active star rating
$(document).on('click', '.flexform-rating__stars label', function() {
    const obj = $(this);
    //remove active from all these star
    $(obj).closest('.flexform-rating__stars').find('label').removeClass('active-star');
    //add active to this star and all the stars before it
    $(obj).addClass('active-star');
    $(obj).prevAll().addClass('active-star');
});

function flexform_client_inits(limit = 1){
    appColorPicker();
    appDatepicker();
    appSelectPicker($('select'));
    $(".bootstrap-select").click(function () {
        $(this).addClass("open");
    });
    flexform_init_signature();
    flexform_init_dropzone(limit);
}

function flexform_init_signature(){
    //check if signature ui is present
    if($('.ff-signature-wrapper').length === 0) return false;
    SignaturePad.prototype.toDataURLAndRemoveBlanks = function() {
        var canvas = this._ctx.canvas;
        // First duplicate the canvas to not alter the original
        var croppedCanvas = document.createElement('canvas'),
            croppedCtx = croppedCanvas.getContext('2d');

        croppedCanvas.width = canvas.width;
        croppedCanvas.height = canvas.height;
        croppedCtx.drawImage(canvas, 0, 0);

        // Next do the actual cropping
        var w = croppedCanvas.width,
            h = croppedCanvas.height,
            pix = {
                x: [],
                y: []
            },
            imageData = croppedCtx.getImageData(0, 0, croppedCanvas.width, croppedCanvas.height),
            x, y, index;

        for (y = 0; y < h; y++) {
            for (x = 0; x < w; x++) {
                index = (y * w + x) * 4;
                if (imageData.data[index + 3] > 0) {
                    pix.x.push(x);
                    pix.y.push(y);

                }
            }
        }
        pix.x.sort(function(a, b) {
            return a - b
        });
        pix.y.sort(function(a, b) {
            return a - b
        });
        var n = pix.x.length - 1;

        w = pix.x[n] - pix.x[0];
        h = pix.y[n] - pix.y[0];
        var cut = croppedCtx.getImageData(pix.x[0], pix.y[0], w, h);

        croppedCanvas.width = w;
        croppedCanvas.height = h;
        croppedCtx.putImageData(cut, 0, 0);

        return croppedCanvas.toDataURL();
    };


    function signaturePadChanged() {

        var input = document.getElementById('signatureInput');
        var $signatureLabel = $('#signatureLabel');
        $signatureLabel.removeClass('text-danger');

        if (signaturePad.isEmpty()) {
            $signatureLabel.addClass('text-danger');
            input.value = '';
            return false;
        }

        $('#signatureInput-error').remove();
        var partBase64 = signaturePad.toDataURLAndRemoveBlanks();
        partBase64 = partBase64.split(',')[1];
        input.value = partBase64;
    }

    var canvas = document.getElementById("signature");
    var clearButton = wrapper.querySelector("[data-action=clear]");
    var undoButton = wrapper.querySelector("[data-action=undo]");
    var identityFormSubmit = document.getElementById('identityConfirmationForm');

    var signaturePad = new SignaturePad(canvas, {
        maxWidth: 2,
        onEnd:function(){
            signaturePadChanged();
        }
    });

    clearButton.addEventListener("click", function(event) {
        signaturePad.clear();
        signaturePadChanged();
    });

    undoButton.addEventListener("click", function(event) {
        var data = signaturePad.toData();
        if (data) {
            data.pop(); // remove the last dot or line
            signaturePad.fromData(data);
            signaturePadChanged();
        }
    });

    $('#identityConfirmationForm').submit(function() {
        signaturePadChanged();
    });

}
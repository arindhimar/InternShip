function download_file(fileURL, fileName) {
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        var filename = fileURL.substring(fileURL.lastIndexOf('/') + 1);
        save.download = fileName || filename;
        if (navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
            document.location = save.href;
            // window event not working here
        } else {
            var evt = new MouseEvent('click', {
                'view': window,
                'bubbles': true,
                'cancelable': false
            });
            save.dispatchEvent(evt);
            (window.URL || window.webkitURL).revokeObjectURL(save.href);
        }
    }

    // for IE < 11
    else if (!!window.ActiveXObject && document.execCommand) {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
}

function removeFile(x) {


    let temp = new FormData();

    if (x == 4) {
        temp.append("flag", 100);
        var files = $("#txtFile")[0].files;

        for (var i = 0; i < files.length; i++) {
            temp.append("img[]", files[i]); // Use "img[]" to send an array of files
        }
    }
    else {
        temp.append("flag", 0);
        temp.append("img", $("#txtFile")[0].files[0]);
    }

    




    $.ajax({
        type: "post",
        url: "ajax/ajax.php",
        data: temp,
        contentType: false,
        processData: false,
        success: function (response) {

        }
    });
}



$(document).ready(function () {
    $("#btnSubmit").click(function (e) {
        e.preventDefault();

        let temp = new FormData();


        var selectedPlatform = $('input[name="platform"]:checked').val();
        var selectedType = $('input[name="layout"]:checked').val();


        
    


        temp.append("flag", selectedPlatform);
        temp.append("ctrlflag", selectedType);

        if (selectedPlatform == 4) {
            temp.append("flag", selectedPlatform);
            var files = $("#txtFile")[0].files;
    
            for (var i = 0; i < files.length; i++) {
                temp.append("img[]", files[i]); // Use "img[]" to send an array of files
            }
        }
        else {
            temp.append("flag", selectedPlatform);
            temp.append("img", $("#txtFile")[0].files[0]);
        }

        $.ajax({
            type: "post",
            url: "ajax/ajax.php",
            data: temp,
            contentType: false,
            processData: false,
            dataType: 'json',  // Specify that you expect JSON response
            success: function (response) {
                // Assuming the response contains the file path
                let generatedFilePath = response.filePath;
                console.log(generatedFilePath)
                // let fileName = generatedFilePath.split('/').pop();

                download_file(generatedFilePath, 'label-cropped ' + generatedFilePath); //call function
                removeFile(selectedPlatform);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

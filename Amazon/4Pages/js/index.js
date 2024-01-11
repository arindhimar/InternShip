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

$(document).ready(function () {
    $("#btnSubmit").click(function (e) {
        e.preventDefault();

        let temp = new FormData();

        temp.append("flag", 1);
        temp.append("img", $("#txtFile")[0].files[0]);

        $.ajax({
            type: "post",
            url: "ajax/final2.php",
            data: temp,
            contentType: false,
            processData: false,
            dataType: 'json',  // Specify that you expect JSON response
            success: function (response) {
                // Assuming the response contains the file path
                let generatedFilePath = response.filePath;
                // console.log(response)
                // Open the file in a new tab
                // window.open(generatedFilePath, '_blank');
                
                download_file(generatedFilePath, 'label-cropped '+$("#txtFile")[0].files[0].name); //call function

            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

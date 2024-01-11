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
            // dataType: 'json',  // Specify that you expect JSON response
            success: function (response) {
                // Assuming the response contains the file path
                // let generatedFilePath = response.filePath;
                // console.log(response)
                // Open the file in a new tab
                window.open(response);
            },
            error: function (xhr, status, error) {
                // console.error(xhr.responseText);
            }
        });
    });
});

$(document).ready(function () {
    $("#btnSubmit").click(function (e) { 
        e.preventDefault();
        
        let temp=new FormData();

        temp.append("flag",1);
        temp.append("img",$("#txtFile")[0].files[0]);

        // console.log(temp)

        $.ajax({
            type: "post",
            url: "ajax.php",
            data: temp,
            contentType:false,
            processData:false,
            // dataType: "dataType",
            success: function (response) {
                console.log(response)
            }
        });

    });
});
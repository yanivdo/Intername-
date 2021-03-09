

$(".dropdown-item").on("click", function(){
    $(".dropdown-toggle").html($(this).data('value'));
});

$(".searchButton").on("click", function(){
   var err = 0;
   var searchText = $('.userFilter').val();
   var searchBy = $(".dropdown-toggle").html();
   $(".searchTextErr").html("");
   if(searchBy.length <= 0 || searchBy == 'Search by'){
    err++;
    $('.searchTextErr').html('Please search by at lease on filter');
   }
   if(searchText.length <= 0){
    err++;
    $('.searchTextErr').html('Please search by at lease on filter');
   }

   if(err == 0){
    $.ajax({
        url: "Client/search",
        type: "POST",
        data: {
            searchText:searchText,
            searchBy:searchBy
        },
    }).done(function (data) {
        $(".dataRow").remove();
        $(".rowText").after(data);
    });
   }
});



$(".formButton").on("click", function () {
    location.href = "/addData";
});
$(".formSubmit").on("click", function (event) {
    event.preventDefault();
    var name = $(".userName").val();
    var email = $(".userEmail").val();
    var title = $(".postTitle").val();
    var body = $(".postBody").val();
    var err = 0;
    $(".err").hide();
    if (name.length <= 0) {
        err++;
        $(".userErr").html("Please enter valid user name");
        $(".userErr").show();
    }

    console.log(!validateEmail(email));
    if (!validateEmail(email)) {
        err++;
        $(".emailErr").html("Please enter valid email");
        $(".emailErr").show();
    }
    if (title.length <= 0) {
        err++;
        $(".titleErr").html("Please enter valid post title");
        $(".titleErr").show();
    }
    if (body.length <= 0) {
        err++;
        $(".bodyErr").html("Please enter valid post body");
        $(".bodyErr").show();
    }
    if (err == 0) {
        $.ajax({
            url: "Client/addPost",
            type: "POST",
            data: {
                name: name,
                email: email,
                title: title,
                body: body
            },
        }).done(function (data) {
            $('.finalMsg').html(data);
            $('.formSubmit').prop('disabled', true);
            $('.formSubmit').css("background-color", "darkgrey");
        });
    }
});
function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

$('form#myForm').submit(function(e){
    e.preventDefault();
    e.stopPropagation();
    var formData = new FormData(this);
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: create_url,
        method: 'post',
        data: formData,
        success: function (res) {
            if(res == 'exist'){
                toastr["warning"]("Exist Email.");
            } else {
                if(res=="notify") {
                    toastr["success"]("Added. You will be notified when the vehicle becomes available.");
                    setInterval(function(){ 
                        location.href = stock_url; 
                    }, 2000);
                } else {
                    alert()
                    toastr["success"]("Success");
                    setInterval(function(){ 
                        location.href = login_url; 
                    }, 2000);
                }
            }
        },
        error: function (res){
            console.log(res)
        },
        cache: false,
        contentType: false,
        processData: false
    })
})
$(document).ready(function () {
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
                toastr["success"]("Success");
                setInterval(function(){ 
                    location.href = window.location.href; 
                }, 2000);
            },
            error: function (res){
                console.log(res)
            },
            cache: false,
            contentType: false,
            processData: false
        })
    })
    $(document).on('click', '.confirm_status', function(e){
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).data('id');
        var user_id = $(this).attr('data-user');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: get_status,
            method: 'get',
            data: {id:id},
            success: function (data){
                $( ".select-status" ).val(data.result.status);
                $( ".count-time input" ).val(data.result.count_time);
                if (data.result.status === "Invoice Issued") {
                    $(".count-time").show();
                } else {
                    $(".count-time").hide();
                }
            }
        })
        $(".select-status").change(function() {
            var status = $( ".select-status option:selected" ).text();
            if (status === "Invoice Issued") {
                $(".count-time").show();
            } else {
                $(".count-time").hide();
            }
        });
        $('.save_button').click(function(){
            var status = $( ".select-status option:selected" ).text();
            var count_time = $( ".count-time input" ).val();
            if (status == "Invoice Issued" && count_time == "") {
                alert("Please insert 'Count Time'")
            } else {
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: change_status,
                    method: 'get',
                    data: {id:id, status:status, count_time:count_time, user_id:user_id},
                    success: function (data){
                        toastr["success"]("Success");
                        $('#exampleModalScrollable').modal('hide');
                        table.ajax.reload();
                    }
                })
            }
        })
    })
    var container = document.querySelector('#chat-scroll .simplebar-content-wrapper'); 
    container.scrollTo({ top: container.scrollHeight, behavior: "smooth" });
})

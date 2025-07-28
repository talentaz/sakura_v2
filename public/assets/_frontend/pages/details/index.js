$(document).ready(function () {
    pc_price_calc();
    mb_price_calc();
    
    $(document).on('click', '.image-count', function(e){
        e.preventDefault();
        e.stopPropagation();
        id = $(this).data("id");
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: light_url,
            method: 'get',
            data: {id:id},
            success: function (data){
                var host = window.location.origin
                var arr = data.data;
                        
                arr.forEach( function(data) {
                    data['src'] = data['image'];
                    delete data['image'];
                    data.src = host +  '/uploads/vehicle/' + id + '/real/'+data.src;
                });
                $(this).lightGallery({
                    dynamic: true,
                    dynamicEl: arr,
                    download: false,
                    mode: 'lg-fade',
                })
            }
        })
    });
    $(document).on('click', '.video-count', function(e){
        e.preventDefault();
        e.stopPropagation();
        video = $(this).data("id");
        console.log(video);
        $(this).lightGallery({
            dynamic: true,
            dynamicEl: [
                {
                    src : video
                },
            ],
            download: false,
            mode: 'lg-fade',
        })
    });
    $('.slider-thumbnails').slick({
        infinite: false,
        slidesToShow: 7,
        slidesToScroll: 1,
        asNavFor: '.slider',
        focusOnSelect: true
    });            
    
    $('.slider').slick({
        infinite: false,
        asNavFor: '.slider-thumbnails',
    });
    $(document).on('click', '#pc-calc', function(){       
        pc_price_calc();
    })
    $(document).on('click', '#mobile-calc', function(){       
        mb_price_calc();
    })

    //  click notify button
    $('.add-notify').click(function(e) {
        e.preventDefault();
        var vehicleId = $(this).data('vehicle-id');
        var userId = $(this).data('user-id');   
        $.ajax({
            url: '/notify/create',
            method: 'get',
            data: { user_id: userId, vehicle_id: vehicleId },
            success: function(res) {
                if(res.result) {
                    toastr["success"]("Added. You will be notified when the vehicle becomes available.");
                    $(e.target).after('<p class="added-notify">Added to NOTIFY ME</p>');
                    $(e.target).remove(); // Remove the anchor tag after adding the notification
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while adding notification.');
                console.error(xhr.responseText);
            }
        });     
    })
    // select country pc version
    $('.select-country').on("change", function (e) { 
        var id = $(e.currentTarget).val()
        $.ajax({
            url: select_port,
            data:{
                id:id
            },
            type: "get",
        })
        .done(function (response) {
            var port_list = response.port_list;
            var port_list_array= $.map(port_list, function(value, index) {
                return [[index,value]];
            });
            html = ''
            if(port_list_array){
                for(i=0; i<port_list_array.length; i++){
                    arr_str = port_list_array[i][1];
                    console.log(arr_str);
                    html +=`<option value='${JSON.stringify(port_list_array[i][1])}'>${capitalizeFirstLetter(port_list_array[i][0])}</option>`
                }
            } 
            html +='<option value="0"></option>'
            $('.port-pc')
                    .find('option')
                    .remove()
                    .end()
                    .append(html)
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    })

    // select country mobile version
    $('.select-country').on("change", function (e) { 
        var id = $(e.currentTarget).val()
        $.ajax({
            url: select_port,
            data:{
                id:id
            },
            type: "get",
        })
        .done(function (response) {
            var port_list = response.port_list;
            var port_list_array= $.map(port_list, function(value, index) {
                return [[index,value]];
            });
            html = ''
            if(port_list_array){
                for(i=0; i<port_list_array.length; i++){
                    arr_str = port_list_array[i][1];
                    console.log(arr_str);
                    html +=`<option value='${JSON.stringify(port_list_array[i][1])}'>${capitalizeFirstLetter(port_list_array[i][0])}</option>`
                }
            } 
            html +='<option value="0"></option>'
            $('.port-mb')
                    .find('option')
                    .remove()
                    .end()
                    .append(html)
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log('Server error occured');
        });
    })

    if($('.insp-value').val() == 0){
        $('.insp-n').addClass('active');    
    } else {
        $('.insp-y').addClass('active');    
    }
    if($('.insu-value').val() == 0){
        $('.insu-n').addClass('active');    
    } else {
        $('.insu-y').addClass('active');    
    }
    $('.insp-n').click(function(){
        $(this).addClass('active')
        $('.insp-y').removeClass('active')    
        $('.insp-value').val($(this).data("id"))
    })
    $('.insp-y').click(function(){
         $(this).addClass('active')
         $('.insp-n').removeClass('active')    
         $('.insp-value').val($(this).data("id"))
    })
    $('.insu-n').click(function(){
         $(this).addClass('active')
         $('.insu-y').removeClass('active')    
         $('.insu-value').val($(this).data("id"))
    })
    $('.insu-y').click(function(){
         $(this).addClass('active')
         $('.insu-n').removeClass('active')    
         $('.insu-value').val($(this).data("id"))
    })

    $('form.inquForm').submit(function(e){
        e.preventDefault();
        e.stopPropagation();
        $('.inqu_fob_price').val($('.fob-value').text());
        $('.inqu_inspection').val($('.insp-value').val());
        $('.inqu_insurance').val($('.insu-value').val());
        $('.inqu_port').val($('.cif p').text());
        $('.inqu_url').val(window.location.href);
        $('.inqu_total_price').val($('.total-price-value').text());
        $('.stock_no').val($('.stock-no').text());
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: inq_url,
            method: 'post',
            data: formData,
            success: function (res) {
                toastr["success"]("Success");
                // setInterval(function(){ 
                //     location.href = list_url; 
                // }, 2000);
            },
            error: function (res){
                console.log(res)
            },
            cache: false,
            contentType: false,
            processData: false
        })
    })

    //facebook share button
    var popupSize = {
        width: 780,
        height: 550
    };

    $(document).on('click', '.social-face', function(e){

        var
            verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
            horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

        var popup = window.open($(this).prop('href'), 'social',
            'width='+popupSize.width+',height='+popupSize.height+
            ',left='+verticalPos+',top='+horisontalPos+
            ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

        if (popup) {
            popup.focus();
            e.preventDefault();
        }

    });
})


//pc calc
function pc_price_calc(){
    port_price = 0; 
    port_array = JSON.parse($('.port-pc option:selected').val());
    port_name = $('.port-pc option:selected' ).text(); 
    inspection_price = parseInt($('.insp-value' ).val());
    insurance_price = parseInt($('.insu-value' ).val()); 
    total_price = parseInt($('.price').val());                                                                                                                 
    cubic_meter = $('.cubic-meter').val();
    body_type = $('.body_type').val(); 
    for (i = 0; i < port_array.length; i++) {
        if(body_type == Object.keys(port_array[i])){
            port_price = port_array[i][body_type];
        }
    }
    price_shipping = port_price*cubic_meter;
    console.log(port_price);  
    if(port_price == 0) {
        cif = "( C&F )"
        final_price = "ASK"    
        port_name = 'Port'
    } else {
        if(inspection_price == 0){          
            cif = '( CIF )'
        }
        if(insurance_price == 0){
            cif = '(  C&F Inspect )'
        }
        if(insurance_price == 0 && inspection_price == 0){
            cif = '( C&F )';
        }
        if(!insurance_price == 0 && !inspection_price == 0){
            cif = '( CIF Inspect )'
        }
        final_price ='$' + Math.round(total_price + price_shipping + inspection_price + insurance_price).toLocaleString();
    }
    $('.cif p').text(cif +' '+ port_name);
    $('.total-price-value').text(final_price);
}

// mobile calc
function mb_price_calc(){
    port_price = 0; 
    port_array = JSON.parse($('.port-mb option:selected').val());
    port_name = $('.port-mb option:selected' ).text(); 
    inspection_price = parseInt($('.insp-value' ).val());
    insurance_price = parseInt($('.insu-value' ).val()); 
    total_price = parseInt($('.price').val());                                                                                                                 
    cubic_meter = $('.cubic-meter').val();
    body_type = $('.body_type').val(); 
    for (i = 0; i < port_array.length; i++) {
        if(body_type == Object.keys(port_array[i])){
            port_price = port_array[i][body_type];
        }
    }
    price_shipping = port_price*cubic_meter;
    console.log(port_price);  
    if(port_price == 0) {
        cif = "( C&F )"
        final_price = "ASK"    
        port_name = 'Port'
    } else {
        if(inspection_price == 0){          
            cif = '( CIF )'
        }
        if(insurance_price == 0){
            cif = '(  C&F Inspect )'
        }
        if(insurance_price == 0 && inspection_price == 0){
            cif = '( C&F )';
        }
        if(!insurance_price == 0 && !inspection_price == 0){
            cif = '( CIF Inspect )'
        }
        final_price ='$' + Math.round(total_price + price_shipping + inspection_price + insurance_price).toLocaleString();
    }
    $('.cif p').text(cif +' '+ port_name);
    $('.total-price-value').text(final_price);
}
function capitalizeFirstLetter(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function countDownTimer() {
    // Get all elements with class 'remaining-time'
    var remainingTimeElement = $('.remaining-time');
    
    var remainingTime = parseInt(remainingTimeElement.attr('data-time'));
        
    // Calculate hours, minutes, and seconds
    var hours = ("0" + Math.floor(remainingTime / 3600)).slice(-2);
    var minutes = ("0" + Math.floor((remainingTime % 3600) / 60)).slice(-2);
    var seconds = ("0" + remainingTime % 60).slice(-2);
    
    // Update the content of the element with the countdown timer
    remainingTimeElement.html('Available in: ' + '<span class="time">'+ hours + '</span>' + '<span class="symbol"> h, </span>' + '<span class="time">'+ minutes + '</span>' + '<span class="symbol"> m, </span>' + '<span class="time">'+ seconds + '</span>' + '<span class="symbol"> s</span>');
    
    // Decrease remaining time by 1 second
    remainingTime--;
    
    // Update the data-time attribute to reflect the new remaining time
    remainingTimeElement.attr('data-time', remainingTime);
}

setInterval(countDownTimer, 1000);
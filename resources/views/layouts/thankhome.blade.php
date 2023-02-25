<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <link rel="shortcut icon" type="image/x-icon" href="{!! FAVICON_PATH !!}">
        <title>{{$title.TITLE_FOR_LAYOUT}}</title>
         <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

        <!-- Bootstrap -->
        {{ HTML::style('public/css/front/bootstrap.min.css')}}
        {{ HTML::style('public/css/front/style.css')}}
        {{ HTML::style('public/css/front/font-awesome.css')}}
        {{ HTML::style('public/css/front/aos.css')}} 
        {{ HTML::style('public/css/AdminLTE.min.css')}}
         {{ HTML::style('public/css/front/owl.theme.default.min.css')}}
        {{ HTML::style('public/css/front/owl.carousel.min.css')}}

        <!---for calender -->
        {{ HTML::style('public/css/calender/main.css')}}
        {{ HTML::style('public/css/calender/list_main.css')}}
        {{ HTML::style('public/css/calender/daygrid_main.css')}}

        
        
        {{ HTML::script('public/js/front/jquery.min.js')}}
        {{ HTML::script('public/js/jquery.validate.js')}}
        {{ HTML::script('public/js/front/bootstrap.min.js')}}
        {{ HTML::script('public/js/front/owl.carousel.js')}}
        {{ HTML::script('public/js/front/custom.min.js')}}
        {{ HTML::script('public/js/front/aos.js')}}

        <!---for calender -->
        {{ HTML::script('public/js/calender/main.js')}}
        {{ HTML::script('public/js/calender/list_main.js')}}
        {{ HTML::script('public/js/calender/google_main.js')}}
        {{ HTML::script('public/js/calender/datagrid_main.js')}}
        {{ HTML::script('public/js/calender/intereaction_main.js')}}
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
          .fc-past:hover{pointer-events: none!important;}
        </style>
    </head>
    <body>
        
        <!-- @include('elements.newheader') -->
        @yield('content') 
        <!-- @include('elements.newfooter') -->
        
        <div id="toTop">{{HTML::image('public/img/front/arrow-top.png',"top")}}</div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        
        <script>
            $(document).on('click', function () {
                $('.collapse').collapse('hide');
            });
        </script>
        
        <script>
            AOS.init({
                duration: 1200, once: true
            });
        </script>
        <script>
            $(window).scroll(function () {
                    if ($(this).scrollTop() > 0) {
                        $('#toTop').fadeIn();
                   } else {
                      $('#toTop').fadeOut();
                    }
                });
                $('#toTop').click(function () {
                     $('body,html').animate({scrollTop: 0}, 800);
                 });
        </script>

    <script type="text/javascript">
      $(document).ready(function() { 
        $('select[name="service_ids"]').on('change', function() {
            var serviceId = $(this).val(); 
             $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(serviceId){ 
              $.ajax({ 
                    type: 'POST',
                    url: "<?php echo HTTP_PATH; ?>/getstaff/"+serviceId,
                    cache: false,
                    success: function (result)
                    {
                        var results = JSON.parse(result);
                        $('select[name="staff_id"]').empty();
                            $.each(results, function(key, value) {
                                $('select[name="staff_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                    }
                });
            }else{
                $('select[name="staff_id"]').empty();
            }
        });
      });
    </script> 

<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var today = new Date();
    var dd = today.getDate();

    var mm = today.getMonth()+1; 
    var yyyy = today.getFullYear();
    if(dd<10) 
    {
        dd='0'+dd;
    } 

    if(mm<10) 
    {
        mm='0'+mm;
    } 
    today = yyyy+'-'+mm+'-'+dd;
    console.log(today);
    var calendar = new FullCalendar.Calendar(calendarEl, {

      plugins: [ 'interaction', 'dayGrid', 'list', 'googleCalendar' ],

      // validRange: {
      //   start: today
      // },
      
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listYear'
      },


      displayEventTime: false, 
      googleCalendarApiKey: 'AIzaSyDcnW6WejpTOCffshGDDb4neIrXVUA1EAE',
      events: 'en.usa#holiday@group.v.calendar.google.com',

      eventClick: function(arg) {
      
        window.open(arg.event.url, 'google-calendar-event', 'width=700,height=600');

        arg.jsEvent.preventDefault() 
      },

    });

    calendar.render();

    //$('#calendar').fullCalendar('render');
  });

//   $(".fc-past").hover(function(){
//   $(this).css("pointer-events", "none");
// });
$("td.fc-past").css("pointer-events","none");

    $(document.body).on('click', '.fc-day,.fc-day-top,.fc-future', function(event){
    var requestDate=event.target.dataset.date;
    console.log("data on click : "+requestDate);
    var mydate = new Date(event.target.dataset.date);
    $('#selected_date').val(requestDate);
    $("#slots-section").html("<div class='spinner-border' role='status'><span class='sr-only'>Loading...</span></div>");
      var staffslug=$('#staff_slug').val();
    var isFixedSlot=$('#is_fixed_slot').val();
    
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    var dateformatstr=days[mydate.getDay()]+", "+mydate.getDate()+" "+mlist[mydate.getMonth()]+", "+mydate.getFullYear();
    $("#click-date").html(dateformatstr);
    $("#available-date").html(dateformatstr);
    console.log(dateformatstr);
    if(isFixedSlot!='0'){
        console.log("is fixed slot yes");
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({ 
          type: 'POST',
          url: "<?php echo HTTP_PATH; ?>/getslotdata/"+requestDate,
          cache: false,
          data:{'date':requestDate,'slug':staffslug,'dayname':days[mydate.getDay()]},
          success: function (result)
          {

            var results = JSON.parse(result);
                console.log(results);
                
                if(results.length>0){
                  var i;
                  var content="";
                  for (i = 0; i < results.length; ++i) {
                     content=content+"<div class='row slot-block'><div class='col-md-12 col-md-8 col-md-8 col-xl-8'><h5><i class ='fa fa-clock-o'></i> "+results[i]+"</h5></div><div class='col-md-12 col-md-4 col-lg-4 col-xl-4'><!-- <button type='button' class='btn btn-appointment'>Book Appointment</button> --><input type='button' class='btn btn-appointment' value='Book Appointment' data-slot='"+results[i]+"' data-slotdate='"+dateformatstr+"' onclick = 'bookAppoinment(this)' id = 'book-"+results[i]+"'></div></div><br>";

                  }
                  $("#slots-section").html(content);
                }else{
                  $("#slots-section").html("<h4 class='text-danger'>No slot available, Please check for another date</h4>");
                }

                // $("#service-name").html(results['names']);
                // $("#service-time").html(results['duration']);
                // $("#service-price").html(results['total']);
          }
        });
       $("#myModal").show();
      $(".modal-backdrop.show").show();
    }    
  $("#myModal").modal();

    });
   

    // function tConvert (time) {
    //   console.log("convert"+time);
    // // Check correct time format and split into components
    //   time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    //   if (time.length > 1) { // If time format correct
    //     time = time.slice (1);  // Remove full string match value
    //     time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
    //     time[0] = +time[0] % 12 || 12; // Adjust hours
    //   }
    //   return time.join (''); // return adjusted time or original string
    // }
    // $x=tConvert('13:45');
    //console.log($x);
    function bookAppoinment(slot) {
      var bookSlotTime=(slot.dataset.slot).split("-",2);
      var bookSlotdate=slot.dataset.slotdate;
      console.log("start : "+bookSlotTime[0]);
      console.log("end : "+bookSlotTime[1]);
      // var a=tConvert(bookSlotTime[0].trim());
      // var b=tConvert(bookSlotTime[1].trim());
      var a=bookSlotTime[0].trim();
      var b=bookSlotTime[1].trim();
      console.log(a);
      console.log(b);

      console.log(bookSlotdate);
      $('#start_time').val(a);
      $('#end_time').val(b);
      $("#appoinment-date").html(bookSlotdate);
      $("#appoinment-time").html(a+" - "+b);
      $("#your-info-div").show();
      $("#select-date-div").hide();
      $("#select-service-div").hide();
      $("#myModal").hide();
      $(".modal-backdrop.show").hide();
      $("#step2-content").show();
      $("body").css("overflow-x","hidden");
      $("body").css("overflow-y","auto");
      $("#your-info-menu").removeClass("not-active");
      $("#your-info-menu").addClass("active-link");
    }
    


</script>
<script type="text/javascript">window.$zopim || (function (d, s) {
        var z = $zopim = function (c) {
            z._.push(c)
        }, $ = z.s =
                d.createElement(s), e = d.getElementsByTagName(s)[0];
        z.set = function (o) {
            z.set.
                    _.push(o)
        };
        z._ = [];
        z.set._ = [];
        $.async = !0;
        $.setAttribute("charset", "utf-8");
        $.src = "https://v2.zopim.com/?4toXhVRHXOtCLes7sRNCMItG7HdblsBt";
        z.t = +new Date;
        $.
                type = "text/javascript";
        e.parentNode.insertBefore($, e)
    })(document, "script");</script>
<script>
    $zopim(function () {
        $zopim.livechat.bubble.setColor('#ee534e');
    });
</script>
</body>
</html>



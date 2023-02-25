<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[\a-zA-Z._ ]+$/i.test(value);
        }, "Only letters and underscore are allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
        $("#loginform").validate();
    });
 
</script>
<section class="slider category">
            <div class="container">
                 <?php echo e(Form::open(array('method' => 'post', 'id' => 'searchform'))); ?>

                <div class="row">
                  <?php echo $__env->make('elements.search_left_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="col-md-8 col-sm-8 col-lg-9">
   
<?php echo $__env->make('elements.deals.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
 <div class="page-list-content"> 
<?php echo $__env->make('elements.deals.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
                    </div>
                    
                </div>
                 <input type="hidden" value="1" id="pageidd" name="page">
                 <?php echo e(Form::close()); ?>

            </div>
            
        </section>

<script type="text/javascript">
    $(document).ready(function () {
/*$('#search_location').keyup(function(){ 
		var thisHref = $(location).attr('href'); 
		var key = $(this).val();
		//alert(txt);
		if(key !=''){
			 
			$.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
			$.ajax({
				url:thisHref,
				type: "POST",
                data: {search:key},
				dataType:"text",
                //beforeSend: function () { $("#searchloader").show();},
                //complete: function () {$("#searchloader").hide();},
                success: function (data) { 
					//alert(data);
               $('.page-list-content').html(data);
			}
		});
		}else{
			
	}
	});*/		
         $(".deltimesub").on('change', function (event) { 
             event.preventDefault();
            updateresult ();
        });
        
          $(document).on('click', '.ajaxpagee a', function () {
            var npage = $(this).html();
            if ($(this).html() == '»') {
                npage = $('.ajaxpagee .active').html() * 1 + 1;
            } else if ($(this).html() == '«') {
                npage = $('.ajaxpagee .active').html() * 1 - 1;
            }
            $('#pageidd').val(npage);
            updateresult ();
            return false;
        });
         $("img.lazy").lazyload();
    <?php if(isset($isajax)): ?>
    $('html, body').animate({
        scrollTop: $('#backtotop').offset().top - 1
    }, 'slow');
    <?php endif; ?>
    });
    
      function updateresult(){ 
	  //alert('hello');
        var thisHref = $(location).attr('href'); 
          $.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
        $.ajax({
            url: thisHref,
            type: "POST",
            data: $('#searchform').serialize(),
            beforeSend: function () { $("#searchloader").show();},
            complete: function () {$("#searchloader").hide();},
            success: function (result) {
               $('.page-list-content').html(result);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/deals/search.blade.php ENDPATH**/ ?>
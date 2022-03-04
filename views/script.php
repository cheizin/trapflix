<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootsnav.js"></script>
<script src="assets/js/viewportchecker.js"></script>
<script src="assets/js/slick.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap-wysihtml5.js"></script>
<script src="assets/plugins/aos-master/aos.js"></script>
<script src="assets/plugins/nice-select/js/jquery.nice-select.min.js"></script>
<script src="assets/js/custom.js"></script>

<!-- color schemes for the charts -->
<script src="assets/js/chartjs-plugin-colorschemes.min.js"></script>

<script src="assets/libs/smartwizard/jquery.smartWizard.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<!-- ChartJS -->
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.js"></script>
<script src="assets/js/pace.min.js"></script>

<!--blockui ver 2.70-->
<script src="assets/js/jquery.blockUI.js"></script>
<!--sweetalert-->
<script src="assets/js/sweetalert2@9.js"></script>


<!--jquery autosize-->
<script src="assets/js/jquery.autosize.js"></script>
<!-- Datatables -->
<script type="text/javascript" src="assets/libs/datatables/pdfmake.min.js"></script>
<script type="text/javascript" src="assets/libs/datatables/vfs_fonts.js"></script>
<script type="text/javascript" src="assets/libs/datatables/datatables.min.js"></script>
<!-- datepicker -->
<script src="assets/js/bootstrap-datepicker.min.js"></script>
<!-- maxlength -->
<script src="assets/js/bootstrap-maxlength.js"></script>

<!--highcharts -->
<script src="assets/js/highcharts.js"></script>
<script src="assets/js/exporting.js"></script>
<script src="assets/js/offline-exporting.js"></script>


<!-- autosave forms sisyphus -->
<script src="assets/js/sisyphus.min.js"></script>

<!--Typed js -->
<script src="assets/js/typed.js"></script>
<script src="assets/js/jq-ajax-progress.js"></script>

<!-- shimmer js -->
<script src="assets/libs/shimmerjs/shimmer.js"></script>

<!-- simpleticker  js -->
<script src="assets/libs/vticker/jquery.vticker-min.js"></script>

<!-- pace min js -->
<script data-pace-options='{ "ajax": true }' src='assets/js/pace.min.js'></script>

<!-- animated event calendar  js -->
<script src="assets/libs/animated-event-calendar/src/jquery.simple-calendar.js"></script>

<!-- roadmap -->
<script src="assets/libs/roadmap/dist/jquery.roadmap.min.js"></script>

<!-- gantt -->
<script src="assets/libs/gantt/js/jquery.fn.gantt.js"></script>

<!-- color schemes for the charts -->
<script src="assets/js/chartjs-plugin-colorschemes.min.js"></script>

<script src="assets/libs/smartwizard/jquery.smartWizard.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script src="assets/js/jquery.lettering.js"></script>
<script src="assets/js/jquery.textillate.js"></script>

<!-- routes -->
<script src="controllers/routes.js?v41"></script>

<!-- custom -->
<script src="controllers/custom.js?v=55"></script>

<!-- skeleton -->
<script src="controllers/skeletons.js?v=22"></script>

<!-- validators -->
<script src="controllers/validators.js"></script>

<!-- forms -->
<script src="controllers/forms.js?v=69"></script>

<script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="fd25f24e-2c7d-4a3e-8307-766c1a69a4ec";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

  <script src="https://timeago.yarp.com/jquery.timeago.js"></script>
<script>
$('#confirm').on('keyup', function () {
  if ($('#password').val() == $('#confirm').val()) {
    $('#password_help').html(' Password Matched').css('color', 'blue');
  } else
    $('#password_help').html('Not Matching').css('color', 'red');
});

</script>

<script>
$("#smartwizard-add-project").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
    if(stepDirection == "3")
    {
      $('.sw-btn-group-extra').removeClass('d-none');
    }
    else
    {
      $('.sw-btn-group-extra').addClass('d-none');
    }
});

</script>
<script>
$crisp.push(["set", "user:nickname", ["<?php echo $_SESSION['fName']; ?>"]]);
</script>
<script>
	$(window).load(function() {
	  $(".page_preloaderr").fadeOut("slow");;
	});
	AOS.init();
</script>

<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
<script>
$(function(){
  GetAllHomePageContent();
})
		$(document).ready(function() {
		// Add minus icon for collapse element which is open by default
		$(".collapse.show").each(function(){
			$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
		});

		// Toggle plus minus icon on show hide of collapse element
		$(".collapse").on('show.bs.collapse', function(){
			$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
		}).on('hide.bs.collapse', function(){
			$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
		});

    $( ".select2" ).select2({
          theme: "material"
    });
    $(".timeago").timeago();
    $("#mdb-lightbox-ui").load("assets/mdb-addons/mdb-lightbox-ui.html");

		});

/*
$('.sticky-content').sticky({
  topSpacing:50,
  zIndex: 2,

})
*/
</script>

<script >

<?php
          $start='quiz';
          $end=date('Y-m-d H:i:s', strtotime('quiz' . ' +20 minutes' ) );
            echo "
                var date_quiz_start='$start';
                var date_quiz_end='$end';
                var time_quiz_end=new Date('$end').getTime();";


                            //  $sql_query3 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM quiz_timer WHERE posted_job ='".$row['posted_job']."' ORDER  by id DESC LIMIT 1"));

  ?>

    var tim;
    var hour= $('#hrs').val();

    var min = $('#mins').val();
    var sec = 60;
    var f = new Date();
    function f1() {
        f2();
        document.getElementById("starttime").innerHTML = "Your started your Exam at " + f.getHours() + ":" + f.getMinutes();

    }
    function f2() {
        if (parseInt(sec) > 0) {
            sec = parseInt(sec) - 1;
            document.getElementById("showtime").innerHTML = "Your Left Time  is :"+hour+" hours:"+min+" Minutes :" + sec+" Seconds";
            tim = setTimeout("f2()", 1000);
        }
        else {
            if (parseInt(sec) == 0) {
                min = parseInt(min) - 1;
                if (parseInt(min) == 0) {
                    clearTimeout(tim);
                    location.href ="index.php";
                }
                else {
                    sec = 60;
                    document.getElementById("showtime").innerHTML = "Your Left Time  is :" + min + " Minutes ," + sec + " Seconds";
                    tim = setTimeout("f2()", 1000);
                }
            }

        }
    }
</script>

<script>
$('.timeago').timeago();
  $('[data-toggle="tooltip"]').tooltip();
</script>

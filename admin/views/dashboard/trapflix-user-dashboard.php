<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

  //dashboard queries
  //all  usersd
  $users_sql = mysqli_query($dbc,"SELECT * FROM users");
    $total_users = mysqli_num_rows($users_sql);


    $videos_sql = mysqli_query($dbc,"SELECT * FROM videos WHERE email ='".$_SESSION['email']."'");


      $total_videos = mysqli_num_rows($videos_sql);

      //Youtube videos
      $youtube_sql = mysqli_query($dbc,"SELECT * FROM videos WHERE youtube_vid  ='youtube' && email ='".$_SESSION['email']."'");


        $total_youtube = mysqli_num_rows($youtube_sql);

        // server videos
        $server_sql = mysqli_query($dbc,"SELECT * FROM videos WHERE youtube_vid  ='server' && email ='".$_SESSION['email']."'");


          $total_server = mysqli_num_rows($server_sql);
  // channel videos
  $total_channels = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM main_categories WHERE recorded_by ='".$_SESSION['name']."'"));

    $server_channels = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM main_categories WHERE approved ='yes' && recorded_by ='".$_SESSION['name']."'"));

      $youtube_channels = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM main_categories WHERE approved ='no' && recorded_by ='".$_SESSION['name']."'"));


  //pending approval stocks
  $pending_approval = mysqli_query($dbc,"SELECT * FROM stock_item WHERE status ='pending_approval'");
//approved stocks
  $approved = mysqli_query($dbc,"SELECT * FROM stock_item WHERE status ='approved'");



  $approved_stocks = mysqli_num_rows($approved);

  $pending_stocks = mysqli_num_rows($pending_approval);

  //all end Product

  //Total Deliveries
  $customer_delivery_sql = mysqli_query($dbc,"SELECT * FROM customer_end_delivery");
//approved delivery



    $get_remaining = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt FROM customer_end_delivery"));

    $get_remaining654 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(qtt) as qtt2 FROM end_product"));


       $get_remaining_pen = $get_remaining654['qtt2'] - $get_remaining['qtt'];

    $get_remaining_pen456 = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(stock_remaining) as qtt FROM customer_end_delivery GROUP BY end_product_ref DESC LIMIT 1"));


  //Pending approval delivery
    $pending_approval_delivery_sql = mysqli_query($dbc,"SELECT * FROM customer_end_delivery WHERE status ='pending_approval'");




    $total_delivery = mysqli_num_rows($customer_delivery_sql);


  $pending_delivery = mysqli_num_rows($pending_approval_delivery_sql );

  //Total Invoices

  $total_invoices_received = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM invoice_received"));


    $get_sum_invoices = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as total FROM invoice_received"));

    $get_sum_paid_invoices = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(debit) as debit FROM invoice_received_payment"));

    $difference_payment = $get_sum_invoices['total'] - $get_sum_paid_invoices['debit'];

    // Total Profits Computations
      $get_stock_cost = mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as tot1 FROM single_product"));

        $get_production_cost= mysqli_fetch_array(mysqli_query($dbc,"SELECT sum(total) as tot2 FROM customer_end_delivery"));


  // Profit Loss calculation
  $get_profit = $get_production_cost['tot2'] - $get_stock_cost['tot1'];

  if($get_profit > 0)
  {
  $get_details = 'Profit';
}
else
{
$get_details = 'Loss';
}






  $resources_sql = mysqli_query($dbc,"SELECT * FROM pm_resources WHERE activity_id IN
                                              (SELECT task_id FROM pm_activities WHERE milestone_id IN
                                              (SELECT id FROM pm_milestones WHERE project_id IN
                                              (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')))
                                              GROUP BY resource_name");

  $active_resources = mysqli_num_rows($resources_sql);




  //risks
  $risks_sql = mysqli_query($dbc,"SELECT * FROM pm_risks WHERE status='open'");
  $active_risks = mysqli_num_rows($risks_sql);

  //cricical risks
  $critical_risks_sql = mysqli_query($dbc,"SELECT risk_id FROM pm_risks_updates
                                                  WHERE  risk_id IN
                                                  (SELECT risk_id FROM pm_risks WHERE status='open')
                                                  && overall_score >=20
                                                  && changed='no' ");

  $critical_risks = mysqli_num_rows($critical_risks_sql);


  //pie chart for task status
  $task_status = mysqli_query($dbc,"SELECT DISTINCT end_product_ref, count(*) AS delivery FROM customer_end_delivery GROUP BY end_product_ref");
  while ($row_task_status = mysqli_fetch_array($task_status))
  {

    $item_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT product_name FROM end_product WHERE id ='".$row_task_status['end_product_ref']."'"));

    $count_tasks[] = $row_task_status['delivery'];
    $task_status_description[] = $item_name['product_name'];
  }
  $counted_tasks = json_encode($count_tasks);
  $task_status_descriptions = json_encode($task_status_description);

  //donut chart for stock request
  $resource_utilization_sql = mysqli_query($dbc,"SELECT email, count(*) AS videoz FROM videos WHERE email ='".$_SESSION['email']."' GROUP BY email");

  $data = array();
  while($row_resources = mysqli_fetch_array($resource_utilization_sql))
  {
      $user_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM users WHERE email ='".$row_resources['email']."' ORDER BY email ASC "));
    /*$data[] = array(
   'label' => $project_name['project_description'],
   'value' => $row_resources['no_of_projects']
   );
*/
   $d[] = $row_resources['videoz'];
   $e[] = $user_name['name'];

  }
  //$json_data = json_encode($data);  // convert to json array
  $dd = json_encode($d);
  $ee = json_encode($e);

//donut chart for out of stock
$resource_utilization_sql2 = mysqli_query($dbc,"SELECT * FROM single_product GROUP BY end_product_ref");

$data = array();
while($end_delivery3 = mysqli_fetch_array($resource_utilization_sql2))
{
  $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$end_delivery3['end_product_ref']."' ORDER BY id DESC LIMIT 1"));
$single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$end_delivery3['end_product_ref']."' ORDER BY id"));

                                                   //echo $single_product23['stock_order_level'];


                                $bal234 = $single_product['stock_remaining'] - $single_product23['stock_order_level'];

                                                //   $OrderL =  $order_level23 - $order_level2;
                                            //  echo $bal234;


          if($bal234 < 0)
            {

                    $single_product = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM single_product WHERE end_product_ref ='".$end_delivery3['end_product_ref']."' ORDER BY id DESC LIMIT 1"));
                    $single_product23 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM invoice_received WHERE reference_no ='".$end_delivery3['end_product_ref']."' ORDER BY id"));
                    $single_product234 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM stock_item WHERE reference_no ='".$end_delivery3['end_product_ref']."' ORDER BY id"));

                                                                 //echo $single_product23['stock_order_level'];
                                                          //   echo $single_product234['item_name'];

$bal234 = $single_product23['stock_order_level'] - $single_product['stock_remaining'];
  /*$data[] = array(
 'label' => $project_name['project_description'],
 'value' => $row_resources['no_of_projects']
 );
*/
 $d2[] = $bal234;
 $e2[] = $single_product234['item_name'];

}
}
//$json_data = json_encode($data);  // convert to json array
$dd2 = json_encode($d2);
$ee2 = json_encode($e2);

  //get event dates from pm_activites
  $a = mysqli_query($dbc,"SELECT * FROM pm_activities WHERE project_id IN
                                (SELECT project_id FROM pm_projects_update_status WHERE project_status='Active' && changed='no')
                                 ");
$d1 = array();
  while($row_tasks = mysqli_fetch_array($a))
        {
        $date0= date_create($row_tasks['start_date']);
        $date1= date_format($date0,"Y-m-d");


        $date00= date_create($row_tasks['end_date']);
        $date11= date_format($date00,"Y-m-d");


          $name[] = $row_tasks['activity_name'];
          $date[] = $date1;


        //  $d1[]['startDate'] = $date1;
        //  $d1[]['endDate'] = $date11;
        //  $d1[]['summary'] = $row_tasks['activity_name'];
        $d1[] =  array (
            'startDate' =>  $date1,
            'endDate' => $date11,
            'summary' => $row_tasks['activity_name']
          );

        }
                                                      //$json_data = json_encode($data);  // convert to json array
        $activity_name = json_encode($name);
        $start_date = json_encode($date);
        $end_date = json_encode($date11);

        $calendar =  json_encode($d1);
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page"><strong>TRAPFLIX USER Management Dashboard</strong></li>
     </ol>
</nav>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box" title="Total End Product Deliveries">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-video"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">  <strong><?php echo $total_videos;?></strong> Videos</span>

            <span class="info-box-number">

              <small>(<span class="text-success"><?php echo $total_server;?>  Server Videos</span>)</small>  </br>
              <small>(<span class="text-success"><?php echo $total_youtube;?>  Youtube Videos</span>)</small>
            </span>
            <small class="float-right text-primary"><a href="#" class="open-standard-videos" data-toggle="modal" data-target="#standard-user-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- Total Projects Modal -->
        <div class="modal fade" id="standard-user-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Video Type Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="video-type-details-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of total projects omdal -->


      </div>
      <!-- /.col -->

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3" title="Projects Payments for Active Projects">
          <span class="info-box-icon bg-success"><i class="fab fa-youtube"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">  <strong><?php echo $total_channels ;?></strong> Channels</span>

            <span class="info-box-number">

            <small>(<span class="text-success"><?php echo $server_channels ;?> Approved Channels</span>)</small> </br>
              <small>(<span class="text-success"><?php echo $youtube_channels ;?> Pending Approval </span>)</small>
            </span>

            <small class="float-right text-primary"><a href="#" class="open-user-channels-modal" data-toggle="modal" data-target="#total-channels-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- projects payments Modal -->
        <div class="modal fade" id="total-channels-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title"><?php echo $total_channels ;?> Channels Statistics</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="dashboard-channel-modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of projects payments omdal -->
      </div>
      <!-- /.col -->


      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3" title="Total Resources for Active Projects">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-dollar-sign"></i></span>

          <div class="info-box-content hvr-overline-from-center">
            <span class="info-box-text">  <strong>  Paid Subscription</span>

            <span class="info-box-number">

            <small>(<span class="text-success">$10,000</span>)</small> </br>
              <small>(<span class="text-success">$100,000 </span>)</small>
            </span>
            <small class="float-right text-primary"><a href="#" class="open-total-profits-modall" data-toggle="modal" data-target="#total-profit-modal">View Details</a></small>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->

        <!-- Total Project resources  Modal -->
        <div class="modal fade" id="total-profit-modal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header bg-light">
                <h5 class="modal-title">Paid subscriptions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="dashboard-total-profit-modal-body">
                Coming Soon
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <!-- End of total project resources modal -->
      </div>

      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">User Video Management</h5>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <p class="text-center">
                  <strong>Videos Uploaded</strong>
                </p>
                <div class="chart">
                  <!-- Resource Distribution Chart Canvas -->
                  <canvas id="resource-distribution-chart" width="477" height="300" style=" display: block;" class="chartjs-render-monitor"</canvas>
                </div>
                <!-- /.chart-responsive -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <p class="text-center">
                  <strong>Videos Uploaded Per User</strong><br/>(<small class="text-muted">Summary</small>)
                </p>
                <div class="table-responsive">

                  <table class="table table-hover table-striped table-bordered" id="dashboard-overdue-tasks-table">
                     <thead class="thead-light">
                       <tr>
                         <th scope="col">#</th>
                         <th scope="col">Username</th>
                         <th scope="col">Total videos</th>

                       </tr>
                     </thead>
                     <tbody>

                       <?php
                          $no = 1;


                          $sql234= mysqli_query($dbc,"SELECT email, count(*) AS vidz FROM videos WHERE email ='".$_SESSION['email']."'GROUP BY email");
                          while($videos_count = mysqli_fetch_array($sql234))
                          {
                            $user_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM users WHERE email = '".$videos_count['email']."' ORDER BY name DESC "));
                            ?>
                            <tr style="cursor: pointer;">
                              <td width="40px"><?php echo $no++ ;?>.

                              </td>

                              <td><?php  echo $user_name['name'];?></td>


                              <td><?php  echo $videos_count['vidz'];?></td>

                              </tr>
                              <?php
                            }
                          ?>
                     </tbody>
                   </table>
                </div>
              </div>
              <!-- /.col -->
            </div>


          </div>
          <!-- ./card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->


    <!-- end row calendar -->
  </div>
   <!-- /.container fluid -->
</section>
   <!-- /.section -->


<script type="application/javascript">

  /*  Morris.Donut({
      element: 'chart',  // div id
      data: <?php echo $json_data; ?>,
      xkey: 'label',
      ykeys: ['value'],
      labels: ['Value'],
      resize: true
  });
  */

</script>

<script>
/*
var ctx = document.getElementById('myChart');
var pieChartCanvas = new Chart(ctx, {
    type: 'doughnut',
    data: <?php echo $json_data;?>,

});
*/


// Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $('#resource-distribution-chart').get(0).getContext('2d');
  var pieData        = {
    labels: <?php echo $ee;?>,
    datasets: [
      {
        fill: false,
        data: <?php echo $dd;?>,
        //backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
      }
    ]
  }
  var pieOptions     = {
    responsive: false,
    legend: {
      display: true,
      position: 'bottom',
      labels: {
          fontColor: '#333',
          usePointStyle:true
      }
    },
    plugins: {

      colorschemes: {

        scheme: 'tableau.HueCircle19'

      }

    }
  }


  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  var pieChart = new Chart(pieChartCanvas, {
    type: 'doughnut',
    data: pieData,
    options: pieOptions
  })


  // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#resource-distribution-chart2').get(0).getContext('2d');
    var pieData        = {
      labels: <?php echo $ee2;?>,
      datasets: [
        {
          fill: false,
          data: <?php echo $dd2;?>,
          //backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
        }
      ]
    }
    var pieOptions     = {
      responsive: false,
      legend: {
        display: true,
        position: 'bottom',
        labels: {
            fontColor: '#333',
            usePointStyle:true
        }
      },
      plugins: {

        colorschemes: {

          scheme: 'tableau.HueCircle19'

        }

      }
    }


    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: pieData,
      options: pieOptions
    })


//START TASK STATUS CHART
var pieChartCanvas = $('#task-status-chart').get(0).getContext('2d');
var pieData        = {
  labels: <?php echo $task_status_descriptions;?>,
  datasets: [
    {
      fill: false,
      data: <?php echo $counted_tasks;?>,
      //backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
    }
  ]
}
var pieOptions     = {
  legend: {
    display: true,
    position: 'bottom',
    labels: {
        fontColor: '#333',
        usePointStyle:true
    }
  },
  plugins: {

    colorschemes: {

      scheme: 'brewer.DarkTwo8'

    }

  }
}
//Create pie or douhnut chart
// You can switch between pie and douhnut using the method below.
var pieChart = new Chart(pieChartCanvas, {
  type: 'pie',
  data: pieData,
  options: pieOptions
})


//END TASK STATUS CHART

  //task calendar
  $("#task-calendar").simpleCalendar({
    fixedStartDay: false,
    disableEmptyDetails: true,
    events:  <?php echo $calendar;?>,


});

$(document).on("click",'.btn-next, .btn-prev', function(e){
  e.preventDefault();
})

$("[data-toggle=popover]").popover();

</script>

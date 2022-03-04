<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../controllers/setup/connect.php");
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/


if(!isset($_POST['job_posting_id']))
{
  exit("Please select The Job Applied ");
}
$row1 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM applied_jobs WHERE job_posting_id ='".$_POST['job_posting_id']."'"));

$row4 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_posting WHERE id ='".$row1['job_posting_id']."'"));


$row3 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM assigned_test WHERE posted_job ='".$row4['job_title']."'"));


$row2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM answered_test WHERE reference_no ='".$row3['id']."'"));

$row5 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM answered_response_test WHERE reference_no ='".$row2['id']."'"));

//Job posted



$row7 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_test WHERE reference_no ='".$row3['reference_no']."'"));

$testLoop = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM application_status_details WHERE email ='".$_SESSION['email']."'"));

$row6 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_test WHERE reference_no ='".$row4['reference_no']."' DESC LIMIT 1"));


?>
<!DOCTYPE html>
<html class="no-js" lang="zxx">

<!-- Mirrored from utouchdesign.com/themes/envato/escort/profile-settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 11 Feb 2021 07:04:42 GMT -->
<head>

<?php
include("views/header.php");
?>


</head>
<body class="utf_skin_area">
<div class="page_preloaderr"></div>
<!-- ======================= Start Navigation ===================== -->
<nav class="navbar navbar-default navbar-mobile navbar-fixed white no-background bootsnav">
  <?php
  include("views/navigation.php");
  ?>

</nav>
<!-- ======================= End Navigation ===================== -->

<!-- ======================= Page Title ===================== -->
<div class="page-title">
  <div class="container">
    <div class="page-caption">
      <h2>Online Test Assessment</h2>
      <p><a href="#" title="Home">Home</a> <i class="ti-angle-double-right"></i> Testing Stage</p>
    </div>
  </div>
</div>
<!-- ======================= End Page Title ===================== -->


<section class="padd-top-80 padd-bot-80">
  <div class="container">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-success card-outline">


      <nav aria-label="breadcrumb">
           <ol class="breadcrumb">
         <li class="breadcrumb-item active" aria-current="page"> Review List By Admin</li>
           </ol>
      </nav>

      <div class="card-body table-responsive">

        <?php

        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM answered_response_test WHERE reference_no ='".$row2['id']."' ORDER BY id DESC limit 1 ");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

       <table class="table table-striped table-bordered table-hover"  id="invoice-received-table"  style="width:100%">

         <thead>
           <tr>
             <td>#</td>
              <td>Questions</td>
             <td>Applicant Response</td>
             <td>Reviewer Response</td>
             <td>Score</td>
             <td>Remarks</td>

          <!--   <td>Status</td> -->
           </tr>
         </thead>
         <?php
         $no = 1;
          $sql = mysqli_query($dbc,"SELECT * FROM answered_response_test WHERE reference_no ='".$row2['id']."' ORDER BY id DESC limit 1");
          while($row = mysqli_fetch_array($sql)){
          ?>
         <tr style="cursor: pointer;">
           <td width="50px"> <?php echo $no++;?>.

           </td>
           <td>
           <div class="card-body box-profile">
             <div class="float-left">

               <?php

                    $result = mysqli_query($dbc, "SELECT * FROM job_test WHERE reference_no ='".$row3['reference_no']."' ORDER BY id"  );
                    if(mysqli_num_rows($result))
                    {
                      while($project= mysqli_fetch_array($result))
                      {
                          ?>

                                                  <div class="row border-bottom mx-4">
                                                    <div class="col-lg-12 col-xs-12 form-group">
                                                        <label for="item_name"><?php echo $project['test_name']; ?></label>


                                                    </div>

                                                  </div>
                                  <?php

                      }
                    }
                    ?>

             </div>
             </td>


           <td>
             <b>
             <?php

                  $result = mysqli_query($dbc, "SELECT * FROM answered_test WHERE reference_no ='".$row3['id']."' ORDER BY id"  );
                  if(mysqli_num_rows($result))
                  {
                    while($profile_pic = mysqli_fetch_array($result))
                    {
                      ?>
                      <?php echo $profile_pic['answer_name']; ?>

                   <?php

                    }
                  }
                  ?>
</b>
           </td>

           <td><b> <?php echo $row['response_name'] ;?> </b>
           </td>
           <td><b> <?php echo $row['marks'] ;?> </b>
           </td>
           <td><b> <?php echo $row['remarks'] ;?> </b>
           </td>



         </tr>
         <?php
            }
          ?>

       </table>
      </div>
    </div>
  </div>
</div>
</div>
</section>
<!-- ================ End Profile Settings ======================= -->


<?php
}
else
{
      ?>
    <br/>
<div class="alert alert-success">
<strong><i class="fa fa-info-circle"></i> No Review Answer has been done</strong>
</div>

  <?php
}
?>

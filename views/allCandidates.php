<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../controllers/setup/connect.php");

if(!isset($_POST['id']))
{
  exit("Please select The Candidate");
}

      $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE id ='".$_POST['id']."'"));

        $job_posted = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_posting WHERE email = '".$_SESSION['email']."'"));

      //  $profile_pic = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE id ='".$_POST['id']."'"));
/*if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/

?>
<nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">
            <i class="far fa-user-plus"></i> <b><?php echo $profile_pic['fName'];?> <?php echo $profile_pic['lName'];?>  Data</b>
            <br/>
                       <button class="btn btn-link" style="float:right;"
                               data-toggle="modal" data-target="#add-status-modal">
                               <i class="fa fa-plus-circle">Click here to Send request to Apply for a job</i>
                       </button>
   </li>



           <!-- start add end product modal -->
           <div class="modal fade" id="add-status-modal" role="dialog">
           <div class="modal-dialog" role="document">
           <div class="modal-content">
           <div class="modal-header alert alert-success">

           <h5 class="modal-title">Sending the request For the candidate  <?php echo $profile_pic['fName'];?>

           <span class="font-weight-bold"></h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
           </button>
           </div>
           <div class="modal-body">
           <form id="send-application-request-form">

           <input type="hidden" name="add_request_application" value="add_request_application">

             <input type="hidden" name="Email" value="<?php echo $profile_pic['Email'];?>"  >
                <input type="hidden" name="fName" value="<?php echo $profile_pic['fName'];?>"  >

                  <input type="hidden" name="lName" value="<?php echo $profile_pic['lName'];?>"  >

             <input type="hidden" name="id" value="<?php echo $profile_pic['id'];?>"  >

              <input type="hidden" name="job_title" value="<?php echo $job_posted['job_title'];?>>"  >

              <input type="hidden" name="company_name" value ="<?php echo $job_posted['company_name'] ;?>" >
                <input type="hidden" name="comp_type" value ="<?php echo $job_posted['comp_type'] ;?>" >
                  <input type="hidden" name="expLength" value ="<?php echo $job_posted['expLength'] ;?>" >
                    <input type="hidden" name="emp_type" value ="<?php echo $job_posted['emp_type'] ;?>" >
                      <input type="hidden" name="job_location" value ="<?php echo $job_posted['job_location'] ;?>" >
                        <input type="hidden" name="country" value ="<?php echo $job_posted['country'] ;?>" >
                          <input type="hidden" name="deadline" value ="<?php echo $job_posted['deadline'] ;?>" >
                            <input type="hidden" name="job_description" value ="<?php echo $job_posted['job_description'] ;?>" >
                                <input type="hidden" name="responsibility" value ="<?php echo $job_posted['responsibility'] ;?>" >

           <!-- start of row -->

              <div class="row">

                <div class="col-lg-12 col-xs-12 form-group">
                <label><span class="required">*</span>Job Posting</label>
                <?php
                $result2 = mysqli_query($dbc, "SELECT * FROM job_posting ORDER BY id ASC");
                echo '
                <select name="job_title" data-tags="true" class="select2 form-control" data-placeholder="Select Job Posted" required>
                <option></option>';
                while($row = mysqli_fetch_array($result2)) {
                    echo '<option value="'.$row['job_title'].'">'.$row['job_title']."</option>";
                }
                echo '</select>';
                ?>
                </div>


                               <div class="col-lg-12 col-xs-12 form-group">
                                   <label for="item_name"><span class="required">*</span>Special Request </label>

                            <textarea name="special_info" id="special_info"  name="special_info"   class="form-control" required></textarea>

                               </div>


              </div>


              <div class="row text-center">
                  <button type="submit" class="btn btn-success btn-block btn_submit_total submitting">SUBMIT</button>
              </div>
            </form>
           </div>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
           </div>
           </div>
           </div>
           </div>


      </ol>
  </nav>
<!-- Profile Image -->
<div class="card card-success card-outline col-md-12 mr-12 ml-8">
  <div class="card-body box-profile">

    <div class="detail-wrapper">
      <div class="detail-wrapper-body">
        <div class="row">
    <div class="col-md-4 text-center user_profile_img mrg-bot-30"> <img src="assets/img/<?php echo $profile_pic['emp_photo']; ?>" class="img-circle width-100" alt=""/>
      <h4 class="meg-0"><?php echo $profile_pic['fName'];?> <?php echo $profile_pic['lName'];?> </h4>
      <span> <?php echo $profile_pic['Email'];?></span>
    </div>
    <div class="col-md-8 user_job_detail">
      <?php
      echo $profile_pic['currentPosition'];
          ?>

           </b> at
          <?php
             echo $profile_pic['companyName'];

          ?>
              <br/>
       Holds a
          </b>

          <?php
      echo $profile_pic['highestQualification'];
               ?>

               with
               <?php
      echo $profile_pic['experience'];
               ?>
               Experience
          </br>  </br>  </br>
                 <?php
                 //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


                 $sql_query1 =  mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$profile_pic['Email']."'");
                 $number = 1;
                 if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
                 {?>


                 <?php
                 $invoice1 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM all_evidence_document WHERE reference_no ='".$profile_pic['Email']."'"));
                  ?>



          <a href="views/documents/<?php echo $invoice1['cv'];?>" target="_blank">Download Resume



                </a><br/>

                 <?php
                 }
                 else
                 {
                   ?>
                 <br/>
                 <div class="alert alert-success">
                 <strong><i class="fa fa-info-circle"></i> No CV was attached</strong>
                 </div>

                 <?php
                 }
                 ?>


    </div>
  </div>
      </div>
    </div>



<hr style="border-top: 1px dashed #8c8b8b;">

<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Employement History</h4>
  </div>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");
    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM employment_history WHERE email ='".$profile_pic['Email']."'");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>

      <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM employment_history WHERE email ='".$profile_pic['Email']."' ORDER BY start_date DESC ");
      while($employment = mysqli_fetch_array($sql))
      {
      ?>

    <div class="edu-history info"> <i></i>
      <div class="detail-info">
        <h3><?php echo $employment['comp_name'];?></h3>
        <i><?php echo $employment['start_date'];?> - <?php echo $employment['end_date'];?></i> <span> <?php echo $employment['job_title'];?>, <i><?php echo $employment['job_level'];?></i></span>
        <p><?php echo $employment['job_responsibilities'];?></p>
      </div>
    </div>
    <?php
    }
    ?>
    <?php
    }
    else
    {
    ?>



    <div class="alert alert-success">
    <strong><i class="fa fa-info-circle"></i>No employment History was Added</strong>
    </div>

    <?php
    }
    ?>

  </div>
</div>

<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Education History</h4>
  </div>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");

    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM education_history WHERE email ='".$profile_pic['Email']."'");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>
      <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM education_history WHERE email ='".$profile_pic['Email']."' ORDER BY start_date DESC ");
      while($employment = mysqli_fetch_array($sql))
      {
      ?>

    <div class="edu-history info"> <i></i>
      <div class="detail-info">
        <h3><?php echo $employment['school_name'];?></h3>
        <i><?php echo $employment['start_date'];?> - <?php echo $employment['end_date'];?></i> <span> <?php echo $employment['qualification'];?>, <i><?php echo $employment['qualification_name'];?></i></span>
        <p><?php echo $employment['description'];?></p>
      </div>
    </div>
    <?php
    }
    ?>
    <?php
    }
    else
    {
    ?>



    <div class="alert alert-success">
    <strong><i class="fa fa-info-circle"></i>No education History was Added</strong>
    </div>

    <?php
    }
    ?>

  </div>
</div>

<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Certificate and Awards</h4>
  </div>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM awards WHERE email ='".$profile_pic['Email']."'");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>

      <?php
      $no = 1;
      $sql2= mysqli_query($dbc,"SELECT * FROM awards WHERE email ='".$profile_pic['Email']."' ORDER BY year_received DESC ");
      while($awards = mysqli_fetch_array($sql2))
      {
      ?>

    <div class="edu-history info"> <i></i>
      <div class="detail-info">
        <h3><?php echo $awards['institution'];?></h3>
        <i><?php echo $awards['year_received'];?></i> <span> <?php echo $awards['award_name'];?>, <i><?php echo $awards['type'];?></i></span>

      </div>
    </div>
    <?php
    }
    ?>
    <?php
    }
    else
    {
    ?>



    <div class="alert alert-success">
    <strong><i class="fa fa-info-circle"></i>No details of Certificates and Awards was added</strong>
    </div>

    <?php
    }
    ?>

  </div>
</div>





<hr style="border-top: 1px dashed #8c8b8b;">
<b>Job Skills</b>

<form id="add-job-skills-form">

<input type="hidden" name="add-job-skills-history" value="add-job-skills-history">
<input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>">
<div class="row">

<div class="col-md-8 col-xs-12 form-group">


              <?php

                $sql_query = mysqli_query($dbc,"SELECT * FROM selected_job_skills WHERE email ='".$profile_pic['Email']."' ");

                while($row = mysqli_fetch_array($sql_query))
                {
                  $sql_staff_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users WHERE EmpNo='".$row['staff_id']."'"));
                  ?>
                  <?php echo $row['skill_name'];?>,

                  <?php
                }

               ?>

          </div>
          </div>




</form>



<!-- start add end product modal -->
<div class="modal fade" id="add-awards-history-modal" role="dialog">
<div class="modal-dialog" role="document">
<div class="modal-content">

<div class="modal-header alert alert-success">

<h5 class="modal-title">Adding Certificates And Awards
<span class="font-weight-bold"></h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span>
</button>
</div>
<div class="modal-body">
<form id="add-awards-history-form">

<input type="hidden" name="add-awards-history" value="add-awards-history">
<input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>" >

<div class="row border-bottom mx-4">
<div class="col-lg-5 col-xs-12 form-group">
<label><span class="required">*</span>Institution</label>
  <input type="text" autocomplete="off" class="select2 form-control" name="institution">
</div>
<div class="col-lg-5 col-xs-12 form-group">
<label><span class="required">*</span>Award Name</label>
<?php
$result2 = mysqli_query($dbc, "SELECT * FROM award_type ORDER BY id ASC");
echo '
<select name="type" data-tags="true" class="select2 form-control" data-placeholder="Select Award Type" required>
<option></option>';
while($row = mysqli_fetch_array($result2)) {
    echo '<option value="'.$row['type'].'">'.$row['type']."</option>";
}
echo '</select>';
?>
</div>
<div class="col-lg-5 col-xs-12 form-group">
<label><span class="required">*</span>Award Name</label>
  <input type="text" autocomplete="off" class="select2 form-control" name="award_name">
</div>
<div class="col-lg-5 col-xs-12 form-group">
<label> <span class="required">*</span> Year Received</label>
<div class="input-group mb-2 mr-sm-2">
<div class="input-group-prepend">
  <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
</div>
<input type="text" class="form-control project_start_date" autocomplete="off" name="year_received" required>
</div>
</div>


</div>

      <!-- start row button -->
<div class="row">
  <div class="col-md-12 text-center">
      <button type="submit" class="btn btn-success btn-block font-weight-bold submitting">SUBMIT</button>
  </div>
</div>

</form>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

</div>
</div>

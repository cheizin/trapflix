
<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
if(!isset($_POST['email']))
{
  exit("No Selected Resume");
}

$row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_titles WHERE id ='".$_POST['email']."'"));

$row2 = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM job_test WHERE reference_no ='".$_POST['email']."'"));
?>
<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Employement History


</h4>
  </div>
  <button class="btn btn-link" style="float:right;"
data-toggle="modal" data-target="#add-employment-history-modal">
<i class="fa fa-plus-circle">Add Employment History</i>
</button>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");
    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM employment_history WHERE email ='".$_SESSION['email']."' ");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>

      <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM employment_history WHERE email ='".$_SESSION['email']."'  ORDER BY start_date DESC ");
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
    <button class="btn btn-link" style="float:right;"
    data-toggle="modal" data-target="#add-employment-history-modal">
    <i class="fa fa-plus-circle"></i> Add a short overview of your Employment History
    </button>
</div>
    <?php
    }
    ?>

  </div>

  <div class="modal fade" id="add-employment-history-modal" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
  <div class="modal-content">

  <div class="modal-header alert alert-success">

  <h5 class="modal-title">Adding Career History
  <span class="font-weight-bold"></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
  <form id="add-employment-history-form">

  <input type="hidden" name="add-employment-history" value="add-employment-history">
  <input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>" >

  <div class="row border-bottom mx-4">
  <div class="col-lg-3 col-xs-12 form-group">
  <label><span class="required">*</span>Employer Name</label>
    <input type="text" autocomplete="off" class="select2 form-control" name="comp_name">
  </div>
  <div class="col-lg-3 col-xs-12 form-group">
  <label><span class="required">*</span>Industry</label>
  <?php
  $result = mysqli_query($dbc, "SELECT * FROM industry ORDER BY industry_name ASC");
  echo '
  <select name="industry" data-tags="true" class="wide form-control" required>
  <option data-display="Select Industry"></option>';
  while($row = mysqli_fetch_array($result)) {
      echo '<option value="'.$row['industry_name'].'">'.$row['industry_name']."</option>";
  }
  echo '</select>';
  ?>
  </div>

  <div class="col-lg-3 col-xs-12 form-group">
    <label><span class="required">*</span>Job Title</label>
    <input type="text" autocomplete="off" class="select2 form-control" name="job_title">
  </div>
  <div class="col-lg-3 col-xs-12 form-group">
  <label><span class="required">*</span>Country</label>
    <input type="text" autocomplete="off" class="select2 form-control"  name="country">
  </div>

  </div>

  <div class="row border-bottom mx-4">



  <div class="col-lg-3 col-xs-12 form-group">
  <label><span class="required">*</span>Work Type</label>
  <?php
  $result = mysqli_query($dbc, "SELECT * FROM work_type ORDER BY work_type ASC");
  echo '
  <select name="work_type" data-tags="true" class="wide form-control" required>
  <option data-display="Select Work Type"></option>';
  while($row = mysqli_fetch_array($result)) {
  echo '<option value="'.$row['work_type'].'">'.$row['work_type']."</option>";
  }
  echo '</select>';
  ?>
  </div>



                <div class="col-lg-3 col-xs-12 form-group">
                    <label><span class="required">*</span>Monthly Salary</label>
                      <input type="text" autocomplete="off" class="select2 form-control"  name="monthly_salary">
                </div>

                <div class="col-lg-3 col-xs-12 form-group">
                    <label><span class="required">*</span>Job Level</label>
                    <?php
                    $result = mysqli_query($dbc, "SELECT * FROM job_level ORDER BY job_level ASC");
                    echo '
                    <select name="job_level" data-tags="true" class="wide form-control"  required>
                    <option data-display="Select Job Level"></option>';
                    while($row = mysqli_fetch_array($result)) {
                        echo '<option value="'.$row['job_level'].'">'.$row['job_level']."</option>";
                    }
                    echo '</select>';
                    ?>
                </div>

  </div>

  <div class="row border-bottom mx-4">

    <div class="col-lg-3 col-xs-12 form-group">
      <label> <span class="required">*</span> Start Date</label>
      <div class="input-group mb-2 mr-sm-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
        </div>
        <input type="text" class="form-control project_start_date" autocomplete="off" name="start_date" required>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12 form-group">
      <label> <span class="required">*</span> End Date</label>
      <div class="input-group mb-2 mr-sm-2">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
        </div>
        <input type="text" class="form-control project_end_date" autocomplete="off" name="end_date" required>
      </div>
    </div>
    <div class="col-lg-3 col-xs-12 form-group">
        <label><span class="required">*</span>Duration</label>


        <input type="hidden" class="form-control project-duration-in-days" name="duration" readonly required>
        <input type="text" class="form-control pull-right project-duration bg-grey" readonly required>
    </div>

  </div>

  <div class="row border-bottom mx-4">

  <div class="col-lg-12 col-xs-12 form-group">
  <label for="job_responsibilities"><span class="required">*</span>Job Responsibilities</label>

    <textarea name="job_responsibilities" class="form-control" required></textarea>
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
<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Education History
</h4>
  </div>

            <button class="btn btn-link" style="float:right;"
            data-toggle="modal" data-target="#add-education-history-modal">
            <i class="fa fa-plus-circle">Add Education History</i>
            </button>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");

    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM education_history WHERE email ='".$_SESSION['email']."' ");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>
      <?php
      $no = 1;
      $sql= mysqli_query($dbc,"SELECT * FROM education_history WHERE email ='".$_SESSION['email']."'  ORDER BY start_date DESC ");
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
      <button class="btn btn-link" style="float:right;"
      data-toggle="modal" data-target="#add-education-history-modal">
      <i class="fa fa-plus-circle"></i> Add a short overview of your Education History</p>
      </button>
    </div>

    <?php
    }
    ?>

  </div>


          <!-- start add end product modal -->
          <div class="modal fade" id="add-education-history-modal" role="dialog">
          <div class="modal-dialog" role="document">
          <div class="modal-content">

          <div class="modal-header alert alert-success">

          <h5 class="modal-title">Adding Education History
          <span class="font-weight-bold"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>
          <div class="modal-body">
          <form id="add-education-history-form">

          <input type="hidden" name="add-education-history" value="add-education-history">
          <input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>" >

          <div class="row border-bottom mx-4">
          <div class="col-lg-12 col-xs-12 form-group">
          <label><span class="required">*</span>Institution</label>
            <input type="text" autocomplete="off" class="select2 form-control" name="school_name">
          </div>
          <div class="col-lg-6 col-xs-12 form-group">
          <label><span class="required">*</span>Qualification</label>
          <?php
          $result2 = mysqli_query($dbc, "SELECT * FROM qualification ORDER BY id ASC");
          echo '
          <select name="qualification" data-tags="true" class="wide form-control"  required>
          <option data-display="Qualification"></option>';
          while($row = mysqli_fetch_array($result2)) {
              echo '<option value="'.$row['rank_name'].'">'.$row['rank_name']."</option>";
          }
          echo '</select>';
          ?>
          </div>


          <div class="col-lg-6 col-xs-12 form-group">
          <label><span class="required">*</span>Course Name</label>
            <input type="text" autocomplete="off" class="select2 form-control" name="qualification_name">
          </div>


          </div>


          <div class="row border-bottom mx-4">

            <div class="col-lg-5 col-xs-12 form-group">
              <label> <span class="required">*</span> Start Date</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                </div>
                <input type="text" class="form-control project_start_date" autocomplete="off" name="start_date" required>
              </div>
            </div>
            <div class="col-lg-5 col-xs-12 form-group">
              <label> <span class="required">*</span> End Date</label>
              <div class="input-group mb-2 mr-sm-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                </div>
                <input type="text" class="form-control project_end_date" autocomplete="off" name="end_date" required>
              </div>
            </div>
            <div class="col-lg-2 col-xs-12 form-group">
                <label>Duration</label>


                <input type="hidden" class="form-control project-duration-in-days" name="duration" readonly required>
                <input type="text" class="form-control pull-right project-duration bg-grey" readonly required>
            </div>

          </div>

          <div class="row border-bottom mx-4">

          <div class="col-lg-12 col-xs-12 form-group">
          <label for="description"><span class="required">*</span>Description</label>

            <textarea name="description" class="form-control" required></textarea>
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

<div class="detail-wrapper">
  <div class="detail-wrapper-header">
    <h4>Certificate and Awards
</h4>
  </div>

  <button class="btn btn-link" style="float:right;"
data-toggle="modal" data-target="#add-awards-history-modal">
<i class="fa fa-plus-circle">Add Certificate and Awards</i>
</button>
  <div class="detail-wrapper-body">
    <?php
    //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


    $sql_query1 =  mysqli_query($dbc,"SELECT * FROM awards WHERE email ='".$_SESSION['email']."' ");
    $number = 1;
    if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
    {?>

      <?php
      $no = 1;
      $sql2= mysqli_query($dbc,"SELECT * FROM awards ORDER BY year_received DESC ");
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
      <button class="btn btn-link" style="float:right;"
      data-toggle="modal" data-target="#add-awards-history-modal">
      <i class="fa fa-plus-circle"></i> Give overview of Certificates and Awards</p>
      </button>
    </div>

    <?php
    }
    ?>

  </div>


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
    <select name="type" data-tags="true" class="wide form-control" required>
    <option data-display="Select Award Type"></option>';
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
<hr style="border-top: 1px dashed #8c8b8b;">
    <!-- Add Skills -->
    <div class="detail-wrapper-header">
      <h4>Skills, Competencies and KPI


</h4>
    </div>
    <div class="row">
      <div class="col-md-6">

        <button class="btn btn-link" style="float:right;"
  data-toggle="modal" data-target="#add-skills-modal">
  <i class="fa fa-plus-circle">Add Skills</i>
  </button>

  <!-- start add end product modal -->
  <div class="modal fade" id="add-skills-modal" role="dialog">
  <div class="modal-dialog" role="document">
  <div class="modal-content">

  <div class="modal-header alert alert-success">

  <h5 class="modal-title">Adding Skills
  <span class="font-weight-bold"></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
    <form id="add-job-skills-form">

<input type="hidden" name="add-job-skills-history" value="add-job-skills-history">
<input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>">
<div class="row">


<div class="col-lg-12 col-xs-12 form-group">
<label><span class="required">*</span>Enter Skills</label>
<input type="text" class="form-control" name="skill_name" placeholder="skill_name">
</div>

          </div>




<div class="row">
<div class="col-md-12 text-center">
<button type="submit" class="btn btn-success btn-block font-weight-bold submitting">SUBMIT Job Skills</button>
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

        <?php
        //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM selected_job_skills WHERE email ='".$_SESSION['email']."' ");
        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>


            <table class="table table-striped table-bordered skills-table" style="width:100%">
        <thead>
        <tr>
        <td>#</td>
        <td>Job Skills</td>

        </tr>
        </thead>
        <?php
        $no = 1;
        $sql2= mysqli_query($dbc,"SELECT * FROM selected_job_skills WHERE email ='".$_SESSION['email']."' ");
        while($awards = mysqli_fetch_array($sql2))
        {
        ?>
        <tr style="cursor: pointer;">
        <td width="40px"><?php echo $no++ ;?>.

        </td>

        <td><?php echo $awards['skill_name'];?></td>


        </tr>
        <?php
        }
        ?>
        </table>

        <?php
        }
        else
        {
        ?>

        <button class="btn btn-link" style="float:right;">
     No Skills</p>
        </button>

        <?php
        }
        ?>
      </div>
      <div class="col-md-6">

        <button class="btn btn-link" style="float:right;"
  data-toggle="modal" data-target="#add-competency-modal">
  <i class="fa fa-plus-circle">Add competency</i>
  </button>

  <!-- start add end product modal -->
  <div class="modal fade" id="add-competency-modal" role="dialog">
  <div class="modal-dialog" role="document">
  <div class="modal-content">

  <div class="modal-header alert alert-success">

  <h5 class="modal-title">Adding competency
  <span class="font-weight-bold"></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
    <form id="add-competency-form">

<input type="hidden" name="add-competency-history" value="add-competency-history">
<input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>">
<div class="row">


<div class="col-lg-12 col-xs-12 form-group">
<label><span class="required">*</span>Enter Competency</label>
<input type="text" class="form-control" name="competency_name" placeholder="competency_name">
</div>

          </div>




<div class="row">
<div class="col-md-12 text-center">
<button type="submit" class="btn btn-success btn-block font-weight-bold submitting">SUBMIT Competency</button>
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

        <?php
        //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM selected_competencies WHERE email ='".$_SESSION['email']."'");
        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

       <table  class="table table-striped table-bordered skills-table" style="width:100%">
        <thead>
        <tr>
        <td>#</td>
        <td>Competencies</td>

        </tr>
        </thead>
        <?php
        $no = 1;
        $sql2= mysqli_query($dbc,"SELECT * FROM selected_competencies WHERE email ='".$_SESSION['email']."' ");
        while($awards = mysqli_fetch_array($sql2))
        {
        ?>
        <tr style="cursor: pointer;">
        <td width="40px"><?php echo $no++ ;?>.

        </td>

        <td><?php echo $awards['competency_name'];?></td>


        </tr>
        <?php
        }
        ?>
        </table>

        <?php
        }
        else
        {
        ?>

        <button class="btn btn-link" style="float:right;">
     No Competency</p>
        </button>

        <?php
        }
        ?>
      </div>
      <div class="col-md-6">

        <button class="btn btn-link" style="float:right;"
  data-toggle="modal" data-target="#add-KPI-modal">
  <i class="fa fa-plus-circle">Add KPI</i>
  </button>

  <!-- start add end product modal -->
  <div class="modal fade" id="add-KPI-modal" role="dialog">
  <div class="modal-dialog" role="document">
  <div class="modal-content">

  <div class="modal-header alert alert-success">

  <h5 class="modal-title">Adding KPI
  <span class="font-weight-bold"></h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
  <form id="add-KPI-form">

<input type="hidden" name="add-KPI-history" value="add-KPI-history">
<input type="hidden" name="email" value ="<?php echo $_SESSION['email'];?>">
<div class="row">


<div class="col-lg-12 col-xs-12 form-group">
<label><span class="required">*</span>Enter KPI</label>
<input type="text" class="form-control" name="kpi_name" placeholder="kpi_name">
</div>

          </div>




<div class="row">
<div class="col-md-12 text-center">
<button type="submit" class="btn btn-success btn-block font-weight-bold submitting">SUBMIT KPI</button>
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


        <?php
        //$sql_query1 =  mysqli_query($dbc,"SELECT * FROM about_me WHERE email ='".$_SESSION['email'].");


        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM selected_kpi WHERE email ='".$_SESSION['email']."'");
        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

<table  class="table table-striped table-bordered skills-table" style="width:100%">
        <thead>
        <tr>
        <td>#</td>
        <td>KPI</td>

        </tr>
        </thead>
        <?php
        $no = 1;
        $sql2= mysqli_query($dbc,"SELECT * FROM selected_kpi WHERE email ='".$_SESSION['email']."' ");
        while($awards = mysqli_fetch_array($sql2))
        {
        ?>
        <tr style="cursor: pointer;">
        <td width="40px"><?php echo $no++ ;?>.

        </td>

        <td><?php echo $awards['kpi_name'];?></td>


        </tr>
        <?php
        }
        ?>
        </table>

        <?php
        }
        else
        {
        ?>

        <button class="btn btn-link" style="float:right;">
     No JPI</p>
        </button>

        <?php
        }
        ?>
      </div>
    </div>

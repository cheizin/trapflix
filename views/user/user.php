<?php
session_start();
include("../../controllers/setup/connect.php");
if($_SERVER['REQUEST_METHOD'] == "POST")
{

    ?>

    <!-- ======================= Start Page Title ===================== -->
    <div class="page-title">
      <div class="container">
        <div class="page-caption">
          <h2>Create an Account</h2>
          <p><a href="#" title="Home">Home</a> <i class="ti-angle-double-right"></i> SignUp</p>
        </div>
      </div>
    </div>
    <!-- ======================= End Page Title ===================== -->

    <!-- ====================== Start Signup Form ============= -->
    <section class="padd-top-80 padd-bot-80">
      <div class="container">
        <div id="response-data" style="width:100%">

          <div class="row module-panel">
              <!-- /.col -->

              <div class="col-md-5 col-xs-12" data-toggle="modal" data-target="#add-job-seeker-modal">
                <div class="card text-center card-success card-outline">
                    <div class="card-header">
                    <b>  Job Seeker<img src="assets/img/project3.jpg" alt="" width="25" height="25"> </b>
                    </div>
                    <div class="card-body card-success">
                      <a href="#" class="btn btn-info btn-lg btn-block">Are you looking for your dream job?</br>
    Create a career profile</a>
                    </div>
                  </div>
               </div>
               <div class="col-md-5 col-xs-12 " data-toggle="modal" data-target="#add-recruiter-modal">
                 <div class="card text-center card-success card-outline">
                     <div class="card-header">
                     <b>  Employer<img src="assets/img/performance.png" alt="" width="25" height="25"> </b>
                     </div>
                     <div class="card-body card-success">
                       <a href="#" class="btn btn-info btn-lg btn-block">Are you looking for quality candidates?</br>
    Advertise and search</a>
                     </div>
                   </div>
                </div>

          </div>
          <!-- add job seeker modal -->
          <div class="modal fade" id="add-job-seeker-modal" onmouseenter="CallSmartWizard()">
          <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header alert alert-success">

                <h5 class="modal-title">Signup As a Job Seeker (All fields are mandatory)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="add-job-seeker-form" class="needs-validation" enctype="multipart/form-data">
                  <input type="hidden" value="add-job-seeker" name="add-job-seeker">
                    <input type="hidden" value="job-seeker" name="job-seeker">

                    <div id="smartwizard-add-job-seeker-form" style="height:400px;">
        <ul class="nav">
           <li>
               <a class="nav-link" href="#step-1">
                  Bio Data
               </a>
           </li>
           <li>
               <a class="nav-link" href="#step-2">
                  Contact
               </a>
           </li>
           <li>
               <a class="nav-link" href="#step-3">
                  Employment
               </a>
           </li>
           <li>
               <a class="nav-link" href="#step-4">
                  Logins
               </a>
           </li>
        </ul>


                  <div class="tab-content" style="min-height:300px;">
                     <div id="step-1" class="tab-pane" role="tabpanel">
                          <div class="row border-bottom mx-4">
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Surname</label>
                            <input type="text" autocomplete="off" class="select2 form-control" name="fName">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Other Names</label>
                            <input type="text" autocomplete="off" class="select2 form-control" name="lName">
                      </div>



                    <div class="col-lg-6 col-xs-12 form-group">
                      <label> <span class="required">*</span> Date Of Birth</label>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                        </div>
                        <input type="text" class="form-control project_start_date" autocomplete="off" name="dob" required>
                      </div>
                    </div>
                    <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span>Gender</label>
                    <?php
                    $result = mysqli_query($dbc, "SELECT * FROM gender ORDER BY gender_name ASC");
                    echo '
                    <select name="gender" data-tags="true" class="select2 form-control" data-placeholder="Select Gender" required>
                    <option></option>';
                    while($row = mysqli_fetch_array($result)) {
                        echo '<option value="'.$row['gender_name'].'">'.$row['gender_name']."</option>";
                    }
                    echo '</select>';
                    ?>
                    </div>
                    </div>
                        </div>
    <div id="step-2" class="tab-pane" role="tabpanel">
                      <div class="row border-bottom mx-4">
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Email</label>
                            <input type="email" autocomplete="off" class="select2 form-control" name="Email">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Location</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="location">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Contact</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="contact">
                      </div>

                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Nationality</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="nationality">
                      </div>

        </div>
    </div>
             <div id="step-3" class="tab-pane" role="tabpanel">
                      <div class="row border-bottom mx-4">
                        <div class="col-lg-6 col-xs-12 form-group">
                            <label><span class="required">*</span>Highest Qualification</label>
                              <input type="text" autocomplete="off" class="select2 form-control" name="highestQualification">
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group">
                            <label><span class="required">*</span>Current Position</label>
                              <input type="text" autocomplete="off" class="select2 form-control"  name="currentPosition">
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group">
                            <label><span class="required">*</span>Company Name</label>
                              <input type="text" autocomplete="off" class="select2 form-control"  name="companyName">
                        </div>

                        <div class="col-lg-6 col-xs-12 form-group">
                            <label><span class="required">*</span>Experience</label>
                              <input type="text" autocomplete="off" class="select2 form-control"  name="experience">
                        </div>


          </div>

            </div>
    <div id="step-4" class="tab-pane" role="tabpanel">
    <div class="row border-bottom mx-4">

    <div class="col-lg-6 col-xs-12 form-group">
    <label for="password">
    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
    title="Your Password associated to your Windows account" id=""></i> <span class="required">*</span>Enter Password</label>
    <div class="input-group add-on">
    <input type="password" name="password" id="password" maxlength="40" class="form-control pwd"  required placeholder="input your password">

    </div>
    <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
    </div>
    <div class="col-lg-6 col-xs-12 form-group">
    <label for="password">
    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
    title="Your Password associated to your Windows account" id="password_help"></i> <span class="required">*</span>Confirm Password</label>
    <div class="input-group add-on">
    <input type="password" id="confirm" name="confirm" maxlength="40" class="form-control pwd"  required placeholder="Confirm Your password">

    </div>
    <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
    </div>

      </div>
    </div>

    </div>
    </div>
    <!-- start row button -->
    <div class="row submit-project-btn d-none">
    <div class="col-md-12 text-center">
    <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">SUBMIT</button>
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
          <!-- end of add job seeker modal -->


          <!-- add recruiter modal -->

          <div class="modal fade" id="add-recruiter-modal" onmouseenter="CallSmartWizard2()">
          <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header alert alert-success">

                <h5 class="modal-title">Signup As a Recruiter or Employment Agency (All fields are mandatory)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="add-recruiter-form" class="needs-validation" enctype="multipart/form-data">
                  <input type="hidden" value="add-recruiter" name="add-recruiter">
                    <input type="hidden" value="recruiter" name="recruiter">

                    <div id="smartwizard-add-recruiter-form" style="height:400px;">
            <ul class="nav">
            <li>
               <a class="nav-link" href="#step-1">
                  Company Data
               </a>
            </li>
            <li>
               <a class="nav-link" href="#step-2">
                  Contact
               </a>
            </li>
            <li>
               <a class="nav-link" href="#step-3">
                Structure
               </a>
            </li>
            <li>
               <a class="nav-link" href="#step-4">
                  Logins
               </a>
            </li>
            </ul>


                  <div class="tab-content" style="min-height:300px;">
                     <div id="step-1" class="tab-pane" role="tabpanel">
                          <div class="row border-bottom mx-4">
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Company Name</label>
                            <input type="text" autocomplete="off" class="select2 form-control" name="cName">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                      <label><span class="required">*</span>Industry</label>
                      <?php
                      $result2 = mysqli_query($dbc, "SELECT * FROM industry ORDER BY id ASC");
                      echo '
                      <select name="industry_name" data-tags="true" class="select2 form-control" data-placeholder="Select Industry" required>
                      <option></option>';
                      while($row = mysqli_fetch_array($result2)) {
                          echo '<option value="'.$row['industry_name'].'">'.$row['industry_name']."</option>";
                      }
                      echo '</select>';
                      ?>
                      </div>

                    <div class="col-lg-6 col-xs-12 form-group">
                      <label> <span class="required">*</span> Date Of Registration</label>
                      <div class="input-group mb-2 mr-sm-2">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fal fa-calendar-day"></i></div>
                        </div>
                        <input type="text" class="form-control project_start_date" autocomplete="off" name="dob" required>
                      </div>
                    </div>


                    </div>
                  </div>
    <div id="step-2" class="tab-pane" role="tabpanel">

                      <div class="row border-bottom mx-4">
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Email</label>
                            <input type="email" autocomplete="off" class="select2 form-control" name="Email">
                      </div>

                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Contact</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="contact">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Country</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="nationality">
                      </div>
                      <div class="col-lg-6 col-xs-12 form-group">
                          <label><span class="required">*</span>Location</label>
                            <input type="text" autocomplete="off" class="select2 form-control"  name="location">
                      </div>




        </div>

      </div>
                   <div id="step-3" class="tab-pane" role="tabpanel">
                      <div class="row border-bottom mx-4">
                        <div class="col-lg-6 col-xs-12 form-group">
                        <label><span class="required">*</span>No Of Employees</label>
                        <?php
                        $result2 = mysqli_query($dbc, "SELECT * FROM employee_no ORDER BY id ASC");
                        echo '
                        <select name="emp_no" data-tags="true" class="select2 form-control" data-placeholder="Select No oF Employees" required>
                        <option></option>';
                        while($row = mysqli_fetch_array($result2)) {
                            echo '<option value="'.$row['emp_no'].'">'.$row['emp_no']."</option>";
                        }
                        echo '</select>';
                        ?>
                        </div>

                        <div class="col-lg-6 col-xs-12 form-group">
                        <label><span class="required">*</span>Type Of Employer</label>
                        <?php
                        $result2 = mysqli_query($dbc, "SELECT * FROM company_type ORDER BY id ASC");
                        echo '
                        <select name="emp_type" data-tags="true" class="select2 form-control" data-placeholder="Select Employer Type" required>
                        <option></option>';
                        while($row = mysqli_fetch_array($result2)) {
                            echo '<option value="'.$row['com_type'].'">'.$row['com_type']."</option>";
                        }
                        echo '</select>';
                        ?>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group">
                        <label><span class="required">*</span>Where did you hear about us</label>
                        <?php
                        $result2 = mysqli_query($dbc, "SELECT * FROM hear_about_us ORDER BY id ASC");
                        echo '
                        <select name="about_us_name" data-tags="true" class="select2 form-control" data-placeholder="Select Industry" required>
                        <option></option>';
                        while($row = mysqli_fetch_array($result2)) {
                            echo '<option value="'.$row['about_us_name'].'">'.$row['about_us_name']."</option>";
                        }
                        echo '</select>';
                        ?>
                        </div>
                        <div class="col-lg-6 col-xs-12 form-group">
                            <label><span class="required">*</span>website URL</label>
                              <input type="text" autocomplete="off" class="select2 form-control"  name="web_url" placeholder="(start with https://)">
                        </div>



          </div>

                          </div>
          <div id="step-4" class="tab-pane" role="tabpanel">

    <div class="row border-bottom mx-4">

    <div class="col-lg-6 col-xs-12 form-group">
    <label for="password">Enter Password
    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
    title="Your Password associated to your Windows account" id=""></i></label>
    <div class="input-group add-on">
    <input type="password" name="password" id="password" maxlength="40" class="form-control pwd"  required placeholder="input your password">

    </div>
    <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
    </div>
    <div class="col-lg-6 col-xs-12 form-group">
    <label for="password">Confirm Password
    <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="right"
    title="Your Password associated to your Windows account" id="password_help"></i></label>
    <div class="input-group add-on">
    <input type="password" id="confirm" name="confirm" maxlength="40" class="form-control pwd"  required placeholder="Confirm Your password">

    </div>
    <span class="text-info invisible" id="caps-lock">CAPS LOCK IS ON!</span>
    </div>

    </div>
    </div>

    </div>
    </div>
    <!-- start row button -->
    <div class="row submit-project-btn d-none">
    <div class="col-md-12 text-center">
    <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">SUBMIT</button>
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
          <!-- end of recruiter modal -->

        </div>
      </div>
    </section>
    <!-- ====================== End Signup Form ============= -->



    <?php

}
else
{
echo "form not submitted";
}


?>

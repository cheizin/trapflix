<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Admin Portal : Period Management

         [<span class="text text-primary">Current : <?php echo $current_quarter_and_year['period']. " ". $current_quarter_and_year['quarter'] ;?></span>
         <span class="text text-secondary">Previous : <?php echo $last_quarter_and_year['period']. " ". $last_quarter_and_year['quarter'] ;?></span>
         ]
       </li>
     </ol>
</nav>


<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light"><i class="fad fa-calendar-check"></i> Set Current Quarter & Period</div>
            <div class="card-body">
                <form id="add-current-period-form">
                  <div class="row">
                            <div class="col-lg-6 col-xs-12 form-group"><br/>
                                    <label for="current-period"><span class="required">*</span>Current Period</label>
                                     <select name="current-period" class="select2 form-control" required>
                                       <option value="">Please Select Current Period</option>
                                       <option value="2017-2018">2017-2018</option>
                                       <option value="2018-2019">2018-2019</option>
                                       <option value="2019-2020">2019-2020</option>
                                       <option value="2020-2021">2020-2021</option>
                                       <option value="2021-2022">2021-2022</option>
                                     </select>
                            </div>
                            <div class="col-lg-6 col-xs-12 form-group"><br/>
                                       <label for="current-quarter"><span class="required">*</span>Current Quarter</label>
                                       <select class="select2 form-control" name="current-quarter" required>
                                           <option value="">Please Select Current Quarter</option>
                                           <option value="July - September (Quarter 1)">July - September (Quarter 1)</option>
                                           <option value="October - December (Quarter 2)">October - December (Quarter 2)</option>
                                           <option value="January - March (Quarter 3)">January - March (Quarter 3)</option>
                                           <option value="April - June (Quarter 4)">April - June (Quarter 4)</option>
                                       </select>
                               </div>
                        <input type="hidden" name="specific_period" value="<?php ;?>">
                  </div><br/>
                     <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                            </div>
                      </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light"><i class="fad fa-calendar-times"></i> Set Deadline for Quarterly Update Submission</div>
            <div class="card-body">
            <form id="set-deadline-form">
                <?php $deadline_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM deadline ORDER BY id DESC LIMIT 1"));?>
                <div id="set-deadline-notification"></div>
                 </br>
                <div class="col-lg-12 col-xs-12 form-group">
                    <label for="deadline"> <span class="required">*</span>Date</label>
                    <div class="input-append date inner-addon left-addon" id="datepicker" data-date="" data-date-viewmode="years" data-date-minviewmode="days">
                  				<input class="span2 form-control deadline"  type="text" name="deadline" readonly="" style="background: white;" value="<?php echo $deadline_row['deadline'] ;?>">
                  				<span class="add-on"></span>
                  	 </div>

                </div>
                <br/>

                <!-- start row button -->
                <div class="row mt-4">
                      <div class="col-md-12 text-center">
                          <button type="submit" class="btn btn-primary btn-block" id="set-deadline-btn">SUBMIT</button>
                      </div>
                </div>

                <!-- end row button -->
            </form>
            </div>
        </div>
    </div>

</div>

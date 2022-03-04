<div class="row">
  <div class="card card-body">
        <div class="row">
          <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                  <?php
                  //select departmental risks
                  $departmental_risks_sql = mysqli_query($dbc,"SELECT * FROM risk_management WHERE department_code='".$_SESSION['department_code']."'
                                                                && changed='no' && risk_opportunity='risk'
                                                                && risk_reference IN
                                                                (SELECT reference_no FROM update_risk_status WHERE changed='no'
                                                                 && period_from='".$current_quarter_and_year['period']."'
                                                                 && quarter='".$current_quarter_and_year['quarter']."'
                                                                 && dep_code='".$_SESSION['department_code']."'
                                                                 && risk_status='open'
                                                                 )
                                                                 ");
                  $total_dep_risks = mysqli_num_rows($departmental_risks_sql);
                   ?>
                    <h3><?php echo $total_dep_risks;?></h3>
                    <p>Risks</p>
                </div>
                <div class="icon">
                        <i class="fa fa-exclamation-triangle fa-lg"></i>
                 </div>
                 <a href="#" class="small-box-footer risk-management-module">
                     <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                 </a>
              </div>
           </div>

           <div class="col-lg-4 col-xs-6">
                     <!-- small box -->
             <div class="small-box bg-blue">
                 <div class="inner">
                   <?php
                   //select departmental opportunities
                   $departmental_opportunities_sql = mysqli_query($dbc,"SELECT * FROM risk_management WHERE
                                                                  department_code='".$_SESSION['department_code']."'
                                                                  && changed='no' && risk_opportunity='opportunity'
                                                                  && risk_reference IN
                                                                  (SELECT reference_no FROM update_risk_status WHERE changed='no'
                                                                   && period_from='".$current_quarter_and_year['period']."'
                                                                   && quarter='".$current_quarter_and_year['quarter']."'
                                                                   && dep_code='".$_SESSION['department_code']."'
                                                                   && risk_status='open'
                                                                   )"
                                                                 );
                   $total_dep_opportunities = mysqli_num_rows($departmental_opportunities_sql);
                    ?>
                     <h3><?php echo $total_dep_opportunities;?></h3>
                     <p>Opportunities</p>
                 </div>
                 <div class="icon">
                         <i class="fa fa-lightbulb-o fa-lg"></i>
                  </div>
                  <a href="#" class="small-box-footer risk-management-module">
                      <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                  </a>
               </div>
            </div>
            <?php
            //total number of risks
            $total_no = mysqli_num_rows(mysqli_query($dbc,"SELECT risk_reference FROM risk_management
                                                             WHERE changed='no' && department_code='".$_SESSION['department_code']."'
                                                             && risk_reference IN
                                                             (SELECT reference_no FROM update_risk_status WHERE changed='no'
                                                              && period_from='".$current_quarter_and_year['period']."'
                                                              && quarter='".$current_quarter_and_year['quarter']."'
                                                              && dep_code='".$_SESSION['department_code']."'
                                                              && risk_status='open'
                                                              )
                                                             "));
            //updated risks for current quarter
            $number_of_updated_risks =mysqli_num_rows(mysqli_query($dbc,"SELECT reference_no FROM update_risk_status
                                                WHERE period_from='".$current_quarter_and_year['period']."'
                                                && quarter = '".$current_quarter_and_year['quarter']."'
                                                && dep_code='".$_SESSION['department_code']."'
                                                && risk_status='open'
                                                && changed='no'"));
            //updated and approved risks for current quarter
            $number_of_updated_pending_approved_risks =mysqli_num_rows(mysqli_query($dbc,"SELECT reference_no FROM update_risk_status
                                                          WHERE period_from='".$current_quarter_and_year['period']."'
                                                          && quarter = '".$current_quarter_and_year['quarter']."'
                                                          && dep_code='".$_SESSION['department_code']."'
                                                          && status='pending approval'
                                                          && risk_status='open'
                                                          && changed='no'"));

              if($number_of_updated_pending_approved_risks == 0)
              {
                $pending_approval = "Fully Approved";
              }
              else
              {
                $pending_approval = $number_of_updated_pending_approved_risks . " Pending Approval";
              }
            //number of risks pending update
            $pending_update = $total_no - $number_of_updated_risks;
            //check if not all risks have been updated
            if($number_of_updated_risks != $total_no)
            {
              ?>
              <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-yellow">
                      <div class="inner">

                          <h3><?php echo $pending_update;?></h3>
                          <p>Pending Update</p>
                          <small><?php echo $pending_approval;?></small>
                      </div>
                    <div class="icon">
                            <i class="fa fa-info-circle fa-lg"></i>
                     </div>
                     <a href="#" class="small-box-footer risk-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>
              <?php
            }
            //check if all risks have been updated

            if($number_of_updated_risks == $total_no)
            {
              ?>
              <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-green">
                      <div class="inner">

                          <h3><?php echo $total_no;?></h3>
                          <p>Fully Updated</p>
                          <small><?php echo $pending_approval;?></small>
                      </div>
                    <div class="icon">
                            <i class="fa fa-check fa-lg"></i>
                     </div>
                     <a href="#" class="small-box-footer risk-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>
              <?php
            }
             ?>

        </div>
        <hr style="border: 1px solid black"/>
        <!-- for activities -->
        <?php
        //total number of activities
        $total_no = mysqli_num_rows(mysqli_query($dbc,"SELECT activity_id FROM perfomance_management
                                                     WHERE department_id='".$_SESSION['department_code']."'
                                                     &&
                                                     activity_status='open'
                                                     &&
                                                     activity_id IN
                                                     (SELECT activity_id FROM activity_strategic_outcomes
                                                     WHERE changed='no' &&
                                                     year_id='".$current_quarter_and_year['period']."')
                                                     "));
        //updated risks for current quarter
        $number_of_updated_activites =mysqli_num_rows(mysqli_query($dbc,"SELECT activity_id FROM performance_update
                                                         WHERE year_id='".$current_quarter_and_year['period']."'
                                                         && quarter_id = '".$current_quarter_and_year['quarter']."'
                                                         && activity_id IN (SELECT activity_id FROM perfomance_management
                                                                                WHERE changed='no' &&
                                                                                 department_id='".$_SESSION['department_code']."'
                                                                            )
                                                         && changed='no'"));

        //activities pending update
        $pending_update = $total_no - $number_of_updated_activites;
        ?>
        <div class="row">
          <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3><?php echo $total_no;?></h3>
                    <p>Activities</p>
                </div>
                <div class="icon">
                        <i class="fa fa-tasks fa-lg"></i>
                 </div>
                 <a href="#" class="small-box-footer performance-management-module">
                     <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                 </a>
              </div>
           </div>

            <?php
            //check if not all risks have been updated
            if($number_of_updated_activites != $total_no)
            {
              ?>
              <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-yellow">
                      <div class="inner">

                          <h3><?php echo $pending_update;?></h3>
                          <p>Pending Update</p>
                      </div>
                    <div class="icon">
                            <i class="fa fa-info-circle fa-lg"></i>
                     </div>
                     <a href="#" class="small-box-footer performance-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>
              <?php
            }
            //check if all risks have been updated

            if($number_of_updated_activites == $total_no)
            {
              ?>
              <div class="col-lg-4 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-green">
                      <div class="inner">

                          <h3><?php echo $total_no;?></h3>
                          <p>Fully Updated</p>
                      </div>
                    <div class="icon">
                            <i class="fa fa-check fa-lg"></i>
                     </div>
                      <a href="#" class="small-box-footer performance-management-module">
                          <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                      </a>
                  </div>
               </div>
              <?php
            }
             ?>

        </div>


  </div>
</div>

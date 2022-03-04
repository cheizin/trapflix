<?php
if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "director")
{
  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                                        prior_overall_score = current_overall_score
                                        && period_from='".$current_quarter_and_year['period']."'
                                        && quarter = '".$current_quarter_and_year['quarter']."'
                                        && changed='no'
                                        && risk_status='open'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Static Risks</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table  width="100%" class="table table-hover" id="analysis-static-risks-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Department</td>
                    <td>Risk Description</td>
                    <td>Prior Overall Score</td>
                    <td>Current Overall Score</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td><?php echo $row['dep_code'];?></td>
                  <td>
                    <a href="#" onclick="ViewRisk('<?php echo $row['reference_no'];?>','<?php echo $row['dep_code'];?>');"
                      class="text-wrap risk-management-module"> <?php echo $row['risk_description'];?>
                    </a>
                  </td>
                  <td><?php echo $row['prior_overall_score'];?></td>
                  <td><?php echo $row['current_overall_score'];?></td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Department</th>
                      <th>Risk Description</th>
                      <th>Prior Overall Score</th>
                      <th>Current Overall Score</th>
                  </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    <?php
  }
  else
  {
    ?>
    <br/>
    <div class="alert alert-warning">
      <strong><i class="fa fa-info-circle"></i> No Static Risks Found</strong>
    </div>
    <?php
  }
}
else
{
  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                                        prior_overall_score = current_overall_score
                                        && dep_code='".$_SESSION['department_code']."'
                                        && period_from='".$current_quarter_and_year['period']."'
                                        && quarter = '".$current_quarter_and_year['quarter']."'
                                        && changed='no'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Static Risks</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table class="table table-hover" id="analysis-static-risks-table" width="100%">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Risk Description</td>
                    <td>Prior Overall Score</td>
                    <td>Current Overall Score</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td>
                    <a href="#" onclick="ViewRisk('<?php echo $row['reference_no'];?>','<?php echo $row['dep_code'];?>');"
                      class="text-wrap risk-management-module"> <?php echo $row['risk_description'];?>
                    </a>
                  </td>
                  <td><?php echo $row['prior_overall_score'];?></td>
                  <td><?php echo $row['current_overall_score'];?></td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Risk Description</th>
                      <th>Prior Overall Score</th>
                      <th>Current Overall Score</th>
                  </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    <?php
  }
  else
  {
    ?>
    <br/>
    <div class="alert alert-warning">
      <strong><i class="fa fa-info-circle"></i> No Static Risks Found</strong>
    </div>
    <?php
  }
}



 ?>

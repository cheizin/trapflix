<?php
if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "director")
{
  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                                        period_from='".$current_quarter_and_year['period']."'
                                        && quarter = '".$current_quarter_and_year['quarter']."'
                                        &&
                                        reference_no NOT IN
                                        (SELECT reference_no FROM update_risk_status WHERE
                                          period_from='".$current_quarter_and_year['period']."'
                                          &&
                                          quarter !='".$current_quarter_and_year['quarter']."'
                                          && changed='no')
                                        && changed='no'
                                        && risk_status='open'
                                        ORDER BY dep_code ASC");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">New Risks/Opportunities</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table width="100%" class="table table-hover" id="analysis-new-risks-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Department</td>
                    <td>Description</td>
                    <td>Risk/Opportunity</td>
                    <td>Rating</td>
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
                  <td><?php echo ucfirst($row['risk_opportunity']);?></td>
                  <td><?php echo $row['current_overall_score'];?></td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Department</th>
                      <th>Description</th>
                      <th>Risk/Opportunity</th>
                      <th>Current Rating Score</th>
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
      <strong><i class="fa fa-info-circle"></i> No New Risks/Opportunities Found</strong>
    </div>
    <?php
  }
}
else
{
  $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE
                                        period_from='".$current_quarter_and_year['period']."'
                                        && quarter = '".$current_quarter_and_year['quarter']."'
                                        &&
                                        reference_no NOT IN
                                        (SELECT reference_no FROM update_risk_status WHERE
                                          period_from='".$current_quarter_and_year['period']."'
                                          && quarter !='".$current_quarter_and_year['quarter']."'
                                          && changed='no')
                                        && changed='no'
                                        && risk_status='open'
                                        && dep_code='".$_SESSION['department_code']."'
                                        ");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">New Risks/Opportunities</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table width="100%" class="table table-hover" id="analysis-new-risks-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Description</td>
                    <td>Current Rating</td>
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
                  <td><?php echo $row['current_overall_score'];?></td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Risk Description</th>
                      <th>Current Rating</th>
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
      <strong><i class="fa fa-info-circle"></i> No New Risks/Opportunities Found</strong>
    </div>
    <?php
  }
}

 ?>

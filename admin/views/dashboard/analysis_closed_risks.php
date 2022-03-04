<?php
if($_SESSION['access_level'] == "superuser" || $_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "director")
{
?>
      <?php
      $sql = mysqli_query($dbc,"SELECT * FROM  risk_management WHERE risk_status='closed' && changed='no'");
      if(mysqli_num_rows($sql) > 0)
      {
        $number = 1;
        ?>
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Closed Risks</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
              <table width="100%" class="table table-hover " id="analysis-closed-risks-table">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Department</td>
                    <td>Risk Description</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td><?php echo $row['department_code']; ?></td>
                  <td>
                    <a href="#" onclick="ViewRisk('<?php echo $row['risk_reference'];?>','<?php echo $row['department_code'];?>');"
                      class="text-wrap risk-management-module"> <?php echo $row['risk_description'];?>
                    </a>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Department</th>
                      <th>Risk Description</th>
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
              <strong><i class="fa fa-info-circle"></i> No Closed Risks Found</strong>
            </div>
            <?php
          }
          //closed risks with no updates
           ?>
    <?php

}
else
{
  $sql = mysqli_query($dbc,"SELECT * FROM risk_management WHERE
                                        department_code='".$_SESSION['department_code']."'
                                        && risk_status='closed'
                                        && changed='no'");
  if(mysqli_num_rows($sql) > 0)
  {
    $number = 1;
    ?>
      <div class="col-xs-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Closed Risks</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive no-padding">
              <table class="table table-hover" id="analysis-closed-risks-table" width="100%">
                <thead>
                  <tr>
                    <td>NO</td>
                    <td>Risk Description</td>
                  </tr>
                </thead>
              <?php

              while($row = mysqli_fetch_array($sql))
              {
                ?>
                <tr style="cursor: pointer;">
                  <td><?php echo $number++;?></td>
                  <td>
                    <a href="#" onclick="ViewRisk('<?php echo $row['risk_reference'];?>','<?php echo $row['department_code'];?>');"
                      class="text-wrap risk-management-module"> <?php echo $row['risk_description'];?>
                    </a>
                  </td>
                </tr>
                <?php
              }
              ?>
              <tfoot>
                  <tr>
                      <th>NO</th>
                      <th>Risk Description</th>
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
      <strong><i class="fa fa-info-circle"></i> No Closed Risks Found</strong>
    </div>
    <?php
  }
}



 ?>

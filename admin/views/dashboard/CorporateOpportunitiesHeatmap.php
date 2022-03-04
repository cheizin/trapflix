<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header bg-light">Top 20 Corporate Opportunities</div>
      <div class="card-body">
        <table class="table table-bordered">
           <?php
              $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE risk_opportunity='opportunity' && changed='no'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' ORDER BY current_overall_score DESC LIMIT 20");
              $sql_query_risk_position = mysqli_fetch_array($sql_query);
              ?>
           <tbody>
              <tr>
                 <td rowspan="5" class="impact_rotate">Impact</td>
                 <td>Transformational <small class="text-primary">5</small></td>
                 <td class="medium" style="background-color: #59b4e0;"  title="OVERALL SCORE: 5">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='5' && current_likelihood_score='1' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='5' && current_likelihood_score='2' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='5' && current_likelihood_score='3' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='5' && current_likelihood_score='4' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 25">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='5' && current_likelihood_score='5' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
              </tr>
              <tr>
                 <td>Major <small class="text-primary">4</small></td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='4' && current_likelihood_score='1' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='4' && current_likelihood_score='2' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            ?>
                            <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                                onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                                <?php echo $risk_position['reference_no'];?>
                            </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='4' && current_likelihood_score='3' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 16">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='4' && current_likelihood_score='4' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="very_high" style="background-color: #0272a6;" title="OVERALL SCORE: 20">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='4' && current_likelihood_score='5' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
              </tr>
              <tr>
                 <td>Moderate <small class="text-primary">3</small></td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='3' && current_likelihood_score='1' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='3' && current_likelihood_score='2' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 9">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='3' && current_likelihood_score='3' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 12">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='3' && current_likelihood_score='4' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 15">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='3' && current_likelihood_score='5' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
              </tr>
              <tr>
                 <td>Minor <small class="text-primary">2</small></td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='2' && current_likelihood_score='1' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='2' && current_likelihood_score='2' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 6">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='2' && current_likelihood_score='3' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 8">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='2' && current_likelihood_score='4' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td  class="high" style="background-color: #008dcf;" title="OVERALL SCORE: 10">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='2' && current_likelihood_score='5' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
              </tr>
              <tr>
                 <td>Insignificant <small class="text-primary">1</small></td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 1">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='1' && current_likelihood_score='1' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="very_low" style="background-color: #d4ecf8;" title="OVERALL SCORE: 2">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='1' && current_likelihood_score='2' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 3">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='1' && current_likelihood_score='3' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="low" style="background-color: #99d1ec;" title="OVERALL SCORE: 4">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='1' && current_likelihood_score='4' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                          ?>
                          <a href="#" class="text-dark" data-toggle="tooltip" title="<?php echo $risk_position['risk_description'] ;?>"
                              onclick="ViewRisk('<?php echo $risk_position['reference_no'];?>','<?php echo $risk_position['dep_code'];?>');">
                              <?php echo $risk_position['reference_no'];?>
                          </a>
                    <?php
                       }
                       }

                       ?>
                 </td>
                 <td class="medium" style="background-color: #59b4e0;" title="OVERALL SCORE: 5">
                    <?php
                       $sql = mysqli_query($dbc,"SELECT * FROM update_risk_status
                                                  WHERE  current_impact_score='1' && current_likelihood_score='5' && changed='no'
                                                  && risk_opportunity='opportunity'  && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20' && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20 ");
                        if(mysqli_num_rows($sql) > 0)
                        {
                          while ($risk_position = mysqli_fetch_array($sql)) {
                            echo "<span class='ref_no'>". $risk_position['reference_no'] ."</span>";
                          }
                        }

                       ?>
                 </td>
              </tr>
              <tr>
                 <td colspan="2" rowspan="2"><i class="fa fa-times fa-lg"></i></td>
                 <td>Rare <small class="text-primary">1</small></td>
                 <td>Unlikely <small class="text-primary">2</small></td>
                 <td>Likely <small class="text-primary">3</small></td>
                 <td>Highly Likely <small class="text-primary">4</small></td>
                 <td>Almost Certain <small class="text-primary">5</small></td>
                 </td>
              </tr>
              <tr>
                 <td colspan="5">Likelihood</td>
              </tr>
           </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-light">Risk Heatmap Scale</div>
          <div class="card-body">
            <div class="TablesBox">
                  Opportunity Heatmap Scale
                  <table border="1" width="100%">
                     <tr>
                        <th>opportunity Rating </th>
                        <th>opportunity Score </th>
                     </tr>
                     <tr>
                        <td>Very High</td>
                        <td class="small" style="background-color: #0272a6;"title="20-25">
                           <font color="white">20-25</font>
                     </tr>
                     <tr>
                        <td>High</td>
                        <td class="small" style="background-color: #008dcf;"title="10-16">
                           <font color="white">10-16</font>
                     </tr>
                     <tr>
                        <td>Medium</td>
                        <td class="small" style="background-color: #59b4e0;"title="5-9">
                           <font color="black">5-9</font>
                     </tr>
                     <tr>
                        <td>Low</td>
                        <td class="small" style="background-color: #99d1ec" title="3-4">
                           <font color="black">3-4</font>
                     </tr>
                     <tr>
                        <td>Very Low</td>
                        <td class="small" style="background-color: #d4ecf8;" title="1-2">
                           <font color="black">1-2</font>
                     </tr>
               </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header bg-light">Details</div>
      <div class="card-body table-responsive">
        <?php
           $sql_query = mysqli_query($dbc,"SELECT * FROM update_risk_status WHERE  risk_opportunity='opportunity' && changed='no'
             && period_from='".$select_period."' && quarter='".$select_quarter."' && current_overall_score >='20'
             && risk_status='open' ORDER BY current_overall_score DESC LIMIT 20");
           $number = 1;
           if($total_rows = mysqli_num_rows($sql_query) > 0)
           {?>
        <table class="table table-bordered table-striped table-hover" id="risks-heatmap-opportunities-table">
           <thead>
              <tr>
                 <td>No</td>
                 <td>Risk</td>
                 <td>Score</td>
                 <td>Ref No</td>
              </tr>
           </thead>
           <?php
              while($row = mysqli_fetch_array($sql_query))
              {
               ?>
           <tr>
              <td><?php echo $number++ ;?></td>
              <td><?php echo $row['risk_description'];?></td>
              <td><?php echo $row['current_overall_score'];?></td>
              <td><?php echo $row['reference_no'];?></td>
           </tr>
           <?php
              }
                       ?>
        </table>
        <?php
           }
           else {
             ?>
        <table class="table table-bordered">
           <thead>
              <tr>
                 <td class="text-danger"><i class="fa fa-info-circle"></i> No Records Found</td>
              </tr>
           </thead>
           <tr>
              <td class="text-danger">Sorry, no records have been found for
                 this quarter (<span class="text-info"><?php echo $select_quarter?></span>)
                 and period (<span class="text-info"><?php echo $select_period?> at the Corporate Level</span>)
              </td>
           </tr>
        </table>
        <?php
           }
                  ?>
      </div>
    </div>
  </div>


</div>

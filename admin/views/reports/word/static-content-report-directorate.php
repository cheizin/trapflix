<?php
require_once('../../../controllers/setup/connect.php');
 ?>
    <h4>ABBREVIATIONS</h4>
    <table class="static-table-heatmap" width="49%" style="float:left;margin-right:3px;">
      <thead>
        <th>TERM</th>
        <th>DEFINITION</th>
      </thead>
      <tbody>
        <?php
            $sql_term = mysqli_query($dbc,"SELECT * FROM abbreviations WHERE id < 27 && definition IS NOT NULL ORDER BY definition ASC");
            while($term = mysqli_fetch_array($sql_term))
            {
              ?>
              <tr>
                <td><?php echo strtoupper($term['term']);?></td>
                <td><?php echo strtoupper($term['definition']);?></td>

              </tr>
              <?php
            }

        ?>
      </tbody>
    </table>
    <table class="static-table-heatmap" width="50%" style="float:left;">
      <thead>
        <th>TERM</th>
        <th>DEFINITION</th>
      </thead>
      <tbody>
        <?php
            $sql_term = mysqli_query($dbc,"SELECT * FROM abbreviations WHERE id >= 27 && definition IS NOT NULL ORDER BY definition ASC");
            while($term = mysqli_fetch_array($sql_term))
            {
              ?>
              <tr>
                <td><?php echo strtoupper($term['term']);?></td>
                <td><?php echo strtoupper($term['definition']);?></td>

              </tr>
              <?php
            }

        ?>
      </tbody>
    </table>
  <div style="page-break-after:always;"></div>

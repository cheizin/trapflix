<footer class="footer">
  <div class="container">
    <div class="row">
	  <div class="col-md-3 col-sm-4">
        <a href="index-2.html"><img class="footer-logo" src="assets/img/logo.png" alt=""></a>
          <h4>About Us</h4>
        <p>We enable organizations to build winning teams by taking credible candidates across two key areas: Talent Acquisition and Development..</p>

        <div class="side-list no-border">
          <ul>
            <li><i class="ti-credit-card padd-r-10"></i>General/Marketing Contact:<strong> info@potentialstaffing.com </strong></li>
            <li><i class="ti-world padd-r-10"></i>Contact: <strong>   </strong></li>
            <li><i class="ti-mobile padd-r-10"></i>Working Hours: <strong>  8am-6pm </strong></li>


          </ul>
        </div>
        <!-- Social Box -->
        <div class="f-social-box">
          <ul>
            <li><a href="#"><i class="fa fa-facebook facebook-cl"></i></a></li>
            <li><a href="#"><i class="fa fa-google google-plus-cl"></i></a></li>
            <li><a href="#"><i class="fa fa-twitter twitter-cl"></i></a></li>
            <li><a href="#"><i class="fa fa-instagram instagram-cl"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-9 col-sm-8">
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <h4>Job Categories</h4>
            <ul>
              <?php
              $sql_query1 =  mysqli_query($dbc,"SELECT * FROM industry  ORDER BY id");

              $number = 1;
              if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
              {

                $no = 1;
                 $sql = mysqli_query($dbc,"SELECT * FROM industry  ORDER BY id ASC");
                 while($row = mysqli_fetch_array($sql)){
                 ?>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> <?php echo $row['industry_name'] ;?></a></li>

              <?php
                 }
               ?>


              <?php
              }
              else
              {
              ?>
              <br/>
              <div class="alert alert-info">
              <strong><i class="fa fa-info-circle"></i> No Category Added</strong>
              </div>

              <?php
              }
              ?>
            </ul>
          </div>

          <div class="col-md-3 col-sm-6">
            <h4>Resources</h4>
            <ul>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> My Account</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Support</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> How It Works</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Underwriting</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Employers</a></li>
            </ul>
          </div>
		  <div class="col-md-3 col-sm-6">
            <h4>Quick Links</h4>
            <ul>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Jobs Listing</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> About Us</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Contact Us</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Privacy Policy</a></li>
              <li><a href="#"><i class="fa fa-angle-double-right"></i> Term & Condition</a></li>
            </ul>
          </div>



          						  <div class="col-md-3 col-sm-6">
          								<div class="footer-widget">
          									<h4 class="widget-title">Download Apps</h4>
          									<a href="assets/android/career.apk" target="_blank" class="other-store-link">
          										<div class="other-store-app">
          											<div class="os-app-icon">
          												<i class="ti-android theme-cl"></i>
          											</div>
          											<div class="os-app-caps">
          												Google Play
          												<span>Get It Now</span>
          											</div>
          										</div>
          									</a>


          								</div>
          							</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="copyright text-center">
          <p>Copyright © Potential Staffing 2021 All Rights Reserved.</p>
        </div>
      </div>
    </div>
  </div>
</footer>

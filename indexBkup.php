<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
        crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets2/css/style.css">

    <style>
h1 {text-align: center;}
p {text-align: center;}
button {text-align: center;}

</style>

    <title>Trapflix - Watch TV Shows Online, Watch Movies Online</title>

      <link rel="icon" href="assets2/images/house.jpg" type="image/x-icon" />

</head>

<body>
    <header>
        <!-- Image and text -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 mt-5">
                    <nav class="navbar navbar-light">

                                  <div class="col-md-8">
                        <a class="navbar-brand" href="#">
                            <img class="brand" src="./assets2/images/logo.png" class="d-inline-block align-top" alt="">
                        </a>
                      </div>
                    <div class="col-md-2">

                                                                              <button class="btn btn-danger btn-lg sing-up">
                                                                                  <a class="signin text-white btn btn-lg btn-danger" href="https://trapflix.com/login.php">Sign In  </a>
                                                                                </button>

                      </div>



                    </nav>
<br/> <br/> <br/> <br/>


                    <section class="text-white">
                        <h1 class="header__title">Unlimited movies and TV shows</h1>
                        <p class="header__subtitle">WATCH ANYWHERE. CANCEL ANYTIME.</p>


                    </section>


              </div>

              <section id="iq-favorites"  class="iq-main-slider p-0">
                 <div class="container-fluid slide">
                    <div class="row">
                       <div class="col-sm-12 overflow-hidden"><br/> <br/>
                          <div class="iq-main-header d-flex align-items-center justify-content-between">
                             <h4 class="main-title"><?php echo $row2['category_name']; ?></h4>

                          </div>

                          <div class="favorites-contens">
                     <ul class="favorites-slider2 list-inline  row p-0 mb-0"> 
                           <!--    <ul class="favorites-slider list-inline  row p-0 mb-0"> -->

                               <?php
                       $sql_query123 =  mysqli_query($dbc,"SELECT * FROM feeds WHERE category ='".$row2['category_name']."' ORDER BY id DESC");

                       $number = 1;
                       if($total_rows1 = mysqli_num_rows($sql_query123) > 0)
                       {?>
                               <?php
                               $no = 1;
                                $sql3 = mysqli_query($dbc,"SELECT * FROM feeds WHERE category ='".$row2['category_name']."' ORDER BY id DESC" );
                                while($row3 = mysqli_fetch_array($sql3)){
                                 //  $video_name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM videos WHERE id ='".$row3['popular_video_id']."' ORDER BY id DESC"));
                                ?>
                                <li class="slide-item">

                                  <form method="post" action="movie-details.php">
                                    <input type="hidden" name="token" value="<?php echo $row3['token']; ?>">

                                           <a onclick="Selectvideos2('<?php echo $row3['token'];?>');">
             

                                      <div class="">
                                         <div class="img-box">
                                            <img src="images/favoriteCompressed/<?php echo $row3['thumbnail']; ?>" loading="lazy" width="100%" height="100%" alt="">
                                         </div>
                                         <div class="">
                                            <h4><?php echo $row3['feeds_header']; ?></h4>
                                         
                                      </div>


                                   </a>

                                    </form>
                                </li>

                       <?php
                }
              ?>

                       <?php
               }

               ?>


                             </ul>
                          </div>

                       </div>
                    </div>
                 </div>
      <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
         <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="44px" height="44px" id="circle"
            fill="none" stroke="currentColor">
            <circle r="20" cy="22" cx="22" id="test"></circle>
         </symbol>
      </svg>
        </section>
            </div>

        </div>
    </header>
    <section id="tabs">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 ">



                            <div class="container">
                                <div class="row features">
                                    <div class="col-md-12">
                                      <section class="text-white">
                                          <h1 class="header__title">Enjoy on your Devices</h1>
                                          <p class="header__subtitle">Watch on Smart TVs, Playstation, Xbox, Chromecast, Apple TV, Blu-ray players, and more..</p>


                                      </section>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="./assets2/images/asset_TV_UI.png" />
                                        <h3>Watch on your TV</h3>
                                        <p>Smart TVs, PlayStation, Xbox, Chromecast, Apple TV, Blu-ray players and more.</p>
                                    </div>

                                    <div class="col text-center">
                                        <img src="./assets2/images/asset_mobile_tablet_UI_2.png" />
                                        <h3>Watch instantly</h3>
                                        <p>Available on phone and tablet</p>
                                    </div>

                                    <div class="col text-center">
                                        <img src="./assets2/images/asset_website_UI.png" />
                                        <h3>Use any computer</h3>
                                        <p>Watch right on trapflix.com.</p>
                                    </div>
                                </div>
                            </div>

                                <hr style="solid"/>
 <br/>  <br/>
                            <div class="container">

                              <div class="row gx-5 align-items-center">
                                  <div class="col-lg-6">
                                      <section class="text-white">
                                          <h1 class="header__title">Create Video Channel</h1>
                                          <p class="header__subtitle">

                                            <ul>
  <li>Create Channel</li>
  <li>Upload Unlimited Videos </li>
  <li>Track video views </li>
  <li>Track Channel Subscription</li>
</ul></p>


                                      </section>
                                    </div>



                                      <div class="col-lg-6">
                                        <video muted="muted" autoplay="true" loop="true" style="max-width: 100%; height: 100%">
                                          <source src="assets2/images/addchannel.mp4" type="video/mp4" /></video>

                                    </div>

                                </div>
                            </div>



                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <p>
                        Questions? Call
                        <a href="tel:+226 55140949">+226 55140949</a>
                    </p>
                </div>
            </div>


        </div>


    </footer>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
        crossorigin="anonymous"></script>
    <script src="./assets2/javascript/app.js"></script>

    <script type="text/javascript"> window.$crisp=[];window.CRISP_WEBSITE_ID="aade7b13-df34-439e-8c2c-0e6fd7cb8c0d";(function(){ d=document;s=d.createElement("script"); s.src="https://client.crisp.chat/l.js"; s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})(); </script>

      <script>
      $crisp.push(["set", "user:nickname", ["guest"]]);
      </script>
</body>

</html>

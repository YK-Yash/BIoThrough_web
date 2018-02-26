<?php
namespace Firebase;
session_start(); 

if (empty($_SESSION['user_id']) && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) != '/user_login.php') {
    header('Location: user_login.php');
    exit;
}

?>

<?php

require_once "firebaseLib.php";
use Exception;
class FirebaseTest
{
    protected $_firebase;
    protected $_todoMilk = array(
        'name' => 'Pick the milk',
        'priority' => 1
    );

    protected $_todoBeer = array(
        'name' => 'Pick the beer',
        'priority' => 2
    );

    protected $_todoLEGO = array(
        'name' => 'Pick the LEGO',
        'priority' => 3
    );

    // --- set up your own database here
    const DEFAULT_URL = 'https://myapplication-d680d.firebaseio.com/';
    const DEFAULT_TOKEN = 'mMfoqOMhOv3TrV3YG3i4QIIFmN89UwduTDVm7UYV';
    const DEFAULT_TODO_PATH = '/Temperature';
    const DELETE_PATH = '/sample';
    const DEFAULT_SET_RESPONSE = '{"name":"Pick the milk","priority":1}';
    const DEFAULT_UPDATE_RESPONSE = '{"name":"Pick the beer","priority":2}';
    const DEFAULT_PUSH_RESPONSE = '{"name":"Pick the LEGO","priority":3}';
    const DEFAULT_DELETE_RESPONSE = 'null';
    const DEFAULT_URI_ERROR = 'You must provide a baseURI variable.';

    public function setUp()
    {
        $this->_firebase = new FirebaseLib(self::DEFAULT_URL, self::DEFAULT_TOKEN);
    }

    public function testNoBaseURI()
    {
        $errorMessage = null;
        try {
            new FirebaseLib();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
        }

        $this->assertEquals(self::DEFAULT_URI_ERROR, $errorMessage);
    }
    
    public function testSet($dataaa,$pathh)
    {
        $response = $this->_firebase->set($pathh, $dataaa);
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testGetAfterSet($pathhh)
    {
        $response = $this->_firebase->get($pathhh);
        return $response;
        //$this->assertEquals(self::DEFAULT_SET_RESPONSE, $response);
    }

    public function testUpdate()
    {
        $response = $this->_firebase->update(self::DEFAULT_TODO_PATH, $this->_todoBeer);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testGetAfterUpdate()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_UPDATE_RESPONSE, $response);
    }

    public function testPush()
    {
        $response = $this->_firebase->push(self::DEFAULT_TODO_PATH, $this->_todoLEGO);
        $this->assertRegExp('/{"name"\s?:\s?".*?}/', $response);
        return $this->_parsePushResponse($response);
    }

    /**
     * @depends testPush
     */
    public function testGetAfterPush($responseName)
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH . '/' . $responseName);
        $this->assertEquals(self::DEFAULT_PUSH_RESPONSE, $response);
    }

    public function testDelete()
    {
        $response = $this->_firebase->delete(self::DELETE_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    public function testGetAfterDELETE()
    {
        $response = $this->_firebase->get(self::DEFAULT_TODO_PATH);
        $this->assertEquals(self::DEFAULT_DELETE_RESPONSE, $response);
    }

    private function _parsePushResponse($response)
    {
        $responseObj = json_decode($response);
        return $responseObj->name;
    }
}

?>

<html>
    
  <head>
      
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>bIOThrogh</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="tree1.gif"><!--content="images/touch/ms-touch-icon-144x144-precomposed.png">-->
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.teal-green.min.css">
    <link rel="stylesheet" href="styles.css">
      
      
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    </style>
      
  </head>
    
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
          <h3>bIOThrough</h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="#overview" class="mdl-layout__tab is-active">Overview</a>
          <a href="#crop_list" class="mdl-layout__tab">Crops I Can Sow</a>
          <a href="#features" class="mdl-layout__tab">Sensor Data</a>
          <a href="#buy" class="mdl-layout__tab">Buy</a>
          <a href="#sell" class="mdl-layout__tab">Sell</a>
            
          <form action="" method="post" name="button_logout"><button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-shadow--4dp mdl-color--accent" id="add" name="abcc">
            <i class="material-icons" role="presentation">exit_to_app</i>
            <span class="visuallyhidden">Add</span>
              </button></form>
            
            <?php 
            
                if (isset($_POST['abcc'])) 
                { 
                    
                    $_SESSION["user_id"] = "";
                    session_destroy();
                    header('Location: user_login.php');
                    exit;

                } 
            ?>
            
        </div>
      </header>
      <main class="mdl-layout__content">
          
          
        <div class="mdl-layout__tab-panel is-active" id="overview">
          <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
            <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--teal-100 mdl-color-text--white">
<!--
              <i class="material-icons">play_circle_filled</i>
-->
                <img src="Apple_Tree_Big-icon.png">
            </header>
            <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
              <div class="mdl-card__supporting-text">
                  <h4>About bIOThrough</h4>
                Biothrough assists the user to maintain his/her kitchen garden with ease with the help of live sensor data monitoring and wasy action trigerring.
              </div>
              <div class="mdl-card__actions">
                <a href="http://localhost/download_files.php" class="mdl-button">Read our features</a>
              </div>
            </div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btn1">
              <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right" for="btn1">
              <li class="mdl-menu__item" disabled>About developer</li>
              <li class="mdl-menu__item" disabled>About team</li>
              <li class="mdl-menu__item" disabled>Contact developer</li>
            </ul>
          </section>
          <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
            <div class="mdl-card mdl-cell mdl-cell--12-col">
              <div class="mdl-card__supporting-text mdl-grid mdl-grid--no-spacing">
                <h4 class="mdl-cell mdl-cell--12-col">Details</h4>
                <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
                  <div class="section__circle-container__circle mdl-color--primary"></div>
                </div>
                <div class="section__text mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
                  <h5>Location based crop assistance</h5>
                  No need to worry about the crop to sow. Based on your location and time, we will suggest the best suited crops for you.
                </div>
                <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
                  <div class="section__circle-container__circle mdl-color--primary"></div>
                </div>
                <div class="section__text mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
                  <h5>Live sensor data monitoring</h5>
                  After you setup the DIY kit, you can see live sensor data. This data can be seen anywhere, the only requirement being an internet connection.
                </div>
                <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
                  <div class="section__circle-container__circle mdl-color--primary"></div>
                </div>
                <div class="section__text mdl-cell mdl-cell--10-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
                  <h5>Community Ecosystem</h5>
                  Got some extra yield? Don't worry.. Just post an add on the community ecosystem and exchange your crops with like minded people for some other crops or sell them.
                </div>
              </div>
              <div class="mdl-card__actions">
                <a href="http://localhost/download_files.php" class="mdl-button">Read our features</a>
              </div>
            </div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btn2">
              <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right" for="btn2">
              <li class="mdl-menu__item" disabled>About developer</li>
              <li class="mdl-menu__item" disabled>About team</li>
              <li class="mdl-menu__item" disabled>Contact developer</li>
            </ul>
          </section>
          <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
            <div class="mdl-card mdl-cell mdl-cell--12-col">
              <div class="mdl-card__supporting-text">
                <h4>Technology</h4>
                Using the latest technologies and with the concept of internet of things, we are able to show sensor data on this web application or the mobile application.
              </div>
              <div class="mdl-card__actions">
                <a href="http://localhost/download_files.php" class="mdl-button">Read our features</a>
              </div>
            </div>
            <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon" id="btn3">
              <i class="material-icons">more_vert</i>
            </button>
            <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right" for="btn3">
            <li class="mdl-menu__item" disabled>About developer</li>
              <li class="mdl-menu__item" disabled>About team</li>
              <li class="mdl-menu__item" disabled>Contact developer</li>
            </ul>
          </section>
          <section class="section--footer mdl-color--white mdl-grid">
            <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
              <div class="section__circle-container__circle mdl-color--accent section__circle--big"></div>
            </div>
            <div class="section__text mdl-cell mdl-cell--4-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
              <h5>Our Motto</h5>
Here at bIOThrough, we want to change conventional ideas with the help of technology. Now reap better than you sow..!
              </div>
            <div class="section__circle-container mdl-cell mdl-cell--2-col mdl-cell--1-col-phone">
              <div class="section__circle-container__circle mdl-color--accent section__circle--big"></div>
            </div>
            <div class="section__text mdl-cell mdl-cell--4-col-desktop mdl-cell--6-col-tablet mdl-cell--3-col-phone">
              <h5>For any help and assistance :</h5>
              Feel free to contact the developers - reap_better@gmail.com
            </div>
          </section>
        </div>
          
          
        <div class="mdl-layout__tab-panel" id="features">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--12-col">
                
                <?php
                $fb = new FirebaseTest();
                $fb->setUp();
                $flag_irrigate = $fb->testGetAfterSet("/Irrigate");
                $flag_irrigate_auto = $fb->testGetAfterSet("/AutoIrrigate");
                $humidity = $fb->testGetAfterSet('/Humidity');
                $moisture = $fb->testGetAfterSet('/Moisture');
                $temperature = $fb->testGetAfterSet('/Temperature');
                //$temperature2 = (float)$temperature;
                echo '<h3>Temperature : '.$temperature.'</h3><br><h3>Moisture : '.$moisture.'</h3><br><h3> Humidity : '.$humidity.'</h3>';
                ?>
                
            </div>
          </section>
        </div>
          
          
        <div class="mdl-layout__tab-panel" id="crop_list">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            
              <div class="mdl-cell mdl-cell--12-col">
                  
                  <form action="" method="post" name="state_filter">
                  <input list="state_f" name="state_f">

                    <datalist id="state_f" name = "state_f">
                      <option value="Tamil Nadu">
                      <option value="Kerala">
                      <option value="Bengaluru">
                      <option value="Delhi">
                      <option value="Orissa">
                    </datalist>
                      <input type="submit" name="login" value="Filter" class="form-submit-button">
                  </form>
                
                <?php
                  $state_name = "";
                  if(!empty($_POST["login"])) {
                       $state_name = $_POST["state_f"];
                  }
                $JsoncropList = file_get_contents('crop_seasons.json');
                $cropList = json_decode($JsoncropList);
                  
                  echo '<h3>Based on your current location, the following crops can be sown.</h3><br>';
                
                echo '<style>
.demo-list-three {
  width: 650px;
}
</style>';
                  
                  echo '
<ul class="demo-list-three mdl-list">';
                for($i=0;$i<69;$i++)
                //echo($cropList[$i]->plant." ".$cropList[$i]->state." ".$cropList[$i]->season."<br>");
                
                //echo 'Temperature : '.$temperature.'<br>Moisture : '.$moisture.'<br> Humidity : '.$humidity;
                  if($cropList[$i]->state==$state_name)
                    echo'
                

  <li class="mdl-list__item mdl-list__item--three-line">
    <span class="mdl-list__item-primary-content">
      <i class="material-icons mdl-list__item-avatar">local_florist</i>
      <span>'.$cropList[$i]->plant.'</span>
      <span class="mdl-list__item-text-body">
        ' .$cropList[$i]->state. '
      </span>
    </span>
  </li>
            
  
</ul>'?>
                  
                  
                
                
                
            </div>
          </section>
        </div>
          
          <div class="mdl-layout__tab-panel" id="buy">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--12-col">
                
                <?php
                      
                  $conn = mysqli_connect("localhost","root","","users_biothrough");
                  $q1  = "select user_name,crop_name,qty,cost,contact_no,Address from e_retail";
                  $result = $conn->query($q1);
                  
                  while($row = mysqli_fetch_assoc($result)) {
        
                    $user_name = $row['user_name']; 
                    $crop_name = $row['crop_name'];
                    $qty = $row['qty'];
                    $cost = $row['cost'];
                    $contact_no = $row['contact_no'];
                    $address = $row['Address'];
                      
                      echo'
                

                      <li class="mdl-list__item mdl-list__item--six-line">
                        <span class="mdl-list__item-primary-content">
                          <i class="material-icons mdl-list__item-avatar">person</i>
                          Crop : '.$crop_name.'
                          
                            <br>Seller : ' .$user_name. ' 
                            <br>Quantity available(kg) : '.$qty.'
                            <br>Cost : ₹'.$cost.'
                            <br>Contact no : '.$contact_no.'
                            <br>Address : '.$address.'
                          
                        </span>
                      </li>
                    </ul>';
                  }                    
                      
                ?>
                
            </div>
          </section>
        </div>
          
          
          <div class="mdl-layout__tab-panel" id="sell">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--12-col">
                
                
                <form action="index2.php" method="post">
                Name.................................<input type="text" name="name2"><br>
                Crop wishing to sell..........<input type="text" name="crop"><br>
                Quantity available.............<input type="text" name="qty"><br>
                Cost per Kg.......................<input type="text" name="cost"><br>
                Contact No........................<input type="text" name="contact_no"><br>
                Address.............................<input type="text" name="address"><br>
                Pin Code............................<input type="number" name="pin"><br><br><br>
                <input type="submit">
                </form>
                
                <?php
                      
                $conn = mysqli_connect("localhost","root","","users_biothrough");
                
                $u_name = "";
                $u_qty = "";
                $u_crop = "";
                $u_contact = "";
                $u_address = "";
                $u_pin = "";
                
                if(!empty($_POST["name2"])){
                
                $u_name = $_POST['name2'];
                $u_qty = $_POST['qty'];
                $u_crop = $_POST['crop'];
                $u_cost = $_POST['cost'];
                $u_contact = $_POST['contact_no'];
                $u_address = $_POST['address'];
                $u_pin = $_POST['pin'];
                
                    $u_address = $u_address.$u_pin;
                    
                $q1  = "insert into e_retail values('$u_name','$u_crop','$u_qty','$u_cost','$u_contact','$u_address')";
                //$q1  = "insert into e_retail values('Raj','potato',23,15,'369852147','dabdjhs')";
                  $result = $conn->query($q1);      
                    print_r($result);
                }       
                      
                ?>
                
            </div>
          </section>
        </div>
          
          
          
        <footer class="mdl-mega-footer">
          <div class="mdl-mega-footer--middle-section">
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Features</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">About</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">Partners</a></li>
                <li><a href="#">Updates</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Details</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">Spec</a></li>
                <li><a href="#">Tools</a></li>
                <li><a href="#">Resources</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">Technology</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">How it works</a></li>
                <li><a href="#">Patterns</a></li>
                <li><a href="#">Usage</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contracts</a></li>
              </ul>
            </div>
            <div class="mdl-mega-footer--drop-down-section">
              <input class="mdl-mega-footer--heading-checkbox" type="checkbox" checked>
              <h1 class="mdl-mega-footer--heading">FAQ</h1>
              <ul class="mdl-mega-footer--link-list">
                <li><a href="#">Questions</a></li>
                <li><a href="#">Answers</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>
            </div>
          </div>
          <div class="mdl-mega-footer--bottom-section">
            <div class="mdl-logo">
              bIOThrough - Reap better than you sow!
            </div>
            <!--<ul class="mdl-mega-footer--link-list">
              <li><a href="https://developers.google.com/web/starter-kit/">Web Starter Kit</a></li>
              <li><a href="#">Help</a></li>
              <li><a href="#">Privacy and Terms</a></li>
            </ul>-->
          </div>
        </footer>
      </main>
    </div>
        <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  </body>
</html>

<?php
  if(!isset($_SESSION)) { 
    session_start(); 
  } 
  if(!isset($_SESSION['is_login']) ){
    header("location: login.php");
    echo '<script>window.location="login.php";</script> ';
  }
  include_once '_general/backend/languageSetting.php'; 
  include_once "_general/backend/generalFunctions.php";
  include_once "_general/backend/createNewFilesToSystem.php";
  include_once '_general/backend/returnDataFromFile.php'; 
	$_returnDataFromFile=new _returnDataFromFile("permission");
  date_default_timezone_set($_SESSION['SYSTimezone']);
  //echo "<pre>";
  //print_r($_SESSION);
  //echo "</pre>";
  //exit;
?>

<!DOCTYPE html>
<html lang="en" dir="<?php echo $_SESSION["languageSetting"]["DIR"]; ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Distribution</title>
    <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=java_disabled.html">
    </noscript>
    <link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/core.css" rel="stylesheet" type="text/css">
    <link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/components.css" rel="stylesheet" type="text/css">
    <link href="../API/assets/<?php echo $_SESSION["languageSetting"]["CSS"]; ?>/colors.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../API/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="../API/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="../API/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="../API/assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="../API/assets/js/plugins/forms/styling/uniform.min.js"></script>     
  </head>
  <body style=" font-family: 'Noto Kufi Arabic', Times, serif;">

    <div class="page-header">
      <div class="page-header-content">
        <div class="page-title">
          <h4>
            <span class="text-semibold multi_lang">Main</span>
            <small class="display-block" id="welcome"><span class="multi_lang">Welcome</span>
              <?php echo $_SESSION['username']; ?></small>
          </h4>
        </div>

        <div class="heading-elements">
          <div class="heading-btn-group">
            <a href="index.php?p=profile" class="btn btn-link btn-float has-text text-size-small"><i class="icon-cog6 text-indigo-400"></i>
              <span class="multi_lang">My Profile</span></a>
            <a href="models/logout.php" class="btn btn-link btn-float has-text text-size-small"><i class="icon-switch2 text-indigo-400"></i>
              <span class="multi_lang">Log-out</span></a>
          </div>
        </div>
      </div>
    </div>

    <div class="page-container">
      <div class="page-content">
        <div class="content-wrapper">
          <div id="demo">
            <div class="text-center " style="margin-top: -20px;">
              <h5 class=" multi_lang" style="font-size: 30px; "><? echo $_SESSION["company_name"];?></h5>
            </div>
            <nav style="margin-top: -55px;">
              <ul class='menu'>
                  <br><br><br>
                  <?php
                      $filePermissionData=$_returnDataFromFile->returnFullData();
                      _createNewFilesToSystem::createNewFile($filePermissionData);

                      $user=json_decode(html_entity_decode($_SESSION["userPermission"]),true);
                      $userKeys=checkLoginActive();
                      for($i=0;$i<count($filePermissionData);$i++){
                        extract($filePermissionData[$i]);
                        $showPage=false;
                        if($page_type=="report"){
                            continue;
                        }else if($_SESSION['ADMProfileType']==2 && $show!="none" && $show!="header"){
                            $showPage=true;
                        }else if($_SESSION['ADMProfileType']==1 && $type!="devloper" && $show!="header" && $show!="none"){
                            $showPage=true;
                        }else if($type!="admin" && $type!="developer" && $show!="header" && in_array($id,$userKeys) && $show!="none"){
                            $showPage=true;
                        }
                        if($showPage){
                            echo '
                              <div class="col-sm-6 col-md-3">
                                <a href="index.php?p='.$page.'">
                                  <div class="panel panel-body '.$color.' has-bg-image">
                                    <div class="media no-margin">
                                      <div class="media-right media-middle">
                                        <i class="'.$icon.' icon-3x opacity-75"></i>
                                      </div>
                                      <div class="media-body text-right">
                                        <h3 class="no-margin multi_lang">'.$name.'</h3>
                                        <span class="text-uppercase text-size-mini multi_lang">'.$name.'</span>
                                      </div>
                                    </div>
                                  </div>
                                </a>
                              </div>
                            ';
                        }
                      }
                  ?>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <footer id="footer">
      <div class="text-center  clearfix">
        <p> <small><a href="http://www.keentech.co" target="_blank">KeenTech</a> for IT Services and Solutions
            <br>&copy;
            <?php echo date("Y"); ?></small> </p>
      </div>
    </footer>

    <script type="text/javascript">
    //Auto_Translate();
    </script>

  </body>
</html>
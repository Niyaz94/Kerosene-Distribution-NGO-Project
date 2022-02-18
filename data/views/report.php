<?php 
    $fileName=__FILE__;
    include_once "header.php";
    include_once "_general/backend/createNewFilesToSystem.php";
    $_returnDataFromFile=new _returnDataFromFile("permission");
    $returnAllReport=$_returnDataFromFile->returnFullReport();
?>
<div class="panel panel-flat">
  <div class="panel-body">
    <nav style="margin-top: -55px;">
      <ul class='menu'>
          <br><br><br>
          <?php
              _createNewFilesToSystem::createNewReport($filePermissionData); 
              $user=json_decode(html_entity_decode($_SESSION["userPermission"]),true);
              $reportUserPermission=$user[8];
              $reportJSONPermission=$pageInfo["buttons"];
              for($i=0;$i<count($reportJSONPermission);$i++){
                extract($reportJSONPermission[$i]);
                $showPage=false;
                if($_SESSION['ADMProfileType']==2 && $show!="none" && $show!="header"){
                    $showPage=true;
                }else if($_SESSION['ADMProfileType']==1 && $type!="devloper" && $show!="header" && $show!="none"){
                    $showPage=true;
                }else if($type!="admin" && $type!="developer" && $show!="header"  && $show!="none" ){
                  if($reportUserPermission["active"]==1 && $reportUserPermission["buttons"][$id]==1){
                    $showPage=true;
                  }
                }
                if($showPage){ 
                    for ($j=0; $j < count($returnAllReport); $j++) { 
                      $idPage=explode("_",$id)[0];
                      if($idPage==$returnAllReport[$j]["page"]){
                        echo '
                          <div class="col-sm-6 col-md-3">
                            <a href="index.php?p='.$returnAllReport[$j]["page"].'">
                              <div class="panel panel-body '.$returnAllReport[$j]["color"].' has-bg-image">
                                <div class="media no-margin">
                                  <div class="media-right media-middle">
                                    <i class="'.$returnAllReport[$j]["icon"].' icon-3x opacity-75"></i>
                                  </div> 
                                  <div class="media-body text-right">
                                    <h3 class="no-margin multi_lang">'.$returnAllReport[$j]["name"].'</h3>
                                    <span class="text-uppercase text-size-mini multi_lang">'.$returnAllReport[$j]["name"].'</span>
                                  </div>
                                </div>
                              </div>
                            </a>
                          </div>
                        ';
                      }
                    }
                    
                  }
              }
          ?>
      </ul>
    </nav>
  </div>
</div>
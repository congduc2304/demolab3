<?php
session_start();
require 'config.php';
require 'language/lang_'.$conf_language.'.php';
require_once 'system/phpcount.php';
require_once 'system/DbConnMain.php';
require_once 'system/cryptor.php';
$php_count = new PHPCount;
$crypt = new Cryptor;
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
if(!isset($_SESSION['username']) || $crypt->decrypt($_SESSION['is_admin'],$secret_key,$secret_iv) == '0'){
	header("location:index.php?login=true");
}
function showSuggestionCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_suggest,$conn){
  try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_suggest."");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->rowCount();
  } catch(PDOException $e) {
   return 0;
 }
}
// Test commit

function showContactsCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_contacts,$conn){
  try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_contacts."");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->rowCount();
  } catch(PDOException $e) {
   return 0;
 }
}

function showReportsCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_reports,$conn){
  try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_reports."");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->rowCount();
  } catch(PDOException $e) {
   return 0;
 }
}
function getMediaType($type,$movie,$serial,$anim,$tv){
	if($type == "type-movie"){
		return $movie;
	} elseif ($type == "type-sers"){
		return $serial;
	} elseif ($type == "type-anim"){
		return $anim;
	} elseif ($type == "type-tv"){
		return $tv;
	}
}
$site_url = str_replace("admin.php","",((isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https")) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$getContactsCount = showContactsCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_contacts,$conn);
$getSuggestionCount = showSuggestionCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_suggest,$conn);
$getReportsCount = showReportsCount($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_reports,$conn);
?>
<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <link rel="shortcut icon" type="images/png" href="images/img_avatar.png"/>
  <title><?php echo $lang_admin_title ?></title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="styles/admin/material.cyan-light_blue.min.css">
  <link rel="stylesheet" href="styles/admin/styles.css">
  <script src="scripts/jquery-3.3.1.min.js"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/10.1.0/classic/ckeditor.js"></script>
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
<body>
  <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
    <header class="demo-header mdl-layout__header" style="box-shadow:none !important;background-color:#ff424f !important;color:#fff !important;">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">B???ng ??i???u khi???n qu???n tr???</span>
        <div class="mdl-layout-spacer"></div>
        <button onclick="window.location.href='admin.php'" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon"><i class="material-icons">refresh</i>
        </button>
      </div>
    </header>
    <div class="demo-drawer mdl-layout__drawer" style="background-color:#141414 !important;color:#fff !important;box-shadow:none !important;">
      <header class="demo-drawer-header">
        <div class="demo-avatar" style="background:url('<?php echo $_SESSION['avatar_img'] ?>') center / cover;">
        </div>
        <div class="demo-avatar-dropdown">
          <span><?php echo $_SESSION['username'] ?></span>
          <div class="mdl-layout-spacer"></div>
          <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
            <i class="material-icons" role="presentation">arrow_drop_down</i>
            <span class="visuallyhidden">Accounts</span>
          </button>
          <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn" style="min-width:200px;">
            <li class="mdl-menu__item" disabled><?php echo $_SESSION['my_email'] ?></li>
            <li onclick="window.location.href='index.php'" class="mdl-menu__item"><i class="material-icons" style="margin-right:10px;">home</i>Quay l???i trang ch???</li>
            <li onclick="window.location.href='system/login/logout.php'" class="mdl-menu__item"><i class="material-icons" style="margin-right:10px;">exit_to_app</i>Tho??t</li>
          </ul>
        </div>
      </header>
      <nav class="demo-navigation mdl-navigation" style="background-color:#191c1f !important;">
        <span class="mdl-navigation__link" onclick="openPage('cn_home', this, '#222b31')" id="defaultOpen"><i class="material-icons" role="presentation">home</i><?php echo $lang_admin_panel_home ?></span>
        <span class="mdl-navigation__link" onclick="openPage('cn_movielist', this, '#222b31')"><i class="material-icons" role="presentation">live_tv</i><?php echo $lang_admin_panel_movies ?></span>
        <span id="open_users_btn" class="mdl-navigation__link" onclick="openPage('cn_users', this, '#222b31')"><i class="material-icons" role="presentation">group</i><?php echo $lang_admin_panel_users ?></span>
        <span 
        class="mdl-navigation__link" onclick="openPage('cn_pages', this, '#222b31')">
        <i class="material-icons" role="presentation">pages</i><?php echo $lang_admin_panel_pages ?>
      </span>
      <span class="mdl-navigation__link" onclick="openPage('cn_settings', this, '#222b31')"><i class="material-icons" role="presentation">settings</i><?php echo $lang_admin_panel_settings ?></span>
      <span class="mdl-navigation__link" onclick="openPage('cn_contacts', this, '#222b31')"><i class="material-icons" role="presentation">drafts</i><?php if($getContactsCount > 0){ echo '<span class="mdl-badge" data-badge="'.$getContactsCount.'">'.$lang_admin_panel_contacts.'</span>'; } else { echo $lang_admin_panel_contacts; } ?></span>
      <span class="mdl-navigation__link" onclick="openPage('cn_suggestions', this, '#222b31')"><i class="material-icons" role="presentation">local_offer</i><?php if($getSuggestionCount > 0){ echo '<span class="mdl-badge" data-badge="'.$getSuggestionCount.'">'.$lang_admin_panel_suggestions.'</span>'; } else { echo $lang_admin_panel_suggestions; } ?></span>
      <span class="mdl-navigation__link" onclick="openPage('cn_reports', this, '#222b31')"><i class="material-icons" role="presentation">warning</i><?php if($getReportsCount > 0){ echo '<span class="mdl-badge" data-badge="'.$getReportsCount.'">'.$lang_admin_panel_reports.'</span>'; } else { echo $lang_admin_panel_reports; } ?></span>
      <div class="mdl-layout-spacer"></div>
      <span id="upload-media-btn" class="mdl-navigation__link" onclick="openPage('cn_newmedia', this, '#222b31')"><i class="material-icons" role="presentation">add</i><?php echo $lang_admin_panel_add_movie ?></span>
    </nav>
  </div>
  <main class="mdl-layout__content" style="background-color:#222b31 !important;">
    <div class="mdl-grid demo-content">
     <div id="cn_home" class="adm-page-content">
      <div class="home-stats">
        <div style="background-color:#2b96dd;">
          <div style="height:inherit;width:150px;color:#fdfefe;background-color:#4ba7e1;float:left;"><i class="material-icons" style="font-size:85px;margin-top:33px;margin-left:33px;">live_tv</i></div>
          <div style="color:#fdfefe;font-size:20px;padding-top:25px;padding-left:180px;width:100%;">Phim v?? video ???? t???i l??n:</div>
          <?php
          try {
            $stmt = $conn->prepare("SELECT * FROM ".$table_movies."");
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            echo '<div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;">'.$stmt->rowCount().'</div>';
          } catch(PDOException $e) {
           echo '<div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;">'.$e->getMessage().'</div>';
         }
         ?>
       </div>
       <div style="background-color:#84b547;">
        <div style="height:inherit;width:150px;color:#fdfefe;background-color:#96bd68;float:left;"><i class="material-icons" style="font-size:85px;margin-top:33px;margin-left:33px;">group</i></div>
        <div style="color:#fdfefe;font-size:20px;padding-top:25px;padding-left:180px;width:100%;">Ng?????i d??ng ???? ????ng k??:</div>
        <?php
        try {
          $stmt = $conn->prepare("SELECT * FROM ".$table_members."");
          $stmt->execute();
          $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
          echo '<div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;">'.$stmt->rowCount().'</div>';
        } catch(PDOException $e) {
         echo '<div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;">'.$e->getMessage().'</div>';
       }
       ?>
     </div>
     <div style="background-color:#e76d3b;">
      <div style="height:inherit;width:150px;color:#fdfefe;background-color:#ea825b;float:left;"><i class="material-icons" style="font-size:85px;margin-top:33px;margin-left:33px;">accessibility</i></div>
      <div style="color:#fdfefe;font-size:20px;padding-top:25px;padding-left:180px;width:100%;">Kh??ch truy c???p ?????c ????o:</div>
      <div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;"><?php echo $php_count->GetTotalHits(true,$mysql_host,$mysql_user,$mysql_pass,$mysql_dbname) ?></div>
    </div>
    <div style="background-color:#cc3e4a;">
      <div style="height:inherit;width:150px;color:#fdfefe;background-color:#d25a65;float:left;"><i class="material-icons" style="font-size:85px;margin-top:33px;margin-left:33px;">play_arrow</i></div>
      <div style="color:#fdfefe;font-size:20px;padding-top:25px;padding-left:180px;width:100%;">L?????t xem:</div>
      <?php
      try {
        $stmt = $conn->prepare("SELECT media_views FROM ".$table_movies."");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $total_media_views = 0;
        if($stmt->rowCount() != 0){
         foreach($stmt->fetchAll() as $row){
          $total_media_views = $total_media_views + $row['media_views'];
        }
      }
      echo '<div style="color:#fdfefe;font-size:50px;padding-top:40px;padding-left:180px;width:100%;">'.$total_media_views.'</div>';
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
  </div>
</div>
</div>
<div id="cn_movielist" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#2b96dd;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">live_tv</i>Phim v?? video</div>
    <div style="padding:20px;">
     <button onclick="openPage('cn_newmedia', document.getElementById('upload-media-btn'), '#222b31')" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;"><i class="material-icons">add</i>T???i l??n m???t b??? phim m???i</button>
   </div>
   <table id="moviesTableId" class="mdl-data-table mdl-js-data-table custom-adm-table" style="width:100% !important;">
    <thead>
      <tr>
        <th class="mdl-data-table__cell--non-numeric">T??n b??? phim</th>
        <th class="mdl-data-table__cell--non-numeric">Th??? lo???i phim</th>
        <th>L?????t xem</th>
        <th>"Th??ch"</th>
        <th class="mdl-data-table__cell--non-numeric">Ho???t ?????ng</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
        $stmt = $conn->prepare("SELECT * FROM ".$table_movies." ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $movie_num = 0;
        foreach($stmt->fetchAll() as $row){
         $movie_num++;
         ?>
         <tr>
          <td class="mdl-data-table__cell--non-numeric"><?php echo $row['media_name'] ?></td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo getMediaType($row['media_type'],$lang_media_type_movie,$lang_media_type_serial,$lang_media_type_anim,$lang_media_type_tv) ?></td>
          <td><?php echo $row['media_views'] ?></td>
          <td><?php echo $row['media_likes'] ?></td>
          <td class="mdl-data-table__cell--non-numeric">
            <button onclick="validateMovieEditMode('<?php echo bin2hex(implode("=exdiv=",$row)) ?>')" class="mdl-button mdl-js-button" style="background-color:#1abc9c;color:#fff;margin-right:5px;"><i class="material-icons">code</i>Ch???nh s???a</button>
            <button onclick="validateMovieDelete('<?php echo $row['id'] ?>',<?php echo $movie_num ?>,'<?php echo bin2hex($row['media_name'])?>')" class="mdl-button mdl-js-button" style="background-color:#e74c3c;color:#fff;"><i class="material-icons">delete</i>X??a</button>
          </td>
        </tr>
        <?php
      }
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
  </tbody>
</table>
</div>
</div>
<div id="cn_users" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#84b547;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">group</i>Ng?????i d??ng</div>
    <table id="usersTableId" class="mdl-data-table mdl-js-data-table custom-adm-table" style="width:100% !important;">
      <thead>
        <tr>
          <th class="mdl-data-table__cell--non-numeric">T??i kho???n</th>
          <th class="mdl-data-table__cell--non-numeric">Email</th>
          <th class="mdl-data-table__cell--non-numeric">Nh??m</th>
          <th class="mdl-data-table__cell--non-numeric">Ho???t ?????ng</th>
        </tr>
      </thead>
      <tbody>
        <?php
        try {
          $stmt = $conn->prepare("SELECT username, email, admin, banned FROM ".$table_members." ORDER BY id DESC");
          $stmt->execute();
          $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
          $user_num = 0;
          foreach($stmt->fetchAll() as $row){
           $user_num++;
           ?>
           <tr style="<?php if($row['banned'] == '1'){ echo 'background-color:#efb2b9;'; } else if($row['admin'] == '1'){ echo 'background-color:#d3e1be;'; } else { echo ''; } ?>">
            <td class="mdl-data-table__cell--non-numeric"><?php echo $row['username'] ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php echo $row['email'] ?></td>
            <td class="mdl-data-table__cell--non-numeric"><?php if($row['admin'] == '1'){ echo $lang_dialog_my_account_user_type_admin; } else { echo $lang_dialog_my_account_user_type_normal; } ?></td>
            <td class="mdl-data-table__cell--non-numeric">
              <button onclick="validateUserAdmin('<?php echo $row['username'] ?>',<?php echo $user_num ?>)" class="mdl-button mdl-js-button" style="background-color:#3498db;color:#fff;margin-right:5px;"><i id="user_admin_btn_<?php echo $user_num ?>" class="material-icons"><?php 
              if($row['admin'] == '1'){ echo 'check_box'; } 
              else { echo 'check_box_outline_blank'; } ?></i>Admin
            </button>
            <button onclick="validateUserBan('<?php echo $row['username'] ?>',<?php echo $user_num ?>)" class="mdl-button mdl-js-button" style="background-color:#e67e22;color:#fff;margin-right:5px;"><i id="user_ban_btn_<?php echo $user_num ?>" class="material-icons"><?php 
            if($row['banned'] == '1'){ echo 'check_box'; }
            else { echo 'check_box_outline_blank'; } ?>
            </i>C???m</button>
            <button onclick="validateUserDelete('<?php echo $row['username'] ?>',<?php echo $user_num ?>)" class="mdl-button mdl-js-button" style="background-color:#e74c3c;color:#fff;">
              <i class="material-icons">delete</i>X??a
            </button>
          </td>
        </tr>
        <?php
      }
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
  </tbody>
</table>
</div>
</div>
<div id="cn_pages" class="adm-page-content">
  <?php
  try {
    $stmt = $conn->prepare("SELECT * FROM ".$table_pages." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach($stmt->fetchAll() as $row){
      ?>
      <div class="adm-content-card">
        <div class="adm-card-header" style="background-color:#e76d3b;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">pages</i> <?php echo $lang_admin_pages_page_title_rules ?></div>
        <div style="padding:20px;background-color:#ecf0f1;">T???i ????y b???n c?? th??? th???c hi???n v?? ch???nh s???a trang ??i???u kho???n d???ch v???.
        </div>
        <div style="padding:20px;">
          <textarea name="content" id="editor1"><?php echo $row['rules_page'] ?></textarea>
        </div>
        <div style="padding:0px 20px 20px 20px;display:flow-root;">
         <button onclick="updateRulesPage(getEditorData(1))" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;">L??u
         </button>
       </div>
     </div>
     <div class="adm-content-card">
      <div class="adm-card-header" style="background-color:#e76d3b;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">pages</i>Ch??nh s??ch b???o m???t</div>
      <div style="padding:20px;background-color:#ecf0f1;">T???i ????y b???n c?? th??? th???c hi???n v?? ch???nh s???a trang ch??nh s??ch quy???n ri??ng t??.
      </div>
      <div style="padding:20px;">
        <textarea name="content" id="editor2"><?php echo $row['policy_page'] ?></textarea>
      </div>
      <div style="padding:0px 20px 20px 20px;display:flow-root;">
       <button onclick="updatePolicyPage(getEditorData(2))" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;">L??u
       </button>
     </div>
   </div>
   <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#e76d3b;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">pages</i>V??? ch??ng t??i</div>
    <div style="padding:20px;background-color:#ecf0f1;">T???i ????y b???n c?? th??? vi???t th??ng tin v??? trang web c???a b???n.</div>
    <div style="padding:20px;">
      <textarea name="content" id="editor3"><?php echo $row['about_page'] ?></textarea>
    </div>
    <div style="padding:0px 20px 20px 20px;display:flow-root;">
     <button onclick="updateAboutPage(getEditorData(3))" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;">L??u
     </button>
   </div>
 </div>
 <?php
}
} catch(PDOException $e) {
	echo $e->getMessage();
}
?>
</div>
<div id="cn_settings" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#e76d3b;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">settings</i> <?php echo $lang_admin_settings_page_title ?>
  </div>
    <div style="padding:20px;background-color:#ecf0f1;">T???i ????y b???n c?? th??? thay ?????i c??i ?????t trang web.
    </div>
    <form id="settingsUpdateFormId" method="post" onsubmit="return validateSettingsUpdateForm()" action="system/functions/update_settings.php">
      <div style="padding:20px;">
        <?php
        try {
          $stmt = $conn->prepare("SELECT * FROM ".$table_settings." WHERE `identify`='1'");
          $stmt->execute();
          $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
          foreach($stmt->fetchAll() as $row){
            ?>
            <div>T??n trang web</div>
            <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
              <input class="mdl-textfield__input" name="site_name" type="text" value="<?php echo $row['site_name'] ?>" id="ydf_a" required>
              <label class="mdl-textfield__label" for="ydf_a">Nh???p t??n c???a trang web n??y...</label>
            </div>
            <div>Ti??u ????? trang web ???????c hi???n th??? trong tab c???a tr??nh duy???t</div>
            <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
              <input 
                    class="mdl-textfield__input" name="site_title" type="text" value="<?php echo $row['site_title'] ?>" id="ydf_b" required>
              <label 
                  class="mdl-textfield__label" for="ydf_b">Nh???p ti??u ????? c???a trang web n??y...
              </label>
            </div>
            <div>M?? t??? META trang web, ???????c hi???n th??? trong k???t qu??? c???a c??ng c??? t??m ki???m</div>
            <div 
              class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
                <textarea class="mdl-textfield__input" name="meta_desc" type="text" id="ydf_c" style="min-height:120px;"><?php echo $row['meta_desc'] ?>
                </textarea>
              <label class="mdl-textfield__label" for="ydf_c">Nh???p m?? t??? META c???a trang web n??y...</label>
            </div>
            <div>T??? kh??a META c???a trang web (c??c t??? kh??a n??n ???????c ph??n t??ch b???ng d???u ph???y)</div>
            <div 
                class="mdl-textfield mdl-js-textfield" style="width:100% !important;">
                 <textarea class="mdl-textfield__input" name="meta_keys" type="text" id="ydf_d" style="min-height:120px;"><?php echo $row['meta_keys'] ?>
                </textarea>
              <label class="mdl-textfield__label" for="ydf_d">Nh???p t??? kh??a META c???a trang web n??y...</label>
            </div>
            <?php
          }
        } catch(PDOException $e) {
         echo $e->getMessage();
       }
       ?>
     </div>
     <div style="padding:0px 20px 20px 20px;display:flow-root;">
       <button type="submit" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;"><?php echo $lang_admin_pages_save_button ?>
       </button>
     </div>
   </form>
 </div>
 <div class="adm-content-card">
  <div 
      class="adm-card-header" style="background-color:#e76d3b;">
        <i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">gps_fixed</i>XML Sitemap</div>
  <div 
      style="padding:20px;background-color:#ecf0f1;">XML Sitemap url cho c??c c??ng c??? t??m ki???m.
  </div>
  <div style="padding:20px;">
   <a href="<?php echo $site_url ?>sitemap.xml" target="_blank"><?php echo $site_url ?>sitemap.xml</a>
 </div>
 <div style="padding:0px 20px 20px 20px;display:flow-root;">
   <button onclick="updateSitemapFile(true)" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;">T???o</button>
 </div>
</div>
</div>
<div id="cn_contacts" class="adm-page-content">
  <div class="adm-content-card">
    <div 
      class="adm-card-header" style="background-color:#cc3e4a;">
      <i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">drafts</i>Li??n h???
    </div>
    <div style="padding:20px;background-color:#ecf0f1;display:flow-root;">
      <div style="float:left;margin-top:9px;">T???i ????y b???n c?? th??? ?????c tin nh???n ???????c g???i b???ng m???u Li??n h???.</div>
      <button onclick="validateAllContactsDelete(<?php echo $getContactsCount ?>)" 
          class="mdl-button mdl-js-button" style="background-color:#e74c3c;color:#fff;float:right;">
          <i class="material-icons">delete</i>X??a t???t c??? tin nh???n
      </button>
    </div>
    <div style="padding:20px;">
      <?php
      try {
        $stmt = $conn->prepare("SELECT id, subject, name, email, message FROM ".$table_contacts." ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $contacts_msg_num = 0;
        if($stmt->rowCount() != 0){
         foreach($stmt->fetchAll() as $row){
           $contacts_msg_num++;
           ?>
           <div id="contacts_id_<?php echo $contacts_msg_num ?>">
             <button class="accordion"><?php echo $row['subject'] ?> 
             <span 
                  style="color:#ccc;">@<?php echo $row['name'] ?>
             </span>
             </button>
             <div class="ac-panel">
               <div style="padding:5px 0px 18px 0px;border-bottom:1px solid #bdc3c7;margin-bottom:18px;color:#555;">
                 <div style="width:60%;">B???i:<?php echo $row['name'] ?></div>
                 <div style="width:60%;">Email:<?php echo $row['email'] ?></div>
                 <button onclick="validateContactDelete('<?php echo $row['id'] ?>',<?php echo $contacts_msg_num ?>)" class="mdl-button mdl-js-button" style="background-color:#e67e22;color:#fff;float:right;margin-top:-40px;"><i class="material-icons">clear</i>X??a</button>
               </div>
               <div><?php echo $row['message'] ?></div>
             </div>
           </div>
           <?php
         }
       } else {
        ?>
        <div style="text-align:center;">Ch??a c?? tin nh???n...</div>
        <?php
      }
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
    <div style="text-align:center;" id="removed_all_contacts"></div>
  </div>
</div>
</div>
<div id="cn_suggestions" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#cc3e4a;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">local_offer</i>Y??u c???u</div>
    <div style="padding:20px;background-color:#ecf0f1;display:flow-root;">
      <div style="float:left;margin-top:9px;">??? ????y b???n c?? th??? th???y c??c y??u c???u.</div>
      <button onclick="validateAllSuggestionsDelete(<?php echo $getSuggestionCount ?>)" class="mdl-button mdl-js-button" style="background-color:#e74c3c;color:#fff;float:right;"><i class="material-icons">delete</i>X??a t???t c??? tin nh???n</button>
    </div>
    <div style="padding:20px;">
      <?php
      try {
        $stmt = $conn->prepare("SELECT id, media_name, email, media_info FROM ".$table_suggest." ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $suggest_msg_num = 0;
        if($stmt->rowCount() != 0){
         foreach($stmt->fetchAll() as $row){
           $suggest_msg_num++;
           ?>
           <div id="suggestion_id_<?php echo $suggest_msg_num ?>">
             <button class="accordion"><?php echo $row['media_name'] ?> <span style="color:#ccc;"><?php echo $row['email'] ?></span></button>
             <div class="ac-panel">
               <div style="padding:5px 0px 18px 0px;border-bottom:1px solid #bdc3c7;margin-bottom:18px;color:#555;">
                 <div style="width:60%;">Phim y??u c???u:<?php echo $row['media_name'] ?></div>
                 <div style="width:60%;">Email:<?php echo $row['email'] ?></div>
                 <button onclick="validateSuggestionDelete('<?php echo $row['id'] ?>',<?php echo $suggest_msg_num ?>)" 
                  class="mdl-button mdl-js-button" 
                  style="background-color:#e67e22;color:#fff;float:right;margin-top:-40px;">
                  <i class="material-icons">clear</i> <?php echo $lang_admin_messages_delete_button ?>
                </button>
               </div>
               <div><?php echo $row['media_info'] ?></div>
             </div>
           </div>
           <?php
         }
       } else {
        ?>
        <div style="text-align:center;">Ch??a c?? tin nh???n...</div>
        <?php
      }
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
    <div style="text-align:center;" id="removed_all_suggestions"></div>
  </div>
</div>
</div>
<div id="cn_reports" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" 
          style="background-color:#cc3e4a;">
          <i class="material-icons" 
          style="float:left;margin:-2px 10px 0px 0px;">warning</i> <?php echo $lang_admin_reports_page_title ?></div>
    <div style="padding:20px;background-color:#ecf0f1;display:flow-root;">
      <div style="float:left;margin-top:9px;">??? ????y b???n c?? th??? xem b??o c??o.</div>
      <button onclick="validateAllReportsDelete(<?php echo $getReportsCount ?>)" class="mdl-button mdl-js-button" style="background-color:#e74c3c;color:#fff;float:right;"><i class="material-icons">delete</i> <?php echo $lang_admin_messages_delete_all_button ?></button>
    </div>
    <div style="padding:20px;">
      <?php
      try {
        $stmt = $conn->prepare("SELECT id, media_name, email, media_info FROM ".$table_reports." ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $report_msg_num = 0;
        if($stmt->rowCount() != 0){
         foreach($stmt->fetchAll() as $row){
           $report_msg_num++;
           ?>
           <div id="report_id_<?php echo $report_msg_num ?>">
             <button class="accordion"><?php echo $row['media_name'] ?> <span style="color:#ccc;"><?php echo $row['email'] ?></span></button>
             <div class="ac-panel">
               <div style="padding:5px 0px 18px 0px;border-bottom:1px solid #bdc3c7;margin-bottom:18px;color:#555;">
                 <div style="width:60%;">L?? do:<?php echo $row['media_name'] ?></div>
                 <div style="width:60%;">Email:<?php echo $row['email'] ?></div>
                 <button onclick="validateReportDelete('<?php echo $row['id'] ?>',<?php echo $report_msg_num ?>)" class="mdl-button mdl-js-button" style="background-color:#e67e22;color:#fff;float:right;margin-top:-40px;"><i class="material-icons">clear</i> <?php echo $lang_admin_messages_delete_button ?></button>
               </div>
               <div><?php echo $row['media_info'] ?></div>
             </div>
           </div>
           <?php
         }
       } else {
        ?>
        <div style="text-align:center;">Ch??a c?? tin nh???n...</div>
        <?php
      }
    }
    catch(PDOException $e) {
      echo "MySQL connection failed: " . $e->getMessage();
    }
    ?>
    <div style="text-align:center;" id="removed_all_reports"></div>
  </div>
</div>
</div>
<div id="cn_newmedia" class="adm-page-content">
  <div class="adm-content-card">
    <div class="adm-card-header" style="background-color:#2b96dd;"><i class="material-icons" style="float:left;margin:-2px 10px 0px 0px;">queue_play_next</i>T???i l??n m???t b??? phim m???i</div>
    <form id="postUploadMovieFormId" method="post" name="UploadMovieForm" onsubmit="return validateUploadMovieForm()" action="system/functions/upload_movie.php">
      <div style="padding:20px;background-color:#ecf0f1;">Th??ng tin chung</div>
      <div style="padding:20px;">
       <div>T??n b??? phim</div>
       <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
        <input class="mdl-textfield__input" type="text" name="media_name" id="adm_a">
        <label class="mdl-textfield__label" for="adm_a">Nh???p t??n phim t???i ????y...</label>
      </div>
      <div style="margin-bottom:25px;">M?? t??? phim</div>
      <textarea name="content" id="editor4"></textarea>
      <input id="movieDescriptionInputId" type="hidden" name="media_desc" value="_no_data">
      <input id="movieUniqId" type="hidden" name="media_uniq_id" value="<?php echo uniqid(rand(),false) ?>">
      <div style="display:flex;margin-top:35px;">
       <div style="width:100%;margin-right:10px;">
         <div style="margin-bottom:25px;">Th??? lo???i phim</div>
         <select id="media-type-id" name="media_type" class="select-drop-stl">
          <option value="type-movie">Phim</option>
          <option value="type-sers">Ho???t h??nh</option>
          <option value="type-anim">Series</option>
          <option value="type-tv">Ch????ng tr??nh truy???n h??nh</option>
        </select>
      </div>
      <div style="width:100%;margin-left:10px;">
       <div style="margin-bottom:25px;">Th??? lo???i <span style=\"color:#777;\">(????? tr???ng n???u kh??ng c???n thi???t)</span></div>
       <select id="genre1_id" name="media_genre1" class="select-drop-stl">
        <option value="_no_data">---</option>
        <option value="cat-comedy">H??i h?????c</option>
        <option value="cat-documentary">C??? trang</option>
        <option value="cat-detective">Trinh th??m</option>
        <option value="cat-romantic">L??ng m???n</option>
        <option value="cat-adventure">Phi??u l??u</option>
        <option value="cat-horror">Kinh d???</option>
        <option value="cat-fantasy">Vi???n t?????ng</option>
        <option value="cat-biography">Ti???u s???</option>
        <option value="cat-sport">Th??? thao</option>
        <option value="cat-action">H??nh ?????ng</option>
        <option value="cat-mystic">Huy???n b??</option>
        <option value="cat-war">Chi???n tranh</option>
        <option value="cat-thriller">Ly k???</option>
        <option value="cat-family">Gia ????nh</option>
        <option value="cat-crime">T???i ph???m</option>
        <option value="cat-western">Mi???n T??y</option>
        <option value="cat-music">??m nh???c</option>
        <option value="cat-history">L???ch s???</option>
        <option value="cat-science">Khoa h???c</option>
        <option value="cat-drama">K???ch</option>
      </select>
      <select id="genre2_id" name="media_genre2" class="select-drop-stl">
        <option value="_no_data">---</option>
        <option value="cat-comedy">H??i h?????c</option>
        <option value="cat-documentary">C??? trang</option>
        <option value="cat-detective">Trinh th??m</option>
        <option value="cat-romantic">L??ng m???n</option>
        <option value="cat-adventure">Phi??u l??u</option>
        <option value="cat-horror">Kinh d???</option>
        <option value="cat-fantasy">Vi???n t?????ng</option>
        <option value="cat-biography">Ti???u s???</option>
        <option value="cat-sport">Th??? thao</option>
        <option value="cat-action">H??nh ?????ng</option>
        <option value="cat-mystic">Huy???n b??</option>
        <option value="cat-war">Chi???n tranh</option>
        <option value="cat-thriller">Ly k???</option>
        <option value="cat-family">Gia ????nh</option>
        <option value="cat-crime">T???i ph???m</option>
        <option value="cat-western">Mi???n T??y</option>
        <option value="cat-music">??m nh???c</option>
        <option value="cat-history">L???ch s???</option>
        <option value="cat-science">Khoa h???c</option>
        <option value="cat-drama">K???ch</option>
        <option value="kong">Kong v?? Godzilla</option>
      </select>
    </div>
  </div>
</div>
<div style="padding:20px;background-color:#ecf0f1;">Th??ng tin th??m <span style=\"color:#777;\">(????? tr???ng n???u kh??ng c???n thi???t)</span></div>
<div style="padding:20px;">
  <div style="display:flex;">
    <div style="width:100%;margin-right:10px;">
      <div>Th???i l?????ng <span style=\"color:#777;\">(v?? d???: 108 ph??t ho???c 1 gi??? 48 ph??t)</span></div>
      <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
        <input class="mdl-textfield__input" type="text" name="media_duration" id="adk_a">
        <label class="mdl-textfield__label" for="adk_a">Nh???p v??o ????y th???i l?????ng c???a phim...</label>
      </div>
    </div>
    <div style="width:100%;margin-left:10px;">
      <div>Ng??n ng??? <span style=\"color:#777;\">(v?? d???: Ti???ng Anh, ti???ng T??y Ban Nha,...)</span></div>
      <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
        <input class="mdl-textfield__input" type="text" name="media_language" id="adk_b">
        <label class="mdl-textfield__label" for="adk_b">Nh???p ng??n ng??? v??o ????y...</label>
      </div>
    </div>
  </div>
  <div style="display:flex;">
    <div style="width:100%;margin-right:10px;">
      <div>Ng??y ph??t h??nh <span style=\"color:#777;\">(V?? d???: 2016)</span></div>
      <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
        <input class="mdl-textfield__input" type="text" name="media_released" id="adk_c">
        <label class="mdl-textfield__label" for="adk_c">Nh???p v??o ng??y ph??t h??nh...</label>
      </div>
    </div>
    <div style="width:100%;margin-left:10px;"></div>
  </div>
  <div>Di???n vi??n</div>
  <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
    <textarea class="mdl-textfield__input" type="text" name="media_actors" id="adg_a" style="min-height:80px;"></textarea>
    <label class="mdl-textfield__label" for="adg_a">Nh???p v??o ????y t??n c???a c??c di???n vi??n...</label>
  </div>
  <div>Nh???p v??o ????y t??n c???a ?????o di???n...</div>
  <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
    <textarea class="mdl-textfield__input" type="text" name="media_director" id="adg_b" style="min-height:80px;"></textarea>
    <label class="mdl-textfield__label" for="adg_b">Nh???p v??o ????y t??n c???a ?????o di???n...</label>
  </div>
  <div>Hosts <span style=\"color:#777;\">(cho ch????ng tr??nh truy???n h??nh)</span></div>
  <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
    <textarea class="mdl-textfield__input" type="text" name="media_leaders" id="adg_c" style="min-height:80px;"></textarea>
    <label class="mdl-textfield__label" for="adg_c">Nh???p v??o ????y t??n c???a hosts...</label>
  </div>
  <div>Th??ng tin kh??c</div>
  <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
    <textarea class="mdl-textfield__input" type="text" name="media_other_info" id="adg_d" style="min-height:80px;"></textarea>
    <label class="mdl-textfield__label" for="adg_d">Nh???p v??o ????y th??ng tin kh??c...</label>
  </div>
  <div style="margin-bottom:25px;">Thu???c t??nh phim</div>
  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="fkb_a">
    <input type="checkbox" name="media_properties[]" value="filter-speak" id="fkb_a" class="mdl-checkbox__input">
    <span class="mdl-checkbox__label">D???ch gi???ng n??i</span>
  </label>
  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="fkb_b">
    <input type="checkbox" name="media_properties[]" value="filter-subtitles" id="fkb_b" class="mdl-checkbox__input">
    <span class="mdl-checkbox__label">C?? ph??? ?????</span>
  </label>
  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="fkb_c">
    <input type="checkbox" name="media_properties[]" value="filter-uhd" id="fkb_c" class="mdl-checkbox__input">
    <span class="mdl-checkbox__label">Ch???t l?????ng cao (4K)</span>
  </label>
  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="fkb_d">
    <input type="checkbox" name="media_properties[]" value="filter-3d" id="fkb_d" class="mdl-checkbox__input">
    <span class="mdl-checkbox__label">Kh??ng gian (3D)</span>
  </label>
  <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="fkb_e">
    <input type="checkbox" name="media_properties[]" value="filter-360" id="fkb_e" class="mdl-checkbox__input">
    <span class="mdl-checkbox__label">To??n c???nh (360&deg;)</span>
  </label>
</div>
<div style="padding:20px;background-color:#ecf0f1;margin-top:15px;">T???p tin phim, trailer v?? b??a</div>
<div style="padding:20px;">
 <div>Nh???p url c???a ???nh b??a...</div>
 <div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
  <input class="mdl-textfield__input" type="text" name="media_cover" id="arm_a">
  <label class="mdl-textfield__label" for="arm_a">Nh???p url c???a ???nh b??a...</label>
</div>
<div>Nh???p url c???a ??o???n gi???i thi???u phim...</div>
<div class="mdl-textfield mdl-js-textfield" style="width:100% !important;margin-bottom:15px;">
  <input class="mdl-textfield__input" type="text" name="media_trailer" id="arm_b">
  <label class="mdl-textfield__label" for="arm_b">Nh???p url c???a ??o???n gi???i thi???u phim...</label>
</div>
<div>Lo???i url phim v?? video ???????c ph??n t??ch b???ng [div] ...</div>
<div class="mdl-textfield mdl-js-textfield" style="width:100% !important;">
  <textarea class="mdl-textfield__input" type="text" name="media_series" id="arm_c" style="min-height:80px;"></textarea>
  <label class="mdl-textfield__label" for="arm_c">Lo???i url phim v?? video ???????c ph??n t??ch b???ng [div] ...</label>
</div>
</div>
<div style="padding:0px 20px 20px 20px;display:flow-root;">
 <button type="submit" class="mdl-button mdl-js-button" style="background-color:#27ae60;color:#fff;float:right;">L??u</button>
</div>
</form>
</div>
</div>
</div>
</main>
</div>
<div id="bottom-toast" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div>
<input type="hidden" id="lang_popup_admin_confirm_user_delete" value="B???n c?? ch???c ch???n mu???n x??a ng?????i d??ng %a?">
<input type="hidden" id="lang_popup_admin_confirm_user_ban" value="B???n c?? ch???c ch???n mu???n c???m/h???y c???m ng?????i d??ng %a?">
<input type="hidden" id="lang_popup_admin_confirm_user_admin" value="B???n c?? ch???c ch???n mu???n ?????t/h???y ?????t nh??m qu???n tr??? vi??n cho ng?????i d??ng %a?">
<input type="hidden" id="lang_popup_admin_confirm_message_delete" value="B???n c?? ch???c ch???n mu???n x??a tin nh???n n??y?">
<input type="hidden" id="lang_popup_admin_confirm_all_messages_delete" value="B???n c?? ch???c ch???n mu???n x??a t???t c??? tin nh???n?">
<input type="hidden" id="lang_text_after_delete_messages" value="T???t c??? tin nh???n ???? b??? x??a...">
<input type="hidden" id="lang_toast_no_messages_to_delete" value="Kh??ng c?? g?? ????? x??a">
<input type="hidden" id="lang_popup_admin_confirm_movie_delete" value="B???n c?? ch???c ch???n mu???n x??a phim %a?">
<input type="hidden" id="lang_popup_admin_error_wrong_cover_img" value="Url b??a kh??ng h???p l??? ho???c ?????nh d???ng kh??ng ???????c h??? tr???!">
<script>
  var editor1;
  var editor2;
  var editor3;
  var editor4;
  ClassicEditor.create(document.querySelector('#editor1')).then(editor => {
    editor1 = editor
  }).catch(error => {
    console.error(error)
  });
  ClassicEditor.create(document.querySelector('#editor2')).then(editor => {
    editor2 = editor
  }).catch(error => {
    console.error(error)
  });
  ClassicEditor.create(document.querySelector('#editor3')).then(editor => {
    editor3 = editor
  }).catch(error => {
    console.error(error)
  });
  ClassicEditor.create(document.querySelector('#editor4')).then(editor => {
    editor4 = editor
  }).catch(error => {
    console.error(error)
  });
  function getEditorData(id) {
    if (id == 1) {
      return editor1.getData()
    } else if (id == 2) {
      return editor2.getData()
    } else if (id == 3) {
      return editor3.getData()
    } else if (id == 4) {
      return editor4.getData()
    }
  }
</script>
<?php
if(!isset($_SESSION['sitemap_updated'])){
	$_SESSION['sitemap_updated'] = true;
	echo "<script>$(document).ready(function(){setTimeout(function(){updateSitemapFile(false)},100);});</script>";
}
?>
<script src="styles/material.min.js"></script>
<script src="scripts/admin_functions.js"></script>
</body>
</html>
<?php
if ( $user_array[ 'verified' ] === "1" )$verified = "<span class='verified'></span>";
$joined = t( "Joined" ) . " " . timeago( $user_array[ 'joined' ], true, true );

if ( $user_array[ 'strikes' ] !== "0" ) {
  $away = 3 - $user_array[ 'strikes' ];
  if ( $user_array[ 'strikes' ] === "3" )echo "<div class='message error inline-message'>Your account currently has {$user_array['strikes']} strikes. Your account will soon be terminated.</div>";
  else echo "<div class='message warning inline-message'>Your account currently has {$user_array['strikes']} strike(s). You are $away strike(s) away from account termination.</div>";
}
?>
<div class="content-block centered-text"><br/>
  <div class="navigation-profile large-profile <?php echo $notallowed; ?>" data-background="/images/avatar/<?php echo $user_array['username']; ?>">
    <div class="edit-profile">
      <div class="edit-profile-action"><?php echo t("Edit"); ?></div>
    </div>
  </div>
  <input type="file" id="avatarFile" style="display: none;"/>
  <div class="upload-photo" style="display: none;">
    <div class="progress-text"><?php echo t("Uploading..."); ?></div>
    <div class="progress">
      <div class="indeterminate"></div>
      <div class="determinate" id="progressbar" style="display: none;width: 0%;"></div>
    </div>
  </div>
  <input type="hidden" id="edit-val-image" value="<?php echo $user_array['image']; ?>"/>
  <div class="profile-name"><?php echo $user_array['displayname']."$verified"; ?></div>
  <div class="profile-detail">@<?php echo $user_array['username']; ?></div>
  <div class="profile-item profile-joined inline-profile-item"><?php echo $joined; ?></div>
</div>
<div class="inline-menu <?php echo $notallowed; ?>">
  <ul>
    <?php
    $items = array(
      array(
        "id" => "account",
        "title" => "Account Details",
        "summary" => "Manage your Name, Username, Email, Bio, and more",
        "action" => "settings/account"
      ),
      array(
        "id" => "privacy",
        "title" => "Data & Account Privacy",
        "summary" => "Manage what followers and strangers can do and see about you.",
        "action" => "settings/privacy"
      ),
      array(
        "id" => "password",
        "title" => "Change Password",
        "summary" => "Change the password you use to login to your account.",
        "action" => "settings/reset"
      ),
      array(
        "id" => "localize",
        "title" => "Localization",
        "summary" => "Change your country and timezone.",
        "action" => "settings/localize"
      ),
      array(
        "id" => "language",
        "title" => "Language",
        "summary" => "Change the language you view Pengin in.",
        "action" => "settings/language"
      ),
      array(
        "id" => "sessions",
        "title" => "Login Activity & Sessions",
        "summary" => "Manage the devices that are logged into your account.",
        "action" => "settings/sessions"
      )
    );
    foreach ( $items as $item ) {
      $id = $item[ 'id' ];
      $action = $item[ 'action' ];
      $title = t( $item[ 'title' ] );
      $summary = t( $item[ 'summary' ] );
      echo "<li class='$id-item' data-action='$action'><div class='inline-title'>$title</div><div class='inline-summary'>$summary</div></li>";
    }
    ?>
  </ul>
</div>

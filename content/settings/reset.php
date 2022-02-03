<?php
if ( $user_session ) {
  ?>
<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="content-block">
  <h2><?php echo t("Change Password"); ?></h2>
  <p><?php echo t("No problem, let's change that password."); ?></p>
  <div class="message error" id="password-error" style="display: none;"></div>
</div>
<form id="change-password-form">
  <input type="hidden" name="action" value="resetpassword"/>
  <div class="edit-column-container" id="column-displayname">
    <div class="edit-column-label"><?php echo t("Current Password"); ?> <span class="red">*</span></div>
    <div class="edit-column-value">
      <div class="edit-column-change" id="change-displayname">
        <input type="password" placeholder="<?php echo t("Current Password"); ?>" name="password" data-require="true" autocomplete="off"/>
      </div>
    </div>
  </div>
  <div class="edit-column-container" id="column-displayname">
    <div class="edit-column-label"><?php echo t("New Password"); ?> <span class="red">*</span></div>
    <div class="edit-column-value">
      <div class="edit-column-change" id="change-displayname">
        <div class="mask-btn"></div>
        <input type="password" placeholder="<?php echo t("New Password"); ?>" name="newpassword" id="password" data-require="true"/>
      </div>
    </div>
  </div>
  <div class="content-block">
    <div class="form-btn-group">
      <button type="submit" class="stretch-btn large-btn"><?php echo t("Change Password"); ?></button>
    </div>
  </div>
</form>
<?php
} else header( "Location: /" );

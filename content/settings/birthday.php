<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="edit-column-container" id="column-birthday">
<div class="edit-column-label"><?php echo t("Birthday"); ?></div>
<div class="edit-column-value">
  <div class="column-static" id="column-value-birthday">
    <div><?php echo date("F j, Y",strtotime($user_array['birthday']))." ($user_age ".t('years old').")."; ?> 
      <!--<a href='javascript:;' data-action='settings/birthday'>Change</a></div><br/>
      <div class="input-context">You can only change your birthday once.</div>--> 
    </div>
  </div>
</div>

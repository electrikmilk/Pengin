<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js" defer></script>
<script>
window.location = "/login";
window.onbeforeunload = function() {
  return true;
};
</script>

<div class="account-splash">
  <div class="account-form-container">
    <h1><?php echo t("Create an account"); ?></h1>
    <p><?php echo t("Unlike other stuff in life, this is free. Fill out all the details below to create your account."); ?></p>
    <div class="message error" id="signup-error" style="display: none;">Error</div>
    <form id="signup-form">
      <input type="hidden" name="action" value="createuser"/>
      <div class="form-field">
        <div>
          <label><?php echo t("Your Name"); ?> <span class="red">*</span></label>
        </div>
        <div>
          <p class="input-context"><?php echo t( "displayname-description" ); ?></p>
          <input type="text" placeholder="<?php echo t("Your Name"); ?>" name="displayname" data-limit="50" data-require="true"/>
          <p class="input-context" id="limit-displayname">0 / 50</p>
        </div>
      </div>
      <div class="form-field">
        <div>
          <label><?php echo t("Username"); ?> <span class="red">*</span></label>
        </div>
        <div>
          <input type="text" placeholder="<?php echo t("Username"); ?>" id="username" name="username" data-limit="20" data-require="true"/>
          <br/>
          <p class="input-context" id="limit-username">0 / 20</p>
        </div>
      </div>
      <div class="form-field">
        <div>
          <label><?php echo t("Your Email"); ?> <span class="red">*</span></label>
        </div>
        <div>
          <input type="email" placeholder="<?php echo t("Email Address"); ?>" id="email" name="email" data-require="true"/>
        </div>
      </div>
      <div class="form-field">
        <div>
          <label><?php echo t("Birthday"); ?> <span class="red">*</span></label>
        </div>
        <div>
          <input type="date" placeholder="<?php echo t("Date of Birth"); ?>" id="bday" name="birthday" data-require="true"/>
        </div>
      </div>
      <div class="form-field">
        <div>
          <label><?php echo t("Password"); ?> <span class="red">*</span></label>
        </div>
        <div>
          <div class="mask-btn"></div>
          <input type="password" placeholder="<?php echo t("New Password"); ?>" id="password" name="password" data-require="true"/>
          <meter id="password-strength-meter" max="4" min="0" value="0" high="0" low="4" optimum="4"></meter>
          <div class="password-strength"><?php echo t("Use at least 8 characters, 1 number and 1 special character."); ?></div>
        </div>
      </div>
      <br/>
      <center>
        <div class="checkbox">
          <input type="checkbox" id="agree-guidelines" name="agree-guidelines"/>
          <label for="agree-guidelines"><?php echo t("I have read and agree to the"); ?> <a href="/about/cookies" target="_blank"><?php echo t("Cookie Policy"); ?></a>, <a href="/about/privacy" target="_blank"><?php echo t("Privacy Policy"); ?></a> <?php echo t("and"); ?> <a href="/about/terms" target="_blank"><?php echo t("Terms of Use"); ?></a>.</label>
        </div>
        <br/>
        <p class="input-context"><?php echo t("Beep-boop, boop-beep?"); ?></p>
        <br/>
        <div class="g-recaptcha" data-sitekey="6LdHRsAUAAAAAK-R2jgZZwF66bEbfgsWi9PSU6wY"></div>
      </center>
      <br/>
      <div class="form-btn-group">
        <button type="submit" class="large-btn"><?php echo t("Sign Up Now"); ?></button>
        <br/>
        <p><?php echo t("Already have an account?"); ?> <a href="/login"><?php echo t("Login"); ?></a>.</p>
      </div>
    </form>
  </div>
</div>
<?php
header( "Location: /login" );
?>

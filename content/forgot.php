<div class="account-splash">
  <?php if(!$ismobile)echo "<br/>"; ?>
  <div class="account-form-container">
    <h1><?php echo t("Forgot Account Password"); ?></h1>
    <p>Wait, I remembered it, <a href="/login">go back to login</a>.</p>
    <div class="message error" id="forgot-error" style="display: none;"><?php echo t("Error"); ?></div>
    <div class="message success" id="forgot-success" style="display: none;"><?php echo t("Success"); ?></div>
    <form id="reset-password-form" method="POST">
      <input type="hidden" name="action" value="sendpasswordlink"/>
      <div class="form-field">
        <div>
          <label><?php echo t("Your Email"); ?></label>
        </div>
        <div>
          <input type="email" id="email" name="email" placeholder="<?php echo t("Email Address"); ?>" data-require="true"/>
        </div>
      </div>
      <br/>
      <center>
        <p class="input-context">Beep-boop, boop-beep?</p>
        <br/>
        <div class="g-recaptcha" data-sitekey="6LdHRsAUAAAAAK-R2jgZZwF66bEbfgsWi9PSU6wY"></div>
      </center>
      <br/>
      <div class="form-btn-group">
        <button type="submit" class="large-btn"><?php echo t("Send Reset Link"); ?></button>
        <br/>
        <br/>
      </div>
    </form>
  </div>
</div>

<div class="account-splash">
  <blockquote class="facts"><b><?php echo t("Penguin Fact:"); ?></b> <?php echo facts(); ?></blockquote>
  <?php if(!$ismobile)echo "<br/>"; ?>
  <div class="account-form-container">
    <h1><?php echo t("Login to your account"); ?></h1>
    <p>Forgot your password? It's cool, <a href="/forgot-password">get a link to reset your password</a>.</p>
    <div class="message error" id="login-error" style="display: none;"><?php echo t("Error"); ?></div>
    <form id="login-form" method="POST">
      <input type="hidden" name="action" value="startsession"/>
      <div class="form-field">
        <div>
          <label><?php echo t("Your Email"); ?></label>
        </div>
        <div>
          <input type="email" id="email" name="email" placeholder="<?php echo t("Email Address"); ?>" data-require="true"/>
        </div>
      </div>
      <div class="form-field">
        <div>
          <label><?php echo t("Password"); ?></label>
        </div>
        <div>
          <div class="mask-btn" tooltip="Show Password" data-placement="top"></div>
          <input type="password" name="password" id="password" placeholder="<?php echo t("Password"); ?>" data-require="true"/>
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
        <button type="submit" class="large-btn"><?php echo t("Log Me In"); ?></button>
        <br/>
        <br/>
        <!-- <p>Don't have an account yet? It's cool, <a href="/signup">sign up for free</a>.</p> --> 
        <br/>
      </div>
    </form>
  </div>
</div>

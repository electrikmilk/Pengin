<div class="dropdowns-container">
  <div class="dropdown" id="post-menu">
    <div class="dropdown-prompt"><?php echo t("Post"); ?></div>
    <div class="opts-container post-opts-container"></div>
  </div>
  <div class="dropdown" id="profile-menu">
    <div class="dropdown-prompt"><?php echo t("Account"); ?></div>
    <div class="opts-container profile-opts-container"></div>
  </div>
  <div class="dropdown" id="more-menu">
    <div class="dropdown-prompt"><?php echo t("More"); ?></div>
    <div class="opts-container more-opts-container">
      <ul>
        <li class="logout-opt" onclick="confirmLogout();"><?php echo t("Log Out"); ?><span><?php echo t("logout-description"); ?></span></li>
      </ul>
    </div>
  </div>
  <div class="dropdown" id="block-menu">
    <div class="dropdown-prompt"><?php echo t("Limit Interactions"); ?></div>
    <div class="opts-container block-opts-container"></div>
  </div>
  <div class="dropdown" id="new-menu">
    <div class="dropdown-prompt"><?php echo t( "Create" ); ?></div>
    <div class="opts-container create-opts-container">
      <ul>
        <li class="post-opt <?php echo $notallowed; ?>" data-modal="new-post"><?php echo t("Create Post"); ?><span><?php echo t("create-post-description"); ?></span></li>
        <li class="threads-opt <?php echo $notallowed; ?>" data-action="create-thread"><?php echo t("Create Public Thread"); ?><span><?php echo t("create-public-thread-description"); ?></span></li>
        <!-- <li class="messages-opt <?php echo $notallowed; ?>" data-modal="new-dm"><?php echo t("Create Direct Message"); ?><span><?php echo t("create-dm-description"); ?></span></li> -->
      </ul>
    </div>
  </div>
  <div class="dropdown" id="convo-menu">
    <div class="dropdown-prompt"><?php echo t( "Conversation" ); ?></div>
    <div class="opts-container convo-opts-container"></div>
  </div>
</div>

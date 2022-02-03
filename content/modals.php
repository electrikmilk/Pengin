<div class="modals-container">
  <?php
  if ($user_session) {
      ?>
  <div class="modal dialog" id="limit">
    <h2><?php echo t("Time Limit"); ?></h2>
    <p><?php echo t("time-limit-description"); ?></p>
    <form onsubmit="event.preventDefault();">
      <div class="message error" id="limit-error" style="display: none;"><?php echo t("Error"); ?></div>
      <div class="message" id="limit-status">
        <div class="load small"></div>
      </div>
      <select id="limit-range">
        <?php
        $minutes = array( 5, 15, 30 );
      $hours = array( 1, 2, 3, 4, 5, 6 );
      $m = t('minutes');
      $h = t('hours');
      foreach ($minutes as $minute) {
          if ($minute !== 1) {
              $s = "s";
          }
          echo "<option value='$minute minute$s'>$minute $m</option>";
      }
      foreach ($hours as $hour) {
          if ($hour !== 1) {
              $s = "s";
          }
          echo "<option value='$hour hour$s'>$hour $h</option>";
      } ?>
        <option value="none"><?php echo t("Clear Limit"); ?></option>
      </select>
      <div class="checkbox">
        <input type="checkbox" id="logout" name="logout"/>
        <label for="logout"><?php echo t("logout-when"); ?></label>
      </div>
      <br/>
      <div class="modal-btn-group">
        <button type="submit" class="set-limit-btn"><?php echo t("Set Limit"); ?></button>
        <button type="reset" class="grey-btn" data-modal="limit"><?php echo t("Cancel"); ?></button>
      </div>
    </form>
  </div>
  <div class="modal dialog centerme" id="nsfw">
    <h2><?php echo t("nsfw-title"); ?></h2>
    <p><?php echo t("nsfw-message"); ?></p>
    <br/>
    <button type="submit" class="large-btn" data-modal="nsfw"><?php echo t("Proceed"); ?></button>
    <div class="hidden-btn" data-action="discuss" data-modal="nsfw"><?php echo t("Take me away from here"); ?></div>
  </div>
  <div class="modal dialog centerme" id="unplug">
    <div class="big-icon big-icon-unplug"></div>
    <h2><?php echo t("Time to Unplug"); ?></h2>
    <p><?php echo t("timelimit-message"); ?><br/>
      <?php echo t("break-message"); ?><br/>
      <br/>
      <?php echo t("close-break-message"); ?></p>
    <hr/>
    <div class="hidden-btn" data-modal="unplug"><?php echo t("Never mind, go back."); ?></div>
  </div>
  <div class="modal" id="new-post">
    <div class="modal-header">
      <div>
        <h2><?php echo t("New Post"); ?></h2>
      </div>
      <div>
        <div class="modal-close cancel-post-btn" data-modal="new-post" onclick="resetPost();" tooltip="<?php echo t("Close"); ?>" data-placement="top">&times;</div>
      </div>
    </div>
    <div class="message error" id="newpost-error" style="display: none;"><?php echo t("Error"); ?></div>
    <form id="new-post-form">
      <div class="ref-post"></div>
      <div class="new-post-grid">
        <div class="navigation-profile" data-background="/images/avatar/<?php echo $user_array['username']; ?>"></div>
        <textarea name="content" id="content" placeholder="<?php echo t('new-post-prompt'); ?>" data-limit="500" style="height: 100px;" data-emoji="true" autofocus></textarea>
      </div>
      <input type="hidden" name="image" id="new-post-image"/>
      <div class="image-upload">
        <input type="file" id="postUpload" accept="image/*"/>
      </div>
      <div class="upload-photo" style="display: none;">
        <div class="progress-text"><?php echo t("Uploading..."); ?></div>
        <div class="progress">
          <div class="indeterminate"></div>
          <div class="determinate" id="progressbar" style="display: none;width: 0%;"></div>
        </div>
      </div>
      <div class="attach-media"></div>
      <div class="privacy-message post-privacy">...</div>
      <div class="privacy-message reply-privacy"><?php echo t("reply-privacy-message"); ?></div>
      <select name="public" id="post-privacy">
        <option value="0" <?php echo $postprivate; ?>><?php echo t("Private"); ?></option>
        <option value="1" <?php echo $postpublic; ?>><?php echo t("Public"); ?></option>
      </select>
      <div class="post-btns-group">
        <p class="input-context" id="limit-content">0 / 500</p>
        <button type="submit" class="icon-btn send-btn"><?php echo t("Send"); ?></button>
        <button type="button" class="grey-btn icon-btn icon-only-btn gif-btn" onclick="modalSwitch('new-post','attach-gif');" tooltip="<?php echo t("Add GIF"); ?>" data-placement="top"></button>
        <button type="button" class="grey-btn icon-btn icon-only-btn photo-btn" tooltip="<?php echo t("Add Photo"); ?>" data-placement="top"></button>
        <button type="button" class="grey-btn icon-btn icon-only-btn emoji-btn" tooltip="<?php echo t("Insert Emojis"); ?>" data-placement="top"></button>
      </div>
    </form>
  </div>
  <div class="modal" id="attach-gif">
    <div class="modal-header">
      <div>
        <h2><?php echo t("add-gif-title"); ?></h2>
      </div>
      <div>
        <div class="modal-close" onclick="modalSwitch('attach-gif','new-post');" tooltip="<?php echo t("Close"); ?>" data-placement="top">&times;</div>
      </div>
    </div>
    <div class="message error" id="gifs-error" style="display: none;"><?php echo t("Error"); ?></div>
    <input type="search" placeholder="Search Giphy for GIFs..." id="gif-search" autofocus/>
    <div class="gif-results"></div>
    <br/>
    <p class="input-context" style="margin: 0;"><?php echo t("Powered by"); ?> Giphy</p>
  </div>
  <div class="modal" id="edit-post">
    <h2><?php echo t("Edit Post"); ?></h2>
    <div class="message warning"><?php echo t("edit-post-warning"); ?></div>
    <input type="text" id="edit-title" placeholder="<?php echo t("Edit your thread title"); ?>..."/>
    <textarea id="edit-content" placeholder="<?php echo t("Edit your post"); ?>..." style="height: 100px;"></textarea>
    <p class="input-context"><?php echo t("edit-photos-message"); ?> <a href="javascript:;" data-action="help/edit-photos"><?php echo t("Learn more"); ?></a></p>
    <div class="modal-btn-group">
      <button type="submit" onclick="savePost();" data-modal="edit-post"><?php echo t("Save"); ?></button>
      <button type="reset" class="grey-btn" data-modal="edit-post"><?php echo t("Cancel"); ?></button>
    </div>
  </div>
  <div class="modal dialog" id="delete-post">
    <h2><?php echo t("Delete Post"); ?></h2>
    <p><?php echo t("delete-post-description"); ?></p>
    <div class="modal-btn-group">
      <button type="submit" class="red-btn" onclick="deletePost();" data-modal="delete-post"><?php echo t("Yes"); ?></button>
      <button type="reset" class="grey-btn" data-modal="delete-post"><?php echo t("Cancel"); ?></button>
    </div>
  </div>
  <div class="modal" id="report-comment">
    <h2>Report Post</h2>
    <div class="message error" id="report-error" style="display: none;">Error</div>
    <p>What's wrong with this post?</p>
    <form id="report-comment-form">
      <div class="radio">
        <input type="radio" name="reason" id="offensive" value="offensive"/>
        <label for="offensive">It's offensive</label>
      </div>
      <div class="radio">
        <input type="radio" name="reason" id="abusive" value="abusive"/>
        <label for="abusive">It's abusive</label>
      </div>
      <div class="radio">
        <input type="radio" name="reason" id="guidelines" value="guidelines"/>
        <label for="guidelines">It breaks community guidelines</label>
      </div>
      <div class="radio">
        <input type="radio" name="reason" id="spam" value="spam"/>
        <label for="spam">It's spam</label>
      </div>
      <div class="radio">
        <input type="radio" name="reason" id="broken" value="broken"/>
        <label for="broken">Something about the post isn't showing up correctly</label>
      </div>
      <div class="modal-btn-group">
        <button type="submit">Submit</button>
        <button type="reset" class="cancel-btn" onclick="modal('report-comment');">Cancel</button>
      </div>
    </form>
  </div>
  <div class="modal" id="reported-comment">
    <h2>Post Reported</h2>
    <div class="message error" id="report-error" style="display: none;"><?php echo t("Error"); ?></div>
    <p>Thank you for reporting this to us. We'll review this comment and the authors account and take action if they've broken community guidelines.</p>
    <div class="modal-btn-group">
      <button type="reset" onclick="modal('reported-comment');">OK</button>
    </div>
  </div>
  <div class="modal" id="new-dm">
    <div class="modal-header">
      <div>
        <h2><?php echo t("New Conversation"); ?></h2>
      </div>
      <div>
        <div class="modal-close" onclick="modals('new-dm'),resetDM();" tooltip="<?php echo t("Close"); ?>" data-placement="top">&times;</div>
      </div>
    </div>
    <p><?php echo t("new-conversation-message"); ?></p>
    <input type="hidden" id="convo-users"/>
    <div class="message error" id="usersearch-error" style="display: none;"><?php echo t("Error"); ?></div>
    <input type="search" placeholder="Search for people..." id="user-search" autofocus/>
    <div class="user-list"></div>
    <br/>
    <button type="submit" class="stretch-btn new-convo-btn" disabled><?php echo t("Create Conversation"); ?></button>
  </div>
  <?php
  }
  ?>
  <div class="modal" id="set-lang">
    <div class="modal-header">
      <div>
        <h2><?php echo t("Set Language"); ?></h2>
      </div>
      <div>
        <div class="modal-close" data-modal="set-lang" tooltip="<?php echo t("Close"); ?>" data-placement="top">&times;</div>
      </div>
    </div>
    <p><?php echo t("language-message"); ?></p>
    <div class="lang-list"></div>
    <br/>
    <p>Support for more languages coming soon.</p>
    <input type="hidden" id="edit-val-language" value="<?php echo $user_lang; ?>"/>
  </div>
</div>

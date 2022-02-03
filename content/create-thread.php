<?php

$topics = mysqli_query( $connect, "select * from data.topics order by title asc" );
while ( $topic = mysqli_fetch_array( $topics ) ) {
  unset( $selected );
  $id = $topic[ 'id' ];
  $title = t( $topic[ 'title' ] );
  $topColor = $topic[ 'color' ];
  $simple = $topic[ 'url' ];
  $desc = $topic[ 'description' ];
  if ( $_REQUEST[ 'topic' ] === $simple )$selected = " selected";
  $topic_list .= "<option value='$id'$selected>$title &mdash; $desc</option>";
}
?>
<div class="content-block">
  <h1><?php echo t("Create Public Thread"); ?></h1>
  <p><?php echo t("create-thread-description"); ?></p>
  <div class="privacy-message"><?php echo t("thread-privacy-message"); ?> (<a href="help/thread-block" target="_blank"><?php echo t("thread-privacy-link"); ?></a>).</div>
  <hr/>
  <div class="message error" id="newthread-error" style="display: none;"><?php echo t("Error"); ?></div>
  <form id="new-thread-form">
    <div class="form-field">
      <div>
        <label><?php echo t("Title"); ?></label>
      </div>
      <div>
        <input type="text" placeholder="Thread Title/Subject" name="title" data-limit="100" data-require="true" autofocus/>
        <p class="input-context" id="limit-title">0 / 100</p>
      </div>
    </div>
    <div class="form-field">
      <div>
        <label><?php echo t("Topic/Category"); ?></label>
      </div>
      <div>
        <select name="topic" style="width: 100%;">
          <optgroup label="Choose a topic..."> <?php echo $topic_list; ?> </optgroup>
        </select>
        <p class="input-context"><?php echo t("choose-topic-prompt"); ?></p>
      </div>
    </div>
    <div class="form-field">
      <div>
        <label><?php echo t("Description"); ?></label>
      </div>
      <div>
        <p><?php echo t("thread-description-prompt"); ?></p>
        <textarea placeholder="<?php echo t("What do you wanna have a conversation about?"); ?>" id="content" name="content" data-limit="500" data-require="true"></textarea>
        <br/>
        <p class="input-context" id="limit-content">0 / 500</p>
      </div>
    </div>
    <div class="form-field">
      <div>
        <label><?php echo t("Tags"); ?></label>
      </div>
      <div>
        <input type="text" placeholder="jazz, superheroes, villians, comedy" name="tags"/>
        <div class="input-context"><?php echo t("Separate by commas."); ?></div>
      </div>
    </div>
    <div class="form-field">
      <div>
        <label><?php echo t("Mature (NSFW)"); ?></label>
      </div>
      <div>
        <div class="checkbox">
          <input type="checkbox" id="isnsfw" name="nsfw"/>
          <label for="isnsfw"><?php echo t("Discusses mature content"); ?></label>
        </div>
        <div class="input-context" style="margin: 5px 10px;"><?php echo t("nsfw-warning"); ?> <a href="help/nsfw" target="_blank"><?php echo t("nsfw-warning-link"); ?></a></div>
      </div>
    </div>
    <br/>
    <div class="form-btn-group">
      <button type="submit" class="large-btn"><?php echo t("Create Thread"); ?></button>
    </div>
    <br/>
  </form>
</div>

<div class="back-link" data-action="settings/main"><?php echo t("Back"); ?></div>
<div class="content-block">
  <h2><?php echo t('Localization'); ?></h2>
  <p><?php echo t('localize-description'); ?></p>
  <div class="privacy-message"><?php echo t("localize-privacy-description"); ?> <a href='javascript:;' data-action='help/privacy'><?php echo t("Learn more"); ?></a></div>
</div>
<hr/>
<?php

function getColumn( $column ) {
  if ( $column === "country" ) {
    $relabel = "Country";
  } else if ( $column === "timezone" ) {
    $relabel = "Timezone";
  }
  if ( $relabel ) {
    return t( $relabel );
  } else {
    return false;
  }
}

function handleVal( $column, $value ) {
  if ( !$value || $value === "undefined" ) {
    echo "<span class='input-context'>Nothing here yet...</span>";
  } else {
    if ( strpos( $value, "@" ) !== false ) {
      return $value;
    } else {
      if ( $column === "extraurl" || $column === "des" ) {
        return nl2br( linkify( $value ) );
      } else if ( $column === "user_name" ) {
        return "<a href='https://pengin.app/@$value' target='_blank'>pengin.app/@$value</a>";
      } else {
        if ( $value = "0" )$value = "Yes";
        if ( $value = "1" )$value = "No";
        return $value;
      }
    }
  }
}
$result = mysqli_query( $connect, "select * from data.users where id = '$user_session'" );
if ( mysqli_num_rows( $result ) > 0 ) {
  while ( $row = mysqli_fetch_assoc( $result ) ) {
    foreach ( $row as $col => $val ) {
      if ( $val === "undefined" )$val = "";
      $column = getColumn( $col );
      if ( $column !== false ) {
        ?>
<div class="edit-column-container" id="column-<?php echo $col; ?>">
  <div class="edit-column-label">
    <?php
    echo getColumn( $col );
    ?>
  </div>
  <div class="edit-column-value">
    <div class="edit-column-change" id="change-<?php echo $col; ?>">
      <form onsubmit="event.preventDefault(),saveEdit('<?php echo $col; ?>');">
        <?php
        if ( $col === "country" ) {
          ?>
        <div class="privacy-message"><?php echo t("country-privacy"); ?></div>
        <select id="edit-val-<?php echo $col; ?>" onchange="saveEdit('<?php echo $col; ?>',true);">
          <?php
          foreach ( $countries as $code => $country ) {
            unset( $current );
            if ( $val === $code )$current = "selected";
            $country = t( $country );
            echo "<option value='$code' $current>$country</option>";
          }
          ?>
        </select>
        <?php
        } else if ( $col === "timezone" ) {
          ?>
        <div class="privacy-message"><?php echo t("timezone-privacy"); ?></div>
        <select id="edit-val-<?php echo $col; ?>" onchange="saveEdit('<?php echo $col; ?>',true);">
          <?php
          foreach ( $timezones as $identifier ) {
            unset( $current );
            if ( $val === $identifier )$current = "selected";
            $identifier = t( $identifier );
            echo "<option $current>$identifier</option>";
          }
          ?>
        </select>
        <?php
        } else {
          ?>
        <input type="text" placeholder="<?php echo $placeholder; ?>" value="<?php echo $val; ?>" id="edit-val-<?php echo $col; ?>" onchange="saveEdit('<?php echo $col; ?>',true);"/>
        <?php
        }
        ?>
      </form>
    </div>
  </div>
</div>
<?php
}
}
}
}

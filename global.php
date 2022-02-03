<?php

header( "X-Frame-Options: DENY" );
header( "Content-Security-Policy: frame-ancestors 'none'", false );

// Page queries, what page to load from /content/ in index.php
$page = $_GET[ 'page' ];
$folder = $_GET[ 'folder' ];

// Autoload Classes
spl_autoload_register( function ( $className ) {
  $url = $_SERVER[ 'REQUEST_URI' ];
  $path = $_SERVER[ 'DOCUMENT_ROOT' ] . "/classes/";
  $className = str_replace( "\\", "/", $className );
  $class_path = "$path$className.php";
  if ( !file_exists( $class_path ) ) return false;
  require_once $class_path;
} );

require_once "database.php";

// Timezone default
setTimezone( "America/Indiana/Indianapolis" );

// User data
$session_id = Cookie::get( "session" );
if ( $session_id ) {
  $session = dataArray( "sessions", $session_id, "token" );
  if ( $session ) {
    $user_array = dataArray( "users", $session[ 'user_id' ], "id" );
    $user_session = $user_array[ 'id' ];
    $user_age = date( "Y" ) - date( "Y", strtotime( $user_array[ 'birthday' ] ) );
    $user_loc = $user_array[ 'country' ];
    $user_tz = $user_array[ 'timezone' ];
    $user_lang = $user_array[ 'language' ];
    $user_locale = $user_array[ 'locale' ];
    if ( $user_array[ 'private' ] !== "0" )$postpublic = "selected";
    else $postprivate = "selected";
    setTimezone( $user_tz );
    setlocale( LC_TIME, $user_locale . ".UTF-8" );
    if ( $user_array[ 'status' ] !== "0" )$notallowed = "disabled";
  } else Cookie::delete( "session" );
}

// Developer
if ( $user_array[ 'id' ] === "3" || $user_array[ 'id' ] === "2" )$dev = true;

// Language Localization
if ( !$user_lang )$lang = "en_us";
else $lang = $user_lang;
$localize = json_decode( file_get_contents( "translations/$lang.json" ), true );
$c2l = array( "US" => "en_us", "DE" => "de", "RU" => "ru", "GB" => "en_uk", "AU" => "en_au", "CA" => "en_ca", "FR" => "fr", "IT" => "it", "JP" => "jp", "KR" => "kr", "CN" => "zh_cn" );

// Localization data
$countries = array( "AD" => "Andorra", "AE" => "United Arab Emirates", "AF" => "Afghanistan", "AG" => "Antigua and Barbuda", "AI" => "Anguilla", "AL" => "Albania", "AM" => "Armenia", "AO" => "Angola", "AQ" => "Antarctica", "AR" => "Argentina", "AS" => "American Samoa", "AT" => "Austria", "AU" => "Australia", "AW" => "Aruba", "AX" => "Åland Islands", "AZ" => "Azerbaijan", "BA" => "Bosnia and Herzegovina", "BB" => "Barbados", "BD" => "Bangladesh", "BE" => "Belgium", "BF" => "Burkina Faso", "BG" => "Bulgaria", "BH" => "Bahrain", "BI" => "Burundi", "BJ" => "Benin", "BL" => "Saint Barthélemy", "BM" => "Bermuda", "BN" => "Brunei Darussalam", "BO" => "Bolivia", "BQ" => "Bonaire, Sint Eustatius and Saba", "BR" => "Brazil", "BS" => "Bahamas", "BT" => "Bhutan", "BV" => "Bouvet Island", "BW" => "Botswana", "BY" => "Belarus", "BZ" => "Belize", "CA" => "Canada", "CC" => "Cocos (Keeling) Islands", "CD" => "Congo", "CF" => "Central African Republic", "CG" => "Congo", "CH" => "Switzerland", "CI" => "Côte d'Ivoire", "CK" => "Cook Islands", "CL" => "Chile", "CM" => "Cameroon", "CN" => "China", "CO" => "Colombia", "CR" => "Costa Rica", "CU" => "Cuba", "CV" => "Cabo Verde", "CW" => "Curaçao", "CX" => "Christmas Island", "CY" => "Cyprus", "CZ" => "Czechia", "DE" => "Germany", "DJ" => "Djibouti", "DK" => "Denmark", "DM" => "Dominica", "DO" => "Dominican Republic", "DZ" => "Algeria", "EC" => "Ecuador", "EE" => "Estonia", "EG" => "Egypt", "EH" => "Western Sahara", "ER" => "Eritrea", "ES" => "Spain", "ET" => "Ethiopia", "FI" => "Finland", "FJ" => "Fiji", "FK" => "Falkland Islands [Malvinas]", "FM" => "Micronesia (Federated States of)", "FO" => "Faroe Islands", "FR" => "France", "GA" => "Gabon", "GB" => "United Kingdom", "GD" => "Grenada", "GE" => "Georgia", "GF" => "French Guiana", "GG" => "Guernsey", "GH" => "Ghana", "GI" => "Gibraltar", "GL" => "Greenland", "GM" => "Gambia", "GN" => "Guinea", "GP" => "Guadeloupe", "GQ" => "Equatorial Guinea", "GR" => "Greece", "GS" => "South Georgia and the South Sandwich Islands", "GT" => "Guatemala", "GU" => "Guam", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HK" => "Hong Kong", "HM" => "Heard Island and McDonald Islands", "HN" => "Honduras", "HR" => "Croatia", "HT" => "Haiti", "HU" => "Hungary", "ID" => "Indonesia", "IE" => "Ireland", "IL" => "Israel", "IM" => "Isle of Man", "IN" => "India", "IO" => "British Indian Ocean Territory", "IQ" => "Iraq", "IR" => "Iran", "IS" => "Iceland", "IT" => "Italy", "JE" => "Jersey", "JM" => "Jamaica", "JO" => "Jordan", "JP" => "Japan", "KE" => "Kenya", "KG" => "Kyrgyzstan", "KH" => "Cambodia", "KI" => "Kiribati", "KM" => "Comoros", "KN" => "Saint Kitts and Nevis", "KP" => "North Korea", "KR" => "South Korea", "KW" => "Kuwait", "KY" => "Cayman Islands", "KZ" => "Kazakhstan", "LA" => "Lao People's Democratic Republic", "LB" => "Lebanon", "LC" => "Saint Lucia", "LI" => "Liechtenstein", "LK" => "Sri Lanka", "LR" => "Liberia", "LS" => "Lesotho", "LT" => "Lithuania", "LU" => "Luxembourg", "LV" => "Latvia", "LY" => "Libya", "MA" => "Morocco", "MC" => "Monaco", "MD" => "Moldova", "ME" => "Montenegro", "MF" => "Saint Martin (French)", "MG" => "Madagascar", "MH" => "Marshall Islands", "MK" => "Republic of North Macedonia", "ML" => "Mali", "MM" => "Myanmar", "MN" => "Mongolia", "MO" => "Macao", "MP" => "Northern Mariana Islands", "MQ" => "Martinique", "MR" => "Mauritania", "MS" => "Montserrat", "MT" => "Malta", "MU" => "Mauritius", "MV" => "Maldives", "MW" => "Malawi", "MX" => "Mexico", "MY" => "Malaysia", "MZ" => "Mozambique", "NA" => "Namibia", "NC" => "New Caledonia", "NE" => "Niger", "NF" => "Norfolk Island", "NG" => "Nigeria", "NI" => "Nicaragua", "NL" => "Netherlands", "NO" => "Norway", "NP" => "Nepal", "NR" => "Nauru", "NU" => "Niue", "NZ" => "New Zealand", "OM" => "Oman", "PA" => "Panama", "PE" => "Peru", "PF" => "French Polynesia", "PG" => "Papua New Guinea", "PH" => "Philippines", "PK" => "Pakistan", "PL" => "Poland", "PM" => "Saint Pierre and Miquelon", "PN" => "Pitcairn", "PR" => "Puerto Rico", "PS" => "Palestine", "PT" => "Portugal", "PW" => "Palau", "PY" => "Paraguay", "QA" => "Qatar", "RE" => "Réunion", "RO" => "Romania", "RS" => "Serbia", "RU" => "Russia", "RW" => "Rwanda", "SA" => "Saudi Arabia", "SB" => "Solomon Islands", "SC" => "Seychelles", "SD" => "Sudan", "SE" => "Sweden", "SG" => "Singapore", "SH" => "Saint Helena, Ascension and Tristan da Cunha", "SI" => "Slovenia", "SJ" => "Svalbard and Jan Mayen", "SK" => "Slovakia", "SL" => "Sierra Leone", "SM" => "San Marino", "SN" => "Senegal", "SO" => "Somalia", "SR" => "Suriname", "SS" => "South Sudan", "ST" => "Sao Tome and Principe", "SV" => "El Salvador", "SX" => "Sint Maarten (Dutch)", "SY" => "Syrian Arab Republic", "SZ" => "Eswatini", "TC" => "Turks and Caicos Islands", "TD" => "Chad", "TF" => "French Southern Territories", "TG" => "Togo", "TH" => "Thailand", "TJ" => "Tajikistan", "TK" => "Tokelau", "TL" => "Timor-Leste", "TM" => "Turkmenistan", "TN" => "Tunisia", "TO" => "Tonga", "TR" => "Turkey", "TT" => "Trinidad and Tobago", "TV" => "Tuvalu", "TW" => "Taiwan", "TZ" => "Tanzania", "UA" => "Ukraine", "UG" => "Uganda", "UM" => "U.S. Outlying Islands", "US" => "United States of America", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VA" => "Holy See", "VC" => "Saint Vincent and the Grenadines", "VE" => "Venezuela (Bolivarian Republic of)", "VG" => "Virgin Islands (British)", "VI" => "Virgin Islands (U.S.)", "VN" => "Vietnam", "VU" => "Vanuatu", "WF" => "Wallis and Futuna", "WS" => "Samoa", "YE" => "Yemen", "YT" => "Mayotte", "ZA" => "South Africa", "ZM" => "Zambia", "ZW" => "Zimbabwe" );
$timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
$languages = array();
$list = json_decode( file_get_contents( "translations/list.json" ), true );
ksort( $list );
foreach ( $list as $item => $details ) {
  $languages[ $details[ 'code' ] ] = $item;
}

// .webp support
if ( strpos( $_SERVER[ 'HTTP_ACCEPT' ], 'image/webp' ) !== false || strpos( $_SERVER[ 'HTTP_USER_AGENT' ], ' Chrome/' ) !== false )$webp = true; // detect webp support, serve webp if low res true and webp is supported
else $webp = false; // serve the original file or a downsized version if requested

// Mobile Detection
$useragent = $_SERVER[ 'HTTP_USER_AGENT' ];
if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|ipad|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) )$ismobile = "true";
if ( preg_match( '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent ) || preg_match( '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr( $useragent, 0, 4 ) ) )$mobilenotipad = "true";

// User Information (browser name/version, basic platform name), include detect.php to get futher details about the OS version and name
function getBrowser( $userAgent = false ) {
  if ( $userAgent === false )$userAgent = $_SERVER[ 'HTTP_USER_AGENT' ];
  $bname = 'Unknown';
  $platform = 'Unknown';
  $version;
  if ( preg_match( '/linux/i', $userAgent ) )$platform = "Linux";
  else if ( preg_match( '/macintosh|mac os x/i', $userAgent ) )$platform = "Mac";
  else if ( preg_match( '/windows|win32/i', $userAgent ) )$platform = "Windows";
  if ( preg_match( '/MSIE/i', $userAgent ) && !preg_match( '/Opera/i', $userAgent ) || strpos( $userAgent, 'trident/7.0' ) !== false && strpos( $userAgent, 'rv:11.0' ) !== false ) {
    $bname = 'Internet Explorer';
    $ub = "MSIE";
    $search = "/MSIE(.*)/i";
  } else if ( preg_match( '/Edge/i', $userAgent ) ) {
    $bname = 'Microsoft Edge';
    $ub = "Edge";
    $search = "/edge(.*)/i";
  } else if ( preg_match( '/Firefox/i', $userAgent ) ) {
    $bname = 'Mozilla Firefox';
    $ub = "Firefox";
    $search = "/Firefox(.*)/i";
  } else if ( preg_match( '/Chrome/i', $userAgent ) ) {
    $bname = 'Google Chrome';
    $ub = "Chrome";
    $search = "/chrome(.*)/i";
  } elseif ( preg_match( '/Safari/i', $userAgent ) ) {
    $bname = 'Apple Safari';
    $ub = "Safari";
    $search = "/version(.*)/i";
  } else if ( preg_match( '/Opera/i', $userAgent ) ) {
    $bname = 'Opera';
    $ub = "Opera";
  } else if ( preg_match( '/Netscape/i', $userAgent ) ) {
    $bname = 'Netscape';
    $ub = "Netscape";
  }
  preg_match_all( $search, $userAgent, $match );
  switch ( $ub ) {
    case "Firefox":
      $version = str_replace( "/", "", $match[ 1 ][ 0 ] );
      break;
    case "MSIE":
      $version = substr( $match[ 1 ][ 0 ], 0, 4 );
      break;
    case "Edge":
      $version = substr( $match[ 1 ][ 0 ], 1, 4 );
      break;
    case "Opera":
      $version = str_replace( "/", "", substr( $match[ 1 ][ 0 ], 0, 5 ) );
      break;
    case "Chrome":
      $version = substr( $match[ 1 ][ 0 ], 1, 4 );
      break;
    case "Safari":
      $version = substr( $match[ 1 ][ 0 ], 1, 4 );
      break;
  }
  $ub = strtolower( $ub );
  return array(
    'userAgent' => $userAgent,
    'browser' => $ub,
    'fullbrowser' => $bname,
    'version' => $version,
    'platform' => $platform
  );
}

$agent = getBrowser();
$browser = $agent[ 'browser' ]; // for easy exclusion (eg. if $browser === 'firefox')
$browser_name = $agent[ 'fullbrowser' ]; // full name of current browser
$browser_version = $agent[ 'version' ]; // version of current browser
$platform = $agent[ 'platform' ]; // basic platform name (Windows, Mac, Linux)

// Misc string functions
function randString( $length ) { // create random string (for creating random filenames, identifiers, etc.)
  $char = str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789" );
  for ( $i = 0, $rand = '', $l = strlen( $char ) - 1; $i < $length; $i++ ) {
    $rand .= $char {
      mt_rand( 0, $l )
    };
  }
  return $rand;
}

function setTimezone( $identifier ) {
  date_default_timezone_set( $identifier ); // Set it for PHP
  define( "TIMEZONE", $identifier );
  $now = new DateTime(); // Now sync MySQL
  $mins = $now->getOffset() / 60;
  $sgn = ( $mins < 0 ? -1 : 1 );
  $mins = abs( $mins );
  $hrs = floor( $mins / 60 );
  $mins -= $hrs * 60;
  $offset = sprintf( '%+d:%02d', $hrs * $sgn, $mins );
  $db = new PDO( 'mysql:host=localhost;dbname=data', 'admin', 'N0rthP013$' );
  $db->exec( "SET time_zone='$offset';" );
}

function convertTimezone( $datetime, $to, $from ) {
  $date = new DateTime( $datetime, new DateTimeZone( $from ) );
  $date->setTimezone( new DateTimeZone( $to ) );
  $datetime = $date->format( "Y-m-d H:i:s" );
  return $datetime;
}

function logAccess( $token, $user ) {
  global $connect;
  $agent = $_SERVER[ 'HTTP_USER_AGENT' ];
  $ip = $_SERVER[ 'REMOTE_ADDR' ];
  $check = mysqli_query( $connect, "select * from data.devices where user = '$user' and ip = '$ip' and agent = '$agent' limit 1" );
  if ( mysqli_num_rows( $check ) === 0 ) {
    mysqli_query( $connect, "insert into data.devices (agent,ip,session,user) values ('$agent','$ip','$token','$user')" );
  } else {
    mysqli_query( $connect, "update data.devices set session = '$token' where id = '$ip' and user = '$user' and agent = '$agent'" );
  }
}

function t( $text ) {
  global $localize;
  if ( $localize[ $text ] )$text = $localize[ $text ];
  else {
    $words = explode( " ", $text ); // look for translations of each word, to at least help this be somewhat understandable if there is no exact translation for it
    foreach ( $words as $word ) {
      if ( $localize[ $word ] )$text = str_replace( $word, $localize[ $word ], $text );
    }
  }
  return $text;
}

function filter( $string ) {
  global $user_array;
  if ( $user_array[ 'profanity' ] === "1" )$string = languageFilter( $string, "mod", true );
  else if ( $user_array[ 'profanity' ] === "2" )$string = languageFilter( $string, "banned", true );
  else if ( $user_array[ 'profanity' ] === "3" )$string = languageFilter( $string, "strict", true );
  return $string;
}

function languageFilter( $string, $level = "mod", $replace = false ) {
  $badword = array();
  $replacementword = array();
  if ( $level === "verystrict" ) {
    $words = explode( ",", file_get_contents( "badwords.txt" ) );
  } else {
    if ( $level === "mod" )$wordlist = "fuck|cunt|vagina|penis|cock|dickhead|nigger|nigga|faggot|fag|tranny|spic|kike|towelhead|fucking|cum|cumming|gangbang|zipperhead|cummies|whore"; // only the most abhorrent swears and slurs
    else if ( $level === "strict" )$wordlist = "fuck|bitch|cunt|vagina|penis|dick|cock|dickhead|pussy|nigger|nigga|faggot|fag|spic|kike|towelhead|fucking|damn|damnit|hell|shit|whore|shitting|porn|cum|cumming|gangbang|tits|boobs|boobies|boobie|tiddies|titties|zipperhead|ass|asshole|cummies"; // any swear words or innappropriate terms (nothing you'd want children to see basically)
    else if ( $level === "banned" )$wordlist = "nigger|nigga|faggot|fag|spic|kike|towelhead|zipperhead|tranny|whore"; // only slurs
    $words = explode( "|", $wordlist );
  }
  foreach ( $words as $key => $word ) {
    $badword[ $key ] = $word;
    $replacementword[ $key ] = addStars( $word );
    $badword[ $key ] = "/\b{$badword[$key]}\b/i";
  }
  $string = preg_replace( $badword, $replacementword, $string );
  if ( $replace === true ) {
    return $string;
  } else {
    if ( stripos( $string, "*" ) !== false ) {
      return true;
    } else {
      return false;
    }
  }
}

// Flag content for moderation
function createFlag( $type, $id, $reporter ) {
  global $connect;
  sendEmail( "mods@pengin.app", false, "New Post Flagged/Reported", "Post Flagged/Reported", "A new post has been flagged by the system or reported by a user." );
  if ( $reporter )$query = "insert into data.flags (type,content_id,reporter) values ('$type','$id','$reporter')";
  else $query = "insert into data.flags (type,content_id) values ('$type','$id')";
  if ( mysqli_query( $connect, $query ) ) return true;
  else return false;
}

function facts() {
  $facts = json_decode( file_get_contents( "facts.json" ), true );
  return array_random( $facts[ "facts" ] );
}

function addStars( $word ) {
  $length = strlen( $word );
  return substr( $word, 0, 1 ) . str_repeat( "*", $length - 2 ) . substr( $word, $length - 1, 1 );
}

function escape( $string ) {
  return e( strip_tags( htmlentities( $string, ENT_QUOTES, 'UTF-8' ) ) );
}


function numberFormat( $n, $precision = 1 ) {
  if ( $n < 1000 )$n_format = number_format( $n ); // Anything less than a thousand
  else if ( $n < 1000000 )$n_format = number_format( $n / 1000, $precision ) . 'K'; // Anything less than a million
  else if ( $n < 1000000000 )$n_format = number_format( $n / 1000000, $precision ) . 'M'; // Anything less than a billion
  else $n_format = number_format( $n / 1000000000, $precision ) . 'B'; // At least a billion
  return intval( $n_format );
}

function clean( $string ) { // remove any special characters, replace spaces with dashes, trim, remove tags, lowercase
  return str_replace( '--', '-', strtolower( preg_replace( '/[^A-Za-z0-9\-]/', '', preg_replace( "/[\"\']/", " ", preg_replace( "/[\/\&%#\$]/", "_", strip_tags( str_replace( ' ', '-', trim( $string ) ) ) ) ) ) ) );
}

function cleanCase( $string ) { // everything the clean function does but maintain the same case (don't make it lowercase)
  return str_replace( '--', '-', preg_replace( '/[^A-Za-z0-9\-]/', '', preg_replace( "/[\"\']/", " ", preg_replace( "/[\/\&%#\$]/", "_", strip_tags( str_replace( ' ', '-', trim( $string ) ) ) ) ) ) );
}

function special( $string ) {
  return preg_replace( '/[ ](?=[ ])|[^-_,A-Za-z0-9 ]+/', '', str_replace( "/", "", str_replace( '\"', '', strip_tags( trim( $string ) ) ) ) );
}

function findReplace( $string, $find, $replace ) {
  if ( preg_match( "/[a-zA-Z\_]+/", $find ) ) return ( string )preg_replace( "/\{\{(\s+)?($find)(\s+)?\}\}/", $replace, $string );
  else throw new\ Exception( "Find statement must match regex pattern: /[a-zA-Z]+/" );
}

function commaRemove( $string, $item ) {
  $parts = explode( ',', $string );
  while ( ( $i = array_search( $item, $parts ) ) !== false ) {
    unset( $parts[ $i ] );
  }
  return implode( ',', $parts );
}

function titleCase( $title ) {
  $smallwordsarray = array( 'of', 'a', 'the', 'and', 'an', 'or', 'nor', 'but', 'is', 'if', 'then', 'else', 'when', 'at', 'from', 'by', 'on', 'off', 'for', 'in', 'out', 'over', 'to', 'into', 'with' );
  $words = explode( ' ', $title );
  foreach ( $words as $key => $word ) {
    if ( $key == 0 or!in_array( $word, $smallwordsarray ) )$words[ $key ] = ucwords( $word );
  }
  $newtitle = implode( ' ', $words );
  return $newtitle;
}

function mockingCase( $str ) {
  $str = str_split( strtolower( $str ) );
  foreach ( $str as & $char ) {
    if ( rand( 0, 1 ) )$char = strtoupper( $char );
  }
  return implode( '', $str );
}

// Post functions

include( "parsedown.php" );
$Parsedown = new Parsedown();
$Parsedown->setSafeMode( true );
$Parsedown->setUrlsLinked( false );

function getPost( $id, $history = false, $actions = true, $timestamp = false, $isrepost = false, $isthread = false, $ispost = false, $inwindow = false, $insidethread = false ) {
  global $connect;
  global $user_session;
  global $Parsedown;
  global $user_tz;
  global $user_locale;
  global $notallowed;
  setlocale( LC_TIME, $user_locale . ".UTF-8" );
  $post = dataArray( "posts", $id, "id" );
  $user = dataArray( "users", $post[ 'author' ], "id" );
  $replies = dataArray( "posts", $post[ 'id' ], "reply" );
  $thread = dataArray( "threads", $post[ 'thread' ], "id" );
  $op = dataArray( "threads", $post[ 'id' ], "op" );
  $author_id = $post[ 'author' ];
  $username = $user[ 'username' ];
  if ( $post ) {
    if ( !$post[ 'thread' ] ) {
      if ( $user[ 'private' ] === "0" && !follows( $user_session, $post[ 'author' ] ) && $user_session !== $post[ 'author' ] && !$post[ 'thread' ] && !$post[ 'reply' ] && $post[ 'public' ] !== "0" ) {
        $notprivate = false;
      } else $notprivate = true;
    } else $notprivate = true;
    if ( !blacklist( "block", $post[ 'author' ], $user_session ) && !blacklist( "block", $user_session, $post[ 'author' ] ) )$blocked = false;
    else if ( !blacklist( "block", $thread[ 'author' ], $user_session ) && !blacklist( "block", $user_session, $thread[ 'author' ] ) )$blocked = false;
    else $blocked = true;
    if ( $blocked === false ) {
      if ( $notprivate === true ) {
        if ( $post[ 'reply' ] && $ispost && !$post[ 'thread' ] )echo getPost( $post[ 'reply' ], false, true, false, false, false, false, false, true );
        $author = $user[ 'displayname' ];
        $content = nl2br( linkify( $Parsedown->line( htmlspecialchars_decode( filter( $post[ 'content' ] ) ) ) ) );
        $datetime = strftime( "%l:%M %P %b %e, %Y", strtotime( convertTimezone( $post[ 'timestamp' ], $user_tz, $user[ 'timezone' ] ) ) );
        $like_count = activityCount( "likes", $id, true );
        $reply_count = activityCount( "replies", $id, true );
        $repost_count = activityCount( "reposts", $id, true );
        if ( $_POST[ 'type' ] === "profile" && $post[ 'pinned' ] === "1" )$pinned = " pinned-post";
        if ( hasActivity( "like", $id ) )$hasliked = " active-like";
        if ( hasActivity( "repost", $id ) )$hasreposted = " active-repost";
        if ( strlen( $content ) < 50 )$short = " post-content-short";
        if ( $post[ 'edited' ] ) {
          if ( !$history )$edited = " &mdash; <span data-action='post' data-args='id=$id&history=true' data-url='/post/$id/history'>View Edit History</span>";
          else $edited = strftime( "%l:%M %P %b %e, %Y", strtotime( $post[ 'edited' ] ) );
        }
        if ( $op && !$history ) {
          $count = numberFormat( mysqli_num_rows( mysqli_query( $connect, "select * from data.posts where id != '$id' and thread = '" . $thread[ 'id' ] . "'" ) ) );
          if ( $count != 1 )$s = "s";
          $datetime = "$count post$s in this thread &bull; $datetime";
        }
        if ( !$pinned && $replies && $isthread === false && $ispost === false && $inwindow === false && $insidethread === false ) {
          $replies = mysqli_query( $connect, "select * from data.posts where reply = '$id' order by timestamp asc limit 1" );
          while ( $reply = mysqli_fetch_array( $replies ) ) {
            if ( $reply[ 'thread' ] || $reply[ 'author' ] === $user_session || follows( $user_session, $reply[ 'author' ] ) ) {
              $this_reply = getPost( $reply[ 'id' ], false, true, false, false, false, false, false, true );
              $hasreplies .= "<div class='post-reply'>$this_reply</div>";
            }
          }
          if ( mysqli_num_rows( $replies ) > 1 )$hasreplies .= "<div class='post-thread-link' data-action='post' data-args='id=$id' data-url='/@$username/post/$id'>See entire thread</div>";
        }
        if ( $actions )$morebtn = "<div class='post-more-btn' data-dropdown='post-menu' data-item='$id'></div>";
        if ( $user[ 'verified' ] === "1" )$verify = "<span class='verified'></span>";
        if ( $timestamp === false )$quicktime = " &bull; " . timeago( $post[ 'timestamp' ], true );
        if ( $replies && $hasreplies && $isthread === false && $ispost === false )$replyline = "<div class='replyline'></div>";
        if ( $replies && $insidethread === true )$replyline = "<div class='replyline'></div>";
        $header = "<div class='post-header'><div><div class='navigation-profile' data-background='/images/avatar/$username' data-action='profile' data-args='user=$username' data-url='/@$username'></div>$replyline</div><div class='posts-author' data-action='profile' data-args='user=$username' data-url='/@$username'><div class='post-author'>$author$verify</div><div class='post-detail'>@$username$quicktime</div></div><div>$morebtn</div></div>";
        if ( $timestamp === false && $post[ 'edited' ] )$content = "$content <span class='input-context'>(edited)</span>";
        $result = "<div class='post$pinned$large' id='post-$id'>";
        if ( $_POST[ 'type' ] === "profile" && $post[ 'pinned' ] === "1" )$result .= "<div class='post-pinned'>Pinned</div>";
        if ( !$op )$result .= $header;
        else {
          $topic = dataArray( "topics", $thread[ 'topic' ], "id" );
          $result .= "<div class='thread-topic subheader'>Public " . $topic[ 'title' ] . " Thread</div>";
          if ( $post[ 'edited' ] && $history && $op[ 'original' ] ) {
            $diff = spotDiff( $op[ 'original' ], $op[ 'title' ] );
            $result .= "<div class='post-title'>" . $diff[ 'old' ] . "</div><div class='post-title'>" . $diff[ 'new' ] . "</div>";
          } else $result .= "<div class='post-title'>" . titleCase( $op[ 'title' ] ) . "</div>";
        }
        if ( $timestamp === false && !$op && !$ispost )$result .= "<div class='post-indent'>";
        if ( $post[ 'reply' ] ) {
          $reply_post = dataArray( "posts", $post[ 'reply' ], "id" );
          $this_op = dataArray( "users", $reply_post[ 'author' ], "id" );
          if ( $this_op ) {
            $reply_user = $this_op[ 'username' ];
            $result .= "<div class='post-replyto'>" . t( 'Replying to' ) . " <a href='javascript:;' data-action='profile' data-args='user=$reply_user' data-url='/@$reply_user'>@$reply_user</a></div>";
          }
        }
        if ( $history ) {
          $original = nl2br( linkify( $Parsedown->line( htmlspecialchars_decode( $post[ 'original' ] ) ) ) );
          $diff = spotDiff( $original, $content );
          $result .= "<br/><div class='post-datetime'>Originally posted on $datetime</div><div class='post-content'>" . $diff[ 'old' ] . "</div><hr/><div class='post-datetime'>Modified on $edited</div><div class='post-content'>" . $diff[ 'new' ] . "</div>";
        } else {
          if ( $op )$postaction = "data-action='thread' data-args='id=" . $thread[ 'id' ] . "' data-url='/thread/" . $thread[ 'id' ] . "'";
          else $postaction = "data-action='post' data-args='id=$id' data-url='/@$username/post/$id'";
          if ( $content )$result .= "<div class='post-content$short' $postaction>$content</div>";
        }
        if ( $post[ 'image' ] && ( $actions || $isrepost ) ) {
          $image = $post[ 'image' ];
          $images = array();
          if ( !filter_var( $image, FILTER_VALIDATE_URL ) ) {
            $makelarge = "-large";
            $photos = explode( ",", $image );
            $i = 1;
            foreach ( $photos as $photo ) {
              array_push( $images, "/images/post/$id/$i" );
              ++$i;
            }
          } else array_push( $images, $image );
          foreach ( $images as $image ) {
            if ( $ispost === true )$result .= "<div class='post-img'><img data-src='$image$makelarge'/></div>";
            else $result .= "<div class='post-link-image' data-background='$image' $postaction></div>";
          }
        }
        if ( $post[ 'repost' ] ) {
          if ( $actions !== false && $isrepost !== true ) {
            $repost = $post[ 'repost' ];
            $result .= "<div class='repost-container' data-action='post' data-args='id=$repost' data-url='/post/$repost'>" . getPost( $post[ 'repost' ], false, false, false, true, false, true ) . "</div>";
          }
        }
        // Display first link in post if database has stored data on it
        if ( !$image && !$repost && !$history && $actions !== false ) {
          preg_match( '/https?\:\/\/[^\" ]+/i', $post[ 'content' ], $matches );
          $post_link = trim( $matches[ 0 ] );
          if ( $post_link ) {
            $url = dataArray( "urls", $post_link, "url" );
            if ( $url ) {
              // title
              $linktitle = $url[ 'title' ];
              // domain
              $parse = parse_url( $post_link );
              $domain = str_replace( "www.", "", $parse[ 'host' ] );
              // image
              $linkimage = $url[ 'image' ];
              // description
              $desc = $url[ 'description' ];
              // if youtube link
              if ( $url[ 'videoid' ] ) {
                $videoid = $url[ 'videoid' ];
                $frameid = uniqid();
                $linkaction = " id='$frameid' onclick='openVideo(&quot;$frameid&quot;,&quot;$videoid&quot;);'";
                $playicon = "<div class='post-link-play-btn'></div>";
              } else {
                $linkaction = "onclick='goTo(&quot;$post_link&quot;,true);'";
              }
              $result .= "<div class='post-link-block'$linkaction>";
              $result .= "<div class='post-link-image' data-background='$linkimage'>$playicon</div>";
              $result .= "<div class='post-link-info'><div class='post-link-title'>$linktitle</div><div class='post-link-desc'>$desc</div><div class='post-link-domain'>$domain</div></div>";
              $result .= "</div>";
              if ( $videoid )$result .= "<div class='post-video' id='frame$frameid'><iframe frameborder=0 allowtransparency></iframe></div>";
            }
          }
        }
        if ( !$history && $timestamp )$result .= "<div class='post-datetime'>$datetime$edited</div>";
        if ( $actions ) {
          if ( $thread )$replyThread = " data-thread='" . $thread[ 'id' ] . "'";
          $result .= "<div class='post-actions $notallowed'><div class='post-action-btn like-btn$hasliked' data-activity='like' data-id='$id' data-author='$author_id'>$like_count</div>";
          $result .= "<div class='post-action-btn reply-btn' data-id='$id'$replyThread>$reply_count</div>";
          if ( !$op )$result .= "<div class='post-action-btn repost-btn$hasreposted' data-id='$id'>$repost_count</div>";
          $result .= "</div>";
        }
        if ( $op )$result .= "<br/>" . $header;
        if ( $timestamp === false && !$op && $actions )$result .= "</div>";
        $result .= $hasreplies;
        $result .= "</div>";
      } else $result .= "<div class='post'><div class='post-error'>This person prefers to keep their posts private.&nbsp;<a href='javascript:;' data-action='profile' data-args='user=$username' data-url='/@$username'>Follow them to see this post</a>.</div></div>";
    } else {
      if ( $post[ 'reply' ] ) {
        if ( blacklist( "block", $post[ 'author' ], $user_session ) )$result .= "<div class='post'><div class='post-error'>This post is unavailable.</div></div>";
        else if ( blacklist( "block", $user_session, $post[ 'author' ] ) )$result .= "<div class='post'><div class='post-error'>You've blocked posts from this person.</div></div>";
      }
    }
  } else $result .= "<div class='post'><div class='post-error'>This post has been removed or there was a glitch in the matrix.</div></div>";
  return $result;
}

function getUser( $id ) {
  global $user_session;
  $user = dataArray( "users", $id, "id" );
  if ( $user ) {
    $name = $user[ 'displayname' ];
    $username = $user[ 'username' ];
    $action = " data-action='profile' data-args='user=$username' data-url='/@$username'";
    if ( $user[ 'verified' ] === "1" )$verify = "<span class='verified'></span>";
    $result = "<div class='post-header'>";
    $result .= "<div class='navigation-profile' data-background='/images/avatar/$username' $action></div>";
    $result .= "<div class='posts-author' $action><div class='post-author'>$name$verify</div><div class='post-detail'>@$username</div></div>";
    $result .= "<div>" . followB( $id, true ) . "</div>";
    $result .= "</div>";
    return $result;
  } else return false;
}

function getMessage( $id, $convo ) {
  global $user_session;
  global $Parsedown;
  global $user_tz;
  $convo = dataArray( "convos", $convo, "id" );
  if ( $convo ) {
    $users = explode( ",", $convo[ 'users' ] );
    if ( in_array( $user_session, $users ) || $convo[ 'author' ] === $user_session ) {
      $message = dataArray( "messages", $id, "id" );
      $user = dataArray( "users", $message[ 'author' ], "id" );
      $username = $user[ 'username' ];
      if ( $user_session === $message[ 'author' ] )$class = "sender";
      else $class = "receiver";
      $content = nl2br( linkify( $Parsedown->line( htmlspecialchars_decode( $message[ 'content' ] ) ) ) );
      $timestamp = timeago( convertTimezone( $message[ 'timestamp' ], $user_tz, $user[ 'timezone' ] ), true );
      if ( $message[ 'new' ] === "0" )$read = " &bull; Sent & Read";
      else $read = " &bull; Sent";
      if ( $class !== "sender" )$profile = "<div class='message-profile navigation-profile' data-background='/images/avatar/$username' data-action='profile' data-args='user=$username' data-url='/@$username' tooltip='@$username' data-placement='top'></div>";
      else $profile = "<div></div>";
      $result = "<div class='message-container $class-container' id='message-$id'>$profile<div><div class='message-item message-$class'>$content</div><div class='message-timestamp'>$timestamp$read</div></div></div>";
    }
  }
  if ( $result ) return $result;
  else return false;
}

function getHashtags( $string ) { // Parse hashtags for trending
  $hashtags = FALSE;
  preg_match_all( "/(#\w+)/u", $string, $matches );
  if ( $matches )$hashtags = array_keys( array_count_values( $matches[ 0 ] ) );
  return $hashtags;
}

function file_get_content( $url ) {
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $ch, CURLOPT_URL, $url );
  $data = curl_exec( $ch );
  curl_close( $ch );
  return $data;
}

function get_og_tags( $url ) {
  $doc = new DomDocument();
  $doc->loadHTML( file_get_content( $url ) );
  $xpath = new DOMXPath( $doc );
  $query = '//*/meta[starts-with(@property, \'og:\')]';
  $metas = $xpath->query( $query );
  $rmetas = array();
  foreach ( $metas as $meta ) {
    $property = $meta->getAttribute( 'property' );
    $content = $meta->getAttribute( 'content' );
    $rmetas[ $property ] = $content;
  }
  return $rmetas;
}

function linkArray( $url, $extra = false ) { // make array of url title, author, description and image and return inside an array
  // if short url, expand
  $headers = get_headers( $url, 1 );
  if ( $headers[ 'Location' ] )$url = $headers[ 'Location' ];
  $meta = array(); // create array
  // title
  $title = preg_match( '/<title[^>]*>(.*?)<\/title>/ims', file_get_content( $url ), $matches ) ? $matches[ 1 ] : null; // get title
  if ( $extra === true ) { // these functions can slow things down
    // get tags
    $tags = get_meta_tags( $url );
    $og_tags = get_og_tags( $url );
    // title
    if ( $og_tags[ 'og:title' ] )$meta[ 'title' ] = $og_tags[ 'og:title' ];
    // author
    $explode_comma = explode( ",", $tags[ 'author' ] );
    $author = $explode_comma[ 0 ];
    $explode_by = explode( "by", $author );
    $author = trim( $explode_by[ 1 ] );
    $meta[ 'author' ] = $author;
    // description
    $desc = $tags[ 'description' ];
    if ( $og_tags[ 'og:description' ] )$desc = $og_tags[ 'og:description' ];
    $meta[ 'description' ] = substr( $desc, 0, 150 ) . "...";
    // image
    if ( $tags[ 'image' ] )$image = $tags[ 'image' ];
    if ( $og_tags[ 'og:image' ] )$image = $og_tags[ 'og:image' ];
    $meta[ 'image' ] = $image;
    // domain
    $parse = parse_url( $url );
    $meta[ 'domain' ] = str_replace( "www.", "", $parse[ 'host' ] );
  }
  return $meta;
}

function getScreenshot( $url ) {
  $response = file_get_content( 'https://www.googleapis.com/pagespeedonline/v2/runPagespeed?screenshot=true&url=' . urlencode( $url ) );
  $googlePagespeedObject = json_decode( $response, true );
  $screenshot = $googlePagespeedObject[ 'screenshot' ][ 'data' ];
  $screenshot = str_replace( array( '_', '-' ), array( '/', '+' ), $screenshot );
  return "data:image/jpeg;base64,{$screenshot}";
  // Or.. base64 decode and store
  // file_put_contents( "", base64_decode( $screenshot ) );
}

function spotDiff( $old, $new ) {
  $from_start = strspn( $old ^ $new, "\0" );
  $from_end = strspn( strrev( $old ) ^ strrev( $new ), "\0" );
  $old_end = strlen( $old ) - $from_end;
  $new_end = strlen( $new ) - $from_end;
  $start = substr( $new, 0, $from_start );
  $end = substr( $new, $new_end );
  $new_diff = substr( $new, $from_start, $new_end - $from_start );
  $old_diff = substr( $old, $from_start, $old_end - $from_start );
  $new = "$start<ins>$new_diff</ins>$end";
  $old = "$start<del>$old_diff</del>$end";
  return array( "old" => $old, "new" => $new );
}

function shortenURLs( $str, $app = false ) { // trunicates the url (eg. link.com/news/someth...)
  $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
  $urls = array();
  $urlsToReplace = array();
  if ( preg_match_all( $reg_exUrl, $str, $urls ) ) {
    $numOfMatches = count( $urls[ 0 ] );
    $numOfUrlsToReplace = 0;
    for ( $i = 0; $i < $numOfMatches; $i++ ) {
      $alreadyAdded = false;
      $numOfUrlsToReplace = count( $urlsToReplace );
      for ( $j = 0; $j < $numOfUrlsToReplace; $j++ ) {
        if ( $urlsToReplace[ $j ] == $urls[ 0 ][ $i ] )$alreadyAdded = true;
      }
      if ( !$alreadyAdded )array_push( $urlsToReplace, $urls[ 0 ][ $i ] );
    }
    $numOfUrlsToReplace = count( $urlsToReplace );
    for ( $i = 0; $i < $numOfUrlsToReplace; $i++ ) {
      if ( strlen( $urlsToReplace[ $i ] ) > 50 )$dots = "...";
      if ( $app === true )$short_url = "<a href='javascript:;' onclick='openBrowser(&quot;" . $urlsToReplace[ $i ] . "&quot;);' target='_blank' rel='noopener'>" . substr( $urlsToReplace[ $i ], 0, 50 ) . "$dots</a>";
      else $short_url = "<a href='" . $urlsToReplace[ $i ] . "' target='_blank' rel='noopener'>" . substr( $urlsToReplace[ $i ], 0, 50 ) . "$dots</a>";
      $str = str_replace( $urlsToReplace[ $i ], $short_url, $str );
    }
    return $str;
  } else return $str;
}

function linkify( $content, $shorten = false, $app = false ) { // add <a> tags to http urls, shorten if specified, return app version if specified
  if ( $app === true ) {
    if ( $shorten === true )$linkify = shortenURLs( $content, true );
    else $linkify = preg_replace( "~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i", "<a href=\"javascript:;\" onclick='openBrowser(&quot;\\0&quot;);' target='_blank'>\\0</a>", $content ); // links
    $linkify = preg_replace( "/@([a-z_0-9]+)/i", "<a href=\"javascript:;\" onclick=\"load('openadvertiser','secondcontent','$1','openpage','Posts');\">$0</a>", $linkify ); // @mentions
    $linkify = preg_replace( "/#([a-z_0-9]+)/i", "<a href=\"javascript:;\" onclick=\"load('hashtag','secondcontent','$1','openpage','Posts');\">$0</a>", $linkify ); // #hashtags
  } else {
    if ( $shorten === true )$linkify = shortenURLs( $content );
    else $linkify = preg_replace( "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\" target='_blank' rel='noopener'>\\0</a>", $content ); // links
    $linkify = preg_replace( "/@([a-z_0-9]+)/i", "<a href=\"javascript:;\" data-action=\"profile\" data-args=\"user=$1\" data-url=\"/@$1\">$0</a>", $linkify ); // @mentions
    $linkify = preg_replace( "/#([a-z_0-9]+)/i", "<a href=\"javascript:;\" data-action=\"search\" data-args=\"query=$1&hashtag=true\" data-url=\"/hashtag/$1\">$0</a>", $linkify ); // #hashtags
  }
  return $linkify;
}

// Create activity if mentions are in this post
function createMentions( $content, $id ) {
  global $connect;
  global $user_session;
  if ( strpos( $content, "@" ) !== false ) {
    preg_match_all( '/@(\w+)|\s+([(\w+)\s|.|,|!|?]+)/', $content, $result, PREG_PATTERN_ORDER );
    for ( $i = 0; $i < count( $result[ 0 ] ); $i++ ) {
      if ( $result[ 1 ][ $i ] )$mentions[ $i ] = $result[ 1 ][ $i ];
    }
    foreach ( $mentions as $mention ) {
      $user = dataArray( "users", $mention, "username" );
      $thismention = $user[ 'id' ];
      $check = mysqli_query( $connect, "select * from data.activity where author = '$user_session' and action = 'mention' and content = '$id' and target = '$thismention'" );
      if ( mysqli_num_rows( $check ) === 0 ) { // If mention hasn't already been created
        if ( $user )$create = mysqli_query( $connect, "insert into data.activity (author,action,content,target) values ('$user_session','mention','$id','$thismention')" );
      }
    }
  }
}

// Database functions

// Quickly get array for a row with a column value
function dataArray( $table, $val, $col ) {
  global $connect;
  if ( $val !== false ) {
    $get = "$col = '$val'";
    $query = mysqli_query( $connect, "select * from data.$table where $get" );
  } else $query = mysqli_query( $connect, "select * from data.$table" );
  if ( mysqli_num_rows( $query ) !== 0 ) return mysqli_fetch_array( $query );
  else return false;
}

// Quickly update one column in a row in the database.
function setValue( $db, $val, $col, $where ) {
  global $connect;
  if ( $db && $val && $col && $where ) {
    if ( $val === "NULL" )$set = "$col = NULL";
    else {
      $val = e( $val );
      $set = "$col = '$val'";
    }
    if ( mysqli_query( $connect, "update data.$db set $set where $where" ) ) return true;
    else return false;
  } else return false;
}

function e( $string ) {
  global $connect;
  return mysqli_real_escape_string( $connect, $string );
}

// Global time functions

function howlongago( $datetime, $full ) {
  $now = new DateTime;
  $diff = $now->diff( new DateTime( $datetime ) );
  $diff->w = floor( $diff->d / 7 );
  $diff->d -= $diff->w * 7;
  if ( $full )$string = array( 'y' => t( 'year' ), 'm' => t( 'month' ), 'w' => t( 'week' ), 'd' => t( 'day' ), 'h' => t( 'hour' ), 'i' => t( 'minute' ), 's' => t( 'second' ), );
  else $string = array( 'y' => 'y', 'm' => 'mo', 'w' => 'w', 'd' => 'd', 'h' => 'h', 'i' => 'm', 's' => 's', );
  foreach ( $string as $k => & $v ) {
    if ( $diff->$k ) {
      if ( $full )$v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
      else $v = $diff->$k . '' . t( $v ) . ( $diff->$k > 1 ? '' : '' );
    } else unset( $string[ $k ] );
  }
  // if ( !$full )$string = array_slice( $string, 0, 1 );
  // return $string ? implode( ',', $string ) . ' ago': 'now';
  $string = array_slice( $string, 0, 1 );
  if ( $full ) return $string ? t( implode( ',', $string ) ) . ' ' . t( 'ago' ): t( 'now' );
  return $string ? implode( ',', $string ) . '': t( 'now' );
}

function timeago( $datetime, $ago = false, $full = false, $shorten = false ) {
  if ( $ago === false ) {
    if ( date( "Y-m-d", strtotime( $datetime ) ) === date( "Y-m-d", strtotime( 'today' ) ) ) {
      if ( date( "H:i:s", strtotime( $datetime ) ) > date( "H:i:s", strtotime( '-5 hours' ) ) )$str = howlongago( date( "Y-m-d H:i:s", strtotime( "$datetime +4 hours" ) ), $full );
      else $str = "Today at " . date( "g:i a", strtotime( $datetime ) );
    } else if ( date( "Y-m-d", strtotime( $datetime ) ) === date( "Y-m-d", strtotime( 'yesterday' ) ) ) {
      $str = "Yesterday at " . date( "g:i a", strtotime( $datetime ) );
    } else {
      if ( date( "Y", strtotime( $datetime ) ) === date( "Y" ) ) {
        if ( $shorten === true )$str = date( "D, M. j", strtotime( $datetime ) ) . " at " . date( "g:i a", strtotime( $datetime ) );
        else $str = date( "l, F j", strtotime( $datetime ) ) . " at " . date( "g:i a", strtotime( $datetime ) );
      } else {
        if ( $shorten === true ) {
          if ( date( "Y" ) !== date( "Y", strtotime( $datetime ) ) )$str = date( "M. j, Y", strtotime( $datetime ) );
          else $str = date( "M. j", strtotime( $datetime ) );
        } else $str = date( "F j, Y", strtotime( $datetime ) );
      }
    }
  } else $str = howlongago( $datetime, $full );
  $str = str_replace( "May.", "May", str_replace( "Sep.", "Sept.", str_replace( "am", "a.m.", str_replace( "pm", "p.m.", str_replace( ":00", "", $str ) ) ) ) );
  return $str;
}

// Global file/folder functions

function formatSize( $bytes ) {
  if ( $bytes >= 1073741824 )$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
  elseif ( $bytes >= 1048576 )$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
  elseif ( $bytes >= 1024 )$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
  elseif ( $bytes > 1 )$bytes = $bytes . ' bytes';
  elseif ( $bytes == 1 )$bytes = $bytes . ' byte';
  else $bytes = '0 bytes';
  return $bytes;
}

function makeFolder( $name ) {
  if ( !file_exists( $name ) ) {
    if ( mkdir( $name, 0777, true ) ) return true;
    else return false;
  } else return false;
}

function deleteDir( $dirPath ) {
  if ( !is_dir( $dirPath ) ) return false;
  else {
    if ( substr( $dirPath, strlen( $dirPath ) - 1, 1 ) != '/' )$dirPath .= '/';
    $files = glob( $dirPath . '*', GLOB_MARK );
    foreach ( $files as $file ) {
      if ( is_dir( $file ) ) {
        deleteDir( $file );
      } else unlink( $file );
    }
    if ( rmdir( $dirPath ) ) return true;
    else return false;
  }
}

function folderEmpty( $dir ) {
  if ( !is_readable( $dir ) ) return NULL;
  return ( count( scandir( $dir ) ) == 2 );
}

function folderArray( $folder ) {
  $folder_array = array();
  if ( $handle = opendir( $folder ) ) {
    while ( false !== ( $entry = readdir( $handle ) ) ) {
      if ( $entry != "." && $entry != ".." && $entry !== ".DS_STORE" )array_push( $folder_array, $entry );
    }
    closedir( $handle );
  }
  if ( empty( $folder_array ) ) return false;
  else return $folder_array;
}

function count_dir( $path ) {
  $count[ 'files' ] = 0;
  $count[ 'folders' ] = 0;
  $count[ 'total' ] = 0;
  $path = realpath( $path );
  $dir = opendir( $path );
  while ( ( $file = readdir( $dir ) ) !== false ) {
    if ( $file != "." && $file != ".." ) {
      if ( is_file( $path . "/" . $file ) ) {
        $count[ 'files' ]++;
        $count[ 'total' ]++;
      }
      if ( is_dir( $path . "/" . $file ) ) {
        $count[ 'folders' ]++;
        $count[ 'total' ]++;
        $counts = count_dir( $path . "/" . $file );
        $count[ 'folders' ] += $counts[ 'folders' ];
        $count[ 'files' ] += $counts[ 'files' ];
      }
    }
  }
  closedir( $dir );
  $count[ 'size' ] = formatSize( filesize( $path ) );
  $count[ 'folders' ] = numberFormat( $count[ 'folders' ] );
  $count[ 'files' ] = numberFormat( $count[ 'files' ] );
  return $count;
}

// Global array functions

function array_random( $arr, $num = 1 ) {
  shuffle( $arr );
  $r = array();
  for ( $i = 0; $i < $num; $i++ )$r[] = $arr[ $i ];
  return $num == 1 ? $r[ 0 ] : $r;
}

function truncateArray( $truncateAt, $arr ) {
  array_splice( $arr, $truncateAt, ( count( $arr ) - $truncateAt ) );
  return $arr;
}

function in_arrayi( $needle, $haystack ) { // case-insensitive array search
  return in_array( strtolower( $needle ), array_map( 'strtolower', $haystack ) );
}

function array_push_key( & $array, $key, $value, $valuetwo, $inarray ) {
  if ( $valuetwo )$array[ $key ] = array( $value, $valuetwo );
  else if ( $inarray )$array[ $key ] = array( $value );
  else $array[ $key ] = $value;
}

function array_push_key_check( & $array, $key, $value ) { // check if this key already exists in the array
  if ( array_key_exists( $key, $array ) ) { // replace existing key and value
    if ( strpos( $array[ $key ], $value ) === false )$array[ $key ] = $array[ $key ] . "," . $value;
  } else $array[ $key ] = $value; // this key did not exist before, insert it
}

//ini_set( 'display_errors', 1 );
//ini_set( 'display_startup_errors', 1 );
//error_reporting( E_ALL );

// Global mail function
function sendEmail( $to, $from, $subject, $title, $message ) { // Quickly send an email
  $headers;
  if ( !$to || $to === false )$to = "contact@pengin.app";
  if ( !$from || $from === false ) {
    $from = "donotreply@pengin.app";
    $headers = "From: Pengin < donotreply@pengin.app >\n";
  } else $headers = "From: < $from >\n";
  $headers .= 'X-Mailer: PHP/' . phpversion();
  $headers .= "X-Priority: 1\n"; // Urgent message!
  $headers .= "Return-Path: contact@pengin.app\n"; // Return path for errors
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
  $subject = "$subject - Pengin App";
  $body = file_get_contents( "../email.html" );
  $body .= "<h2>$title</h2>";
  $body .= "$message<br/><br/>- <i>Pengin Team</i>";
  $body .= "</div></div>";
  $body .= "</body></html>";
  $send = mail( $to, $subject, $body, $headers ); // , '-contact@pengin.app'
  if ( $send ) return true;
  else return false;
}

function follows( $user, $target, $both = false ) {
  global $connect;
  if ( $both === false ) {
    $check = mysqli_query( $connect, "select * from data.activity where action = 'follow' and author = '$user' and content = '$target'" );
    if ( mysqli_num_rows( $check ) !== 0 )$status = true;
    else $status = false;
  } else {
    $check1 = mysqli_query( $connect, "select * from data.activity where action = 'follow' and author = '$user' and content = '$target'" );
    $check2 = mysqli_query( $connect, "select * from data.activity where action = 'follow' and author = '$target' and content = '$user'" );
    if ( mysqli_num_rows( $check ) !== 0 && mysqli_num_rows( $check2 ) !== 0 )$status = true;
    else $status = false;
  }
  return $status;
}

function followsArray( $id, $following = false, $limit = false, $rand = false ) {
  global $connect;
  $list = array();
  if ( $limit )$haslimit = "limit $limit";
  if ( $rand )$makerandom = "order by rand()";
  if ( $following === true )$query = "select * from data.activity where action = 'follow' and author = '$id' $haslimit $makerandom";
  else $query = "select * from data.activity where action = 'follow' and content = '$id' $haslimit";
  $query = mysqli_query( $connect, $query );
  while ( $item = mysqli_fetch_array( $query ) ) {
    if ( $following === true )array_push( $list, $item[ 'content' ] );
    else array_push( $list, $item[ 'author' ] );
  }
  return $list;
}

function followB( $id, $small = false ) {
  global $user_session;
  global $connect;
  global $ismobile;
  if ( $small !== false )$only = "-only";
  $profile = dataArray( "users", $id, "id" );
  if ( $id === $user_session ) {
    if ( !$ismobile )$button = "<button type='submit' class='profile-btn grey-btn' data-action='settings/main'>" . t( 'Settings' ) . "</button>";
  } else {
    if ( follows( $user_session, $id ) === true ) {
      if ( $small === false )$label = t( "Following" );
      $button = "<button type='submit' class='profile-btn icon$only-btn follow-btn active-follow' data-activity='follow' data-id='$id'>$label</button>";
    } else {
      if ( $profile[ 'canfollow' ] === "2" ) {
        $check = mysqli_query( $connect, "select * from data.activity where action = 'request' and content = '$id' and author = '$user_session'" );
        if ( mysqli_num_rows( $check ) !== 0 ) {
          if ( $small === false )$request_status = t( "Request Sent" );
          $request_class = "active-request";
        } else {
          if ( $small === false )$request_status = t( "Follow" );
        }
        $button = "<button type='submit' class='profile-btn icon$only-btn request-btn grey-btn follow-btn $request_class' data-activity='request' data-id='$id'>$request_status</button>";
      } else {
        if ( $small === false )$label = t( "Follow" );
        $button = "<button type='submit' class='profile-btn icon$only-btn grey-btn follow-btn' data-activity='follow' data-id='$id'>$label</button>";
      }
    }
  }
  return $button;
}

function messageB( $id ) {
  global $user_session;
  global $connect;
  $profile = dataArray( "users", $id, "id" );
  if ( $id !== $user_session ) {
    if ( $profile[ 'canmessage' ] === "0" || $profile[ 'canmessage' ] === "2" ) {
      if ( $profile[ 'canmessage' ] === "2" || $profile[ 'followmessage' ] === "2" )$button = "<button type='submit' class='profile-btn icon-btn grey-btn message-btn' data-id='$id'>" . t( 'Message' ) . "</button>";
      else if ( $profile[ 'canmessage' ] === "0" || ( $profile[ 'followmessage' ] === "0" && follows( $user_session, $id ) ) )$button = "<button type='submit' class='profile-btn icon-btn grey-btn message-btn' data-id='$id'>" . t( 'Message' ) . "</button>";
    }
  }
  return $button;
}

function blacklist( $type, $user, $target ) {
  global $connect;
  if ( $type !== false )$istype = "and type = '$type'";
  $check = mysqli_query( $connect, "select * from data.blacklist where author = '$user' and target = '$target' $istype" );
  if ( $check ) {
    if ( mysqli_num_rows( $check ) !== 0 ) return true;
    else return false;
  } else return false;
}

function activityCount( $type, $id, $shorten = false ) {
  global $connect;
  if ( $type === "followers" ) {
    $query = "select * from data.activity where action = 'follow' and content = '$id'";
  } else if ( $type === "following" ) {
    $query = "select * from data.activity where action = 'follow' and author = '$id'";
  } else if ( $type === "likes" ) {
    $query = "select * from data.activity where action = 'like' and content = '$id'";
  } else if ( $type === "replies" ) {
    $query = "select * from data.posts where reply = '$id'";
  } else if ( $type === "reposts" ) {
    $query = "select * from data.posts where repost = '$id'";
  } else if ( $type === "favorites" ) {
    $query = "select * from data.activity where action = 'favorite' and content = '$id'";
  }
  $query_count = mysqli_num_rows( mysqli_query( $connect, $query ) );
  if ( $query_count === 0 ) {
    if ( $type === "likes" )$count = t( "Like" );
    if ( $type === "replies" )$count = t( "Reply" );
    if ( $type === "reposts" )$count = t( "Repost" );
    if ( $type === "favorites" )$count = t( "Save" );
  }
  if ( !$count ) {
    $count = intVal( $query_count );
    if ( $shorten === true )$count = numberFormat( $query_count );
  }
  return $count;
}

function hasActivity( $action, $id ) {
  global $connect;
  global $user_session;
  if ( $action === "reply" || $action == "repost" )$query = mysqli_query( $connect, "select * from data.posts where author = '$user_session' and $action = '$id'" );
  else $query = mysqli_query( $connect, "select * from data.activity where author = '$user_session' and action = '$action' and content = '$id' or target = '$id'" );
  if ( mysqli_num_rows( $query ) !== 0 ) return true;
  else return false;
}
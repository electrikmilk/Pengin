<?php

function browser_detection( $which_test, $test_excludes = '', $external_ua_string = '' ) {
  static $a_full_assoc_data, $a_khtml_data, $a_mobile_data, $a_moz_data, $a_engine_data, $a_blink_data, $a_trident_data, $a_webkit_data, $b_dom_browser, $b_repeat, $b_safe_browser, $blink_type, $blink_type_number, $browser_name, $browser_number, $browser_math_number, $browser_user_agent, $browser_working, $html_type, $ie_version, $khtml_type, $khtml_type_number, $mobile_test, $moz_type_number, $moz_rv, $moz_rv_full, $moz_release_date, $moz_type, $os_number, $os_type, $layout_engine, $layout_engine_nu, $layout_engine_nu_full, $trident_type, $trident_type_number, $true_ie_number, $ua_type, $webkit_type, $webkit_type_number;
  if ( $external_ua_string )$b_repeat = false;
  if ( !$b_repeat ) {
    $a_blink_data = '';
    $a_browser_math_number = '';
    $a_full_assoc_data = '';
    $a_full_data = '';
    $a_khtml_data = '';
    $a_mobile_data = '';
    $a_moz_data = '';
    $a_os_data = '';
    $a_trident_data = '';
    $a_unhandled_browser = '';
    $a_webkit_data = '';
    $b_dom_browser = false;
    $b_os_test = true;
    $b_mobile_test = true;
    $b_safe_browser = false;
    $b_success = false;
    $blink_type = '';
    $blink_type_number = '';
    $browser_math_number = '';
    $browser_temp = '';
    $browser_working = '';
    $browser_number = '';
    $html_type = '';
    $html_type_browser_nu = '';
    $ie_version = '';
    $layout_engine = '';
    $layout_engine_nu = '';
    $layout_engine_nu_full = '';
    $khtml_type = '';
    $khtml_type_number = '';
    $mobile_test = '';
    $moz_release_date = '';
    $moz_rv = '';
    $moz_rv_full = '';
    $moz_type = '';
    $moz_type_number = '';
    $os_number = '';
    $os_type = '';
    $run_time = '';
    $trident_type = '';
    $trident_type_number = '';
    $true_ie_number = '';
    $ua_type = 'bot';
    $webkit_type = '';
    $webkit_type_number = '';
    if ( $test_excludes ) {
      switch ( $test_excludes ) {
        case '1':
          $b_os_test = false;
          break;
        case '2':
          $b_mobile_test = false;
          break;
        case '3':
          $b_os_test = false;
          $b_mobile_test = false;
          break;
        default:
          die( 'Error: bad $test_excludes parameter 2 used: ' . $test_excludes );
          break;
      }
    }
    if ( $external_ua_string )$browser_user_agent = strtolower( $external_ua_string );
    else if ( isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) )$browser_user_agent = strtolower( $_SERVER[ 'HTTP_USER_AGENT' ] );
    else $browser_user_agent = '';
    $a_browser_types = array( array( '360spider', false, '360spider', 'bot' ), array( 'adsbot-google', false, 'google-ads', 'bot' ), array( 'applebot', false, 'applebot', 'bot' ), array( 'baidu', false, 'baidu', 'bot' ), array( 'bingbot', false, 'bing', 'bot' ), array( 'bingpreview', false, 'bing', 'bot' ), array( 'ips-agent', false, 'ips-agent', 'bot' ), array( 'msnbot', false, 'msn', 'bot' ), array( 'exabot', false, 'exabot', 'bot' ), array( 'googlebot', false, 'google', 'bot' ), array( 'google web preview', false, 'googlewp', 'bot' ), array( 'surdotlybot', false, 'surdotlybot', 'bot' ), array( 'yandex', false, 'yandex', 'bot' ), array( 'edge', true, 'edge', 'bro' ), array( 'msie', true, 'ie', 'bro' ), array( 'trident', true, 'ie', 'bro' ), array( 'blink', true, 'blink', 'bro' ), array( 'opr/', true, 'blink', 'bro' ), array( 'vivaldi', true, 'blink', 'bro' ), array( 'webkit', true, 'webkit', 'bro' ), array( 'opera', true, 'op', 'bro' ), array( 'khtml', true, 'khtml', 'bro' ), array( 'gecko', true, 'moz', 'bro' ), array( 'netpositive', false, 'netp', 'bbro' ), array( 'lynx', false, 'lynx', 'bbro' ), array( 'elinks ', false, 'elinks', 'bbro' ), array( 'elinks', false, 'elinks', 'bbro' ), array( 'links2', false, 'links2', 'bbro' ), array( 'links ', false, 'links', 'bbro' ), array( 'links', false, 'links', 'bbro' ), array( 'w3m', false, 'w3m', 'bbro' ), array( 'webtv', false, 'webtv', 'bbro' ), array( 'amaya', false, 'amaya', 'bbro' ), array( 'dillo', false, 'dillo', 'bbro' ), array( 'ibrowse', false, 'ibrowse', 'bbro' ), array( 'icab', false, 'icab', 'bro' ), array( 'crazy browser', true, 'ie', 'bro' ), array( ' bot', false, 'misc-bot', 'bot' ), array( '-bot', false, 'misc-bot', 'bot' ), array( '_bot', false, 'misc-bot', 'bot' ), array( 'crawl', false, 'misc-bot', 'bot' ), array( 'download', false, 'misc-dow', 'dow' ), array( 'fetch', false, 'misc-dow', 'dow' ), array( 'link', false, 'misc-bot', 'bot' ), array( 'robot', false, 'misc-bot', 'bot' ), array( 'scanner', false, 'misc-bot', 'bot' ), array( 'scrape', false, 'misc-bot', 'bot' ), array( 'seobot', false, 'misc-bot', 'bot' ), array( 'seo ', false, 'misc-bot', 'bot' ), array( 'seo_', false, 'misc-bot', 'bot' ), array( 'seo-', false, 'misc-bot', 'bot' ), array( 'sitemap', false, 'misc-bot', 'bot' ), array( 'spider', false, 'misc-bot', 'bot' ), array( 'webbot', false, 'misc-bot', 'bot' ), array( 'http client', false, 'httpclient', 'lib' ), array( 'http-client', false, 'httpclient', 'lib' ), array( 'http_client', false, 'httpclient', 'lib' ), array( 'httpclient', false, 'httpclient', 'lib' ), array( 'http_request', false, 'httprequest', 'lib' ), array( 'http-request', false, 'httprequest', 'lib' ), array( 'httprequest', false, 'httprequest', 'lib' ), array( 'http_transport', false, 'httptransport', 'lib' ), array( 'http-transport', false, 'httptransport', 'lib' ), array( 'httptransport', false, 'httptransport', 'lib' ), array( 'java', false, 'java', 'lib' ), array( 'php-', false, 'php', 'lib' ), array( 'perl', false, 'perl', 'lib' ), array( 'python', false, 'python', 'lib' ), array( 'ruby', false, 'ruby', 'lib' ), array( '8leg', false, '8leg', 'bot' ), array( 'ahrefsbot', false, 'ahrefsbot', 'bot' ), array( 'alexa', false, 'alexa', 'bot' ), array( 'almaden', false, 'ibm', 'bot' ), array( 'answerbus', false, 'answerbus', 'bot' ), array( 'archive.org', false, 'archive.org', 'bot' ), array( 'ask jeeves', false, 'ask', 'bot' ), array( 'teoma', false, 'ask', 'bot' ), array( 'betabot', false, 'betabot', 'bot' ), array( 'blexbot', false, 'blexbot', 'bot' ), array( 'bpimagewalker', false, 'bp-imagewalker', 'bot' ), array( 'bhcbot', false, 'bhcbot', 'bot' ), array( 'boitho.com-dc', false, 'boitho', 'bot' ), array( 'catexplorador', false, 'catexplorador', 'bot' ), array( 'checkmark', false, 'checkmark', 'bot' ), array( 'clockwork data', false, 'clockwork-data', 'bot' ), array( 'coccoc', false, 'coccoc', 'bot' ), array( 'docomo', false, 'docomo', 'bot' ), array( 'domainreanimator', false, 'domainreanimator', 'bot' ), array( 'domainstatsbot', false, 'domainstatsbot', 'bot' ), array( 'dotbot', false, 'dotbot', 'bot' ), array( 'downnotifier', false, 'downnotifier', 'bot' ), array( 'ez publish', false, 'ez-publish', 'bot' ), array( 'exabot', false, 'exabot', 'bot' ), array( 'experibot', false, 'experibot', 'bot' ), array( 'ezooms', false, 'ezooms', 'bot' ), array( 'facebookexternalhit', false, 'facebook', 'bot' ), array( 'facebot', false, 'facebook', 'bot' ), array( 'fatbot', false, 'fatbot', 'bot' ), array( 'findxbot', false, 'findxbook', 'bot' ), array( 'gigabot', false, 'gigabot', 'bot' ), array( 'googledocs', false, 'google-docs', 'bot' ), array( 'gozaikbot', false, 'gozaikbot', 'bot' ), array( 'grammarly', false, 'grammarly', 'bot' ), array( 'guardcrwlr', false, 'guardcrwlr', 'bot' ), array( 'headmasterseo', false, 'headmasterseo', 'bot' ), array( 'hosttracker', false, 'hosttracker', 'bot' ), array( 'hubspot', false, 'hubspot', 'bot' ), array( 'hybridbot', false, 'hybridbot', 'bot' ), array( 'ia_archiver', false, 'ia_archiver', 'bot' ), array( 'iltrovatore-setaccio', false, 'il-set', 'bot' ), array( 'imagewalker', false, 'imagewalker', 'bot' ), array( 'istellabot', false, 'istellabot', 'bot' ), array( 'kocmohabt', false, 'kocmohabt', 'bot' ), array( 'lexxebotr', false, 'lexxebotr', 'bot' ), array( 'ltx71', false, 'ltx71', 'bot' ), array( 'mail.ru_bot', false, 'mail.ru_bot', 'bot' ), array( 'mediapartners-google', false, 'adsense', 'bot' ), array( 'mj12bot', false, 'mj12bot', 'bot' ), array( 'naverbot', false, 'naverbot', 'bot' ), array( 'nutch', false, 'nutch', 'bot' ), array( 'objectssearch', false, 'objectsearch', 'bot' ), array( 'omgilibot', false, 'omgilibot', 'bot' ), array( 'openbot', false, 'openbot', 'bot' ), array( 'paperlibot', false, 'paperlibot', 'bot' ), array( 'pingdom', false, 'pingdom', 'bot' ), array( 'pinterest', false, 'pinterest', 'bot' ), array( 'primalbot', false, 'primalbot', 'bot' ), array( 'proximic', false, 'proximic', 'bot' ), array( 'psbot', false, 'psbot', 'bot' ), array( 'pulsepoint', false, 'pulsepoint', 'bot' ), array( 'qwantify', false, 'qwantify', 'bot' ), array( 'rankvalbot', false, 'rankvalbot', 'bot' ), array( 'redback', false, 'redback', 'bot' ), array( 'safednsbot', false, 'safednsbot', 'bot' ), array( 'salesintelligent', false, 'salesintelligent', 'bot' ), array( 'scooter', false, 'scooter', 'bot' ), array( 'scrapy', false, 'scrapy', 'bot' ), array( 'searchie', false, 'searchiet', 'bot' ), array( 'semrushbot', false, 'semrushbot', 'bot' ), array( 'seokicks', false, 'seokicks', 'bot' ), array( 'seobilitybot', false, 'seobilitybot', 'bot' ), array( 'seznambot', false, 'seznambot', 'bot' ), array( 'slackbot', false, 'slackbot', 'bot' ), array( 'slack.com', false, 'slackbot', 'bot' ), array( 'sogou', false, 'sogou', 'bot' ), array( 'socialrankiobot', false, 'socialrankiobot', 'bot' ), array( 'sohu-search', false, 'sohu', 'bot' ), array( 'spbot', false, 'spbot', 'bot' ), array( 'surveybot', false, 'surveybot', 'bot' ), array( 'telegrambot', false, 'telegrambot', 'bot' ), array( 'twitterbot', false, 'twitterbot', 'bot' ), array( 'vbseo', false, 'vbseo', 'bot' ), array( 'xenu', false, 'xenu', 'bot' ), array( 'youdaobot', false, 'youdaobot', 'bot' ), array( 'yahoo link preview', false, 'yahoo-preview', 'bot' ), array( 'yahoo-verticalcrawler', false, 'yahoo', 'bot' ), array( 'yahoo! slurp', false, 'yahoo', 'bot' ), array( 'slurp', false, 'inktomi', 'bot' ), array( 'inktomi', false, 'inktomi', 'bot' ), array( 'yahoo-mm', false, 'yahoomm', 'bot' ), array( 'zyborg', false, 'looksmart', 'bot' ), array( 'ahc', false, 'ahc', 'lib' ), array( 'anyevent-http', false, 'anyevent-http', 'lib' ), array( 'go 1', false, 'go-http', 'lib' ), array( 'w3c_validator', false, 'w3c', 'lib' ), array( 'wdg_validator', false, 'wdg', 'lib' ), array( 'google-http', false, 'google-http', 'lib' ), array( 'okhttp', false, 'okhttp', 'lib' ), array( 'pcore', false, 'pcore-http', 'lib' ), array( 'pear http', false, 'pear', 'lib' ), array( 'winhttp', false, 'winhttp', 'lib' ), array( 'wordpress', false, 'winhttp', 'lib' ), array( 'zgrab', false, 'zgrab', 'lib' ), array( 'curl', false, 'curl', 'dow' ), array( 'guzzle', false, 'guzzle', 'dow' ), array( 'getright', false, 'getright', 'dow' ), array( 'pagefreezer', false, 'pagefreezer', 'dow' ), array( 'wget', false, 'wget', 'dow' ), array( 'zgrab', false, 'zgrab', 'dow' ), array( 'mozilla/4.', false, 'ns', 'bbro' ), array( 'mozilla/3.', false, 'ns', 'bbro' ), array( 'mozilla/2.', false, 'ns', 'bbro' ) );
    $a_blink_types = array( 'opr/', 'otter', 'qupzilla', 'slimjet', 'vivaldi', 'chromium', 'chrome', 'blink' );
    $a_gecko_types = array( 'bonecho', 'camino', 'conkeror', 'epiphany', 'fennec', 'firebird', 'flock', 'galeon', 'iceape', 'icecat', 'k-meleon', 'minimo', 'multizilla', 'phoenix', 'skyfire', 'songbird', 'swiftfox', 'seamonkey', 'shadowfox', 'shiretoko', 'iceweasel', 'firefox', 'minefield', 'netscape6', 'netscape', 'rv' );
    $a_khtml_types = array( 'konqueror', 'khtml' );
    $a_trident_types = array( 'ucbrowser', 'ucweb', 'msie' );
    $a_webkit_types = array( 'arora', 'bolt', 'beamrise', 'chromium', 'puffin', 'chrome', 'crios', 'dooble', 'epiphany', 'gtklauncher', 'icab', 'konqueror', 'maxthon', 'midori', 'omniweb', 'opera', 'otter', 'qupzilla', 'rekonq', 'rocketmelt', 'samsungbrowser', 'silk', 'uzbl', 'ucbrowser', 'ucweb', 'shiira', 'sputnik', 'steel', 'teashark', 'safari', 'slimboat', 'applewebkit', 'webos', 'xxxterm', 'vivaldi', 'yabrowser', 'webkit' );
    $i_count = count( $a_browser_types );
    for ( $i = 0; $i < $i_count; $i++ ) {
      $browser_temp = $a_browser_types[ $i ][ 0 ];
      if ( strstr( $browser_user_agent, $browser_temp ) ) {
        $b_safe_browser = true;
        $browser_name = $browser_temp;
        $b_dom_browser = $a_browser_types[ $i ][ 1 ];
        $browser_working = $a_browser_types[ $i ][ 2 ];
        $ua_type = $a_browser_types[ $i ][ 3 ];
        switch ( $browser_working ) {
          case 'ns':
            $b_safe_browser = false;
            $browser_number = get_item_version( $browser_user_agent, 'mozilla' );
            break;
          case 'blink':
            if ( $browser_name == 'opr/' )get_set_count( 'set', 0 );
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            $layout_engine = 'blink';
            if ( strstr( $browser_user_agent, 'blink' ) ) {
              $layout_engine_nu_full = get_item_version( $browser_user_agent, 'blink' );
            } else {
              $layout_engine_nu_full = get_item_version( $browser_user_agent, 'webkit' );
            }
            $layout_engine_nu = get_item_math_number( $browser_number );
            $j_count = count( $a_blink_types );
            for ( $j = 0; $j < $j_count; $j++ ) {
              if ( strstr( $browser_user_agent, $a_blink_types[ $j ] ) ) {
                $blink_type = $a_blink_types[ $j ];
                if ( $browser_name == 'opr/' )get_set_count( 'set', 0 );
                $blink_type_number = get_item_version( $browser_user_agent, $blink_type );
                $browser_name = $a_blink_types[ $j ];
                if ( $browser_name == 'opr/' )get_set_count( 'set', 0 );
                $browser_number = get_item_version( $browser_user_agent, $browser_name );
                break;
              }
            }
            if ( $browser_name == 'opr/' )$browser_name = 'opera';
            break;
          case 'dillo':
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            $layout_engine = 'dillo';
            $layout_engine_nu = get_item_math_number( $browser_number );
            $layout_engine_nu_full = $browser_number;
            break;
          case 'edge':
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            $layout_engine = 'edgehtml';
            $layout_engine_nu = get_item_math_number( $browser_number );
            $layout_engine_nu_full = $browser_number;
            break;
          case 'khtml':
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            $layout_engine = 'khtml';
            $layout_engine_nu = get_item_math_number( $browser_number );
            $layout_engine_nu_full = $browser_number;
            $j_count = count( $a_khtml_types );
            for ( $j = 0; $j < $j_count; $j++ ) {
              if ( strstr( $browser_user_agent, $a_khtml_types[ $j ] ) ) {
                $khtml_type = $a_khtml_types[ $j ];
                $khtml_type_number = get_item_version( $browser_user_agent, $khtml_type );
                $browser_name = $a_khtml_types[ $j ];
                $browser_number = get_item_version( $browser_user_agent, $browser_name );
                break;
              }
            }
            break;
          case 'moz':
            get_set_count( 'set', 0 );
            $moz_rv_full = get_item_version( $browser_user_agent, 'rv:' );
            $moz_rv = floatval( $moz_rv_full );
            $j_count = count( $a_gecko_types );
            for ( $j = 0; $j < $j_count; $j++ ) {
              if ( strstr( $browser_user_agent, $a_gecko_types[ $j ] ) ) {
                $moz_type = $a_gecko_types[ $j ];
                $moz_type_number = get_item_version( $browser_user_agent, $moz_type );
                break;
              }
            }
            if ( !$moz_rv ) {
              $moz_rv = floatval( $moz_type_number );
              $moz_rv_full = $moz_type_number;
            }
            if ( $moz_type == 'rv' )$moz_type = 'mozilla';
            $browser_number = $moz_rv;
            get_set_count( 'set', 0 );
            $moz_release_date = get_item_version( $browser_user_agent, 'gecko/' );
            $layout_engine = 'gecko';
            $layout_engine_nu = $moz_rv;
            $layout_engine_nu_full = $moz_rv_full;
            if ( ( $moz_release_date < 20020400 ) || ( $moz_rv < 1 ) )$b_safe_browser = false;
            break;
          case 'ie':
            $b_gecko_ua = false;
            if ( strstr( $browser_user_agent, 'rv:' ) ) {
              $browser_name = 'msie';
              $b_gecko_ua = true;
              get_set_count( 'set', 0 );
              $browser_number = get_item_version( $browser_user_agent, 'rv:', '', '' );
            } else $browser_number = get_item_version( $browser_user_agent, $browser_name, true, 'trident/' );
            get_set_count( 'set', 0 );
            $layout_engine_nu_full = get_item_version( $browser_user_agent, 'trident/', '', '' );
            if ( $layout_engine_nu_full ) {
              $layout_engine_nu = get_item_math_number( $layout_engine_nu_full );
              $layout_engine = 'trident';
              if ( strstr( $browser_number, '7.' ) && !$b_gecko_ua )$true_ie_number = get_item_math_number( $browser_number ) + ( intval( $layout_engine_nu ) - 3 );
              else $true_ie_number = $browser_number;
              $j_count = count( $a_trident_types );
              for ( $j = 0; $j < $j_count; $j++ ) {
                if ( strstr( $browser_user_agent, $a_trident_types[ $j ] ) ) {
                  $trident_type = $a_trident_types[ $j ];
                  $trident_type_number = get_item_version( $browser_user_agent, $trident_type );
                  break;
                }
              }
              if ( !$trident_type && $b_gecko_ua ) {
                $trident_type = 'msie';
                $trident_type_number = $browser_number;
              }
            } elseif ( intval( $browser_number ) <= 7 && intval( $browser_number ) >= 4 ) {
              $layout_engine = 'trident';
              if ( intval( $browser_number ) == 7 ) {
                $layout_engine_nu_full = '3.1';
                $layout_engine_nu = '3.1';
              }
            }
            if ( $browser_number >= 9 )$ie_version = 'ie9x';
            else if ( $browser_number >= 7 )$ie_version = 'ie7x';
            elseif ( strstr( $browser_user_agent, 'mac' ) )$ie_version = 'ieMac';
            elseif ( $browser_number >= 5 )$ie_version = 'ie5x';
            elseif ( ( $browser_number > 3 ) && ( $browser_number < 5 ) ) {
              $b_dom_browser = false;
              $ie_version = 'ie4';
              $b_safe_browser = true;
            } else {
              $ie_version = 'old';
              $b_dom_browser = false;
              $b_safe_browser = false;
            }
            break;
          case 'op':
            if ( $browser_name == 'opr/' )$browser_name = 'opr';
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            if ( strstr( $browser_number, '9.' ) && strstr( $browser_user_agent, 'version/' ) ) {
              get_set_count( 'set', 0 );
              $browser_number = get_item_version( $browser_user_agent, 'version/' );
            }
            get_set_count( 'set', 0 );
            $layout_engine_nu_full = get_item_version( $browser_user_agent, 'presto/' );
            if ( $layout_engine_nu_full ) {
              $layout_engine = 'presto';
              $layout_engine_nu = get_item_math_number( $layout_engine_nu_full );
            }
            if ( !$layout_engine_nu_full && $browser_name == 'opr' ) {
              if ( strstr( $browser_user_agent, 'blink' ) )$layout_engine_nu_full = get_item_version( $browser_user_agent, 'blink' );
              else $layout_engine_nu_full = get_item_version( $browser_user_agent, 'webkit' );
              $layout_engine_nu = get_item_math_number( $layout_engine_nu_full );
              $layout_engine = 'blink';
              $browser_name = 'opera';
            }
            if ( $browser_number < 5 )$b_safe_browser = false;
            break;
          case 'webkit':
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            $layout_engine = 'webkit';
            $layout_engine_nu = get_item_math_number( $browser_number );
            $layout_engine_nu_full = $browser_number;
            $j_count = count( $a_webkit_types );
            for ( $j = 0; $j < $j_count; $j++ ) {
              if ( strstr( $browser_user_agent, $a_webkit_types[ $j ] ) ) {
                $webkit_type = $a_webkit_types[ $j ];
                if ( $webkit_type == 'omniweb' )get_set_count( 'set', 2 );
                $webkit_type_number = get_item_version( $browser_user_agent, $webkit_type );
                if ( $a_webkit_types[ $j ] == 'gtklauncher' )$browser_name = 'epiphany';
                else $browser_name = $a_webkit_types[ $j ];
                if ( ( $a_webkit_types[ $j ] == 'chrome' || $a_webkit_types[ $j ] == 'chromium' ) && get_item_math_number( $webkit_type_number ) >= 28 ) {
                  if ( strstr( $browser_user_agent, 'blink' ) ) {
                    $layout_engine_nu_full = get_item_version( $browser_user_agent, 'blink' );
                    $layout_engine_nu = get_item_math_number( $layout_engine_nu_full );
                  }
                  $layout_engine = 'blink';
                }
                $browser_number = get_item_version( $browser_user_agent, $browser_name );
                break;
              }
            }
            break;
          default:
            $browser_number = get_item_version( $browser_user_agent, $browser_name );
            break;
        }
        $b_success = true;
        break;
      }
    }
    if ( !$b_success ) {
      $browser_name = substr( $browser_user_agent, 0, strcspn( $browser_user_agent, '();' ) );
      if ( $browser_name && preg_match( '/[^0-9][a-z]*-*\ *[a-z]*\ *[a-z]*/', $browser_name, $a_unhandled_browser ) ) {
        $browser_name = $a_unhandled_browser[ 0 ];
        if ( $browser_name == 'blackberry' )get_set_count( 'set', 0 );
        $browser_number = get_item_version( $browser_user_agent, $browser_name );
      } else {
        $browser_name = 'NA';
        $browser_number = 'NA';
      }
    }
    if ( $b_os_test ) {
      $a_os_data = get_os_data( $browser_user_agent, $browser_working, $browser_number );
      $os_type = $a_os_data[ 0 ];
      $os_number = $a_os_data[ 1 ];
    }
    $b_repeat = true;
    $browser_math_number = get_item_math_number( $browser_number );
    if ( $b_mobile_test ) {
      $mobile_test = check_is_mobile( $browser_user_agent );
      if ( $mobile_test ) {
        $a_mobile_data = get_mobile_data( $browser_user_agent );
        $ua_type = 'mobile';
      }
    }
  }
  switch ( $which_test ) {
    case 'math_number':
      $which_test = 'browser_math_number';
      break;
    case 'number':
      $which_test = 'browser_number';
      break;
    case 'browser':
      $which_test = 'browser_working';
      break;
    case 'moz_version':
      $which_test = 'moz_data';
      break;
    case 'true_msie_version':
      $which_test = 'true_ie_number';
      break;
    case 'type':
      $which_test = 'ua_type';
      break;
    case 'webkit_version':
      $which_test = 'webkit_data';
      break;
  }
  if ( !$a_engine_data )$a_engine_data = array( $layout_engine, $layout_engine_nu_full, $layout_engine_nu );
  if ( !$a_blink_data )$a_blink_data = array( $blink_type, $blink_type_number, $browser_number );
  if ( !$a_khtml_data )$a_khtml_data = array( $khtml_type, $khtml_type_number, $browser_number );
  if ( !$a_moz_data )$a_moz_data = array( $moz_type, $moz_type_number, $moz_rv, $moz_rv_full, $moz_release_date );
  if ( !$a_trident_data )$a_trident_data = array( $trident_type, $trident_type_number, $layout_engine_nu, $browser_number );
  if ( !$a_webkit_data )$a_webkit_data = array( $webkit_type, $webkit_type_number, $browser_number );
  if ( $layout_engine_nu )$html_type = get_html_level( $layout_engine, $layout_engine_nu );
  if ( !$a_full_assoc_data )$a_full_assoc_data = array( 'browser_working' => $browser_working, 'browser_number' => $browser_number, 'ie_version' => $ie_version, 'dom' => $b_dom_browser, 'safe' => $b_safe_browser, 'os' => $os_type, 'os_number' => $os_number, 'browser_name' => $browser_name, 'ua_type' => $ua_type, 'browser_math_number' => $browser_math_number, 'moz_data' => $a_moz_data, 'webkit_data' => $a_webkit_data, 'mobile_test' => $mobile_test, 'mobile_data' => $a_mobile_data, 'true_ie_number' => $true_ie_number, 'run_time' => $run_time, 'html_type' => $html_type, 'engine_data' => $a_engine_data, 'trident_data' => $a_trident_data, 'blink_data' => $a_blink_data );
  switch ( $which_test ) {
    case 'full':
      $a_full_data = array( $browser_working, $browser_number, $ie_version, $b_dom_browser, $b_safe_browser, $os_type, $os_number, $browser_name, $ua_type, $browser_math_number, $a_moz_data, $a_webkit_data, $mobile_test, $a_mobile_data, $true_ie_number, $run_time, $html_type, $a_engine_data, $a_trident_data, $a_blink_data );
      return $a_full_data;
      break;
    case 'full_assoc':
      return $a_full_assoc_data;
      break;
    case 'header_data':
      if ( !headers_sent() ) {
        if ( stristr( $_SERVER[ "HTTP_ACCEPT" ], "application/xhtml+xml" ) ) {} else {}
      }
      break;
    default:
      if ( isset( $a_full_assoc_data[ $which_test ] ) ) {
        return $a_full_assoc_data[ $which_test ];
      } else {
        die( "You passed the browser detector an unsupported option for parameter 1: " . $which_test );
      }
      break;
  }
}

function get_item_math_number( $pv_browser_number ) {
  $browser_math_number = '';
  if ( $pv_browser_number && preg_match( '/^[0-9]*\.*[0-9]*/', $pv_browser_number, $a_browser_math_number ) ) {
    $browser_math_number = $a_browser_math_number[ 0 ];
  }
  return $browser_math_number;
}

function get_os_data( $pv_browser_string, $pv_browser_name, $pv_version_number ) {
  $os_working_type = '';
  $os_working_number = '';
  $a_mac = array( 'intel mac', 'OS X', 'ppc mac', 'mac68k' );
  $a_unix_types = array( 'dragonfly', 'freebsd', 'openbsd', 'netbsd', 'bsd', 'unixware', 'solaris', 'sunos', 'sun4', 'sun5', 'suni86', 'sun', 'irix5', 'irix6', 'irix', 'hpux9', 'hpux10', 'hpux11', 'hpux', 'hp-ux', 'aix1', 'aix2', 'aix3', 'aix4', 'aix5', 'aix', 'sco', 'unixware', 'mpras', 'reliant', 'dec', 'sinix', 'unix' );
  $a_linux_distros = array( ' cros ', 'ubuntu', 'kubuntu', 'xubuntu', 'mepis', 'xandros', 'linspire', 'winspire', 'jolicloud', 'sidux', 'kanotix', 'debian', 'opensuse', 'suse', 'fedora', 'redhat', 'slackware', 'slax', 'mandrake', 'mandriva', 'gentoo', 'sabayon', 'linux' );
  $a_linux_process = array( 'i386', 'i586', 'i686', 'x86_64' );
  $a_os_types = array( 'blackberry', 'iphone', 'palmos', 'palmsource', 'symbian', 'beos', 'os2', 'amiga', 'webtv', 'macintosh', 'mac_', 'mac ', 'nt', 'win', 'android', $a_unix_types, $a_linux_distros );
  $i_count = count( $a_os_types );
  for ( $i = 0; $i < $i_count; $i++ ) {
    $os_working_data = $a_os_types[ $i ];
    if ( !is_array( $os_working_data ) && strstr( $pv_browser_string, $os_working_data ) && !strstr( $pv_browser_string, "linux" ) ) {
      $os_working_type = $os_working_data;
      switch ( $os_working_type ) {
        case 'nt':
          preg_match( '/nt ([0-9]+[\.]?[0-9]?)/', $pv_browser_string, $a_nt_matches );
          if ( isset( $a_nt_matches[ 1 ] ) ) {
            $os_working_number = $a_nt_matches[ 1 ];
          }
          break;
        case 'win':
          if ( strstr( $pv_browser_string, 'vista' ) ) {
            $os_working_number = 6.0;
            $os_working_type = 'nt';
          } elseif ( strstr( $pv_browser_string, 'xp' ) ) {
            $os_working_number = 5.1;
            $os_working_type = 'nt';
          } elseif ( strstr( $pv_browser_string, '2003' ) ) {
            $os_working_number = 5.2;
            $os_working_type = 'nt';
          } elseif ( strstr( $pv_browser_string, 'windows ce' ) ) {
            $os_working_number = 'ce';
            $os_working_type = 'nt';
          } elseif ( strstr( $pv_browser_string, '95' ) ) {
            $os_working_number = '95';
          } elseif ( ( strstr( $pv_browser_string, '9x 4.9' ) ) || ( strstr( $pv_browser_string, ' me' ) ) ) {
            $os_working_number = 'me';
          } elseif ( strstr( $pv_browser_string, '98' ) ) {
            $os_working_number = '98';
          } elseif ( strstr( $pv_browser_string, '2000' ) ) {
            $os_working_number = 5.0;
            $os_working_type = 'nt';
          }
          break;
        case 'mac ':
        case 'mac_':
        case 'macintosh':
          $os_working_type = 'mac';
          if ( strstr( $pv_browser_string, 'os x' ) ) {
            if ( strstr( $pv_browser_string, 'os x ' ) )$os_working_number = str_replace( '_', '.', get_item_version( $pv_browser_string, 'os x' ) );
            else $os_working_number = 10;
          } elseif ( $pv_browser_name == 'saf' || $pv_browser_name == 'cam' || ( ( $pv_browser_name == 'moz' ) && ( $pv_version_number >= 1.3 ) ) || ( ( $pv_browser_name == 'ie' ) && ( $pv_version_number >= 5.2 ) ) ) {
            $os_working_number = 10;
          }
          break;
        case 'iphone':
          $os_working_number = 10;
          break;
        default:
          break;
      }
      break;
    } elseif ( is_array( $os_working_data ) && ( $i == ( $i_count - 2 ) ) ) {
      $j_count = count( $os_working_data );
      for ( $j = 0; $j < $j_count; $j++ ) {
        if ( strstr( $pv_browser_string, $os_working_data[ $j ] ) ) {
          $os_working_type = 'unix';
          $os_working_number = ( $os_working_data[ $j ] != 'unix' ) ? $os_working_data[ $j ] : '';
          break;
        }
      }
    } elseif ( is_array( $os_working_data ) && ( $i == ( $i_count - 1 ) ) ) {
      $j_count = count( $os_working_data );
      for ( $j = 0; $j < $j_count; $j++ ) {
        if ( strstr( $pv_browser_string, $os_working_data[ $j ] ) ) {
          $os_working_type = 'lin';
          $os_working_number = ( $os_working_data[ $j ] != 'linux' ) ? $os_working_data[ $j ] : '';
          break;
        }
      }
    }
  }
  $a_os_data = array( $os_working_type, $os_working_number );
  return $a_os_data;
}

function get_item_version( $pv_browser_user_agent, $pv_search_string, $pv_b_break_last = '', $pv_extra_search = '' ) {
  $substring_length = 15;
  $start_pos = 0;
  $string_working_number = '';
  for ( $i = 0; $i < 4; $i++ ) {
    if ( strpos( $pv_browser_user_agent, $pv_search_string, $start_pos ) !== false ) {
      $start_pos = strpos( $pv_browser_user_agent, $pv_search_string, $start_pos ) + strlen( $pv_search_string );
      if ( !$pv_b_break_last || ( $pv_extra_search && strstr( $pv_browser_user_agent, $pv_extra_search ) ) ) break;
    } else break;
  }
  $start_pos += get_set_count( 'get' );
  $string_working_number = substr( $pv_browser_user_agent, $start_pos, $substring_length );
  $string_working_number = substr( $string_working_number, 0, strcspn( $string_working_number, ' );/' ) );
  if ( !is_numeric( substr( $string_working_number, 0, 1 ) ) )$string_working_number = '';
  return $string_working_number;
}

function get_set_count( $pv_type, $pv_value = '' ) {
  static $slice_increment;
  $return_value = '';
  switch ( $pv_type ) {
    case 'get':
      if ( is_null( $slice_increment ) )$slice_increment = 1;
      $return_value = $slice_increment;
      $slice_increment = 1;
      return $return_value;
      break;
    case 'set':
      $slice_increment = $pv_value;
      break;
  }
}

function check_is_mobile( $pv_browser_user_agent ) {
  $mobile_working_test = '';
  $a_mobile_search = array( 'android', 'blackberry', 'epoc', 'palmos', 'palmsource', 'windows ce', 'windows phone os', 'windows phone', 'symbianos', 'symbian os', 'symbian', 'webos', 'benq', 'blackberry', 'danger hiptop', 'ddipocket', ' droid', 'ipad', 'ipod', 'iphone', 'kindle', 'kobo', 'lge-cx', 'lge-lx', 'lge-mx', 'lge vx', 'lge ', 'lge-', 'lg;lx', 'nexus', 'nintendo wii', 'nokia', 'nook', 'palm', 'pdxgw', 'playstation', 'rim', 'sagem', 'samsung', 'sec-sgh', 'sharp', 'sonyericsson', 'sprint', 'zune', 'j-phone', 'n410', 'mot 24', 'mot-', 'htc-', 'htc_', 'htc ', 'playbook', 'sec-', 'sie-m', 'sie-s', 'spv ', 'touchpad', 'vodaphone', 'smartphone', 'midp', 'mobilephone', 'avantgo', 'blazer', 'elaine', 'eudoraweb', 'fennec', 'iemobile', 'minimo', 'mobile safari', 'mobileexplorer', 'opera mobi', 'opera mini', 'netfront', 'opwv', 'polaris', 'puffin', 'samsungbrowser', 'semc-browser', 'skyfire', 'up.browser', 'ucweb', 'ucbrowser', 'webpro/', 'wms pie', 'xiino', 'astel', 'docomo', 'novarra-vision', 'portalmmm', 'reqwirelessweb', 'vodafone' );
  $j_count = count( $a_mobile_search );
  for ( $j = 0; $j < $j_count; $j++ ) {
    if ( strstr( $pv_browser_user_agent, $a_mobile_search[ $j ] ) ) {
      if ( $a_mobile_search[ $j ] != 'zune' || strstr( $pv_browser_user_agent, 'iemobile' ) ) {
        $mobile_working_test = $a_mobile_search[ $j ];
        break;
      }
    }
  }
  return $mobile_working_test;
}

function get_mobile_data( $pv_browser_user_agent ) {
  $mobile_browser = '';
  $mobile_browser_number = '';
  $mobile_device = '';
  $mobile_device_number = '';
  $mobile_os = '';
  $mobile_os_number = '';
  $mobile_server = '';
  $mobile_server_number = '';
  $mobile_tablet = '';
  $a_mobile_browser = array( 'avantgo', 'blazer', 'crios', 'elaine', 'eudoraweb', 'fennec', 'iemobile', 'minimo', 'ucweb', 'ucbrowser', 'mobile safari', 'mobileexplorer', 'opera mobi', 'opera mini', 'netfront', 'opwv', 'polaris', 'puffin', 'samsungbrowser', 'semc-browser', 'silk', 'steel', 'ultralight', 'up.browser', 'webos', 'webpro/', 'wms pie', 'xiino' );
  $a_mobile_device = array( 'benq', 'blackberry', 'danger hiptop', 'ddipocket', ' droid', 'htc_dream', 'htc espresso', 'htc hero', 'htc halo', 'htc huangshan', 'htc legend', 'htc liberty', 'htc paradise', 'htc supersonic', 'htc tattoo', 'ipad', 'ipod', 'iphone', 'kindle', 'kobo', 'lge-cx', 'lge-lx', 'lge-mx', 'lge vx', 'lg;lx', 'nexus', 'nintendo wii', 'nokia', 'nook', 'palm', 'pdxgw', 'playstation', 'sagem', 'samsung', 'sec-sgh', 'sharp', 'sonyericsson', 'sprint', 'j-phone', 'milestone', 'n410', 'mot 24', 'mot-', 'htc-', 'htc_', 'htc ', 'lge ', 'lge-', 'sec-', 'sie-m', 'sie-s', 'spv ', 'smartphone', 'midp', 'mobilephone', 'wp', 'zunehd', 'zune' );
  $a_mobile_os = array( 'windows phone os', 'windows phone', 'android', 'blackberry', 'epoc', 'cpu os', 'iphone os', 'palmos', 'palmsource', 'windows ce', 'symbianos', 'symbian os', 'symbian', 'webos' );
  $a_mobile_server = array( 'astel', 'docomo', 'novarra-vision', 'portalmmm', 'reqwirelessweb', 'vodafone' );
  $a_mobile_tablet = array( 'ipad', 'android 3', 'cros', ' gt-p', 'sm-t', 'kindle', 'kobo', 'nook', 'playbook', 'silk', 'touchpad', 'tablet', 'xoom' );
  $k_count = count( $a_mobile_browser );
  for ( $k = 0; $k < $k_count; $k++ ) {
    if ( strstr( $pv_browser_user_agent, $a_mobile_browser[ $k ] ) ) {
      $mobile_browser = $a_mobile_browser[ $k ];
      $mobile_browser_number = get_item_version( $pv_browser_user_agent, $mobile_browser );
      break;
    }
  }
  $k_count = count( $a_mobile_device );
  for ( $k = 0; $k < $k_count; $k++ ) {
    if ( strstr( $pv_browser_user_agent, $a_mobile_device[ $k ] ) ) {
      $mobile_device = trim( $a_mobile_device[ $k ], '-_' );
      if ( $mobile_device == 'blackberry' ) {
        get_set_count( 'set', 0 );
      }
      $mobile_device_number = get_item_version( $pv_browser_user_agent, $mobile_device );
      $mobile_device = trim( $mobile_device );
      break;
    }
  }
  $k_count = count( $a_mobile_os );
  for ( $k = 0; $k < $k_count; $k++ ) {
    if ( strstr( $pv_browser_user_agent, $a_mobile_os[ $k ] ) ) {
      $mobile_os = $a_mobile_os[ $k ];
      if ( $mobile_os != 'blackberry' ) {
        $mobile_os_number = str_replace( '_', '.', get_item_version( $pv_browser_user_agent, $mobile_os ) );
      } else {
        $mobile_os_number = str_replace( '_', '.', get_item_version( $pv_browser_user_agent, 'version' ) );
        if ( empty( $mobile_os_number ) ) {
          get_set_count( 'set', 5 );
          $mobile_os_number = str_replace( '_', '.', get_item_version( $pv_browser_user_agent, $mobile_os ) );
        }
      }
      break;
    }
  }
  $k_count = count( $a_mobile_server );
  for ( $k = 0; $k < $k_count; $k++ ) {
    if ( strstr( $pv_browser_user_agent, $a_mobile_server[ $k ] ) ) {
      $mobile_server = $a_mobile_server[ $k ];
      $mobile_server_number = get_item_version( $pv_browser_user_agent, $mobile_server );
      break;
    }
  }
  $pattern = '/android[[:space:]]*[4-9]/';
  if ( preg_match( $pattern, $pv_browser_user_agent ) && !stristr( $pv_browser_user_agent, 'mobile' ) ) {
    $mobile_tablet = 'android tablet';
  } else {
    $k_count = count( $a_mobile_tablet );
    for ( $k = 0; $k < $k_count; $k++ ) {
      if ( strstr( $pv_browser_user_agent, $a_mobile_tablet[ $k ] ) ) {
        $mobile_tablet = trim( $a_mobile_tablet[ $k ] );
        if ( $mobile_tablet == 'gt-p' || $mobile_tablet == 'sch-i' || $mobile_tablet == 'sm-t' ) {
          $mobile_tablet = 'galaxy-' . $mobile_tablet;
        } elseif ( $mobile_tablet == 'silk' )$mobile_tablet = 'kindle fire';
        break;
      }
    }
  }
  if ( !$mobile_os && ( $mobile_browser || $mobile_device || $mobile_server ) && strstr( $pv_browser_user_agent, 'linux' ) ) {
    $mobile_os = 'linux';
    $mobile_os_number = get_item_version( $pv_browser_user_agent, 'linux' );
  }
  $a_mobile_data = array( $mobile_device, $mobile_browser, $mobile_browser_number, $mobile_os, $mobile_os_number, $mobile_server, $mobile_server_number, $mobile_device_number, $mobile_tablet );
  return $a_mobile_data;
}

function get_html_level( $pv_render_engine, $pv_render_engine_nu ) {
  $html_return = 1;
  $engine_nu = $pv_render_engine_nu;
  $a_html5_basic = array( 'blink' => 10, 'edgehtml' => 4, 'gecko' => 20, 'khtml' => 45, 'presto' => 20, 'trident' => 50, 'webkit' => 5250 );
  $a_html5_forms = array( 'blink' => 10, 'edgehtml' => 4, 'gecko' => 20, 'khtml' => 50, 'presto' => 20, 'trident' => 60, 'webkit' => 5280 );
  $engine_nu = intval( 10 * floatval( $engine_nu ) );
  if ( array_key_exists( $pv_render_engine, $a_html5_forms ) && $a_html5_forms[ $pv_render_engine ] <= $engine_nu )$html_return = 3;
  elseif ( array_key_exists( $pv_render_engine, $a_html5_basic ) && $a_html5_basic[ $pv_render_engine ] <= $engine_nu )$html_return = 2;
  return $html_return;
}

function userInfo( $ext_ua = '' ) {
  $os = '';
  $os_starter = '';
  $os_finish = '';
  $full = '';
  $handheld = '';
  $tablet = '';
  $userinfo = array();
  $browser_info = browser_detection( 'full', false, $ext_ua );
  if ( $browser_info[ 8 ] == 'mobile' ) {
    if ( $browser_info[ 13 ][ 8 ] ) {
      if ( $browser_info[ 13 ][ 0 ] )$tablet = ' (tablet)';
      else $handheld .= ucwords( $browser_info[ 13 ][ 8 ] ) . ' Tablet</br>';
      $userinfo[ 'device' ] = $browser_info[ 13 ][ 8 ];
    }
    if ( $browser_info[ 13 ][ 0 ] ) {
      $userinfo[ 'device' ] = $browser_info[ 13 ][ 0 ];
      $handheld .= 'Type: ' . ucwords( $browser_info[ 13 ][ 0 ] );
      if ( $browser_info[ 13 ][ 7 ] )$handheld = $handheld . ' v: ' . $browser_info[ 13 ][ 7 ];
      $handheld = $handheld . $tablet . '<br />';
    }
    if ( $browser_info[ 13 ][ 3 ] ) {
      if ( $browser_info[ 13 ][ 3 ] == 'cpu os' )$browser_info[ 13 ][ 3 ] = 'ipad os';
      $handheld .= 'OS: ' . ucwords( $browser_info[ 13 ][ 3 ] ) . ' ' . $browser_info[ 13 ][ 4 ] . '<br />';
      if ( !$browser_info[ 5 ] ) {
        $os_starter = '';
        $os_finish = '';
      }
    }
    // let people know OS couldn't be figured out
    if ( !$browser_info[ 5 ] && $os_starter )$os_starter .= 'OS: N/A';
    if ( $browser_info[ 13 ][ 1 ] )$handheld .= 'Browser: ' . ucwords( $browser_info[ 13 ][ 1 ] ) . ' ' . $browser_info[ 13 ][ 2 ] . '<br />';
    if ( $browser_info[ 13 ][ 5 ] )$handheld .= 'Server: ' . ucwords( $browser_info[ 13 ][ 5 ] . ' ' . $browser_info[ 13 ][ 6 ] ) . '<br />';
    $handheld .= '</p>';
  }

  switch ( $browser_info[ 5 ] ) {
    case 'win':
      $os .= 'Windows ';
      break;
    case 'nt':
      $os .= 'Windows ';
      break;
    case 'lin':
      $os .= '';
      break;
    case 'mac':
      if ( $browser_info[ 6 ] > 10.12 )$os .= 'macOS ';
      else $os .= 'OS X ';
      break;
    case 'iphone':
      $os .= 'iOS ';
      break;
    case 'unix':
      $os .= 'Unix Version: ';
      break;
    default:
      $os .= $browser_info[ 5 ];
      $userinfo[ 'osversion' ] = $browser_info[ 5 ];
  }
  if ( $browser_info[ 5 ] == 'nt' ) {
    if ( $browser_info[ 5 ] == 'nt' ) {
      switch ( $browser_info[ 6 ] ) {
        case '5.0':
          $os .= '2000';
          break;
        case '5.1':
          $os .= 'XP';
          break;
        case '5.2':
          $os .= 'XP x64 Edition or Server 2003';
          break;
        case '6.0':
          $os .= 'Vista';
          break;
        case '6.1':
          $os .= '7';
          break;
        case '6.2':
          $os .= '8';
          break;
        case '6.3':
          $os .= '8.1';
          break;
        case '10.0':
          $os .= '10';
          break;
        case 'ce':
          $os .= 'CE';
          break;
          # note: browser detection 5.4.5 and later return always
          # the nt number in <number>.<number> format, so can use it
          # safely.
        default:
          if ( $browser_info[ 6 ] != '' ) {
            $os .= $browser_info[ 6 ];
          } else {
            $os .= '(Unknown Version)';
          }
          break;
      }
    }
  } elseif ( $browser_info[ 5 ] == 'iphone' ) {
      $explode = explode( "i", $userinfo[ 'device' ] );
      $this_device = "i" . ucwords( $explode[ 1 ] );
      $os = "$this_device $os " . $browser_info[ 13 ][ 4 ];
    }
    // note: browser detection now returns os x version number if available, 10 or 10.4.3 style
  elseif ( ( $browser_info[ 5 ] == 'mac' ) && ( strstr( $browser_info[ 6 ], '10' ) ) ) {
    if ( strpos( $browser_info[ 6 ], "10.14" ) !== false )$os .= "Mojave " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.13" ) !== false )$os .= "High Sierra " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.12" ) !== false )$os .= "Sierra " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.11" ) !== false )$os .= "El Capitan " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.10" ) !== false )$os .= "Yosemite " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.9" ) !== false )$os .= "Mavericks " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.8" ) !== false )$os .= "Mountain Lion " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.7" ) !== false )$os .= "Lion " . $browser_info[ 6 ];
    else if ( strpos( $browser_info[ 6 ], "10.6" ) !== false )$os .= "Snow Leopard " . $browser_info[ 6 ];
    else $os .= $browser_info[ 6 ];
  }
  elseif ( $browser_info[ 12 ] == 'android' ) {
    if ( strpos( $browser_info[ 13 ][ 4 ], "9" ) !== false )$version = "Pie " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "8" ) !== false )$version = "Oreo " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "7" ) !== false )$version = "Nougat " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "6" ) !== false )$version = "Marshmellow " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "5" ) !== false )$version = "Lollipop " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "4.4" ) !== false )$version = "KitKat " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "4.1" ) !== false )$version = "Jelly Bean " . $browser_info[ 13 ][ 4 ];
    else if ( strpos( $browser_info[ 13 ][ 4 ], "4" ) !== false )$version = "Ice Cream Sandwich " . $browser_info[ 13 ][ 4 ];
    else $version = $browser_info[ 13 ][ 4 ];
    $os .= "Android $version";
  }
  elseif ( $browser_info[ 5 ] == 'lin' )$os .= 'Linux';
  // default case for cases where version number exists
  elseif ( $browser_info[ 5 ] && $browser_info[ 6 ] )$os .= " " . ucwords( $browser_info[ 6 ] );
  elseif ( $browser_info[ 5 ] && $browser_info[ 6 ] == '' )$os .= ' (Unknown version)';
  elseif ( $browser_info[ 5 ] )$os .= ucwords( $browser_info[ 5 ] );
  $userinfo[ 'os' ] = $os;
  $os = $os_starter . $os . $os_finish;
  $full .= $handheld . $os;
  switch ( $browser_info[ 0 ] ) {
    case 'moz':
      $a_temp = $browser_info[ 10 ]; // use the moz array
      $full .= ( $a_temp[ 0 ] != 'mozilla' ) ? 'Mozilla/ ' . ucwords( $a_temp[ 0 ] ) . ' ': ucwords( $a_temp[ 0 ] ) . ' ';
      $full .= $a_temp[ 1 ] . '<br />';
      $full .= 'ProductSub: ';
      $full .= ( $a_temp[ 4 ] != '' ) ? $a_temp[ 4 ] : 'Not Available';
      break;
    case 'ns':
      $full .= 'Browser: Netscape<br />';
      $full .= 'Full Version Info: ' . $browser_info[ 1 ];
      break;
    case 'webkit':
      $a_temp = $browser_info[ 11 ]; // use the webkit array
      $full .= 'User Agent: ';
      $full .= ucwords( $a_temp[ 0 ] ) . ' ' . $a_temp[ 1 ];
      break;
    case 'ie':
      $full .= 'User Agent: ';
      $full .= strtoupper( $browser_info[ 7 ] );
      if ( $browser_info[ 14 ] ) {
        if ( $browser_info[ 14 ] != $browser_info[ 1 ] ) {
          $full .= '<br />(compatibility mode)';
          $full .= '<br />Actual Version: ' . number_format( $browser_info[ 14 ], '1', '.', '' );
          $full .= '<br />Compatibility Version: ' . $browser_info[ 1 ];
        } else {
          if ( is_numeric( $browser_info[ 1 ] ) && $browser_info[ 1 ] < 11 )$full .= '<br />(standard mode)';
          $full .= '<br />Full Version Info: ' . $browser_info[ 1 ];
        }
      } else {
        $full .= '<br />Full Version Info: ';
        $full .= ( $browser_info[ 1 ] ) ? $browser_info[ 1 ] : 'Not Available';
      }
      break;
    default:
      $full .= 'User Agent: ';
      $full .= ucwords( $browser_info[ 7 ] );
      $full .= '<br />Full Version Info: ';
      $full .= ( $browser_info[ 1 ] ) ? $browser_info[ 1 ] : 'Not Available';
      break;
  }

  if ( $browser_info[ 1 ] != $browser_info[ 9 ] ) {
    $full .= '<br />Main Version Number: ' . $browser_info[ 9 ];
    $userinfo[ 'browserversion' ] = $browser_info[ 9 ];
  }
  if ( $browser_info[ 17 ][ 0 ] ) {
    $full .= '<br />Layout Engine: ' . ucfirst( ( $browser_info[ 17 ][ 0 ] ) );
    $userinfo[ 'engine' ] = ucfirst( ( $browser_info[ 17 ][ 0 ] ) );
    if ( $browser_info[ 17 ][ 1 ] ) {
      $full .= '<br />Engine Version: ' . ( $browser_info[ 17 ][ 1 ] );
      $userinfo[ 'engine' ] = $userinfo[ 'engine' ] . " " . ( $browser_info[ 17 ][ 1 ] );
    }
  }
  return $userinfo;
}
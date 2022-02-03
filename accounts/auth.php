<?php
require_once '../global.php';
$session_token = randString(40);
$action = $_POST[ 'action' ];

error_reporting(0);

// grab recaptcha library
include_once '../recaptchalib.php';
// your secret key
$secret = $_SERVER['RECAPTCHA_SECRET'];
// empty response
$response = null;
// check secret key
$reCaptcha = new ReCaptcha($secret);
// if submitted check response
if ($_POST[ "g-recaptcha-response" ]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER[ "REMOTE_ADDR" ],
        $_POST[ "g-recaptcha-response" ]
    );
}

$remote = false;

// Protect against remote access that isn't the app or website
//if ( $_SERVER[ 'SERVER_ADDR' ] !== $_SERVER[ 'REMOTE_ADDR' ] ) {
//  $app = $_POST[ 'thisistheapp' ];
//  $token = $_POST[ 'token' ];
//  $session = dataArray( "sessions", $token, "token" );
//  if ( $app || $token && $session ) {
//    $remote = false;
//  }
//} else $remote = false;

// Account management backend
if ($remote === true) {
    $this->output->set_status_header(400, 'No Remote Access Allowed');
    exit; //just for good measure
} else {
    if ($action === "createuser") {
        try {
            // $defaults = array();
            // if ($handle = opendir("default")) {
            //     while (false !== ($entry = readdir($handle))) {
            //         if ($entry != "." && $entry != "..") {
            //             array_push($defaults, pathinfo("/accounts/default/$entry", PATHINFO_FILENAME));
            //         }
            //     }
            //     closedir($handle);
            // }
            if ($response === null && !$response->success) {
                throw new Exception("No offense, but please confirm you're not a robot.");
            }
            $displayname = escape($_POST[ 'displayname' ]);
            $username = e(cleanCase($_POST[ 'username' ]));
            $email = e($_POST[ 'email' ]);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                unset($email);
            }
            $raw_password = $_POST[ 'password' ];
            $options = [ 'cost' => 12, ];
            $password = password_hash($raw_password, PASSWORD_BCRYPT, $options);
            $check_username = dataArray("users", $username, "username");
            $check_email = dataArray("users", $email, "email");
            $birthday = escape($_POST[ 'birthday' ]);
            $bmath = date("Y") - date("Y", strtotime($_POST[ 'birthday' ]));
            $defaults = folderArray("default");
            $default = array_random($defaults);
            $ip = $_SERVER[ 'REMOTE_ADDR' ];
            $details = json_decode(file_get_contents("http://ipinfo.io/$ip/json"));
            $country = $details->country;
            $timezone = $details->timezone;
            $locale = Locale::acceptFromHttp($_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ]);
            $language = $c2l[ $country ];
            if (!$language) {
                $language = "en_us";
            }
            $ip = md5($ip);
            if (!$displayname || !$username || !$email || !$raw_password || !$birthday) {
                if (!$email) {
                    throw new Exception("Please enter a valid email address.");
                } else {
                    throw new Exception("Invalid or missing information.");
                }
            }
            if (!$_POST[ 'agree-guidelines' ]) {
                throw new Exception("Please agree to the basic rules, terms of use and privacy policy to sign up.");
            }
            if ($bmath < 13) {
                throw new Exception("You must be at least 13 years of age to create an account.");
            }
            if ($check_username && $check_email) {
                if ($check_email && $check_username) {
                    throw new Exception("Username and email address are both taken.");
                } elseif ($check_username) {
                    throw new Exception("Sorry, but someone beat you to that username.");
                } elseif ($check_email) {
                    throw new Exception("Email address is already in use. <a href='/forgot-password'>Forgot your password?</a>");
                }
            }
            if (!mysqli_query($connect, "insert into data.users (displayname,username,email,birthday,password_hash,image,country,timezone,language,locale) values ('" . $displayname . "','" . $username . "','" . $email . "','" . $birthday . "','" . $password . "','$default','$country','$timezone','$language','$locale')")) {
                throw new Exception("Sorry, something went wrong trying to create an account. Please try again later.");
            }
            $user_id = mysqli_insert_id($connect);
            $confirm_token = randString(10);
            $link = "<a href='https://pengin.app/confirm/$confirm_token' class='link-btn'>Confirm Email Address</a>";
            if (!mysqli_query($connect, "insert into data.sessions (user_id,token) values ('" . $user_id . "','" . $session_token . "')") && mysqli_query($connect, "insert into data.sessions (user_id,token) values ('" . $user_id . "','" . $confirm_token . "')") && !sendEmail($_POST[ 'email' ], "donoreply@pengin.app", "Activate Your Account", "Account activation", "Here's the link to confirm your email and activate your account:<br/><br/>$link<br/><br/>Don't share this with anyone.")) {
                throw new Exception("Something went wrong trying to send a confirmation email, please contact us.");
            }
            $token_id = mysqli_insert_id($connect);
            logAccess($session_token, $user_id);
            Cookie::set("session", $session_token, "1 day");
            echo "go";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    if ($action === "startsession") {
        try {
            $email = e($_POST[ 'email' ]);
            $raw_password = $_POST[ 'password' ];
            $account = dataArray("users", $email, "email");
            if ($response === null && !$response->success) {
                throw new Exception("No offense, but please confirm you're not a robot.");
            }
            if (!$account && !password_verify($raw_password, $account[ 'password_hash' ])) {
                throw new Exception("Incorrect email address or password.");
            }
            $user_id = $account[ 'id' ];
            if (!mysqli_query($connect, "insert into data.sessions (user_id,token) values ('$user_id ','" . $session_token . "')")) {
                throw new Exception("Sorry, something went wrong trying to log you in. Please try again later.");
            }
            $token_id = mysqli_insert_id($connect);
            logAccess($session_token, $user_id);
            Cookie::set("session", $session_token, "1 day");
            echo "go";
        } catch (Exception $e) {
            echo t($e->getMessage());
        }
    }
    if ($action === "sendpasswordlink") {
        $email = $_POST[ 'email' ];
        try {
            if ($response === null && !$response->success) {
                throw new Exception("No offense, but please confirm you're not a robot.");
            }
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address");
            }
            $account = dataArray("users", $email, "email");
            $user_id = $account[ 'id' ];
            $link = "<a href='https://pengin.app/reset-password/$session_token' class='link-btn'>Reset Your Password</a>";
            if ($account[ 'status' ] === "1") {
                throw new Error("Your account has been suspended for until " . date("F j, Y", strtotime($account[ 'suspend' ])) . ". If you believe this is in error, please contact us at support@pengin.app.");
            } elseif ($account[ 'status' ] === "2") {
                throw new Error("Your account has been terminated and will eventually be deleted. If you believe this is in error, please contact us at support@pengin.app.");
            }
            if (!mysqli_query($connect, "insert into data.sessions (user,token) values ('$user_id','$session_token')")) {
                throw new Exception("Error creating reset password link.");
            }
            if (!sendEmail($email, "noreplypls@pengin.app", "Reset Password Link", "Reset Your Password", "Here's the link to reset your password:<br/><br/>$link<br/><br/>Don't share this with anyone.")) {
                throw new Exception("Error sending reset password link.");
            }
            echo "sent";
        } catch (Exception $e) {
            echo t($e->getMessage());
        }
    }
    if ($action === "resetpassword") {
        $raw_password = $_POST[ 'password' ];
        $new_password = $_POST[ 'newpassword' ];
        $token = $_POST[ 'token' ];
        try {
            if (!$raw_password && !$new_password) {
                throw new Exception("Please enter your current password and a new password.");
            }
            if (!$token) {
                throw new Exception("No reset password token was recieved. Please request another password reset link.");
            }
            $thistoken = dataArray("tokens", $token, "token");
            if (!$thistoken) {
                throw new Exception("Invalid password reset token.");
            }
            $token_id = $thistoken[ 'id' ];
            if (!$user_session) {
                $user_id = $thistoken[ 'user' ];
                $user_array = dataArray("users", $user_id, "id");
                if (!$user_array) {
                    throw new Exception("That account could not be found.");
                }
            } else {
                $user_id = $user_session;
            }
            if (!password_verify($raw_password, $user_array[ 'password_hash' ])) {
                throw new Exception("Incorrect current password.");
            }
            $options = [ 'cost' => 12, ];
            $password = password_hash($new_password, PASSWORD_BCRYPT, $options);
            if (!mysqli_query($connect, "update data.users set password_hash = '$password' where id = '$user_id'")) {
                throw new Exception("Error updating your account.");
            }
            if (!$user_session) {
                if (!mysqli_query($connect, "delete from data.tokens where id = '$token_id'")) {
                    throw new Exception("Error deleting reset token");
                }
            }
            echo "reset";
        } catch (Exception $e) {
            echo t($e->getMessage());
        }
    }
    if ($action === "save") {
        $col = $_POST[ 'column' ];
        if (!$_POST[ 'value' ]) {
            $val = "NULL";
        } else {
            $val = e($_POST[ 'value' ]);
        }
        if (stripos($col, "phone") !== false) {
            $val = phoneFormat($val);
        }
        try {
            if (!$user_session) {
                throw new Exception("Whoops! You do not appear to be logged in.");
            }
            if ($val !== "NULL") {
                if ($col === "username") {
                    $val = clean($val); // clean up (spec chars, spaces, etc)
                    $check = mysqli_query($connect, "select * from data.users where username = '$val' and id != '$user_session'"); // username availablily
                    if (mysqli_num_rows($check) !== 0) {
                        throw new Exception("taken");
                    }
                }
                if ($col === "link") {
                    $val = filter_var($val, FILTER_SANITIZE_URL);
                    if (!filter_var($val, FILTER_VALIDATE_URL)) {
                        throw new Exception("Please enter a valid URL.");
                    }
                }
                if ($col === "email") {
                    $val = strtolower($val);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                        throw new Exception("Please enter a valid email address.");
                    }
                }
            }
            if (!mysqli_query($connect, 'update data.users set ' . $col . ' = "' . $val . '" where id = "' . $user_session . '"')) {
                throw new Exception("Error saving field. $col = $val " . mysqli_error($connect));
            } // save whatever column with this new value
            echo "saved";
        } catch (Exception $e) {
            echo t($e->getMessage());
        }
    }
}

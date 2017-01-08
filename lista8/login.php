<?php
if (!isset($_SESSION)) {
  session_start();
}

$fbSVG = <<<EOT
<div class="icon" id="pass" title="Zapomniałem hasła"><a href="lostPass.php"><svg viewBox="0 0 24 24">
    <path d="M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V10A2,2 0 0,1 6,8H15V6A3,3 0 0,0 12,3A3,3 0 0,0 9,6H7A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17Z" />
</svg></a></div><div class="icon" id="Google" data-onsuccess="onSignIn" title="Logowanie przez Google"><svg viewBox="0 0 24 24">
    <path d="M21.35,11.1H12.18V13.83H18.69C18.36,17.64 15.19,19.27 12.19,19.27C8.36,19.27 5,16.25 5,12C5,7.9 8.2,4.73 12.2,4.73C15.29,4.73 17.1,6.7 17.1,6.7L19,4.72C19,4.72 16.56,2 12.1,2C6.42,2 2.03,6.8 2.03,12C2.03,17.05 6.16,22 12.25,22C17.6,22 21.5,18.33 21.5,12.91C21.5,11.76 21.35,11.1 21.35,11.1V11.1Z" />
</svg></div><div class="icon" id="Facebook" title="Logowanie przez Facebooka"><svg viewBox="0 0 24 24">
    <path d="M19,4V7H17A1,1 0 0,0 16,8V10H19V13H16V20H13V13H11V10H13V7.5C13,5.56 14.57,4 16.5,4M20,2H4A2,2 0 0,0 2,4V20A2,2 0 0,0 4,22H20A2,2 0 0,0 22,20V4C22,2.89 21.1,2 20,2Z" />
</svg></div>
EOT;

$PAGE = <<<EOT
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="google-signin-client_id" content="669734942069-hc5vqkkolfcs25fbj813vesmhnl775v2.apps.googleusercontent.com">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/checkInput.js"></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=pl'></script>
    <script src="https://apis.google.com/js/api:client.js"></script>
    <title>Logowanie</title>
    <script>
      $(document).ready(function() {
        var googleUser = {};
        var startApp = function() {
          gapi.load('auth2', function(){
            auth2 = gapi.auth2.init({
              client_id: '669734942069-hc5vqkkolfcs25fbj813vesmhnl775v2.apps.googleusercontent.com',
              cookiepolicy: 'single_host_origin',
            });
            attachSignin(document.getElementById('Google'));
          });
        };
        function attachSignin(element) {
          auth2.attachClickHandler(element, {}, function(googleUser) {
            var token = googleUser.getAuthResponse().id_token;
            $.post('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' + token)
              .done(function(data) {
                $.post('google.php', {email: data.email})
                .done(function(result) {
                  window.location.href = 'login.php';
                });
              });
          }, function(error) {
            console.error(JSON.stringify(error, undefined, 2));
          });
        }
        $('.icon#Google').click(function() {
          startApp();   
        });
        $('.icon#Facebook').click(function() {
          logInWithFacebook();
        });
        $('.icon#Google').trigger('click');
      });
    </script>
  </head>
  <body>
    <div id="main">
      <div class="panel recaptcha">
        <h2>Logowanie {{FB}}</h2>
        <form method="post" action="auth.php">
          <input type="text" name="login" placeholder="Login" maxlength="24">
          <input type="password" name="pass" placeholder="Hasło" maxlength="24">
          <input type="submit" value="Zaloguj się">
          <div class="g-recaptcha" data-sitekey="6Lew9g4UAAAAAKAR7FSc3N4LL9neTkEd2HeSj3ov"></div> 
        </form>
        <div id="name"></div>
        <script>
          logInWithFacebook = function() {
            FB.login(function(response) {
              if (response.authResponse) {
                window.location.href = 'fb.php';
              } else {
                console.error('User cancelled login or did not fully authorize.');
              }
            }, { auth_type: 'reauthenticate' });
            return false;
          };
          window.fbAsyncInit = function() {
            FB.init({
              appId: '178746739264254',
              cookie: true, // This is important, it's not enabled by default
              version: 'v2.2'
            });
          };
          (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
        </script>

      </div>
      {{MSG}}
    </div>
  </body>
</html>
EOT;

$logWarn = "<div class='warningMsg'>Żadne z pól nie może pozostać puste.</div>";
$logSucc = "<div class='successMsg'>Wylogowanie zakończone pomyślnie.</div>";
$changePassSucc = "<div class='successMsg'>Hasło zostało zmienione.</div>";
$logErr = "<div class='errorMsg'>Nieprawidłowa nazwa użytkownika lub hasło.</div>";
$captcha = "<div class='errorMsg'>reCAPTCHA: nie potwierdziłeś, że nie jesteś robotem!</div>";
$fb = "<div class='errorMsg fb'>Nie odnaleziono takiego użytkownika w systemie.</div>";
$gmail = "<div class='errorMsg gmail'>Konto Google nie jest powiązane z żadnym użytkownikiem.</div>";

if (empty($_SESSION["login"]) || empty($_SESSION["pass"])) {
  $PAGE = str_replace("{{FB}}", $fbSVG, $PAGE);
  session_unset();
  session_destroy();
  if (!empty($_COOKIE["logWarn"])) {
    $PAGE = str_replace("{{MSG}}", $logWarn, $PAGE);
    setcookie("logWarn", "", time()-1);
  } else if (!empty($_COOKIE["logErr"])) {
    $PAGE = str_replace("{{MSG}}", $logErr, $PAGE);
    setcookie("logErr", "", time()-1);  
  } else if (!empty($_COOKIE["logSucc"])) {
    $PAGE = str_replace("{{MSG}}", $logSucc, $PAGE);
    setcookie("logSucc", "", time()-1);
  } else if (!empty($_COOKIE["captchaError"])) {
    $PAGE = str_replace("{{MSG}}", $captcha, $PAGE);
    setcookie("captchaError", "", time()-1);  
  } else if (!empty($_COOKIE["fbErr"])) {
    $PAGE = str_replace("{{MSG}}", $fb, $PAGE);
    setcookie("fbErr", "", time()-1);  
  } else if (!empty($_COOKIE["gmailErr"])) {
    $PAGE = str_replace("{{MSG}}", $gmail, $PAGE);
    setcookie("fbErr", "", time()-1);
  } else if (!empty($_COOKIE["changePassSucc"])) {
    $PAGE = str_replace("{{MSG}}", $changePassSucc, $PAGE);
    setcookie("changePassSucc", "", time()-1);
  } else {
    $PAGE = str_replace("{{MSG}}", "", $PAGE);
  }
  echo $PAGE;
}

if (!empty($_SESSION["login"]) || !empty($_SESSION["pass"]) || !empty($_SESSION['fb_access_token'])) {
  header("location: http://" .$_SERVER["HTTP_HOST"]. "/index.php");
}
?>
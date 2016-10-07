<?php

session_start();
$currentCookieParams = session_get_cookie_params(); 
$rootDomain = 'naprojectwater.com'; 
session_set_cookie_params( 
    $currentCookieParams['lifetime'], 
    $currentCookieParams['path'], 
    $rootDomain, 
    $currentCookieParams['secure'], 
    $currentCookieParams['httponly'] 
);

function generateToken() {
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < 15; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

include 'GDS/GDS.php';

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

$user = UserService::getCurrentUser();
$obj_store = new GDS\Store('Administrators');

if (isset($user)) {
    $email = $user->getEmail();
    
    $data = $obj_store->fetchOne('SELECT * From Administrators WHERE Email=@email', ['email' => $email]);
    
    if($data == null){
        if(isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Google App Engine') !== false){
            $logoutURL = UserService::createLogoutUrl('/login?error=loginFail');
            header('Location: ' . $logoutURL);
            die();
        }
    }
    
    $token = generateToken();
    setcookie('Token', $token);
    $_SESSION['Token'] = $token;
    $_SESSION['Email'] = $email;
    
    header('Location: /admin');
    
} else {
    include 'template-upper.php';
    echo '<link href="css/login.css" rel="stylesheet">';

echo '<h2 class="text-center">Login</h2>';
    if(isset($_GET['error']) && $_GET['error'] == 'loginFail')
        echo '<div class="alert alert-danger" role="alert">Sorry but this email is not allowed to access this part of the website</div>';
  echo sprintf('<a href="%s"><div class="googleLogin button raised"><img src="img/google_logo.svg"><span class="googleLoginText">Sign in With Google</span><paper-ripple fit></paper-ripple></div></a>',
               UserService::createLoginUrl('/login'));
}

include 'template-lower.php';

?>
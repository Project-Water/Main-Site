<?php
$currentCookieParams = session_get_cookie_params(); 
$rootDomain = 'naprojectwater.com'; 
session_set_cookie_params( 
    $currentCookieParams['lifetime'], 
    $currentCookieParams['path'], 
    $rootDomain, 
    $currentCookieParams['secure'], 
    $currentCookieParams['httponly'] 
); 
session_start();
if(!isset($_COOKIE['Token']) || !isset($_SESSION['Token']) || $_SESSION['Token'] != $_COOKIE['Token'] && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Google App Engine') !== false)
    header('Location: /login');
?>
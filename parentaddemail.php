<?php

//var_dump($_POST);

//url is string, data is array
    function performPost($url, $data){
        // use key 'http' even if you send the request to https://...
        $options = array(
                         'http' => array(
                                         'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                         'method'  => 'POST',
                                         'content' => http_build_query($data),
                                         ),
                         );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }

//recaptcha check
$url = 'https://www.google.com/recaptcha/api/siteverify';

$gcaptcharesponse = $_POST["g-recaptcha-response"];

$data = array('secret' => '6LcYtRATAAAAACtYWmjjRjjIDpz8KEv_Ou4_NOoH', 'response' => $gcaptcharesponse, 'remoteip' => $_SERVER["HTTP_CF_CONNECTING_IP"]);
$recaptchaResponse = performPost($url, $data);
$recaptchaJSON = json_decode($recaptchaResponse,true);
if(!($recaptchaJSON["success"])){
    include 'template-upper.php';
    echo "Are you a robot? Try the reCAPTCHA again";
    include 'template-lower.php';
    die();
}
if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    include 'template-upper.php';
    echo "Invalid email, please go back and try again.";
    include 'template-lower.php';
    die();
}


//get email data ready
$emailString = "https://api:key-***REMOVED***@api.mailgun.net/v3/naprojectwater.com/messages";

$emailMessage = "<div style='color:black'>";

$emailMessage = $emailMessage . "&nbsp;&nbsp;&nbsp;&nbsp;";
$emailMessage = $emailMessage . "Welcome to the NA Project Water Parent Association! The NA PWPA was formed to support the students who founded NA Project Water with the sole intent of raising funds to build clean water wells in Africa.";

$emailMessage = $emailMessage . "<br><br>";
$emailMessage = $emailMessage . "&nbsp;&nbsp;&nbsp;&nbsp;";
$emailMessage = $emailMessage . "Since its inception in Spring 2015, this student led organization has raised awareness for the cause of clean water by bringing a message of both service and responsibility to thousands of high school students in the NA school district. To date, the group has raised $25,000 toward its vision of partnership with the international organization, World Vision. While the mission of the group has remained consistent, their creative fundraising ideas are continually evolving. In 2016, over 700 students participated dodgeball tournaments at both NAI and NASH. Since then, NA Project Water has transitioned to the planning stages of a 2017 fundraiser.";

$emailMessage = $emailMessage . "<br><br>";
$emailMessage = $emailMessage . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$emailMessage = $emailMessage . "The goals of the NA PWPA are to support the efforts of NA Project Water via:";
$emailMessage = $emailMessage . "<br>";
$emailMessage = $emailMessage . "<ul>";
$emailMessage = $emailMessage . "<li>Mentorship</li>";
$emailMessage = $emailMessage . "<li>Accountability</li>";
$emailMessage = $emailMessage . "<li>Fundraising</li>";
$emailMessage = $emailMessage . "<li>Community Support</li>";
$emailMessage = $emailMessage . "<li>Corporate Sponsorship</li>";
$emailMessage = $emailMessage . "</ul>";

$emailMessage = $emailMessage . "&nbsp;&nbsp;&nbsp;&nbsp;";
$emailMessage = $emailMessage . "As an organization, we will come alongside the students in a practical capacity. To that end, we plan to have 2-3 organizational meetings as we approach the April 13th, 2017 tournament date. Please click the link below to register with the PWPA. We look forward to meeting you and growing NA Project Water to reach beyond our community and into the world. Together, we can serve those in need with the strength and perseverance that comes from teamwork.";

$emailMessage = $emailMessage . "<br><br>";
$emailMessage = $emailMessage . "Link: <a href='http://goo.gl/forms/OOVHJQMNDf'>http://goo.gl/forms/OOVHJQMNDf</a>";

$emailMessage = $emailMessage . "<br><br>";
$emailMessage = $emailMessage . "Your partnership is greatly appreciated!";

$emailMessage = $emailMessage . "<br><br>";
$emailMessage = $emailMessage . "Tracey Thomas";
$emailMessage = $emailMessage . "<br>";
$emailMessage = $emailMessage . "<a href='mailto:tthomas@naprojectwater.com'>tthomas@naprojectwater.com</a>";

$emailMessage = $emailMessage . "</div>";

$emailData = array('from' => 'NA Project Water <donotreply@naprojectwater.com>', 'to' => $_POST["email"], 'subject' => " NA Project Water Parent Association", 'html' => $emailMessage, 'domain' => "naprojectwater.com");

//perform the email request
performPost($emailString,$emailData);

?>

<?php include 'template-upper.php'; ?>

<h1 class="subPageTitle">Thank you</h1>

<article class="dodgeBallArticle" style="text-align:center;">
    Your email has been added to our list. Please check your email for a link to online registration.
</article>

<?php include 'template-lower.php'; ?>
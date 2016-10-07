<?php include 'template-upper.php'; ?>

    <div id="parentHeader">
        <img src="img/na_logo.jpg" class="parentImage">
        <h1 class="parentTitle">PWPA</h1>
    </div>

    <br>
    <h4 class="parentSubHeader">NA Project Water Parent Association</h4>

    <br>

    <h3 class="parentSubHeader">Register</h3>
    <div id="parentRegisterBox">

        <form action="parentaddemail" method="post">
            <input id="parentEmailField" type="email" placeholder="Email" name="email"></input>
            <div class="g-recaptcha" data-sitekey="6LcYtRATAAAAAE_XVa432X_APMzIXuUxg7YDrVdi"></div>
            <input id="parentSubmit" type="submit"></input>
        </form>

    </div>

    <p id="parentArticle">
        The NA Project Water Parent Association was built upon the principles that Project Water was established. Our goal is to help supplement student leadership in both a local and international setting through mentorship, fundraising, and community-driven service. Throughout the year, we help students extend their reach as they work towards their long term project, an all-school dodgeball tournament.
    </p>
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <?php include 'template-lower.php'; ?>
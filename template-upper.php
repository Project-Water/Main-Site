<?php
header("Link: </css/design.min.css>; rel=preload; as=stylesheet", false);
header("Link: </css/bootstrap.min.css>; rel=preload; as=stylesheet", false);
if($_SERVER[ 'REQUEST_URI']=="/"){
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NA Project Water</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/design.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
    <![endif]-->

    <link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/img/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/img/favicon/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/img/favicon/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/img/favicon/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/img/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/img/favicon/manifest.json">
    <link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#33ccff">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#33ccff">
    <meta name="msapplication-TileImage" content="/img/favicon/mstile-144x144.png">
    <meta name="msapplication-config" content="/img/favicon/browserconfig.xml">
    <meta name="theme-color" content="#33ccff">
    <meta name="Description" content="NA Project Water is a student run initiative that brings clean water and education to underdeveloped villages through fundraisers and service projects that impact communities across the street and around the globe.">
    <?php if(isset($_SERVER[ 'HTTP_USER_AGENT']) && (strpos($_SERVER[ 'HTTP_USER_AGENT'], 'Trident')== true)) echo "<style>#waterDropContainer{height:406px;}</style>"; ?>
</head>

<body>
    <div id="overlay"></div>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" onclick="addOverlayListener()">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div><a class="navbar-brand" href="/">PROJECT WATER</a>
                </div>
            </div>
            <br>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-center">
                    <li><a href="/" <?php if($_SERVER[ 'REQUEST_URI']=="/index" || $_SERVER[ 'REQUEST_URI']=="/" ){echo 'class="active"';} ?>>HOME</a>
                    </li>
                    <li><a href="/about" <?php if($_SERVER[ 'REQUEST_URI']=="/about" ){echo 'class="active"';} ?>>ABOUT</a>
                    </li>
                    <li><a href="/events" <?php if($_SERVER[ 'REQUEST_URI']=="/events" ){echo 'class="active"';} ?>>EVENTS</a>
                    </li>
                    <li><a href="/videos" <?php if($_SERVER[ 'REQUEST_URI']=="/videos" ){echo 'class="active"';} ?>>VIDEOS</a>
                    </li>
                    <li id="navBreak"><a href="/dodgeball" <?php if($_SERVER[ 'REQUEST_URI']=="/dodgeball" ){echo 'class="active"';} ?>>DODGEBALL</a>
                    </li>
                    <li><a href="/donate" <?php if($_SERVER[ 'REQUEST_URI']=="/donate" ){echo 'class="active"';} ?>>DONATE</a>
                    </li>
                    <li><a href="/sponsors" <?php if($_SERVER[ 'REQUEST_URI']=="/sponsors" ){echo 'class="active"';} ?>>SPONSORS</a>
                    </li>
                    <li><a href="/parents" <?php if($_SERVER[ 'REQUEST_URI']=="/parents" ){echo 'class="active"';} ?>>PARENTS</a>
                    </li>
                    <li><a href="/contact" <?php if($_SERVER[ 'REQUEST_URI']=="/contact" ){echo 'class="active"';} ?>>CONTACT</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
   <?php if($_SERVER[ 'REQUEST_URI']!="/admin/tournaments" ){echo '<div class="container">';} else{echo '<div class="container-fluid tournamentContainer">';}?>
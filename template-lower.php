</div>
<footer class="footer">
	<div style="position: relative;"class="innerFooter"><span><a href="credits" class="creditsLink">Credits</a></span><span><a href="team" class="creditsLink">Our Team</a></span>
       
       <?php
       
       use google\appengine\api\users\User;
       use google\appengine\api\users\UserService;
       if (isset($_SESSION['Token']))
       {
           echo '<a href="' . UserService::createLogoutUrl('/') . '" class="creditsLink">Logout</a>';
       }
       else{
           echo '<a href="login" class="creditsLink">Login</a>';
       }
       ?>
   
   </div>
    <div style="position: relative;"class="innerFooter"><span>Alex&nbsp;Taffe,&nbsp;Joshua&nbsp;Thomas,&nbsp;Shane&nbsp;Mitnick,&nbsp;Nikhil&nbsp;Behari</span><span>&copy;2015-<?php echo date("Y"); ?></span></div>

</footer>
<script src="/js/jquery-1.11.2.min.js"></script>
<script src="/js/bootstrap.min.js" data-no-instant></script>
<script src="/js/hammer.min.js" data-no-instant></script>
<script type="text/javascript" async defer src="//platform.twitter.com/widgets.js"></script>
<?php if($_SERVER[ 'REQUEST_URI']=="/administrators" ){echo '<script>$(function () { $(\'[data-toggle="tooltip"]\').tooltip()});</script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/index" || $_SERVER[ 'REQUEST_URI']=="/" ){echo '<script src="/js/main.js" async></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/dodgeball" ){echo '<script src="/js/jquery.countdown.min.js"></script>';echo '<script src="/js/counter.js"></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/about" ){echo '<script src="/js/about.js" async></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/admin/tournaments" ){echo '<script src="/js/jquery.datetimepicker.full.min.js"></script><script src="/ckeditor/ckeditor.js"></script><script src="/js/tournaments.js"></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/admin/events" ){echo '<script src="/js/jquery.datetimepicker.full.min.js"></script><script src="/js/eventeditor.js"></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/admin/videos" ){echo '<script src="/js/Sortable.min.js"></script><script src="/js/videoeditor.js"></script>';} ?>
<?php if($_SERVER[ 'REQUEST_URI']=="/admin/homeimages" ){echo '<script src="/js/Sortable.min.js"></script><script src="/js/homeimageeditor.js"></script>';} ?>
<script>
    function addOverlayListener(){
        $('#bs-example-navbar-collapse-1').on('show.bs.collapse', function () {
            $("#overlay").css("display", "block");
        });
        $('#bs-example-navbar-collapse-1').on('hide.bs.collapse', function () {
            $("#overlay").css("display", "none");
        });
    }
</script><?php
if (!(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false))
{
    echo '<script src="/js/instantclick.min.js" data-no-instant></script>
<script data-no-instant>
    InstantClick.init();
</script>
<script>
    InstantClick.on("change", function () {
        addOverlayListener();
    });
</script>';
}?>

</body>

</html>
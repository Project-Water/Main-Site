<?php include 'checklogged.php'; include 'GDS/GDS.php'; include 'template-upper.php'; ?>
    <link href="/css/administrators.css" rel="stylesheet">

    <h1 class="subPageTitle">Administrators</h1>

    <?php
$obj_store = new GDS\Store('Administrators');
$data = $obj_store->fetchAll();
if($data == null)
    echo "No administrators";
else{
    echo '<table id="adminTable">';
    echo '<thead><tr><th><span>Name</span></th><th><span>Email</span></th><th><span>Date Added</span></th><th><span>Delete</span></th></tr></thead>';
    echo '<tbody>';
    foreach($data as $admin){
        echo '<tr>';
        echo '<td>' . $admin->Name . '</td>';
        echo '<td>' . $admin->Email . '</td>';
        echo '<td>' . $admin->DateAdded . '</td>';
        echo '<td';
        if(!$admin->CanBeDeleted)
            echo ' data-toggle="tooltip" data-placement="top" title="User cannot be deleted">';
        else
            echo '>';
        echo '<form method="post" action="/api">';
        echo '<input type="hidden" name="action" value="deleteAdmin">';
        echo '<input type="hidden" name="toDelete" value="' . $admin->getKeyId() . '">';
        echo '<button type="submit" class="btn btn-danger"';
        if(!$admin->CanBeDeleted)
            echo ' disabled';
        echo '>Delete</button></form></td>';
        
    }
    echo '</tbody></table>';
}
?>


        <div id="userForm">
            <div class="formHeader">
                <h3>ADD ADMINISTRATOR</h3>
            </div>
            <form action="/api" method="post">
                <div class="text-center">Email must be a Gmail or Google Apps email</div><br>
                <input type="hidden" name="action" value="addAdmin">
                <input type="text" name="name" placeholder="Full Name"><br>
                <input type="text" name="email" placeholder="Email"><br>
                <input type="submit" value="SUBMIT">
            </form>
        </div>


        <?php include 'template-lower.php'; ?>
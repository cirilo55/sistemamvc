<?php
    use App\Model\Notifications;

    $m = new Notifications();
    $notifications = $m->findByField('user_notification', $_SESSION['id']);
?>

<div id='notification-tab' class="notification-tab inactive">

<?php 
    foreach($notifications as $notification)
    {
        echo "<div class='notification-panel'>";
        echo "<div class='notification-container'>";
        echo "<div class='flex'><h4>".$notification->title."</h4><div id='close-notification-x' style=''>X</div></div>";
        echo "<div class='notification-description'>".$notification->description."</div>";
        echo "</div>";

    }
?>
</div>
<style>
    .notification-tab{
        position: absolute;
        left:85%;
        width: 15%;
        background-color: rgba(66, 63, 63, 0.651);
        height: 90%;
        z-index: -1;

    }
    .notification-panel{
        height: 94%;
    }
    .notification-container{
        background-color: white;
        margin: 2%;
        border-radius: 2%;
    }
    #close-notification-x{
        margin-left:auto;
        margin-right:2%;
    }
    .notification-description{
        background-color: #dfdddd ;
        border-radius: 1%;
        margin: 1%;

    }
    @keyframes slideToggle {
    from {
        width: 0;
    }
    to {
        width: 100px; 
    }
    }
    
</style>

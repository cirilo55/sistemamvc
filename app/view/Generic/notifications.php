<?php
    use App\Model\Notifications;

    $m = new Notifications();
    $notifications = $m->findByField('user_notification', $_SESSION['id']);
?>

<div id='notification-tab' class="notification-tab inactive">
    <div class='notification-panel'>
        <?php 
            if($notifications){
            foreach($notifications as $notification)
            {
                echo "<div class='notification-container'>";
                echo "<div class='flex'>";
                echo "<div class='notification-title'>".$notification->title."</div>";
                echo "<div id='close-notification-x' class='notification-close-button' style=''>X</div>";
                echo "</div>";
                echo "<div class='notification-description'>".$notification->description."</div>";
                echo "</div>";
            } // Closing brace for foreach loop
            } else{
                echo "</div>";
            }
        ?>
    </div>

<style>
    .notification-tab{
        left:85%;
        height: 100%;
        width: 100%;
        background-color: rgba(66, 63, 63, 0.651);

    }
    .notification-panel{
        height: 100%;
        margin: 0 0.5rem;
        overflow: auto; 
    }
    .notification-container{
        background-color: white;
        margin: 0.5rem 0;
        border-radius: 2%;
    }
    .notification-title{
        font-size: var(--font-large);
        font-weight: bold;
        font-style: italic;
    }
    #close-notification-x{
        margin-left:auto;
        margin-right:2%;
    }
    .notification-description{
        font-size: var(--font-medium);
        background-color: #dfdddd ;
        border-radius: 1%;
        margin: 1%;

    }
    .notification-close-button:hover{
        cursor: pointer;
    }
    @keyframes slideToggle {
    from {
        width: 0;
    }
    to {
        width: 100px; 
    }
    }

    @media (min-width: 1300px) {
    .notification-title{
        font-size: var(--font-medium);
        font-weight: bold;
        font-style: italic;
    }
    .notification-description{
        font-size: var(--font-small);
        background-color: #dfdddd ;
        border-radius: 1%;
        margin: 1%;
    }
    }

    @media (min-width: 1600px) {
    .notification-title{
        font-size: var(--font-large);
        font-weight: bold;
        font-style: italic;
    }
    .notification-description{
        font-size: var(--font-medium);
        background-color: #dfdddd ;
        border-radius: 1%;
        margin: 1%;

    }

    }
    
</style>

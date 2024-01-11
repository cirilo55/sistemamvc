<?php
    use App\Model\Menu;
    $title = false;
    $menu = new Menu();
    $menu->joinModuleItems();
    $all = $menu->allWithRelations();
?>

<div id='main-menu' class="main-menu inactive">
    
    <div id='menu-search' class="menu-search center">
        <input class='seachInput' type="text">
    </div>


    <?php if($all) : foreach($all as $itemMenu): ?>
        <div class="panel-group">
        <div class="panel panel-default">

            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapse<?=$itemMenu->id?>"><?=$itemMenu->moduleName?></a>
                </h4>
            </div>


            <div id="collapse<?=$itemMenu->id?>" class="panel-collapse collapse">
                <ul class="list-group">
                    <?php foreach($itemMenu->moduleItem as $module) { ?>
                        <a href="/<?=$module->archorValue?>">
                            <li class="list-group-item"><?=$module->itemName?></li>
                        </a>
                    <?php }?>
                    
                </ul>
            </div>

        </div>
        </div>
    <?php endforeach; endif;?>




</div>
<style>
    .main-menu{
        position: absolute;
    }
    .panel-group{
        margin-bottom: 0 !important; 
        width: 100% !important;
    }
    .menu-search{
        height: 5%;
        border-radius: 5px;
    }
    .list-group-item{
        margin: 0 ;
    }
    
</style>

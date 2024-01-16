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

    <?php if ($all) : foreach ($all as $itemMenu): ?>
        <div class="accordion">
            <div class="accordion">

                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$itemMenu->id?>">
                        <?=$itemMenu->moduleName?>
                    </button>
                </h2>

                <div id="collapse<?=$itemMenu->id?>" class="accordion-collapse collapse">
                    <div class="accordion-body">
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
        </div>
    <?php endforeach; endif;?>

</div>
<style>
    .accordion-body{
        padding: 0;
    }
</style>


<script>
    // Adicione este script jQuery para controlar o comportamento de colapso
    $(document).ready(function() {
        $('.accordion-collapse').on('show.bs.collapse', function () {
            $(this).siblings('.accordion-header').addClass('active');
        });

        $('.accordion-collapse').on('hide.bs.collapse', function () {
            $(this).siblings('.accordion-header').removeClass('active');
        });
    });

    // Adicione este script jQuery para alternar a classe 'inactive' e 'active' no clique do botão de menu
    $(document).ready(function() {
        $('#main-menu-toggle').click(function() {
            $('#main-menu').toggleClass('active');
        });
    });
</script>




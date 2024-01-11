<?php

namespace Sys\Component;
use Sys\Component\ButtonComponent;
use Sys\Component\ComboboxComponent;
class GridComponent
{
        
    /** 
     * 
     * SEMPRE PASSAR PRIMEIRO O id NO ARRAY DATA 
     * E SEMPRE COMO A CHAVE DE ID
     * @model: model da classe;
     * @data: array de classes STD
     * @columns: [nomedaColuna =>nomeDisplay ]
     * 
    **/
    public static function render($model,array $data ,array $columns,bool $btnAdicionar=true, bool $btnEditar=true, bool $btnDeletar=true, int $itemsPerPage = 15)
    {
        $idKey = array_search("id", $columns);
        $urlController = ($model->getUrl());

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $totalItems = count($data);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $pagedData = array_slice($data, $startIndex, $itemsPerPage);
        // var_dump($pagedData);
        echo "<div id='hidden-controller' class='inactive' data-url=".$urlController."></div>";
        echo "<div id='grid-component'>";
        echo "<div id='grid-component-body'>";
        self::renderGridHead($columns);
        self::filtersPersist($columns);
        if($btnEditar)
        {
            $columns['edit'] = 'Editar';
        }
        if($btnDeletar)
        {
            $columns['delete'] = 'Deletar';
        }
        echo "<table class='grid-table'>";
        self::renderHeader($columns);
        self::renderRows($pagedData, $columns, $idKey);
        echo '</table>';
        echo "</div>";

        self::paginateRow($currentPage,$totalPages);
        echo "</div>";

    }

    public static function renderGridHead($columns)
    {
        $searchBy = isset($_GET['grid-search-input']) ? $searchBy = $_GET['grid-search-input'] : NULL;
        $searchField = isset($_GET['grid-combo']) ? $searchField = $_GET['grid-combo'] : NULL;

        echo "<div class='grid-form'> <form id='form-gridComponent' method='GET'> 
        <input type='text' placeholder='buscar' name='grid-search-input' id='grid-search-input' value={$searchBy}>"
        .ComboboxComponent::render('grid-combo', $columns, $searchField, ['id' => 'grid-search-combo'])
        .ButtonComponent::render("", "lupa-btn")
        ."</form>"
        ."<div class='add-button'> <a id='grid-component-add'>" //ficar Ligado aqui
        .ButtonComponent::render("", "grid-add")
        ."</div></a></div>";
    }

    private static function renderHeader(array $columns)
    {
        $orderBy = isset($_GET['orderby']) ? $_GET['orderby'] : NULL;
        $orderType = isset($_GET['order-type']) ? $searchField = $_GET['order-type'] : NULL;

        echo "<tr class='table-head'>";
        foreach ($columns as $campo => $column) {
            echo '<th class="grid-component-th-div">';
            echo '<div class="flex">';

            if($column == "Editar" || $column == 'Deletar'){
                echo '<div class="grid-component" data-order="'. $campo . '">' . $column . '</div>';
            }else{
            if ($orderBy === $campo) {
                if($orderType == 'asc')
                {
                    echo '<div class="grid-component-th-desc" data-order="'. $campo . '">' . $column . '</div>';
                    echo '<div class="triangulo-para-cima"></div>';
                }elseif ($orderType == 'desc') {
                    echo '<div class="grid-component-th-asc" data-order="'. $campo . '">' . $column . '</div>';
                    echo '<div class="triangulo-para-baixo"></div>';

                }else{
                echo '<div class="grid-component-th-not" data-order="'. $campo . '">' . $column . '</div>';
                }
            }else 
            {
            echo '<div class="grid-component-th-not" data-order="'. $campo . '">' . $column . '</div>';
            }
        }
            
            echo '</div>';
            echo '</th>';
        }
        echo '</tr>';
    }

    private static function renderRows(array $data, array $columns, string $idKey)
    {
        //vai pegar o nome do id;
        foreach ($data as $row) {
            echo "<tr class='grid-row' ondblclick=jsEdit(".$row->{$idKey}.")>";

            foreach ($columns as $columnName  => $columnLabel) {
                if(!($columnName == 'edit' || $columnName == 'delete')){
                echo '<td>' . $row->{$columnName} . '</td>';
                }
            }
            
            if (isset($columns['edit'])) {
                echo '<td>'.ButtonComponent::render("Edit", "btn-primary" , ['id' => 'btn-edit'.$row->{$idKey}, 'onClick' =>'jsEdit('.$row->{$idKey}.')']) .'</td>';
            }
            if (isset($columns['delete'])) {
                echo '<td>'.ButtonComponent::render("X", "btn-warning" ,['id' => 'btn-delete'.$row->{$idKey}, 'onClick' =>'jsDelete('.$row->{$idKey}.')']) .'</td>';
            }
            echo '</tr>';
        }
    }

    private static function paginateRow($currentPage, $totalPages)
    {
        echo "<div class='grid-footer'>";

        // Link para a página anterior
        echo "<a href='?page=" . max($currentPage - 1, 1) . "'><div> < </div></a>";

        // Links para as páginas individuais
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=$i'><div>" . $i . "</div></a>";
        }

        // Link para a próxima página
        echo "<a href='?page=" . min($currentPage + 1, $totalPages) . "'><div> > </div></a>";

        echo "</div>";
    }

    private static function filtersPersist($columns)
    {
        $orderBy = isset($_GET['orderby']) ? $orderBy = $_GET['orderby'] : NULL;
        $searchBy = isset($_GET['grid-search-input']) ? $searchBy = $_GET['grid-search-input'] : NULL;
        $searchField = isset($_GET['grid-combo']) ? $searchField = $_GET['grid-combo'] : NULL;
        $orderType = isset($_GET['order-type']) ? $orderType = $_GET['order-type'] : NULL;

        if($orderBy)
        {
        echo "<div id='orderBy-hidden' data-order={$orderBy}> </div>";
        }
        if($orderType)
        {
        echo "<div id='orderType-hidden' data-order={$orderType}> </div>";
        }
        echo "<div class='persist-row'>";
        if($searchBy){
        $columnName = $columns[$searchField];
        echo "<div id='persist-search' data-field={$searchField} data-search={$searchBy} class='persist-card'> <div id='closePersist' class='closeButton'>X</div> <div>{$columnName} é igual á {$searchBy} </div></div>";
        }  
        echo "</div>";
    }
}
?>
<script>
$(document).ready(function() {
    let urlAjax = $('#hidden-controller').data('url');

    $('#form-gridComponent').submit(function(event) {
        let orderBy = undefined;
        let orderType = undefined;
        let gridValue = undefined;
        let gridSearch = undefined;

        if($('#grid-search-input').val()){
            gridValue = $('#grid-search-input').val();
        }
        if($("#grid-search-combo").val()){
            gridSearch = $("#grid-search-combo").val();
        }

        //get do persist.
        if($('#orderBy-hidden').data('order'))
        {
          orderBy = $('#orderBy-hidden').data('order');
        }
        if($('#orderType-hidden').data('order'))
        {
          orderType = $('#orderType-hidden').data('order');
        }

        sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch);
    });

    $('.grid-component-th-asc').on('click', function() {
        let orderBy = $(this).data('order');
        let orderType = 'asc';
        let gridValue = $("#persist-search").data('search');
        let gridSearch = $("#persist-search").data('field');

        sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch);
    });

    $('.grid-component-th-not').on('click', function() {
        let orderBy = $(this).data('order');
        let orderType = 'desc';
        let gridValue = $("#persist-search").data('search');
        let gridSearch = $("#persist-search").data('field');

        sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch);
    });

    $('.grid-component-th-desc').on('click', function() {
        let gridValue = $("#persist-search").data('search');
        let gridSearch = $("#persist-search").data('field');

        sendRequestGridComponet(undefined, undefined, gridValue, gridSearch);
    });

    function sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch) {
        event.preventDefault();
        console.log(orderBy, orderType, gridValue, gridSearch);
        $.ajax({
            type: 'GET',
            url: this.urlAjax,
            data: {
                'grid-search-input': gridValue,
                'orderby': orderBy,
                'grid-combo': gridSearch,
                'order-type': orderType,

            },
            success: function(response) {
                $('#grid-component').empty();
                $('#grid-component').append(response);
            }
        });
    }
    
    $('#grid-component-add').on('click', () => {
        let urlAjax = $('#hidden-controller').data('url');

        $.ajax({
            type: 'GET',
            url: urlAjax+'/add',
            data: {

            },
            success: function(response) {
                openLevel2(response);
             }

        });
    })

    $('#closePersist').on('click', () => { 

        let orderBy = undefined;
        let orderType = undefined;

        $('#persist-search').remove();

        if($('#orderBy-hidden').data('order'))
        {
          orderBy = $('#orderBy-hidden').data('order');
        }
        if($('#orderType-hidden').data('order'))
        {
          orderType = $('#orderType-hidden').data('order');
        }

        sendRequestGridComponet(orderBy, orderType, undefined, undefined);
    })

});
    function jsEdit(id)
    {
        let urlAjax = $('#hidden-controller').data('url');

        $.ajax({
            type: 'GET',
            url: urlAjax+'/find/'+id,
            data: {

            },
            success: function(response) {
                openLevel2(response);
             }

        });
    }

    function jsDelete(id)
    {
        let urlAjax = $('#hidden-controller').data('url');

        $.ajax({
            type: 'GET',
            url: urlAjax+'/remove/'+id,
            data: {

            },
            success: function(response) {
                openLevel2(response);
            }

        });
    }

</script>
<style>
    /* #grid-component{
        height: 100%;
    }
    #grid-component-body{
        height: 90%;
    } */
    .grid-component-th-asc{
        cursor: pointer;
    }
    .grid-component-th-desc{
        cursor: pointer;
    }
    .grid-component-th-not{
        cursor: pointer;
        
    }
    .grid-component{
        transition: opacity 0.3s ease, height 0.3s ease;

    }
    .triangulo-para-baixo {
        margin-top: 5px;
        width: 0; 
        height: 0; 
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #f9a825;
    }
    .triangulo-para-cima {
        margin-top: 5px;
        width: 0; 
        height: 0; 
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #f9a825;
    }
    .persist-card{
        max-width: 300px;
        min-width: 150px;
        border: 1px solid black;
        padding: 1px;
        margin: 5px;
        color: #a9a9a9;
        border-radius: 3px;
        font-size: 9px;
    }
    .closeButton{
        width: 100%;
        color: red;
        height: 5px;
        font-size: 10px;
        margin-left: 97%;
        cursor: pointer;

    }
    .grid-form{
        display: flex;
        margin: 5px;
    }
    #form-gridComponent{
        display: flex;
        padding: 5px;
        font-size: 14px;
        border-width: 1px;
        border-color: #CCCCCC;
        background-color: #FFFFFF;
        color: #000000;
        border-style: solid;
        border-radius: 0px;
        box-shadow: 0px 0px 5px rgba(66,66,66,.75);
        text-shadow: 0px 0px 5px rgba(66,66,66,.75);

    }
    #grid-search-combo{
        border: none;
    }
    #grid-search-input{
        border: none;
    }
    .grid-search-input:focus {
     outline:none;
    }
    .add-button{
        margin-left: auto;
        width: 50px;
        display: flex;
        justify-content: center;

    }
    .grid-table{
        width: calc(100% - 40px);
        margin-left: 20px;
        box-shadow: 3px 3px grey;
        border-radius: 2px;

    }

    .persist-row{
        width: calc(100% - 80px);
        margin-left: 40px;

    }
    .table-head
    {
       Color: #7492ec;
       font-family: "Roboto",sans-serif;

    }
    .grid-row{
        height: 10%;
    }
    .grid-row:hover{
        background-color: #e1ffef;
    }
    .lupa-btn{
        background-image: url(./../../imgs/generic/image.png);
        border:none;
        background-size: 15px 15px;
        width: 25px;
        height: 100%;
        background-repeat: no-repeat;
        background-position: center;
    }
    .grid-add{
        background-image: url(./../../imgs/generic/green-add-button-12023.png);
        background-size: 28px 28px;
        width: 32px;
        height: 100%;
        background-repeat: no-repeat;
        background-position: center;
        border:none;

    }
    .grid-footer{
        margin-left: auto;
        display: flex;
        width: 20%;
    }
    @media (max-width: 1300px) {
    .grid-row{
        height: 20%;
    }

    }

</style>
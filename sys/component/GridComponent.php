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
    public static function render($model, array $data, array $columns,bool $btnAdicionar=true, bool $btnEditar=true,bool $btnDeletar=true)
    {
        $idKey = array_search("id", $columns);
        $urlController = ($model->getUrl());

        echo "<div id='hidden-controller' class='inactive' data-url=".$urlController."></div>";
        echo "<div id='grid-component'>";
        self::renderGridHead($columns);
        self::filtersPersist();
        if($btnEditar)
        {
            $columns['edit'] = 'Editar';
        }
        if($btnDeletar)
        {
            $columns['delete'] = 'Deletar';
        }
        echo '<table>';
        self::renderHeader($columns);
        self::renderRows($data, $columns, $idKey);
        echo '</table>';
        self::paginateRow();
        echo "</div>";

    }

    public static function renderGridHead($columns)
    {
        echo "<div class='flex'> <form id='form-gridComponent' method='GET'> 
        <input type='text' placeholder='buscar' name='grid-search-input' id='grid-search-input'>"
        .ComboboxComponent::render('grid-combo', $columns, '', ['id' => 'grid-search-combo'])
        .ButtonComponent::render("Lupa", "btn-primary full-btn")
        ."</form>"
        ."<div style='margin-left: auto;'> <a id='grid-component-add'>" //ficar Ligado aqui
        .ButtonComponent::render("Add", "btn-success full-btn")
        ."</div></a></div>";
    }

    private static function renderHeader(array $columns)
    {
        $orderBy = isset($_GET['orderby']) ? $_GET['orderby'] : NULL;
        $orderType = isset($_GET['order-type']) ? $searchField = $_GET['order-type'] : NULL;

        echo '<tr>';
        foreach ($columns as $campo => $column) {
            echo '<th class="grid-component-th-div">';
            echo '<div class="flex">';

            if ($orderBy === $campo) {
                if($orderType == 'asc')
                {
                    echo '<div class="grid-component-th-desc" data-order="'. $campo . '">' . $column . '</div>';
                    echo '<div class="triangulo-para-cima"></div>';
                }else{
                echo '<div class="grid-component-th-asc" data-order="'. $campo . '">' . $column . '</div>';
                echo '<div class="triangulo-para-baixo"></div>';
                }
            }else 
            {
            echo '<div class="grid-component-th-asc" data-order="'. $campo . '">' . $column . '</div>';
            // echo '<div class="triangulo-para-cima"></div>';
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
            echo '<tr>';
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

    private static function paginateRow()
    {
        echo "<div class='flex-center'><a href='#'><div> < </div></a><div class=''> 1 </div> <a  href='#'> <div> > </div></a></div>";
    }

    private static function filtersPersist()
    {
        $orderBy = isset($_GET['orderby']) ? $orderBy = $_GET['orderby'] : NULL;
        $searchBy = isset($_GET['grid-search-input']) ? $searchBy = $_GET['grid-search-input'] : NULL;
        $searchField = isset($_GET['grid-combo']) ? $searchField = $_GET['grid-combo'] : NULL;
        // var_dump($orderBy);die();
        if($orderBy)
        {
        echo "<input id='order-by-hidden' style='display:none'>$orderBy</input>";
        }
        if($searchBy){
        echo "<div class='card flex'> {$searchField} = {$searchBy} </div>";
        } 
    }
}
?>
<script>
$(document).ready(function() {
    let urlAjax = $('#hidden-controller').data('url');

    $('#form-gridComponent').submit(function(event) {
        event.preventDefault();
        let gridValue = $('#grid-search-input').val();
        let gridSearch = $("#grid-search-combo").val();
        let orderBy = $("#order-by-hidden").val();
        orderType = 'desc';
        sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch);
    });

    $('.grid-component-th-asc').on('click', function() {
        let orderBy = $(this).data('order');
        let orderType = 'asc';
        sendRequestGridComponet(orderBy, orderType);
    });

    $('.grid-component-th-desc').on('click', function() {
        let orderBy = $(this).data('order');
        let orderType = 'desc';
        sendRequestGridComponet(orderBy, orderType);
    });

    function sendRequestGridComponet(orderBy, orderType, gridValue, gridSearch) {

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

});

</script>
<style>
    .grid-component-th-asc{
        cursor: pointer;
    }
    .grid-component-th-desc{
        cursor: pointer;
    }
    .triangulo-para-baixo {
        margin-top: 5px;
        width: 0; 
        height: 0; 
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #f9a825;
        /* background-color: yellow; */
    }
    .triangulo-para-cima {
        margin-top: 5px;
        width: 0; 
        height: 0; 
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 8px solid #f9a825;
    }

</style>
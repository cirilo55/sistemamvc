<?php

namespace Sys\Component;
use Sys\Component\ButtonComponent;

class GridComponent
{
        
    /** 
     * 
     * SEMPRE PASSAR PRIMEIRO O id NO ARRAY DATA 
     * E SEMPRE COMO A CHAVE DE ID
     * @data: array de classes STD
     * @columns: [nomedaColuna =>nomeDisplay ]
     * 
    **/
    public static function render(array $data, array $columns,bool $btnAdicionar=true, bool $btnEditar=true,bool $btnDeletar=true)
    {
        $idKey = array_search("id", $columns);

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
    }

    private static function renderHeader(array $columns)
    {
        echo '<tr>';
        foreach ($columns as $column) {
            echo '<th>' . $column . '</th>';
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
}

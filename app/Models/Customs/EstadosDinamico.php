<?php

namespace App\Models\Customs;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customs\ModelIndexColumn;

class EstadosDinamico extends Model
{
    protected $table = 'EstadosDinamico';

    public $timestamps = false;

    protected $fillable = ['id_uf', 'uf', 'estado'];

    static function index()
    {
        $select = EstadosDinamico::getSelect('EstadosDinamico');

        $rows = EstadosDinamico::select($select->select)
                               ->get();

        return (object)[
            'rows' => $rows,
            'columns' => $select->columns
        ];
    }

    static function getColumns()
    {
        $select = EstadosDinamico::getSelect('EstadosDinamico');

        $data = [];

        foreach($select->select as $column){
            $cols = explode('.', $column);

            $data[$cols[0]] = null;
        }

        return [
            'apiForm' => $data
        ];
    }

    static function getById($id)
    {
        $select = EstadosDinamico::getSelect('EstadosDinamico');

        $data = EstadosDinamico::where('id', $id)
                               ->select($select->select)
                               ->first();

        return [
            'apiForm' => $data
        ];
    }

    static function store($insert)
    {
        return EstadosDinamico::insertGetId($insert);
    }

    static function updateItem($id, $update)
    {
        return EstadosDinamico::where('id', $id)
                              ->update($update);

    }

    static function destoy($id)
    {
        return EstadosDinamico::where('id', $id)
                              ->delete();
    }

    static function getSelect($model){
        $columns = [];
        $cols = ModelIndexColumn::getColumns($model);

        $columns = ["{$model}.id"];

        foreach($cols as $column){
            $columns[] = $column->column_name;
        }

        return (object)[
            'select' => $columns,
            'columns' => $cols
        ];
    }
}

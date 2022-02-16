<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

class SysForeignKeyColumns extends Model
{
    protected $table = 'sys.foreign_key_columns';

    public $timestamps = false;

    protected $fillable = [
        'constraint_column_id',
        'parent_object_id',
        'parent_column_id',
        'referenced_object_id',
        'referenced_column_id',
    ];

    static function getRelationships($parentId){
        $relationships = SysForeignKeyColumns::join('sysobjects', 'sys.foreign_key_columns.parent_object_id', '=', 'sysobjects.id')
                                             ->join('sys.columns', function($join){
                                                $join->on('sysobjects.id', '=', 'sys.columns.object_id');
                                                $join->on('sys.foreign_key_columns.parent_column_id', '=', 'columns.column_id');
                                             })
                                             ->join('sysobjects AS sysobjects2', 'sys.foreign_key_columns.referenced_object_id', '=', 'sysobjects2.id')
                                             ->join('sys.columns AS syscolumns2', function($join){
                                                $join->on('sysobjects2.id', '=', 'syscolumns2.object_id');
                                                $join->on('sys.foreign_key_columns.referenced_column_id', '=', 'syscolumns2.column_id');
                                             })
                                             ->where('parent_object_id', $parentId)
                                             ->selectRaw('sysobjects.name  AS parentTable,
                                                          sys.columns.name AS parentCol,
                                                          sysobjects2.name AS referencedTable,
                                                          syscolumns2.name AS referencedCol')
                                             ->get();

        return $relationships;
    } 

    static function getByIds($parentId, $referencedId){
        $foreignKeys = SysForeignKeyColumns::where('parent_object_id', $parentId) 
                                           ->where('referenced_object_id', $referencedId) 
                                           ->get();

        return $foreignKeys;
    }

}

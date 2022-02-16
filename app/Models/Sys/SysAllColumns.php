<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

class SysAllColumns extends Model
{
    protected $table = 'sys.all_columns';

    public $timestamps = false;

    protected $fillable = [
        'object_id',
        'name',
        'column_id',
        'system_type_id',
        'user_type_id',
        'max_length',
        'precision',
        'scale',
        'collation_name',
        'is_nullable',
        'is_ansi_padded',
        'is_rowguidcol',
        'is_identity',
        'is_computed',
        'is_filestream',
        'is_replicated',
        'is_non_sql_subscribed',
        'is_merge_published',
        'is_dts_replicated',
        'is_xml_document',
        'xml_collection_id',
        'default_object_id',
        'rule_object_id',
        'is_sparse',
        'is_column_set',
    ];

    static function getTableColumnsById($objectId){
        $columns = SysAllColumns::join('sys.objects', 'sys.all_columns.object_id', '=', 'sys.objects.object_id')
                                ->join('sys.foreign_key_columns', function($join){
                                    $join->on('sys.objects.object_id', '=', 'sys.foreign_key_columns.parent_object_id');
                                    $join->on('sys.all_columns.column_id', '=', 'sys.foreign_key_columns.parent_column_id');
                                })
                                ->where('sys.objects.object_id', $objectId)
                                ->select('sys.all_columns.name')
                                ->get();

        return $columns;
    }

    static function getTableColumnsByName($tableName){
        $columns = SysAllColumns::join('sys.objects', 'sys.all_columns.object_id', '=', 'sys.objects.object_id')
                                ->join('sys.foreign_key_columns', function($join){
                                    $join->on('sys.objects.object_id', '=', 'sys.foreign_key_columns.referenced_object_id');
                                    $join->on('sys.all_columns.column_id', '=', 'sys.foreign_key_columns.referenced_column_id');
                                })
                                ->join('sys.key_constraints', 'sys.objects.object_id', '=', 'sys.key_constraints.parent_object_id')
                                ->join('INFORMATION_SCHEMA.TABLE_CONSTRAINTS', 'sys.key_constraints.name', '=', 'INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_NAME')
                                ->join('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE', function($join){
                                    $join->on('INFORMATION_SCHEMA.TABLE_CONSTRAINTS.CONSTRAINT_NAME', '=', 'INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.CONSTRAINT_NAME');
                                    $join->on('INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE.COLUMN_NAME', '=', 'sys.all_columns.name');
                                })
                                ->where('sys.objects.name', $tableName)
                                ->where('sys.objects.type', 'U')
                                ->where('sys.key_constraints.type', 'PK')
                                ->select('sys.all_columns.name')
                                ->get();

        return $columns;
    }
}

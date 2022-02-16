<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

class SysColumns extends Model
{
    protected $table = 'sys.columns';

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

    static function getColumnById($objectId, $columnId){
        $column = SysColumns::where('object_id', $objectId)
                            ->where('column_id', $columnId)
                            ->first();

        return $column;
    }

    static function getColumnByName($objectId, $name){
        $column = SysColumns::where('object_id', $objectId)
                            ->where('name', $name)
                            ->first();

        return $column;
    }
}

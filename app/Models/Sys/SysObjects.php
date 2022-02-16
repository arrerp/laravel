<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;

class SysObjects extends Model
{
    protected $table = 'sys.objects';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'object_id',
        'principal_id',
        'schema_id',
        'parent_object_id',
        'type',
        'type_desc',
        'create_date',
        'modify_date',
        'is_ms_shipped',
        'is_published',
        'is_schema_published',
    ];

    static function getByType($type){
        $objects = SysObjects::where('type', $type) 
                             ->orderBy('name')
                             ->get();

        return $objects;
    }

    static function getObjectById($type, $objectId){
        $object = SysObjects::where('type', $type)
                            ->where('object_id', $objectId)
                            ->first();

        return $object;
    }

    static function getObjectByName($type, $name){
        $object = SysObjects::where('type', $type)
                            ->where('name', $name)
                            ->first();

        return $object;
    }
}

<?php

namespace App\Util;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomSchema 
{
    static function createTable($tableName, $columns){
        Schema::connection('sqlsrv')->create($tableName, function($table) {
            $table->increments('id');
            $table->integer('id_uf');
            $table->string('uf');
            $table->string('estado');
        });
    }
}

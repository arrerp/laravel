<?php

namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models;
use App\Models\Sys\SysObjects;
use App\Models\Sys\SysColumns;
use App\Models\Sys\SysAllColumns;
use App\Models\Sys\SysForeignKeyColumns;
use App\Util\Util;
use App\Util\CustomSchema;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ScaffoldController extends Controller
{
    public function getTables(){
        $tableNames = [];

        $tables = SysObjects::getByType('U');

        foreach($tables as $table){
            $tableNames[] = $table->name;
        }

        return response()->json($tableNames, 200);
    }

    public function createAndImport(Request $request){
        $request = json_decode($request->getContent());

        $insertColumns = $this->createSchema($request->tableName, $request->columnsConfig);

        $insert = $this->storeSchema($request->tableName, $insertColumns, $request->data);

        Models::store(5, $request->tableName);

        $this->runArtisan($request->tableName, $insertColumns);

        return response()->json($insert, 200);
    }

    
    public function viewConstruct(Request $request){
        $relationships = $this->getRelationships($request->mainTable);

        $tables = $this->getReferencedTables($relationships);

        $relationships = $this->getRelationshipsRecursive($relationships, $tables);

        $relationships = $this->serializeRelationships($relationships);

        return response()->json($relationships, 200);
    }

    public function getTableColumns($table){
        $columns = SysAllColumns::getTableColumnsByName($table);

        return response()->json($columns, 200);
    }

    public function execQuery(Request $request){
        $request = json_decode($request->getContent());

        
        $query = $this->queryBuilder($request->mainTable, $request->joins);
        
        return response()->json($query->get(), 200);
    }
  
    private function runArtisan($modelName, $columns){
        $artisanParams = '';

        foreach($columns as $column){
            $artisanParams .= "{$column}:fillable|";
        }

        $artisanParams = substr($artisanParams, 0, -1);

        $artisan = "make:dynamic_resources {$modelName} $artisanParams";

        return Artisan::call($artisan);

    }

    private function createSchema($tableName, $columns){
        $insertColumns = [];

        Schema::create($tableName, function($table) use ($columns){
            $primaryKeys = [];

            $table->increments('id');
            
            foreach($columns as $column){
                $this->columnSchemaBuilder($table, $column);

                if(!$column->nullable && $column->primaryKey){
                    $primaryKeys[] = $column->column;
                }
            }

            if(!empty($primaryKeys)){
                $table->primary($primaryKeys);
            }
        });

        foreach($columns as $column){
            if($column->referenced && $column->referencedTable && $column->referencedCol){
                Schema::table('tempEstados', function($table) use ($column){
                        $table->foreign($column->column)->references($column->referencedCol)->on($column->referencedTable);
                });
            }
        }

        foreach($columns as $column){
            $insertColumns[] = $column->column;
        }

        return $insertColumns;
    }

    private function storeSchema($table, $columns, $data){
        foreach($data as $value){
            $insert = [];

            foreach($columns as $column){
                $insert[$column] = $value->$column;
            }

            $create = DB::table($table)->insert($insert);
        }

        return $create;
    }

    private function columnSchemaBuilder($table, $column){
        $dataType = $column->dataType;

        if($dataType === 'string'){
            $table = $table->string($column->column, $column->maxLength);
        
        } else if($dataType === 'decimal'){
            $table = $table->decimal($column->column, $column->precision, $column->scale);
        
        } else {
            $table = $table->$dataType($column->column);
        }

        if($column->nullable && !$column->primaryKey){
            $table = $table->nullable();
        }

        return $table;
    }


    private function getRelationshipsRecursive($relationships, $tables){
        $curTables = $tables;

        foreach($relationships as $relationship){
            $newRels = $this->getRelationships($relationship->referencedTable);            

            foreach($newRels as $newRel){
                if(in_array($newRel->referencedTable, $tables)){
                    continue;
                }

                $tables[] = $newRel->referencedTable;
                $relationships[] = $newRel;
            }
        }

        if(count($curTables) !== count($tables)){
            $this->getRelationshipsRecursive($relationships, $tables);
        }

        return $relationships;
    }

    private function getRelationships($table){
        $parent = SysObjects::getObjectByName('U', $table);

        $relationships = SysForeignKeyColumns::getRelationships($parent->object_id);

        return $relationships;
    }

    private function queryBuilder($mainTable, $relationships) {
        $searchModel = $this->getModel($mainTable);
        $className = "App\Models\\$searchModel->menu\\{$searchModel->model}";
        $model = new $className();

        $result = $model;

        if(!empty($relationships)){
            $result = $this->queryBuilderWithRelationships($model, $relationships);
        } 

        return $result;
    }

    private function queryBuilderWithRelationships($query, $relationships) {
        $selectRaw = '';
        $typeJoins = [
            'INNER JOIN' => 'join',
            'LEFT JOIN' => 'leftJoin',
            'RIGHT JOIN' => 'rightJoin',
        ];

        foreach($relationships as $relationship){  
            $columns = SysAllColumns::getTableColumnsByName($relationship->referencedTable);

            foreach($columns as $column){
                $selectRaw .= $relationship->referencedTable . '.' . $column->name . ' AS ' . $relationship->referencedTable . '_' . $column->name .',';

                $selectRaw = strtolower($selectRaw);
            }

            $typeJoin =  $typeJoins[$relationship->typeJoin];      

            $query = $query->$typeJoin($relationship->referencedTable, "$relationship->parentTable.$relationship->parentCol", '=',  "$relationship->referencedTable.$relationship->referencedCol");
        }

        $selectRaw = rtrim($selectRaw,',');

        return $query->selectRaw($selectRaw);
    }

    private function getModel($table){
        $model = Models::getModel($table);

        return $model;
    }

    private function getReferencedTables($relationships){
        $tables = [];

        foreach($relationships as $relationship){
            $tables[] = $relationship->referencedTable;
        }

        return $tables;
    }

    private function serializeRelationships($relationships){
        foreach($relationships as $relationship){
            $relationship['typeJoin'] = 'INNER JOIN';
        }

        return $relationships;
    }
}

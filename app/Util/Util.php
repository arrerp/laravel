<?php

namespace App\Util;
use Illuminate\Support\Facades\DB;

class Util
{
    /**
     * Retorna as keys do array preparadas para o Select no VueJS
     * @var mixed
     * @return array
     */
    static function toSelectKeys($arr, $label, $value, $extras = []){
        $newArr = [];

        foreach($arr as $key){
            $aux = [
                'label' => $key[$label],
                'value' => $key[$value],
            ];

            foreach($extras as $extra){
                if(isset($key[$extra])){
                    $aux[$extra] = $key[$extra];
                }
            }

            $newArr[] = $aux;
        }

        return $newArr;
    }

    /**
     * Converte valores booleanos para 'S' ou 'N'
     * 
     * @var array
     * @return object
     * 
     */
    static function booleanToVarchar($array){
        $keys = array_keys($array);

        foreach($keys as $key){
            if($array[$key] === true){
                $array[$key] = 'S';

            } else if($array[$key] === false){
                $array[$key]= 'N';

            } else {
                continue;
            }
        }

        return Util::toObject($array);
    }

    /**
     * Converte valores booleanos para 'S' ou 'N'
     * 
     * @var array
     * @return object
     * 
     */
    static function varcharToBoolean($array, $customVars = false, $arrVars = []){
        $keys = array_keys($array);

        foreach($keys as $key){
            if($array[$key] === 'S'){
                $array[$key] = true;

            } else if($array[$key] === 'N'){
                $array[$key] = false;

            } if($customVars){
                if(in_array($array[$key], $arrVars['true'])){
                    $array[$key] = true;

                } else if(in_array($array[$key], $arrVars['false'])){
                    $array[$key] = false;

                } else {
                    continue;
                }

            } else {
                continue;
            }
        }

        return Util::toObject($array);
    }

    /**
     * Converte array em objeto
     * @var array
     * @return object
     */
    static function toObject($array){
        return json_decode(json_encode($array));
    }

    /**
     * Converte objeto/collection em array
     * @var object
     * @return array
     */
    static function toArray($array){
        return json_decode(json_encode($array), true);
    }

    /**
     * Retira todos os valores nao numericos
     * @var string
     * @return string
    */
    static function onlyNumbers($string){
        return preg_replace('/\D/', '', $string);
    }

    /**
     * Formata Moeda
     * @var string
     * @return string
    */
    static function formataMoedaDB($string){
        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        return preg_replace("/[^0-9.]/", '', $string);
    }

    /**
     * Formata Moeda
     * 
     * @var string
     * @return string
     * 
     * 
    */
    static function formataSaldo($saldo){
        if(Util::getDecimal($saldo) == 0){
            $saldo = (int)$saldo;

        } else {
            $saldo = number_format($saldo, 2);
        }

        return $saldo;
    }

    /**
     * Formata Moeda
     * @var string
     * @return string
    */
    static function getDecimal($number){
        $inteiro = (int) $number;
        $decimal  = $number - $inteiro;

        return $decimal;
    }

    static function underscoreToCamel($input, $separator = '_'){
        return lcfirst(str_replace($separator, '', ucwords(strtolower($input), $separator)));
    }

    static function getTableInfo($model){
        $columns = $model->getFillable();
        $table = $model->getTable();
        $tableInfo = [];

        foreach($columns as $column){
            $dbTable = DB::connection()->getDoctrineColumn($table, $column);

            $camelized = Util::underscoreToCamel($column);
            $length = $dbTable->getLength();
            $type = $dbTable->getType()->getName();

            if($length === -1){
                $length = 0;
            }

            $tableInfo[$camelized] = [
                'length' => $length,
                'type' => $type,
                'nullable' => !$dbTable ->getNotnull()
            ];

            $tableInfo['columns'][] = $camelized;
        }

        return $tableInfo;
    }

    static function getTableInfoBackEnd($model){
        $columns = $model->getFillable();
        $table = $model->getTable();
        $tableInfo = [];

        foreach($columns as $column){
            $dbTable = DB::connection()->getDoctrineColumn($table, $column);

            $length = $dbTable->getLength();
            $type = $dbTable->getType()->getName();

            if($length === -1){
                $length = 0;
            }

            $tableInfo[$column] = [
                'length' => $length,
                'type' => $type,
                'nullable' => !$dbTable ->getNotnull()
            ];

        }

        $tableInfo['columns'] = $columns;

        return $tableInfo;
    }

    static function getTableColumns($model){
        $columns = $model->getFillable();

        return $columns;

    }

    static function getDBTables(){
        $tables = [];
        $query = "SELECT *
                    FROM INFORMATION_SCHEMA.TABLES WITH(NOLOCK)
                   WHERE TABLE_TYPE = 'BASE TABLE'
                ORDER BY TABLE_NAME";

        $all =  DB::connection()->select($query);

        foreach($all as $table){
            $tables[] = $table->TABLE_NAME;
        }

        return $tables;
    }

    static function ImageToBase64($imagePath){
        $basePath = 'C:/Intel/ERP/VueJS/public/';

        $path = $basePath . $imagePath;

        if(!file_exists($path) || !is_file($path)){
            $path = $basePath . 'img/avatars/default.jpg';
        }

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        return $base64 ;
    }



}

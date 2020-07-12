<?php

namespace App\Modules\RestoreDb\Classes;

use Illuminate\Support\Facades\DB;

class SqlClass
{
    public function getConnection()
    {
        try {
            $connection = DB::connection();
        } catch (\Exception $e) {
            $connection = false;
        }

        return $connection;
    }

    public function createBySql($sql_query)
    {
        try {
            $result = DB::connection()->statement($sql_query);
        } catch (\Exception $e) {
            $result = ['status' => false, 'error' => $e];
        }

        return $result;
    }

    public function insertDataBySql($sql_query)
    {
        try {
            $result = DB::connection()->statement($sql_query);
        } catch (\Exception $e) {
            $result = ['status' => false, 'error' => $e];
        }

        return $result;
    }

    public function getSqlHeaders()
    {
        return  DB::connection()->select("SHOW columns FROM test.test");
    }

    public function getSqlBody($limit, $offset)
    {
        return DB::connection()->select("SELECT * FROM test.test LIMIT ? OFFSET ?", [$limit, $offset]);
    }

    public function getHeaders()
    {
        return  $this->filterSqlValues($this->getSqlHeaders(), 1);
    }

    public function getBody($limit, $offset)
    {
        return  $this->filterSqlValues($this->getSqlBody($limit, $offset), 2);
    }

    public function filterSqlValues($sql_data, $type)
    {
        $result = [];

        switch ($type) {
            case 2:
                foreach ($sql_data as $object) {
                    $values = array_values((array) $object);
                    array_push($result, $values);
                }
                break;
            case 1:
                foreach ($sql_data as $key => $value) {
                    array_push($result, $value->Field);
                }
                break;
            default:
                false;
        }

        return $result;
    }

    public function getFilteredData($data)
    {
        $where_query = '';
        foreach ($data as $key => $value) {
            if (strlen(trim($value['value'])) > 1) {
                if (($key + 1) !== count($data)) {
                    $where_query .= "`" . $value['field'] . "` = '" . $value['value'] . "' AND ";
                }
                $where_query .= "`" . $value['field'] . "` = '" . $value['value'] . "'";
            }
        }

        return DB::select('select * from test.test where ' . $where_query);
    }
}

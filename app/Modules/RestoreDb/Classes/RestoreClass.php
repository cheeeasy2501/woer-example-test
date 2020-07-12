<?php

namespace App\Modules\RestoreDb\Classes;

use Illuminate\Support\Facades\Storage;
use App\Modules\RestoreDb\Classes\SqlClass as SqlManager;

class RestoreClass
{

    private $db_name;
    protected $database_storage_path = 'db';
    private $db_file;
    private $table_file;
    private $data_file;

    public function __construct(
        $db_name,
        $db_file,
        $table_file,
        $data_file
    ) {
        $this->db_name = $db_name;
        $this->db_file = $db_file;
        $this->table_file = $table_file;
        $this->data_file = $data_file;
        $this->sqlManager = new SqlManager();
    }

    public function restoreByStorage($filename)
    {
        if (Storage::disk($this->database_storage_path)->exists($filename)) {
            $sql_query = Storage::disk($this->database_storage_path)->get($filename);
            $result = $this->sqlManager->createBySql($sql_query);

            return $result;
        }

        return ['status' => false, 'error' => 'File not exists'];
    }

    public function restoreDataByStorage($filename)
    {
        if (Storage::disk($this->database_storage_path)->exists($filename)) {
            $sql_query = Storage::disk($this->database_storage_path)->get($filename);
            $result = $this->sqlManager->insertDataBySql($sql_query);

            return $result;
        }

        return ['status' => false, 'error' => 'File not exists'];
    }

    public function restoreAll()
    {
        $connectionResult = $this->sqlManager->getConnection();
        if ($connectionResult) {
            if ($this->restoreByStorage($this->db_file) && $this->restoreByStorage($this->table_file)) {
                $this->restoreDataByStorage($this->data_file);
            }
        }

        return $connectionResult;
    }
}

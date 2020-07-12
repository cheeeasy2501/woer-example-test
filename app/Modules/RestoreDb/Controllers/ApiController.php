<?php

namespace App\Modules\RestoreDb\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Modules\RestoreDb\Classes\SqlClass as SqlManager;
use Illuminate\Http\Request;

class ApiController extends BaseController
{

    public function getDataByFilter(Request $request)
    {
        $sqlManager = new SqlManager();
        $data = $request->all();
        $filteredData = $sqlManager->filterSqlValues($sqlManager->getFilteredData($data), 2);

        return response()->json($filteredData, 200);
    }

    public function getData($page = 1, $limit = 10)
    {
        $status = 1;
        $error = "";
        $sqlManager = new SqlManager();
        try {
            if (!is_numeric($page) || !is_numeric($limit) || ($limit && $page) < 1) {
                $status = 0;
                return response()->json(["status" => $status, "error" => "Invalid params"], 400);
            }
            $offset = $limit * ($page - 1);
            $headers = $sqlManager->getHeaders();
            $body = $sqlManager->getBody($limit, $offset);
        } catch (\Exception $e) {
            $status = 0;
            return response()->json(["status" => $status, "error" => $error], 500);
        }

        return response()->json(["status" => $status, "error" => $error, "data" => ["head" => $headers, "body" => $body]], 200);
    }

    public function getHeadNames()
    {
        $sqlManager = new SqlManager();
        return $sqlManager->getHeaders();
    }
}

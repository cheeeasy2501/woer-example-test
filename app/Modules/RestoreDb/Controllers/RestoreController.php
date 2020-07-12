<?php

namespace App\Modules\RestoreDb\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Modules\RestoreDb\Classes\RestoreClass as Restore;

class RestoreController extends BaseController
{

    public function index()
    {
        return view('RestoreDb::index');
    }

    public function restore()
    {
        $restore = new Restore(
            'mysql',
            'db_test.sql',
            'table_test.sql',
            'table_test_data.sql'
        );

        if ($restore->restoreAll()) {
            return response()->json(['message' => 'Database Restored'],  200);
        }

        return  response()->json(['message' => 'Errors'], 400);
    }
}

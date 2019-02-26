<?php

namespace Komicho\Laravel\Traits;

use Illuminate\Support\Facades\Schema;

trait LaravelIid
{
    public static function checkHaveColumn()
    {
        $tableName = with(new static)->getTable();
        $connectionName = \DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $isColExist = Schema::connection($connectionName)->hasColumn($tableName, 'iid');
        if (!$isColExist) {
            throw new \Exception('Komicho : The `iid` column was not found in `'.$tableName.'` table.');
        }
    }

    public static function createiid($data)
    {
        self::checkHaveColumn();

        $guideColumn = self::$guideColumn;
        $getFirst = self::where($guideColumn, '=', $data[$guideColumn])->orderBy('id', 'DESC')->first();
        
        if (!$getFirst) {
            $data['iid'] = 1;
        } else {
            $data['iid'] = $getFirst->iid+1;
        }
        
        self::create($data);
        return $data['iid'];
    }
}
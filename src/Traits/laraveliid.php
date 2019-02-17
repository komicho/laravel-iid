<?php

namespace komicho\Traits;

use Illuminate\Support\Facades\Schema;

trait laraveliid
{
    public static function checkHaveColumn()
    {
        $tableName = with(new static)->getTable();
        $connectionName = \DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $isColExist = Schema::connection($connectionName)->hasColumn($tableName, 'iid');
        if (!$isColExist) {
            throw new \Exception("Error Processing Request", 1);
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
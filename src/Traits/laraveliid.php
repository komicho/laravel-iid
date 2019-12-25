<?php

namespace Komicho\Laravel\Traits;

use Illuminate\Support\Facades\Schema;

trait LaravelIid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $isColExist = Schema::connection(env('DB_CONNECTION'))->hasColumn($model->getTable(), 'iid');

            if (!$isColExist) {
                throw new \Exception('Komicho : The `iid` column was not found in `'.$model->getTable().'` table.');
            }

            $getFirst = $model->where($model->guideColumn, '=', $model[$model->guideColumn])
                ->where('iid', '!=', 'NULL')
                ->orderBy('id', 'DESC')
                ->first();

            if (!$getFirst) {
                $model['iid'] = 1;
            } else {
                $model['iid'] = $getFirst->iid+1;
            }

        });
    }
}
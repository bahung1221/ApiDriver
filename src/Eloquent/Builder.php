<?php

namespace Hungnguyenba\Apidriver\Eloquent;

use Illuminate\Database\Eloquent\Builder as BaseEloquentBuilder;

class Builder extends BaseEloquentBuilder
{
     /**
     * Send mass update data into connection
     *
     * @param array $values
     * @return void
     */
    public function massUpdate(array $values)
    {
        return $this->toBase()->massUpdate($this->addUpdatedAtColumn($values));
    }

     /**
     * Send batch update data into connection
     *
     * @param array $models
     * @return void
     */
    public function batchUpdate(array $models)
    {
        return $this->toBase()->batchUpdate($this->addUpdatedAtColumn($models));
    }

    
    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function first($columns = ['*'])
    {
        $model = $this->take(1)->get($columns)->first();

        return is_null($model) ? $this->model->newCollection([]) : $model;
    }
}
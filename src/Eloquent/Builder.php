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
}
<?php

namespace Hungnguyenba\Apidriver\Eloquent;

use Illuminate\Database\Eloquent\Builder as BaseEloquentBuilder;

class Builder extends BaseEloquentBuilder
{
    public function massUpdate(array $values)
    {
        return $this->toBase()->massUpdate($this->addUpdatedAtColumn($values));
    }
}
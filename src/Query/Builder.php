<?php

namespace Hungnguyenba\Apidriver\Query;

use Illuminate\Database\Query\Builder as BaseQueryBuilder;

class Builder extends BaseQueryBuilder
{
    public function massUpdate(array $values)
    {
        $records = $this->get();
        $ids = array_column_string($records, 'id');
        $api = $this->from ?? '';

        foreach ($records as $key => $record) {
            $updateData[] = $values;
        }

        return $this->connection->massUpdate($api, $ids, $updateData);
    }
}
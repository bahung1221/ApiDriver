<?php

namespace Hungnguyenba\Apidriver\Query;

use Illuminate\Database\Query\Builder as BaseQueryBuilder;

class Builder extends BaseQueryBuilder
{
    /**
     * Send mass update data into connection
     *
     * @param array $values
     * @return void
     */
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

    /**
     * Send batch update & insert data into connection
     *
     * @param array $models
     * @return void
     */
    public function batchUpdate(array $models)
    {
        if (empty ($models)) {
            return [];
        }

        $api = $this->from ?? '';
        
        return $this->connection->batchUpdate($api, $models);
    }
}
<?php

namespace Hungnguyenba\Apidriver\Connection;

use Illuminate\Database\Connection;
use Hungnguyenba\Apiconnectionservice\Service;
use Hungnguyenba\Apidriver\Grammar\ApiGrammar;
use Hungnguyenba\Apidriver\Processor\ApiProcessor;

class ApiConnection extends Connection
{
    use Service;

    /**
     * Run a select statement against the api.
     *
     * @param  array  $query
     * @param  array  $bindings
     * @param  bool  $useReadPdo
     * @return array
     */
    public function select($query, $bindings = [], $useReadPdo = true)
    {
        if (empty($query) || empty($query['api'])) {
            return [];
        }

        // Get api string from query and unset it from query
        $api = $query['api'];
        unset($query['api']);

        // Get flag for get metadata and unset it from query
        $isGetMetaData = ! empty($query['isGetMetaData']) && $query['isGetMetaData'] == 1 ? true : false;
        unset($query['isGetMetaData']);
        
        // Execute get request from api and receive response data
        $data = $this->get($api, $query, $isGetMetaData);
        // Check flag for get metadata
        if ($isGetMetaData) {
            $res['total'] = $data['total'];
            $res['per_page'] = $data['per_page'];
            $res['current_page'] = $data['current_page'];
            $res['last_page'] = $data['last_page'];
            $res['next_page_url'] = $data['next_page_url'];
            $res['prev_page_url'] = $data['prev_page_url'];
            $res['from'] = $data['from'];
            $res['to'] = $data['to'];
            $data = $data['data'];
        }

        // Validate data and set index
        if (! empty($data)) {
            $attribute = isset($options['index_by']) ? $options['index_by'] : '';
            $isIdx = ! empty($attribute);
            try {
                foreach ($data as $record) {
                    if ($isIdx) {
                        $idx = $record[$attribute] ?? null;
                        if (! empty($idx)) {
                            $res['data'][$idx] = $this->getModel()->fill($record)->toArray();
                        }
                    } else {
                        $model = $this->getModel()->fill($record);
                        if ($model->validate()) {
                            $res['data'][] = $model->toArray();
                        }
                    }
                }
            } catch (\Exception $e) {
                return [];
            }
        }

        return $isGetMetaData ? $res ?? [] : $res['data'] ?? [];
    }

    /**
     * Run an insert statement against the database.
     *
     * @param  array  $query
     * @param  array   $bindings
     * @return bool
     */
    public function insert($query, $bindings = [])
    {
        if (empty($query) || empty($query['api'])) {
            return [];
        }

        // Set api name then unset it from query array
        $api = $query['api'];
        unset($query['api']);

        // Execute post request and get response
        return $this->post($api, $query) ?? [];
    }

      /**
     * Run an update statement against the database.
     *
     * @param  array  $query
     * @param  array   $bindings
     * @return int
     */
    public function update($query, $bindings = [])
    {
        if (empty($query) || empty($query['api']) || empty($query['id'])) {
            return 0;
        }

        // Get the api name from query, then unset it
        $api = $query['api'];
        unset($query['api']);

        // Get the id value from query, then unset it
        $id = $query['id'];
        unset($query['id']);

        // Execute put request and get response
        $res = $this->put($api, $id, $query);

        return empty($res) ? 0 : 1;
    }

    public function massUpdate(string $api, $ids, array $values)
    {
        if (empty($api)) {
            return [];
        }
        
        $res = $this->put($api, $ids, $values);
        return $res ?? [];
    }

    /**
     * Run a delete statement against the database.
     *
     * @param  string  $query
     * @param  array   $bindings
     * @return int
     */
    public function delete($query, $bindings = [])
    {
        if (empty($query) || empty($query['api']) || empty($query['id'])) {
            return 0;
        }

        $api = $query['api'];
        $id = $query['id'];

        $res = $this->deleteById($api, $id);
        
        return empty($res) ? 0 : 1;
    }

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    public function getSchemaBuilder()
    {
        return null;
    }

    /**
     * Get the default query grammar instance.
     *
     * @return \App\Database\ApiGrammar
     */
    protected function getDefaultQueryGrammar()
    {
        return $this->withTablePrefix(new ApiGrammar);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return null;
    }

    /**
     * Get the default post processor instance.
     *
     * @return \App\Database\ApiProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new ApiProcessor;
    }

    /**
     * Get the Doctrine DBAL driver.
     *
     * @return \Doctrine\DBAL\Driver\PDOMySql\Driver
     */
    protected function getDoctrineDriver()
    {
        return null;
    }
}

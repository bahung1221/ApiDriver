<?php

namespace Hungnguyenba\Apidriver\Model;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\Builder;

class Model extends BaseModel
{
    /**
     * @inheritDoc Illuminate\Database\Eloquent\Concerns\HasTimestamps
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
     /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        
        //custom
        $conn->setModel(static::class);

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    /**
     * Get the table qualified key name.
     *
     * @return string
     */
    public function getQualifiedKeyName()
    {
        return $this->getKeyName();
    }

     /**
     * Validate attributes before return it via toArray method or create a model instance of it
     *
     * @return bool
     */
    public function validate() : bool
    {
        return (empty($this->is_valid)) ? true : $this->is_valid == 1;
    }

    /**
     * Create a collection of models from plain arrays.
     *
     * @param  array  $items
     * @param  string|null  $connection
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function hydrate(array $items, $connection = null)
    {
        $instance = (new static)->setConnection($connection);

        if (! empty($items['total'])) {
            $meta = $items;
            $items = $items['data'] ?? [];
            unset($meta['data']);
        }

        $items = array_map(function ($item) use ($instance) {
            return $instance->newFromBuilder($item);
        }, $items);

        if (! empty($meta)) {
            $meta = $instance->newCollection($meta);
            $items = $instance->newCollection($items);
            $meta['data'] = $items;
        }

        return empty($meta) ? $instance->newCollection($items) : $meta;
    }
}
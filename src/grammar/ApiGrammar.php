<?php

namespace Hungnguyenba\Apidriver\Grammar;

use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Builder;

class ApiGrammar extends Grammar
{
     /**
     * The components that make up a select clause.
     *
     * @var array
     */
    protected $selectComponents = [
        'aggregate',
        'columns',
        'from',
        'joins',
        'wheres',
        'groups',
        'havings',
        'orders',
        'limit',
        'offset',
        'lock',
    ];

    /**
     * Compile a select query into api condition.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileSelect(Builder $query)
    {
        if (empty($query->from)) {
            return [];
        }
        
        // Get api string from query
        $api['api'] = $query->from;
        
        // get conditions from wheres attribute
        $conditions = head($query->wheres);
        
        // Check if not nested condition
        if (empty($conditions['query'])) {

        } else {
            $conditions = $conditions['query']->wheres;
        }

        // Check whether a single condition or multiple condition, then change format to multiple condition
        if (! is_array(head($conditions))) {
            $tmp = $conditions;
            $conditions = [];
            $conditions[] = $tmp;
        }

        // Loop and get conditions as array
        foreach ($conditions as $key => $condition) {
            $column = $condition['column'];
            $operator = $condition['operator'] === '=' ? '' : $condition['operator']; 
            $api[$column . $operator] = $condition['value'] ?? null;
        }
        
        // Check limit attribute and add it into query conditions
        if ($query->limit > 0) {
            $api['limit'] = $query->limit;
        }

        return $api ?? [];
    }

     /**
     * Compile an insert and get ID statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array   $values
     * @param  string  $sequence
     * @return array
     */
    public function compileInsertGetId(Builder $query, $values, $sequence)
    {
        if (empty($query) || empty($values) || empty($query->from)) {
            return [];
        }

        // Set api name from query builder
        $conditions['api'] = $query->from;

        // Set attributes for insert
        foreach ($values as $key => $value) {
            $conditions[$key] = $value;
        }

        return $conditions ?? [];
    }

     /**
     * Compile an update statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $values
     * @return array
     */
    public function compileUpdate(Builder $query, $values)
    {
        if (empty($query) || empty($values)) {
            return [];
        }
        
        // Get condition for update
        $conditions = $this->compileSelect($query);

        // Get query
        foreach ($values as $key => $value) {
           $conditions[$key] = $value; 
        }

        return $conditions ?? [];
    }

    /**
     * Compile a delete statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public function compileDelete(Builder $query)
    {
        if (empty($query)) {
            return [];
        }

        // Get condition for delete
        $conditions = $this->compileSelect($query);

        return $conditions ?? [];
    }

}

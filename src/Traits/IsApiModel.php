<?php

namespace Mapi\Easyapi\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

trait IsApiModel
{


    /**
     * @var array The default columns to load when requesting records as a list.
     */
    protected $listColumnsToRetrieve = [];

    /**
     * @var string[] Set the columns the client is allowed to filter on.
     * Client send the key in this format: where_columnName
     */
    protected $allowedFilters = [];

    /**
     * @var string[] Associative array.
     * The keys is the Relations allowed to be filtered and the values are the columns of the relation to filter on.
     * Client send the key in this format: where_relation_relationName_relationColumn
     */
    protected $allowedRelationsFilters = [];


    /**
     * @var string[] Set the default allowed relations to load with the model.
     */
    protected $allowedRelationsToLoad = [];

    /**
     * @var array The default columns to search when calling search method.
     */
    protected $allowedColumnsToSearch = ['*'];

    /**
     * @var array The default relations to search when calling the search method.
     */
    protected $allowedRelationsToSearch = [];

    /**
     * @var string[] Set the allowed columns to sort accordingly. Default value is all.
     */
    protected $allowedColumnsToSortBy = ['*'];


    protected function scopeFilter($query, array $input, array $allowedFilters = [], array $allowedRelationFilters = [])
    {

        //Normal queries
        $filtersKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'where_');
        });
        $filtersOrKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_where_');
        });
        $filterInKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereIn_');
        });
        $filterNotInKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNotIn_');
        });
        $filterOrInKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereIn_');
        });
        $filterOrNotInKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNotIn_');
        });
        $filterLikeKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereLike_');
        });
        $filterOrLikeKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereLike_');
        });
        $filterNull = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNull_');
        });
        $filterNotNull = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNotNull_');
        });
        $filterOrNull = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNull_');
        });
        $filterOrNotNull = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNotNull_');
        });

        //Relations
        $filterRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'where_relation');
        });
        $filterOrRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_where_relation');
        });
        $filterOrInRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereIn_relation');
        });
        $filterOrNotInRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNotIn_relation');
        });
        $filterInRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereIn_relation');
        });
        $filterNotInRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNotIn_relation');
        });
        $filterLikeRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereLike_relation');
        });
        $filterOrLikeRelations = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereLike_relation');
        });
        $filterNullRelation = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNull_relation');
        });
        $filterNotNullRelation = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'whereNull_relation');
        });
        $filterOrNullRelation = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNull_relation');
        });
        $filterOrNotNullRelation = collect(array_keys($input))->filter(function ($key) use ($input) {
            return str_contains($key, 'or_whereNotNull_relation');
        });

        if (count($allowedFilters) > 0) {
            $directFilters = $allowedFilters;
        } else {
            $directFilters = $this->allowedFilters;
        }
        foreach ($filterNull as $key) {
            $filterName = str_replace('whereNull_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->whereNull($filterName);
            }
        }
        foreach ($filterNotNull as $key) {
            $filterName = str_replace('whereNotNull_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->whereNotNull($filterName);
            }
        }
        foreach ($filterOrNull as $key) {
            $filterName = str_replace('or_whereNull_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhereNull($filterName);
            }
        }
        foreach ($filterOrNotNull as $key) {
            $filterName = str_replace('or_whereNotNull_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhereNotNull($filterName);
            }
        }
        foreach ($filtersKeys as $key) {
            $filterName = str_replace('where_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->where($filterName, $input[$key]);
            }
        }
        foreach ($filtersOrKeys as $key) {
            $filterName = str_replace('or_where_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhere($filterName, $input[$key]);
            }
        }
        foreach ($filterInKeys as $key) {
            $filterName = str_replace('whereIn_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->whereIn($filterName, $input[$key]);
            }
        }
        foreach ($filterNotInKeys as $key) {
            $filterName = str_replace('whereNotIn_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->whereNotIn($filterName, $input[$key]);
            }
        }
        foreach ($filterOrInKeys as $key) {
            $filterName = str_replace('or_whereIn_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhereIn($filterName, $input[$key]);
            }
        }
        foreach ($filterOrNotInKeys as $key) {
            $filterName = str_replace('or_whereNotIn_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhereNotIn($filterName, $input[$key]);
            }
        }
        foreach ($filterLikeKeys as $key) {
            $filterName = str_replace('whereLike_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->where($filterName, 'like', '%' . $input[$key] . '%');
            }
        }
        foreach ($filterOrLikeKeys as $key) {
            $filterName = str_replace('or_whereLike_', '', $key);
            if (in_array($filterName, $directFilters)) {
                $query = $query->orWhere($filterName, 'like', '%' . $input[$key] . '%');
            }
        }

        if (count($allowedRelationFilters) > 0) {
            $relationsFilters = $allowedRelationFilters;
        } else {
            $relationsFilters = $this->allowedRelationsFilters;
        }
        foreach ($filterNullRelation as $key) {
            $queryParam = explode('_', str_replace('whereNull_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['whereNull_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereNull($relationColumn);
                    });
                }
            }
        }
        foreach ($filterNotNullRelation as $key) {
            $queryParam = explode('_', str_replace('whereNotNull_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['whereNotNull_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereNotNull($relationColumn);
                    });
                }
            }
        }
        foreach ($filterOrNullRelation as $key) {
            $queryParam = explode('_', str_replace('or_whereNull_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['or_whereNull_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->orWhereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereNull($relationColumn);
                    });
                }
            }
        }
        foreach ($filterOrNotNullRelation as $key) {
            $queryParam = explode('_', str_replace('or_whereNotNull_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['or_whereNotNull_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->orWhereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereNotNull($relationColumn);
                    });
                }
            }
        }
        foreach ($filterRelations as $key) {
            $queryParam = explode('_', str_replace('where_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['where_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->where($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterOrRelations as $key) {
            $queryParam = explode('_', str_replace('or_where_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['where_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->orWhereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->where($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterInRelations as $key) {
            $queryParam = explode('_', str_replace('whereIn_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['whereIn_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereIn($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterNotInRelations as $key) {
            $queryParam = explode('_', str_replace('whereNotIn_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['whereNotIn_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->whereNotIn($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterOrInRelations as $key) {
            $queryParam = explode('_', str_replace('or_whereIn_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['or_whereIn_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->orWhereIn($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterOrNotInRelations as $key) {
            $queryParam = explode('_', str_replace('or_whereNotIn_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['or_whereNotIn_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->orWhereNotIn($relationColumn, $value);
                    });
                }
            }
        }
        foreach ($filterLikeRelations as $key) {
            $queryParam = explode('_', str_replace('whereLike_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['whereLike_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->where($relationColumn, 'like', '%' . $value . '%');
                    });
                }
            }
        }
        foreach ($filterOrLikeRelations as $key) {
            $queryParam = explode('_', str_replace('or_whereLike_relation_', '', $key));
            $relationName = $queryParam[0];
            $relationColumn = implode('_', array_slice($queryParam, 1));
            if (in_array($relationName, array_keys($relationsFilters))) {
                $relationColumns = $relationsFilters[$relationName];
                if (in_array($relationColumn, $relationColumns)) {
                    $value = $input['or_whereLike_relation_' . $relationName . '_' . $relationColumn];
                    $query = $query->whereHas($relationName, function ($query) use ($value, $relationColumn) {
                        $query->orWhere($relationColumn, 'like', '%' . $value . '%');
                    });
                }
            }
        }

        return $query;
    }

    protected function scopeLoadRelations($query, array $input, array $allowedRelationsOverridden = [])
    {
        //Extract requested relations
        $relationsKeys = collect(array_keys($input))->filter(function ($key) use ($input) {
            return (str_contains($key, 'with_') && $input[$key]);
        });

        //Check if allowed relations are overridden
        if (count($allowedRelationsOverridden) > 0) {
            $allowedRelationsToLoad = $allowedRelationsOverridden;
        } else {
            $allowedRelationsToLoad = $this->allowedRelationsToLoad;
        }

        //Load requested relations with their requested columns
        foreach ($relationsKeys as $key) {
            $relation = str_replace('with_', '', $key);
            //Concatenate the columns to glue with the relation name
            if (in_array($relation, array_keys($allowedRelationsToLoad))) {
                if (isset($allowedRelationsToLoad[$relation]) && count($allowedRelationsToLoad[$relation]) > 0) {
                    $columns = '';
                    foreach ($allowedRelationsToLoad[$relation] as $column) {
                        $columns .= $column;
                        if ($column != end($allowedRelationsToLoad[$relation])) {
                            $columns .= ',';
                        }
                    }
                    $relation .= ':' . $columns;
                }
                $query = $query->with($relation);
            }
        }
        return $query;
    }

    /**
     * Sort the records based on specified column
     * @param $query Auto-loaded
     * @param array $input An array of key-value. The keys are (sort, sort_desc). The sort key value indicates the column to sort the records according to it. The sort_desc key value is a boolean indicates whether to sort descendingly or ascendingly. If not passed then sort by id
     * @return mixed Query
     */
    protected function scopeSort($query, array $input = [])
    {
        if (isset($input['sort']) && ($this->isAllColumns($this->allowedColumnsToSortBy) || in_array($input['sort'], $this->allowedColumnsToSortBy))) {
            if ($input['sort'] == 'id') {
                $input['sort'] = $this->getTable() . '.' . $input['sort'];
            }
            if (isset($input['sort_desc']) && $input['sort_desc']) {
                $query = $query->orderByDesc($input['sort']);
            } else {
                $query = $query->orderBy($input['sort']);
            }
        }
        return $query;
    }

    /**
     * Search for specific value in records and records' relations
     * @param $query Auto-loaded
     * @param array $input The value we are searching for.
     * @param array $customSearchColumns An array of columns' names to search for the value.
     * @param array $customSearchRelations A key-value array. The key is a relation name and the value is an array of relation's columns names to search for the value into it.
     * @return mixed Query
     */
    protected function scopeSearch($query, array $input, array $customSearchColumns = [], array $customSearchRelations = [])
    {
        if (!isset($input['search'])) {
            return $query;
        }

        if (count($customSearchColumns) == 0) {
            $directColumnsToSearch = $this->isAllColumns($this->allowedColumnsToSearch) ? $this->getColumnsNames() : $this->allowedColumnsToSearch;
        } else {
            $directColumnsToSearch = $customSearchColumns;

        }

        if (count($customSearchRelations) == 0) {
            $relationsToSearch = $this->allowedRelationsToSearch;
        } else {
            $relationsToSearch = $customSearchRelations;
        }
        $searchValue = $input['search'];
        $query = $query->where(function ($query) use ($searchValue, $directColumnsToSearch, $relationsToSearch) {
            foreach ($directColumnsToSearch as $column) {
                if (in_array($column, $directColumnsToSearch)) {
                    if ($column == $directColumnsToSearch[0]) {
                        $query = $query->where($column, 'like', "%{$searchValue}%");
                    } else {
                        $query = $query->orWhere($column, 'like', "%{$searchValue}%");
                    }
                }
            }

            foreach ($relationsToSearch as $relation => $relationColumns) {
                $query = $query->orWhereHas($relation, function ($query) use ($searchValue, $relationColumns) {
                    foreach ($relationColumns as $column) {
                        if (in_array($column, $relationColumns)) {
                            //First column should start with where then add orWhere
                            if ($column == $relationColumns[0]) {
                                $query = $query->where($column, 'like', "%{$searchValue}%");
                            } else {
                                $query = $query->orWhere($column, 'like', "%{$searchValue}%");
                            }
                        }
                    }
                });
            }

        });
        return $query;
    }

    public function scopeGetData($query, array $input, array $customListColumns = [])
    {
        if (isset($input['list']) && $input['list']) {
            if (count($customListColumns) != 0) {
                $this->listColumnsToRetrieve = $customListColumns;
            }
            if (isset($input['number']) && $input['number'] != -1) {
                $records = $query->select($this->listColumnsToRetrieve)->paginate($input['number']);
            } else {
                $records = $query->get($this->listColumnsToRetrieve);
            }
        } else {
            if (isset($input['number']) && $input['number'] != -1) {
                $records = $query->paginate($input['number']);
            } else {
                $records = $query->get();
            }
        }
        return $records;
    }

    protected function scopeApplyAllFilters($query, array $input)
    {
        return $query->filter($input)->loadRelations($input)->search($input)->sort($input)->getData($input);
    }

    protected function isAllColumns($columnsArray): bool
    {
        return count($columnsArray) == 1 && $columnsArray[0] == '*';
    }

    protected function getColumnsNames()
    {
        return Schema::getColumnListing($this->getTable());
    }

}
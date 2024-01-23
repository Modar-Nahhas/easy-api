# easy-api

This package provides an easy way to build REST Apis providing the basic functionalities
in a controlled yet somehow dynamic way.

### These basic functionalities are:

1. Pagination.
2. Sorting.
3. Searching.
4. Filtering based on columns.

# Installation

composer require mapi/easyapi

# How it works

This package depends on inheritance and traits to add scopes to the Eloquent model.
These scopes provide the basic functionalities mentioned before.
It also provides a form request to make it easier to use these functionalities.

# Description of the models provided

1. To use it in an eloquent model, a model should inherit from one of these classes:
    * `ApiModel`: this class is used for models that should extend the `Illuminate\Database\Eloquent\Model` class.
    * ApiPivot: this class is used for models that should extend the `Illuminate\Database\Eloquent\Relations\Pivot`
      class.
    * `ApiUser`: this is used for a user classes that should extend the `Illuminate\Foundation\Auth\User`.
2. If your class is already inheriting from another class you should use the provided trait:
    * `IsApiModel`: this trait provides the same functionalities as the previous models, but it requires a
      special treatment to control the behavior of the model.
3. This step is optional and depends on your application. We provide a base form request `BaseRequest`
   in which we define the validation rules for pagination, sorting, and searching.

# How to use

We will be showing examples on how to implement each functionality in our code.

### Pagination

We have implemented this functionality in a scope called `getData`. This scope uses
the `paginate` function provided by Laravel framework. It also allows to retrieve all data
and minified version of the data. <br/>
`PS: The page number should be passed with the request with key name page`

1. Get paginated data <br/>
   This can be done by passing an associative array of data which contains a key `number` and its
   value represents how many records you want ro retrieve per page.
2. Get all data <br/>
   To get all data you should send the number parameter in the request with value equal to -1 `number = -1`.
3. Get minified data
   This functionality allows the developer to control which fields should be retrieved by the model.
   In some cases, like populating a dropdown list, you probably need two fields. One to represent the value
   `id` for example and the second to represent the text displayed to the user: `name` for example.
   to achieve this functionality, there are two steps: <br/>First override the property `$listColumnsToRetrieve` in the
   model. Its value should be an array of columns names that should be retrieved if the functionality is called.
   <br/>Second to tell the scope to load a minified version of the result, you should send in
   the data array passed to the scope a key called `list` with value `true`.<br/>

<pre>
<code>
// Inside your model (MyModel)
class MyModel extends ApiModel {

   protected $listColumnsToRetrieve = [
      'id','name'
   ];

}

// Where you need to retrieve the data (in controller for example)
$data = [
   'list' => 1,
   'number' => 10
];
MyModel::query()->getData($data);`
</code>
</pre>

### Sorting

We have implemented this functionality in a scope called `sort`. This scope uses
the `orderBy` and the `orderByDesc` functions provided by Laravel framework. For now, sorting
support sorting on one column. By default, the user is allowed to sort based on all columns.
In order to limit the columns allowed to sort by, you can declare a variable
called `$allowedColumnsToSortBy`. Its value should be an array of columns' names
the user are allowed to sort by. The client can ask to sort the records by sending
parameters called `sort` which is a string represents the column name and `sort_desc` which
is a boolean represent if the sort direction is descending or not.

<pre>
<code>
// Inside your model (MyModel)
class MyModel extends ApiModel {

   protected $allowedColumnsToSortBy = [
      'id','name'
   ];

}

// Where you need to sort the data (in controller for example)
$data = [
   'sort' => 'column_name',
   'sort_desc' => 1
];
MyModel::query()->sort($data);`
</code>
</pre>

### Searching

We have implemented this functionality in a scope called `search`. This scope
uses the `where like` to search specific columns in the direct columns of the model
or relation columns. By default, the search is not allowed for any column.
In order to activate this functionality on `direct columns`, you need to define a variable in the model
called `$allowedColumnsToSearch`. Its value should be an array of columns' names that
the search process will be applied on. In order to activate this functionality on
`relation columns`, you need to define a variable in the model called `$allowedRelationsToSearch`.
Its value is an associative array. The `key` represents the `name of the relation` and the `value` is
an array of columns' names that the code should search in the respective table.
The client can use the search functionality by sending a parameter called `search` and the system
will automatically search the direct columns and relations columns defined in the model


<pre>
<code>
// Inside your model (MyModel)
class MyModel extends ApiModel {

   protected $allowedColumnsToSearch = [
      'id','name
   ];

   protected $allowedRelationsToSearch = [
      'relation_name_1' => [ 'id','name' ],
      'relation_name_2' => [ 'name','price' ]
   ];

}

// Where you need to sort the data (in controller for example)
$data = [
   'search' => 'search string',
];
MyModel::query()->search($data);`
</code>
</pre>

### Filtering

We have implemented this functionality in a scope called filter.
This scope support different types of filtering. Supported types are:

#### Direct filter types

* where_.
* or_where_.
* whereIn_.
* whereNotIn_.
* whereLike_.
* or_whereLike_.
* whereNull_.
* whereNotNull_.
* or_whereNull_.
* or_whereNotNull_.

#### Relation filter types

* where_relation_.
* or_where_relation_.
* whereIn_relation_.
* whereNotIn_relation_.
* whereLike_relation_.
* or_whereLike_relation_.
* or_whereNull_relation_.
* or_whereNotNull_relation_.
* whereNull_relation_.
* whereNotNull_relation_.

The filters work on direct columns of the model or relation's columns.
In order to activate this functionality on `direct columns` you need to define
a variable called `$allowedFilters` in the model. Its value should be an
array of columns' names that the client is allowed to filter based on. In order
to activate this functionality on `relation columns`, you need to define a variable in
the model called `$allowedRelationsFilters`. Its value should ba an associative array.
The `key` is the `name of the relation` we need to filter by its value, and the `value` is
an array of columns' name the client is allowed to filter by its value. After you define
the allowed columns to filter based on, the client can use the filters by sending the
column name as parameter `prefixed` by one of the `direct filters types` for direct columns
and `relation filters types` for relations' columns.


<pre>
<code>
// Inside your model (MyModel)
class MyModel extends ApiModel {

   protected $allowedFilters = [
      'id','name
   ];

   protected $allowedRelationsFilters = [
      'relation_name_1' => [ 'id','name' ],
      'relation_name_2' => [ 'name','price' ]
   ];

}

// Where you need to sort the data (in controller for example)
$data = [
   'where_name' => 'name 1',
   'where_relation_price' => 500
];
MyModel::query()->filter($data);`
</code>
</pre>

### Loading relations

We have implemented this functionality in a scope called `loadRelations`. This scope
allow the client to ask the server to load relations without the need to create multiple
APIs from the server side. One API can service different use cases of the client. In order to
activate this functionality, you should define a variable called `$allowedRelationsToLoad` in the model.
Its value should be an associative array. Its `key` represents the relation name. Its `value` should
be an array of the `relation's columns we need to load`. You can use `*` to indicate that you want all
columns to be loaded. </br>
`PS: when you define a subset of the columns to be loaded, the foreign key column should be one
of these values or the relation will be empty`.
The client can use this functionality by sending a parameter representing the `relation name`
needed to be loaded `prefixed` by `with_` and its value is boolean indicates that the relation is
needed or not.


<pre>
<code>
// Inside your model (MyModel)
class MyModel extends ApiModel {

   protected $allowedRelationsToLoad = [
      'relation_name_1' => [ 'id','name' ],
      'relation_name_2' => [ 'id','name','price' ]
   ];

}

// Where you need to sort the data (in controller for example)
$data = [
   'with_relation_name_1' => 0,
   'with_relation_name_2' => 1
];
MyModel::query()->loadRelations($data);`
</code>
</pre>

### Applying all functionalities

If you want all functionalities to be used, you can use the scope `applyAllFilters` and pass
the input sent by the client.

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

# Description of the models provides

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
      'id','name
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
   

<?php

namespace Mapi\Easyapi\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    protected $getRules = [
        'page' => ['required_with:number', 'integer', 'min:1'],
        'number' => ['required_with:page', 'integer'],
        'search' => ['sometimes', 'string'],
        'sort' => ['required_with:sort_desc', 'string'],
        'sort_desc' => ['sometimes', 'boolean'],
        'list' => ['sometimes', 'boolean']
    ];

    protected $postRules = [

    ];

    protected $putRules = [
        '_method' => ['required', 'in:put']
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (strtolower($this->getMethod()) == 'get') {
            return $this->getRules;
        } else if (strtolower($this->getMethod()) == 'put') {
            return $this->putRules;
        } else {
            return $this->postRules;
        }
    }
}

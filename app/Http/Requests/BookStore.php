<?php

namespace App\Http\Requests;

use App\Rules\BookRules;
use Illuminate\Foundation\Http\FormRequest;

class BookStore extends FormRequest
{
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
    public function rules(): array
    {
        if ($this->route() === null) {
            return [];
        }

        $routeName = $this->route()->getName();

        switch ($routeName) {
            case 'books.store':
                return [
                    'title' => 'required|max:255',
                    'publisher' => 'required',
                    'year' => 'required|numeric',
                ];
            case 'books.update':
                return [
                    'title' => 'required|max:255',
                    'publisher' => 'required',
                    'year' => 'required|numeric',
                ];
            case 'loan.create':
                return [
                    'book_id' => ['required', 'string'],
                    'branch_id' => 'required|numeric',
                    'card_no' => 'required|numeric|',
                ];
            case 'loan.update':
                return [
                    'book_id' => ['required', 'string'],
                    'branch_id' => 'required|numeric',
                    'card_no' => 'required|numeric|',
                ];    
            default:
                return [];
        }
    }
}

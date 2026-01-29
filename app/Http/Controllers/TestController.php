<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class TestController extends Controller
{
    public function index()
    {
        $integer = 13789101113;
        $string = 'Обычная строка';
        $fruits = ['apple', 'banana', 'orange', 'grape', 'cherry'];
        $person = [
            'firstName' => 'Ирина',
            'lastName' => 'Руш',
            'age' => 43,
            'gender' => 'female',
            'isMarried' => true,
        ];

        return Inertia::render('Test', [
            'integer' => $integer,
            'string' => $string,
            'array' => $fruits,
            'associativeArray' => $person,
        ]);
    }
}

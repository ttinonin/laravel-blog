<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        $name = "Daniel Silva";
        $animals = ['Lion', 'Cat', 'Dog'];

        return view('homepage', ['name' => $name, "animals" => $animals]);
    }

    public function about() {
        return view('single-post');
    }

    public function testeJSON() {
        return ['name' => 'daniel', 'password' => 123];
    }
}

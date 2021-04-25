<?php

namespace App\Http\Controllers;

use App\Http\Actions\CreateAirtimeAction;
use Illuminate\Http\Request;

class AeonController extends Controller
{
    public function index(){
        return app(CreateAirtimeAction::class)->execute();
    }
}

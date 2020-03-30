<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
  /**
   * Main Display
   */
  public function index()
  {
      return view('product');
  }

}

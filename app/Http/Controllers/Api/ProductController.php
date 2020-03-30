<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list() {

    	// initialize product array 
    	$product_array = array();	
    	// number of product display 
    	$loop = 8;

		// loop product list 
    	for($i=1; $i<= $loop; $i++) {
    		array_push($product_array, 
    				array(
		            'id' => $i,
		            'name' => 'Addidas '.$i,
		            'image' => 'shoe'.$i.'.jpg',
		            'price' => round(rand(12, 250) / 1 * 5, 0),
		        )		
    		);
    	}

    	return $product_array;

    }
}

<?php 

namespace App\Controllers;

use App\Models\Product;
use Slim\Views\Twig as View;

class HomeController extends Controller
{
	public function index ($request, $responce)
	{
		$currency = $this->db->table('currency')
		->select(
			'country',
			'currency',
			'code',
			'symbol'
		)
		->get();
		return $this->view->render($responce,'home.twig',['currency' => $currency]);
	}

	public function fetchProduct ($request, $responce)
	{
		$req = $request->getParams();
		$product_category = $req['product_category'];
		$sorting_by 	  = $req['sorting_by'];
		$sorting_order 	  = $req['sorting_order'];
		$currency_code 	  = $req['currency_code'];
		$per_rupee_amt 	  = 1;

		if ($currency_code != 'INR') 
		{
			$url = "https://api.exchangeratesapi.io/latest?base=INR";
			$response = \Httpful\Request::get($url)
		    ->expectsJson()
		    ->send();

		    $character = $response->body->rates;
		    // var_dump($character);
		    $flag = 1;
		    foreach ($character as $key => $value) 
		    {
		    	if ($key == $currency_code) 
		    	{
		    		$flag =1;
		    		$per_rupee_amt = $value;
		    		break;
		    	}
		    	else
		    		$flag =0;
		    }
		    if($flag == 0)
	    	{
	    		return json_encode([
				    'success'=> 0, 
				    'msg'    => 'currency not found.',
				]);
	    	}
		    // var_dump($response->body->rates);
		}

		$product = Product::select(
			'product_category',
			'product_name',
			'product_prize',
			'product_image'
		)
		->where('product_category',$product_category)
		->orderBy($sorting_by, $sorting_order)
		->skip(0)
		->take(8)
		->get();

		return json_encode([
		    'success'=> 1, 
		    'msg'    => 'Record fetched successfully.',
		    'product'=> $product,
		    'per_rupee_amt'=> $per_rupee_amt
		]);
	}
}
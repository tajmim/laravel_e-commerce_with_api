<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);


        // create a token number for user with passport
        $token = $user->createToken('Token')->accessToken;


        $user->save();

        return  response()->json(['user'=>$user , 'token'=>$token],200);
    }




    public function user_login(Request $request){
    $data = [
        'email'=>$request->email,
        'password'=>$request->password,
    ];
    if(auth()->attempt($data)){

        $token = auth()->user()->createToken('Token')->accessToken;
        return  response()->json(['token'=>$token],200);
    }else{
        return  response()->json(['message'=>'cant login'],20);
    }
  }
    public function seller_register(Request $request)
    {
        $user = new Seller;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);


        // create a token number for user with passport
        $token = $user->createToken('Token')->accessToken;


        $user->save();

        return  response()->json(['user'=>$user , 'token'=>$token],200);
    }




    public function seller_login(Request $request){
    $data = [
        'email'=>$request->email,
        'password'=>$request->password,
    ];
    if(auth()->attempt($data)){

        $token = auth()->user()->createToken('Token')->accessToken;
        return  response()->json(['token'=>$token],200);
    }else{
        return  response()->json(['message'=>'cant login'],20);
    }
  }


  public function show_products()
  {
      $products = Product::all();
      return response()->json(['products'=>$products]);
  }


}

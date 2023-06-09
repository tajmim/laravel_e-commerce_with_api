<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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




    public function user_login(Request $request)
    {
        $user_data = $request->all();
        $validation = validator($user_data,[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()],422);
        }

        if(Auth::attempt(['email' => $user_data['email'] , 'password' => $user_data['password'] ])){
            $user = Auth::user();

            $token = $user->createToken('Token Name')->accessToken;

            return response()->json(['message'=>'you are logged in','token'=>$token]);
        }else{
            return response()->json(['message'=>'records not found']);

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

  public function place_order(Request $request , $id){
    
        $user = Auth::guard('api')->user();
        return response()->json(['profile'=> $user]);

  }


}

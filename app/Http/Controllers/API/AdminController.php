<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\admin;
use App\Models\User;
use App\Models\Seller;
use App\Models\manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Product;



class AdminController extends Controller
{
    public function __construct()
    {
        config(['auth.defaults.guard' => 'api-admin']);
    }

        public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        if (! $token = Auth::attempt($validator->validated())) {
            return response()->json(['errors' => 'unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
        ]);
            if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        // create username
        $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        $user = admin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'username'=>$username ]
        ));

        return response()->json([
            'message' => 'admin successfully registered',
            'user' => $user,
        ]);
    }
    public function profile_details()
    {
        return response()->json(['user'=>auth()->user()]);
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
        ]);
    }
    // admin creates the manager.
    public function create_manager(Request $request){

        $request->validate(['name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:managers',]);
    // create username
        $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

    $password = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    $user = manager::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'username'=>$username,
        'password' => Hash::make($password),
    ]);
    return response()->json(['message'=>'manager created successfully','manager'=>$user,'password'=>$password]);

    }
    
// user get,delete,crt

    public function create_user(Request $request){

        $request->validate(['name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',]);
    
    $password = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'username'=>$username,
        'password' => Hash::make($password),
    ]);
    return response()->json(['message'=>'user created successfully','user'=>$user,'password'=>$password]);

    }
    public function show_all_user(){
        $users = User::all();
        return response()->json(['users'=>$users ]);

    }
    public function show_user($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json(['user' => $user]);
        }
        else{
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function delete_user($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'user deleted successfully']);

    }
    public function show_all_order(){
        $orders = order::all();
        return response()->json(['orders'=>$orders ]);
    }


    public function show_all_seller(){
        $users = Seller::all();
        return response()->json(['sellers'=>$users ]);
    }
    public function show_seller($id)
    {
        $seller = Seller::find($id);
        if($seller){
            return response()->json(['seller' => $seller]);

            }else {
            return response()->json(['message' => 'seller not found'], 404);
        }
    }
    public function delete_seller_product($id){
        $seller = Seller::find($id);
        if(!$seller){
            return response()->json(['message' => 'seller not found']);
        }
    // Find the products associated with the seller
        $products = Product::where('user_id',$id)->get();
        if($products->isEmpty()){
            $seller->delete();
            return response()->json(['message' => 'seller deleted successfully']);
        }
        foreach($products as $product){
            Variation::where('product_id', $product->id)->delete();
            $product->delete();
        }
        $seller->delete();
        return response()->json(['message' => 'seller and attached product deleted succesfully' ]);

    }
//         function fix_percentage(Request $request, $id)
//     {
//         $seller = seller::find($id);
//         $seller->percentage = $request->percantage;
//         $seller->save();
//         return response()->json(['message' => 'percantage added successfully' ]);
//     }
}













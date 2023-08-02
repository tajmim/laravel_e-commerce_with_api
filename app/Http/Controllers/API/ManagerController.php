<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\order;
use App\Models\Seller;
use App\Models\Balancesheet;




class ManagerController extends Controller
{
    public function __construct()
    {
        config(['auth.defaults.guard' => 'api-manager']);
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
            'email' => 'required|email|unique:managers',
            'password' => 'required|min:6',
        ]);
            if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        // create username
        $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        $user = manager::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'username'=>$username ]
        ));

        return response()->json([
            'message' => 'man successfully registered',
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
     public function edit_profile(Request $request)
    {
        $user = manager::find(auth()->user()->id);

        if($request->name){
            $name = $request->name;
            $user->name = $name;
        }
        if($request->email){
            $email = $request->email;
            $user->email = $email;
        }
        $user->save();
        return response()->json(['message'=>'profile updated succesfully']);

    }

// add category................../

    public function show_category(){
        $categories = Category::all();
        return response()->json(['categories'=>$categories]);
    }
    public function add_category(Request $request){
        $category = new Category;
        $category->name =$request->name;
        $category->save();
        return response()->json(['message'=>'category successfully','category'=>$category]);
    }
    public function delete_category($id){
        $del_category = Category::find($id);
        $del_category->delete();
        return response()->json(['message'=>'category deleted successfully']);
     
    }

// product crud..................../
        public function add_product(Request $request){
        $product = new Product;
        $product->user_id = auth()->user()->id;
        $product->usertype = 'manager';
        $product->title = $request->title;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->category = $request->category;
        

        if($request->file('image1')){
            $file = $request->file('image1');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image1 = $uniqueName;  
        }
        

        if($request->file('image2')){
            $file = $request->file('image2');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image2 = $uniqueName;  
        }


        if($request->file('image3')){
            $file = $request->file('image3');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image3 = $uniqueName;  
        }


        if($request->file('image4')){
            $file = $request->file('image4');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image4 = $uniqueName;  
        }
        $product->save();
        return response()->json(['message' => 'product added']);
    }
    public function add_variation(Request $request,$id){
        $variation = new Variation;
        $variation->product_id = $id;
        $variation->color = $request->color;
        $variation->size = $request->size;
        $variation->price = $request->price;
        $variation->discount_price = $request->discount_price;
        $variation->reseller_price = $request->reseller_price;
        $variation->reseller_quantity = $request->reseller_quantity;
        $variation->quantity = $request->quantity;

        if($request->file('image1')){
            $file = $request->file('image1');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $variation->image1 = $uniqueName;  
        }


        if($request->file('image2')){
            $file = $request->file('image2');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $variation->image2 = $uniqueName;  
        }


        if($request->file('image3')){
            $file = $request->file('image3');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $variation->image3 = $uniqueName;  
        }
        $variation->save();
        return response()->json(['message' => 'variation added']);
    }

    public function show_product(){
        $products = DB::table('products')
            ->leftJoin('variations', 'products.id', '=', 'variations.product_id')
            ->get();
            return response()->json(['message' => $products]);
    }



    public function edit_product(Request $request,$id){
        $product = Product::find($id);
        if($request->title){
            $product->title = $request->title;
        }
        if($request->description){
            $product->description = $request->description;
        }
        if($request->category){
            $product->category = $request->category;
        }
        
        if($request->file('image1')){
            $file = $request->file('image1');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image1 = $uniqueName;
        }
        if($request->file('image2')){
            $file = $request->file('image2');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image2 = $uniqueName;
        }
        if($request->file('image3')){
            $file = $request->file('image3');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image3 = $uniqueName;
        }
        if($request->file('image4')){
            $file = $request->file('image4');
            $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $uniqueName);
            $product->image4 = $uniqueName;
        }
        $product->save();
        return response()->json(['message' => 'product edited']);


    }
    public function edit_variation(Request $request,$id){
        $variation = variation::find($id);
        if($variation->color){
            $variation->color = $request->color;
        }
        if($variation->size){
            $variation->size = $request->size;
        }
        if($variation->size){
            $variation->size = $request->size;
        }
        if($variation->price){
            $variation->price = $request->price;
        }
        $variation->save();
        return response()->json(['message' => 'variation edited']);
    }
    public function delete_product($id){
        $product = Product::find($id);
        if($product->usertype == "manager"||$product->usertype == "admin"){
            variation::where('product_id',$id)->delete();
            $product->delete();
            return response()->json(['message' => 'own product deleted']);
        }else{
            return response()->json(['message' => 'its not your product']);
        }
    }


//     public function view_order()
//     {
//        $order = order::where('seller_type','manager')->get();
//        return response()->json(['order' => $order]);
//     } 

//     public function accept_order($id){
//         $order = order::find($id);
//         if($order->seller_type != 'manager'){
//             return response()->json(['message' => 'order cant be placed']);
//         }
//         $order->order_status = "accepted";
//         $order->save();
//         return response()->json(['message' => 'order accepted successfully']);
//     }
//     public function cancel_order($id){
//         $order = order::find($id);
//         if($order->seller_type == 'manager'){
//             $order->order_status = "canceled";
//             $order->save();
//             return "order cancelled";
//         }












//     public function recieve_payment($id){
//         $order = order::find($id);
//         if($order->is_paid == "yes"){
//             return response()->json(['message' => 'payment already recieved']);
//         }
//         if($order->seller_type != 'seller'){
//             $tk = $order->product_price;
//             // tk dei
//             $balancesheet = new Balancesheet;
//             $balancesheet->order_id = $id;
//             $balancesheet->amount += $tk;
//             $balancesheet->save();
//             $order->is_paid = 'yes';
//             $order->save();
//             return response()->json(['message' => 'payment recieve success']);
//         }

//         $tk = $order->product_price;
//         $the_identification_number_of_seller = $order->seller_id;
//         $seller = Seller::find($the_identification_number_of_seller);
//         $percent = $seller->percentage;
//         $amount_for_admin = $tk * $percent / 100;
//         // tk dei
//         $balancesheet = new Balancesheet;
//         $balancesheet->order_id = $id;
//         $balancesheet->amount += $amount_for_admin;
//         $balancesheet->save();
//         $seller->total_earning += $tk - $amount_for_admin;
//         $seller->save();
//         $order->is_paid = 'yes';
//         $order->save();
//         return response()->json(['message' => 'payment recieve success']);


//     }







//     public function show_all_order(){
//         $orders = order::all();
//         return response()->json(['orders'=>$orders ]);

//     }
//     public function show_all_seller(){
//         $users = Seller::all();
//         return response()->json(['sellers'=>$users ]);

//     }
//     public function show_seller($id)
//     {
//         $seller = Seller::find($id);
//         if($seller){
//             return response()->json(['seller' => $seller]);

//             }else {
//             return response()->json(['message' => 'seller not found'], 404);
//         }
//     }
//     public function delete_seller_product($id){
//         $seller = Seller::find($id);
//         if(!seller){
//             return response()->json(['message' => 'seller not found']);
//         }
//     // Find the products associated with the seller
//         $products = Product::where('user_id',$id)
//                              ->where('usertype','seller')
//                              ->get();
//         if($products->isEmpty()){
//             $seller->delete();
//             return response()->json(['message' => 'seller deleted successfully']);
//         }
//         foreach($products as $product){
//             $product->delete();
//         }
//         $seller->delete();
//         return response()->json(['message' => 'seller and attached product deleted succesfully']);
//     }


    

}


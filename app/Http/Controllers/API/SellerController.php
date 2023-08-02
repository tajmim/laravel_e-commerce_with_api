<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Product;
use App\Models\order;
use App\Models\Variation;



class SellerController extends Controller
{
    public function __construct()
    {
        config(['auth.defaults.guard' => 'api-seller']);
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
            'email' => 'required|email|unique:sellers',
            'password' => 'required|min:6',
        ]);
            if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        // create username
        $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        $user = Seller::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'username'=>$username ]
        ));

        return response()->json([
            'message' => 'seller successfully registered',
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
        $user = Seller::find(auth()->user()->id);

        if($request->name){
            $name = $request->name;
            $user->name = $name;
        }
        if($request->email){
            $email = $request->email;
            $user->email = $email;
        }
        if($request->shop_name){
            $shop_name = $request->shop_name;
            $user->shop_name = $shop_name;
        }
        
        
        
        $user->save();

        return response()->json(['message'=>'profile updated succesfully']);

    }

// add category

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

// // product crud
//     public function show_product(){
//         $products = Product::where('user_id',Auth::user()->id)
//                             ->where('usertype','seller')->get();
//         $categories = Category::all();
//         return response()->json(['message'=>' showed successfully','product'=>$products,'category'=>$categories]);
//     }
//     public function add_product(Request $request){
//         $product = new Product;
//         $product->user_id = auth()->user()->id;
//         $product->usertype = 'seller';
//         $product->title = $request->title;
//         $product->description = $request->description;
//         $product->quantity = $request->quantity;
//         $product->category = $request->category;
//         $product->price = $request->price;
//         $product->color = $request->color;

//         $file = $request->file('image');
//         $uniqueName = uniqid().'.'.$file->getClientOriginalExtension();
//         $path = $file->storeAs('uploads', $uniqueName);
//         $product->image = $uniqueName;

//         $product->save();
//         return response()->json(['message' => 'product added']);
//     }








    public function add_product(Request $request){
        $product = new Product;
        $product->user_id = auth()->user()->id;
        $product->usertype = 'seller';
        $product->title = $request->title;
        $product->description = $request->description;
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
            ->leftJoin('categories', 'products.category', '=', 'categories.id')
            ->where('products.user_id',Auth::user()->id)
            ->where('products.usertype','seller')
            ->select('products.*','variations.color','variations.size','variations.price','variations.discount_price','variations.reseller_price','variations.reseller_quantity','variations.quantity','variations.image1','variations.image2','variations.image3','variations.image4','categories.name')
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
    public function delete_product($id){
        $product = Product::find($id);
        if($product->user_id == auth()->user()->id and $product->usertype == 'seller' )
        {
            variation::where('product_id',$id)->delete();
            $product->delete();
            return response()->json(['message' => 'own product deleted']);
        }else{
            return response()->json(['message' => 'its nor your product']);
        }
    }
    public function view_order()
    {
        $order = order::where('seller_id',auth()->user()->id)->where('seller_type','seller')->get();
        return response()->json(['order' => $order]);
    }
    public function accept_order($id){
        $order = order::find($id);
        if($order->seller_id == auth()->user()->id and $order->seller_type == 'seller'){
            $order->order_status = "accepted";
            $order->save();
            return response()->json(['message' => 'order accepted']);
        }
        
    }
    public function cancel_order($id){
        $order = order::find($id);
        if($order->seller_id == auth()->user()->id and $order->seller_type == 'seller'){
            $order->order_status = "canceled";
            $order->save();
            return response()->json(['message' => 'order cancelled']);
        }
        
    }





}


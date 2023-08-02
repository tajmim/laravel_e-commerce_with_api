<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\ContactMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\order;
use App\Models\Review;

class UserController extends Controller
{
    public function __construct()
    {
        config(['auth.defaults.guard' => 'api-user']);
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
            if (! $token = Auth::attempt($validator->validated()))
            {
                return response()->json(['errors' => 'unauthorized'], 401);
            }
            return $this->createNewToken($token);
        }

    public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
            if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }

        // create username
        $username = substr($request->email, 0, strpos($request->email, '@')).str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);

        $user = user::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password),'username'=>$username ]
        ));

        return response()->json([
            'message' => 'user successfully registered',
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
// product details........................./

    public function show_product(){
        $products = Product::all();
        return response()->json(['products'=>$products ]);

    }
    public function product_details($id){
        $product = Product::find($id);
        return response()->json(['products'=>$product ]);
      
    }
// // add cart................................................?/
//     public function add_cart(Request $request, $p_id){
//         if(Auth::user()){
//             $cart = new Cart;
//             $product = Product::find($p_id);
//             if($request->quantity > $product->quantity){
//                 return response()->json(['message'=>"product stock out" ]);
//             }
//             $found_cart = Cart::where('user_id',Auth::user()->id)->where('product_id',$p_id)->first();
//             if($found_cart){
//                 $found_cart->quantity += $request->quantity;
//                 $found_cart->save();
//                 return response()->json(['carts'=>$cart ]);
//             }else{
//                 $cart->user_id = Auth::user()->id;
//                 $cart->user_name = Auth::user()->name;
//                 $cart->seller_id = $product->user_id;
//                 $cart->seller_type = $product->usertype;
//                 $cart->product_id = $product->id;
//                 $cart->product_title = $product->title;
//                 $cart->product_image = $product->image;
//                 $cart->quantity = $request->quantity;
//                 $cart->price = $product->price;
//                 if($request->color){
//                    $cart->color = $request->color; 
//                 }
//                 $cart->save();
//                 return response()->json(['carts'=>$cart ]);
//             }
//         }
//     }
//     public function show_cart(){
//         $carts = Cart::where('user_id',Auth::user()->id);
//         return response()->json(['carts'=>$carts]);
//     }

//     public function delete_cart($id){
//         $delete_cart = Cart::find($id);
//         return response()->json(['message'=>"cart deleted successfully" ]);

//     }

// // add wish.,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,.....;;;;;;/

//     public function add_wish($p_id){
//         if(Auth::user()){
//             $wish = new Wish;
//             $product = Product::find($p_id);
            
//             $wish->user_id = Auth::user()->id;
//             $wish->user_name = Auth::user()->name;
//             $wish->seller_id = $product->user_id;
//             $wish->seller_name = $product->usertype;
//             $wish->product_title = $product->title;
//             $wish->product_image = $product->image;
//             $wish->price = $product->price;
//             $wish->save();
//             return response()->json(['wish'=>$wish ]);

//         }
//     }

//     public function show_wish(){
//         $wishes = Wish::where('user_id',Auth::user()->id);
//         return response()->json(['wishes'=>$wishes]);
//     }

//     public function delete_wish($id){
//         $delete_wish = Wish::find($id);
//         return response()->json(['message'=>"wish deleted successfully" ]);

//     }


// // order details......................................./

//     public function make_order(Request $request){
//         $user_id = Auth::user()->id;
//         $carts = Cart::where('user_id',$user_id)->get();
//         foreach ($carts as $cart) {
//             $order = new order;
//             $order->user_id = $cart->user_id;
//             $order->user_name = $cart->user_name;
//             $order->user_email = Auth::user()->email;
//             $order->seller_id = $cart->seller_id;
//             $order->seller_type = $cart->seller_type;
//             $order->product_id = $cart->product_id;

//             $product = Product::find($cart->product_id);

//             $order->product_title = $product->title;
//             $order->product_desc = $product->description;
//             if($cart->quantity > $product->quantity){
//                 return response()->json(['message'=>"product stock out" ]);
//             }
//             $order->product_quantity = $cart->quantity;
//             $order->product_price = $cart->quantity * $product->price;
//             $order->shipping_address = $request->shipping_address;
//             $order->phone = $request->phone;
//             $product->quantity -= $cart->quantity;
//             $order->order_status = 'pending';
//             $product->save();
//             $order->save();
//             $cart->delete();
//             return response()->json(['message'=>"success",'orders'=>$order ]);

//         }
//     }

//     public function show_order(){
//         $order = order::where('user_id',Auth::user()->id)->get();
//         return response()->json(['order'=>$order ]);

//     }
// // customer review
//     public function add_review(Request $request, $id){
//         $order = order::find($id);
//         $review = new Review;
//         $review->product_id = $order->product_id;
//         $review->product_title = $order->product_title;
//         $review->user_id = $order->user_id;
//         $review->email = $order->email;
//         $review->review = $request->review;
//         $review->save();
//         $order->order_status = "reviewd";
//         $order->save();
//         return response()->json(['message' => "review added successfully" , 'review' => $review]);

//      }
// // contact by send mail
//     public function contact(Request $request){
//         $subject = $request->subject;
//         $email = $request->email;
//         $phone = $request->phone_no;
//         $message = $request->message;

//         $contactData = [
//             'subject'=>$subject,
//             'email'=>$email,
//             'phone'=>$phone,
//             'message'=>$message,
//         ];
//         Mail::to("shamima.bhola@gmail.com")->send( new ContactMail($contactData));
//         return response()->json(['message' => "review added successfully" ]);
//      }


}



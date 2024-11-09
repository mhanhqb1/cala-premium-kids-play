<?php
namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->get();
        $categories = Category::all();
        $users = User::where('role', UserRole::USER)->get();
        $guest = User::where('role', UserRole::GUEST)->first();
        return view('admin.pos.index', compact('products', 'categories', 'users', 'guest'));
    }

    public function searchProducts(Request $request)
    {
        $query = Product::query();

        // Lọc theo tên sản phẩm
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $query->get();

        // Trả về HTML của danh sách sản phẩm đã lọc
        return response()->json([
            'html' => view('admin.pos.partials.product_list', compact('products'))->render()
        ]);
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);

        // Kiểm tra sản phẩm có tồn tại trong session hay chưa
        $cart = session()->get('cart', []);
        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1
            ];
        }
        session()->put('cart', $cart);

        return response()->json(['cartHtml' => view('admin.pos.cart')->render()]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart');
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return response()->json(['cartHtml' => view('admin.pos.cart')->render()]);
    }

    public function checkout(Request $request)
    {
        $userId = $request->get('user_id');
        // Tạo đơn hàng và lưu vào database
        $cart = session()->get('cart');

        // Logic lưu đơn hàng vào database...
        $order = new Order();
        $order->status = OrderStatus::COMPLETED;
        $order->user_id = $userId;
        $order->total_amount = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $order->save();

        session()->forget('cart'); // Xóa giỏ hàng sau khi thanh toán

        return response()->json(['success' => true]);
    }

    public function holdOrder(Request $request)
    {
        $userId = $request->get('user_id');
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart');

        if (!$cart) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        // Tạo đơn hàng và lưu vào database với trạng thái "hold"
        $order = new Order();
        $order->status = OrderStatus::HOLD;
        $order->user_id = $userId;
        $order->total_amount = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $order->save();

        // Lưu từng sản phẩm trong giỏ hàng vào order_items
        foreach ($cart as $productId => $details) {
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'unit_price' => $details['price'],
                'total_price' => $details['price'] * $details['quantity'],
            ]);
        }

        // Xóa giỏ hàng sau khi tạm giữ
        session()->forget('cart');

        return response()->json(['success' => true, 'message' => 'Đơn hàng đã được tạm giữ']);
    }

    public function showHoldOrders()
    {
        $holdOrders = Order::where('status', OrderStatus::HOLD)->get();
        return view('admin.pos.hold_orders', compact('holdOrders'));
    }

    public function resumeOrder(Order $order)
    {
        // Lấy thông tin sản phẩm từ đơn hàng để đưa vào giỏ hàng
        $cart = [];
        foreach ($order->items as $item) {
            $cart[$item->product_id] = [
                "name" => $item->product->name,
                "price" => $item->price,
                "quantity" => $item->quantity
            ];
        }
        session()->put('cart', $cart); // Lưu giỏ hàng vào session

        return redirect()->route('admin.pos.index')->with('message', 'Đã tiếp tục xử lý đơn hàng.');
    }

    public function createCustomer(Request $request)
    {
        $customer = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make(config('app.default_pass')),
        ]);

        return response()->json(['customer' => $customer]);
    }

}

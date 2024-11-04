<?php
namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->get();
        return view('admin.pos.index', compact('products'));
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
        // Tạo đơn hàng và lưu vào database
        $cart = session()->get('cart');

        // Logic lưu đơn hàng vào database...

        session()->forget('cart'); // Xóa giỏ hàng sau khi thanh toán

        return response()->json(['success' => true]);
    }

    public function holdOrder(Request $request)
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart');

        if (!$cart) {
            return response()->json(['error' => 'Giỏ hàng trống'], 400);
        }

        // Tạo đơn hàng và lưu vào database với trạng thái "hold"
        $order = new Order();
        $order->status = OrderStatus::HOLD;
        $order->user_id = 1;
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

}

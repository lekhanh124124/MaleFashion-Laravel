<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;

class CartController extends BaseController
{
    // Hiển thị trang giỏ hàng
    public function index()
    {
        $cartItems = [];
        $total = 0;

        // TRƯỜNG HỢP 1: Đã đăng nhập -> Lấy từ Database
        if (Auth::check()) {
            $userId = Auth::user()->USER_ID;
            $cart = Cart::where('USER_ID', $userId)->first();

            if ($cart) {
                // Lấy CartItem kèm theo Variant -> Product -> Thumbnail
                $dbItems = CartItem::with(['variant.product', 'variant.size', 'variant.color'])
                    ->where('CART_ID', $cart->CART_ID)
                    ->get();

                // Chuẩn hóa dữ liệu để View dễ đọc
                foreach ($dbItems as $item) {
                    $variant = $item->variant;
                    $product = $variant->product;

                    $cartItems[] = [
                        'rowId'    => $variant->VARIANT_ID, // Dùng ID biến thể làm key xóa/sửa
                        'name'     => $product->PRODUCT_NAME,
                        'image'    => $product->thumbnail->IMAGE_URL ?? 'assets/malefashion/img/shopping-cart/cart-1.jpg', // Ảnh mặc định nếu thiếu
                        'price'    => $item->UNIT_PRICE,
                        'quantity' => $item->QUANTITY,
                        'size'     => $variant->size->SIZE_NAME ?? '',
                        'color'    => $variant->color->COLOR_NAME ?? '',
                        'total'    => $item->UNIT_PRICE * $item->QUANTITY
                    ];
                }
            }
        }
        // TRƯỜNG HỢP 2: Khách vãng lai -> Lấy từ Session
        else {
            $sessionCart = session()->get('cart', []);

            foreach ($sessionCart as $item) {
                $cartItems[] = [
                    'rowId'    => $item['variant_id'],
                    'name'     => $item['name'],
                    'image'    => $item['image'],
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                    'size'     => $item['size'] ?? '',
                    'color'    => $item['color'] ?? '',
                    'total'    => $item['price'] * $item['quantity']
                ];
            }
        }

        // Tính tổng tiền giỏ hàng
        $total = array_sum(array_column($cartItems, 'total'));

        return view('pages.shopping-cart', compact('cartItems', 'total'));
    }

    public function update(Request $request)
    {
        $items = $request->input('items', []);

        if (Auth::check()) {
            // LOGIC 1: Cập nhật Database (User đã đăng nhập)
            $userId = Auth::user()->USER_ID;
            $cart = Cart::where('USER_ID', $userId)->first();

            if ($cart) {
                foreach ($items as $item) {
                    CartItem::where('CART_ID', $cart->CART_ID)
                        ->where('VARIANT_ID', $item['rowId'])
                        ->update(['QUANTITY' => $item['quantity']]);
                }
            }
        } else {
            // LOGIC 2: Cập nhật Session (Khách vãng lai)
            $cart = session()->get('cart', []);

            foreach ($items as $item) {
                if (isset($cart[$item['rowId']])) {
                    $cart[$item['rowId']]['quantity'] = $item['quantity'];
                }
            }

            session()->put('cart', $cart);
        }

        return response()->json(['status' => 'success', 'message' => 'Cập nhật thành công']);
    }

    public function addToCart(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:PRODUCTS,PRODUCT_ID',
            'variant_id' => 'required|exists:PRODUCT_VARIANTS,VARIANT_ID',
            'quantity'   => 'required|integer|min:1'
        ]);

        $variantId = $request->variant_id;
        $quantity = $request->quantity;

        // Kiểm tra tồn kho
        $variant = ProductVariant::find($variantId);
        if ($variant->STOCK < $quantity) {
            return response()->json(['status' => 'error', 'message' => 'Sản phẩm tạm hết hàng hoặc số lượng không đủ!']);
        }

        // LOGIC 1: NẾU ĐÃ ĐĂNG NHẬP -> LƯU VÀO DB
        if (Auth::check()) {
            $userId = Auth::user()->USER_ID;

            // Tìm hoặc tạo giỏ hàng cho user
            $cart = Cart::firstOrCreate(
                ['USER_ID' => $userId],
                ['CREATED_AT' => now()]
            );

            // Kiểm tra xem sản phẩm (biến thể) đã có trong giỏ chưa
            $cartItem = CartItem::where('CART_ID', $cart->CART_ID)
                ->where('VARIANT_ID', $variantId)
                ->first();

            if ($cartItem) {
                // Nếu có rồi -> Cộng dồn số lượng
                $cartItem->QUANTITY += $quantity;
                $cartItem->save();
            } else {
                // Nếu chưa -> Tạo mới item
                CartItem::create([
                    'CART_ID' => $cart->CART_ID,
                    'VARIANT_ID' => $variantId,
                    'QUANTITY' => $quantity,
                    'UNIT_PRICE' => $variant->PRICE // Lưu giá tại thời điểm thêm
                ]);
            }
        }
        // LOGIC 2: NẾU CHƯA ĐĂNG NHẬP -> LƯU SESSION
        else {
            $cart = session()->get('cart', []);

            if (isset($cart[$variantId])) {
                $cart[$variantId]['quantity'] += $quantity;
            } else {
                $cart[$variantId] = [
                    'variant_id' => $variantId,
                    'quantity' => $quantity,
                    'price' => $variant->PRICE,
                    // Lưu thêm thông tin hiển thị để tiện render popup giỏ hàng nhỏ
                    'name' => $variant->product->PRODUCT_NAME,
                    'image' => $variant->product->thumbnail->IMAGE_URL ?? '',
                    'size' => $variant->size->SIZE_NAME ?? '',
                    'color' => $variant->color->COLOR_NAME ?? ''
                ];
            }
            session()->put('cart', $cart);
        }

        // --- ĐOẠN MỚI THÊM: TÍNH LẠI TỔNG ĐỂ TRẢ VỀ CHO AJAX ---
        $newCartCount = 0;
        $newCartTotal = 0;

        if (Auth::check()) {
            // Lấy lại từ DB
            $userId = Auth::user()->USER_ID;
            $cart = Cart::where('USER_ID', $userId)->first();
            if ($cart) {
                $items = CartItem::where('CART_ID', $cart->CART_ID)->get();
                foreach ($items as $item) {
                    $newCartCount += $item->QUANTITY;
                    $newCartTotal += $item->QUANTITY * $item->UNIT_PRICE;
                }
            }
        } else {
            // Lấy lại từ Session
            $cart = session()->get('cart', []);
            foreach ($cart as $item) {
                $newCartCount += $item['quantity'];
                $newCartTotal += $item['quantity'] * $item['price'];
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            // Trả về dữ liệu mới
            'new_count' => $newCartCount,
            'new_total' => $newCartTotal
        ]);
    }
}

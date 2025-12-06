<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Import Auth Facade
use App\Models\User;

class AuthController extends BaseController
{
    // 1. Trang Đăng nhập
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('ok', 'Bạn đã đăng nhập.');
        }
        return view('pages.login');
    }

    // 2. Xử lý Đăng nhập
    public function login(Request $req)
    {
        $data = $req->validate([
            'EMAIL'    => 'required|email',
            'PASSWORD' => 'required|string|min:6'
        ]);

        // Cấu hình thông tin đăng nhập
        // Laravel mặc định tìm cột 'password', nhưng ta cần map dữ liệu đầu vào
        // EMAIL là tên cột trong DB, 'password' là key bắt buộc của Auth::attempt
        $credentials = [
            'EMAIL' => $data['EMAIL'],
            'password' => $data['PASSWORD'], // Auth::attempt tự động hash và so sánh
            'ROLE' => 'customer',            // Chỉ cho phép khách hàng đăng nhập
            'STATUS' => 'active'             // Chỉ tài khoản active
        ];

        // Auth::attempt trả về true nếu thành công và tự động tạo session
        if (Auth::attempt($credentials)) {
            $req->session()->regenerate(); // Bảo mật session
            return redirect()->route('home')->with('ok', 'Đăng nhập thành công.');
        }

        return back()->withErrors(['EMAIL' => 'Email hoặc mật khẩu không đúng, hoặc tài khoản bị khóa.'])->withInput();
    }

    // 3. Trang Đăng ký
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home')->with('ok', 'Bạn đã đăng nhập.');
        }
        return view('pages.register');
    }

    // 4. Xử lý Đăng ký
    public function register(Request $req)
    {
        $data = $req->validate([
            'FIRST_NAME' => 'required|string|max:100',
            'LAST_NAME'  => 'required|string|max:100',
            'EMAIL'      => 'required|email|max:255|unique:USERS,EMAIL',
            'PHONE'      => 'required|string|max:20',
            'PASSWORD'   => 'required|string|min:6|confirmed',
            'ADDRESS'    => 'nullable|string|max:255'
        ]);

        $user = User::create([
            'FIRST_NAME' => $data['FIRST_NAME'],
            'LAST_NAME'  => $data['LAST_NAME'],
            'EMAIL'      => $data['EMAIL'],
            'PHONE'      => $data['PHONE'],
            'PASSWORD'   => Hash::make($data['PASSWORD']),
            'ADDRESS'    => $data['ADDRESS'] ?? null,
            'ROLE'       => 'customer',
            'STATUS'     => 'active'
        ]);

        // Đăng nhập ngay sau khi đăng ký
        Auth::login($user);

        return redirect()->route('home')->with('ok', 'Đăng ký thành công.');
    }

    // 5. Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('ok', 'Đã đăng xuất.');
    }

    // 6. Trang Profile
    public function profile()
    {
        $user = Auth::user(); // Lấy user hiện tại
        if (!$user) abort(403);
        
        return view('pages.profile', compact('user'));
    }

    // 7. Trang Đổi mật khẩu
    public function showChangePassword()
    {
        if (!Auth::check()) abort(403);
        return view('pages.change-password');
    }

    // 8. Xử lý Đổi mật khẩu
    public function updatePassword(Request $req)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        $data = $req->validate([
            'current_password' => 'required|string|min:6',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        // Kiểm tra mật khẩu cũ (Dùng getAuthPassword() để lấy hash)
        if (!Hash::check($data['current_password'], $user->getAuthPassword())) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        // Cập nhật mật khẩu mới
        // Lưu ý: Laravel Eloquent cần gán vào thuộc tính model, sau đó save()
        // Ở model User bạn khai báo fillable là PASSWORD, nên gán $user->PASSWORD ok
        $user->PASSWORD = Hash::make($data['new_password']);
        $user->save();

        Auth::logout(); // Đăng xuất để bắt đăng nhập lại
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('login')->with('ok', 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.');
    }
}
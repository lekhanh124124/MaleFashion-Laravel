<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem session có chứa 'admin_user_id' không
        // Biến này sẽ được tạo ra khi bạn xử lý đăng nhập thành công ở Controller
        if (!session()->has('admin_user_id')) {
            // Nếu chưa đăng nhập, đá về trang login
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return $next($request);
    }
}
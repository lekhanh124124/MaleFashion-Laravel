<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends BaseController
{
    public function index(Request $req)
    {
        $q = trim($req->get('q',''));
        $users = User::query()
            ->whereIn('ROLE',['admin','staff'])
            ->when($q,function($qr) use ($q){
                $qr->where(function($w) use ($q){
                    $w->where('FIRST_NAME','like',"%$q%")
                      ->orWhere('LAST_NAME','like',"%$q%")
                      ->orWhere('EMAIL','like',"%$q%")
                      ->orWhere('PHONE','like',"%$q%");
                });
            })
            ->orderByDesc('USER_ID')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.staff', compact('users','q'));
    }

    public function store(Request $req)
    {
        $data = $this->validateData($req, true);
        $data['PASSWORD'] = \Illuminate\Support\Facades\Hash::make('123456789');
        User::create($data);
        return redirect()->route('admin.staff.index')->with('ok','Tạo thành công (mật khẩu mặc định 123456789)');
    }

    public function update(Request $req, User $user)
    {
        $data = $this->validateData($req, false);
        if(!empty($data['PASSWORD'])) {
            $data['PASSWORD'] = \Illuminate\Support\Facades\Hash::make($data['PASSWORD']);
        } else {
            unset($data['PASSWORD']);
        }
        $user->update($data);
        return redirect()->route('admin.staff.index')->with('ok','Cập nhật thành công');
    }

    public function toggleStatus(User $user)
    {
        if(!in_array($user->ROLE,['admin','staff'])) {
            return redirect()->route('admin.staff.index')->with('err','Không hợp lệ');
        }
        $user->STATUS = $user->STATUS === 'active' ? 'inactive' : 'active';
        $user->save();
        return redirect()->route('admin.staff.index')->with('ok','Đã đổi trạng thái');
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'EMAIL' => 'required|email',
            'PASSWORD' => 'required|string|min:6'
        ]);

        $user = User::query()
            ->where('EMAIL', $data['EMAIL'])
            ->whereIn('ROLE', ['admin','staff'])
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại hoặc không có quyền'], 422);
        }
        if ($user->STATUS !== 'active') {
            return response()->json(['message' => 'Tài khoản đã bị khóa'], 422);
        }
        if (!Hash::check($data['PASSWORD'], $user->PASSWORD)) {
            return response()->json(['message' => 'Sai mật khẩu'], 422);
        }

        // Lưu phiên (session) cơ bản
        session([
            'admin_user_id' => $user->USER_ID,
            'admin_user_role' => $user->ROLE
        ]);

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => [
                'USER_ID' => $user->USER_ID,
                'FULL_NAME' => $user->FULL_NAME,
                'EMAIL' => $user->EMAIL,
                'PHONE' => $user->PHONE,
                'ROLE' => $user->ROLE,
                'STATUS' => $user->STATUS
            ]
        ]);
    }

    public function logout(Request $req)
    {
        $req->session()->forget(['admin_user_id','admin_user_role']);
        return redirect()->route('admin.login');
    }

    private function validateData(Request $req, bool $isCreate)
    {
        $rules = [
            'FIRST_NAME' => 'required|string|max:100',
            'LAST_NAME'  => 'required|string|max:100',
            'EMAIL'      => 'required|email|max:255|unique:USERS,EMAIL'.($isCreate?'':','.$req->route('user')->USER_ID.',USER_ID'),
            'PHONE'      => 'nullable|string|max:20',
            'ADDRESS'    => 'nullable|string|max:255',
            'ROLE'       => 'required|in:admin,staff',
            'STATUS'     => 'required|in:active,inactive',
        ];
        if(!$isCreate){
            $rules['PASSWORD'] = 'nullable|string|min:6|max:255';
        }
        return $req->validate($rules,[],[
            'FIRST_NAME'=>'FIRST_NAME','LAST_NAME'=>'LAST_NAME'
        ]);
    }

    public function changePassword(Request $req)
    {
        $uid = session('admin_user_id');
        if(!$uid){
            return $req->expectsJson()
                ? response()->json(['message'=>'Chưa đăng nhập'], 401)
                : redirect()->route('admin.login');
        }

        $data = $req->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::find($uid);
        if(!$user){
            return $req->expectsJson()
                ? response()->json(['message'=>'Không tìm thấy người dùng'], 404)
                : redirect()->route('admin.login');
        }

        if(!Hash::check($data['current_password'], $user->PASSWORD)){
            return $req->expectsJson()
                ? response()->json(['message'=>'Mật khẩu hiện tại không đúng'], 422)
                : back()->withErrors(['current_password'=>'Mật khẩu hiện tại không đúng']);
        }

        $user->PASSWORD = Hash::make($data['new_password']);
        $user->save();

        // Ép đăng nhập lại: xóa session
        $req->session()->forget(['admin_user_id','admin_user_role']);

        if($req->expectsJson()){
            return response()->json([
                'message'=>'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.',
                'force_relogin' => true
            ]);
        }

        return redirect()
            ->route('admin.login')
            ->with('ok','Đổi mật khẩu thành công. Vui lòng đăng nhập lại.');
    }

    public function customers(Request $req)
    {
        $q = trim($req->get('q',''));
        $customers = User::customers()
            ->when($q,function($qr) use ($q){
                $qr->where(function($w) use ($q){
                    $w->where('FIRST_NAME','like',"%$q%")
                      ->orWhere('LAST_NAME','like',"%$q%")
                      ->orWhere('EMAIL','like',"%$q%")
                      ->orWhere('PHONE','like',"%$q%");
                });
            })
            ->orderByDesc('USER_ID')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.customers', [
            'customers'=>$customers,
            'q'=>$q
        ]);
    }

    public function toggleCustomerStatus(User $user)
    {
        // Chỉ cho phép với khách hàng (không phải admin/staff)
        if(in_array($user->ROLE,['admin','staff'])){
            return redirect()->route('admin.customers.index')->with('err','Không hợp lệ');
        }
        $user->STATUS = $user->STATUS === 'active' ? 'inactive' : 'active';
        $user->save();
        return redirect()->route('admin.customers.index')->with('ok','Đã đổi trạng thái');
    }
}
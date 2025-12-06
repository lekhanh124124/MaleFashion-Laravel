<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends BaseController
{
    public function index(Request $request)
    {
        $query = Contact::with('user');

        // 1. Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('STATUS', $request->status);
        }

        // 2. Tìm kiếm (Tên, Email, Nội dung)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('NAME', 'like', "%{$search}%")
                  ->orWhere('EMAIL', 'like', "%{$search}%")
                  ->orWhere('MESSAGE', 'like', "%{$search}%");
            });
        }

        // Sắp xếp mới nhất trước
        $contacts = $query->orderBy('CREATED_AT', 'desc')->paginate(10);

        return view('admin.pages.contacts', compact('contacts'));
    }

    // API: Lấy chi tiết tin nhắn hiển thị lên Modal
    public function show($id)
    {
        $contact = Contact::with('user')->findOrFail($id);
        return response()->json($contact);
    }

    // Cập nhật trạng thái
    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        
        $request->validate([
            'STATUS' => 'required|in:new,read,replied'
        ]);

        $contact->STATUS = $request->STATUS;
        // Vì tắt timestamp tự động, nếu muốn track ngày update có thể tự set thủ công nếu DB có cột UPDATED_AT
        $contact->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
    }

    public function destroy($id)
    {
        Contact::destroy($id);
        return redirect()->route('admin.contacts.index')->with('success', 'Đã xóa tin nhắn liên hệ!');
    }
}
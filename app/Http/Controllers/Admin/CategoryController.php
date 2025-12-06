<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends BaseController
{
    public function index(Request $request)
    {
        // Danh mục cha
        $parentQuery = Category::withCount('children')->whereNull('PARENT_ID');
        if ($request->filled('parent_search')) {
            $parentQuery->where('CATEGORY_NAME', 'like', '%' . $request->parent_search . '%');
        }
        $parentSort = $request->get('parent_sort', 'CATEGORY_ID');
        $parentOrder = $request->get('parent_order', 'desc');
        $parentCategories = $parentQuery->orderBy($parentSort, $parentOrder)
            ->paginate(10, ['*'], 'parent_page')
            ->appends($request->all());

        // Danh mục con
        $childQuery = Category::with('parent')->whereNotNull('PARENT_ID');
        if ($request->filled('child_search')) {
            $childQuery->where('CATEGORY_NAME', 'like', '%' . $request->child_search . '%');
        }
        if ($request->filled('parent_id')) {
            $childQuery->where('PARENT_ID', $request->parent_id);
        }
        $childSort = $request->get('child_sort', 'CATEGORY_ID');
        $childOrder = $request->get('child_order', 'desc');
        $childCategories = $childQuery->orderBy($childSort, $childOrder)
            ->paginate(10, ['*'], 'child_page')
            ->appends($request->all());

        $allCategories = Category::all();

        return view('admin.pages.categories', compact('parentCategories', 'childCategories', 'allCategories'));
    }

    // CATEGORY CRUD
    public function storeCategory(Request $request)
    {
        $request->validate([
            'CATEGORY_NAME' => 'required|string|max:255',
            'PARENT_ID' => 'nullable|exists:CATEGORIES,CATEGORY_ID'
        ]);
        Category::create([
            'CATEGORY_NAME' => $request->CATEGORY_NAME,
            'PARENT_ID' => $request->PARENT_ID
        ]);
        return back()->with('success', 'Thêm danh mục thành công!');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'CATEGORY_NAME' => 'required|string|max:255',
            'PARENT_ID' => 'nullable|exists:CATEGORIES,CATEGORY_ID'
        ]);
        $cat = Category::findOrFail($id);
        $cat->update([
            'CATEGORY_NAME' => $request->CATEGORY_NAME,
            'PARENT_ID' => $request->PARENT_ID
        ]);
        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroyCategory($id)
    {
        Category::destroy($id);
        return back()->with('success', 'Xóa danh mục thành công!');
    }
}
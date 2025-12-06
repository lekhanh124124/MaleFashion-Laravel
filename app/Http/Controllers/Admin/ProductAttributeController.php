<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\Size;
use App\Models\Color;

class ProductAttributeController extends BaseController
{
    public function index(Request $request)
    {
        // BRAND
        $brandQuery = Brand::query();
        if ($request->filled('brand_search')) {
            $brandQuery->where('BRAND_NAME', 'like', '%' . $request->brand_search . '%');
        }
        $brandSort = $request->get('brand_sort', 'BRAND_ID');
        $brandOrder = $request->get('brand_order', 'desc');
        $brands = $brandQuery->orderBy($brandSort, $brandOrder)
            ->paginate(5, ['*'], 'BRANDS_PAGE')
            ->appends($request->all());

        // TAG
        $tagQuery = Tag::query();
        if ($request->filled('tag_search')) {
            $tagQuery->where('TAG_NAME', 'like', '%' . $request->tag_search . '%');
        }
        $tagSort = $request->get('tag_sort', 'TAG_ID');
        $tagOrder = $request->get('tag_order', 'desc');
        $tags = $tagQuery->orderBy($tagSort, $tagOrder)
            ->paginate(5, ['*'], 'TAGS_PAGE')
            ->appends($request->all());

        // SIZE
        $sizeQuery = Size::query();
        if ($request->filled('size_search')) {
            $sizeQuery->where('SIZE_NAME', 'like', '%' . $request->size_search . '%');
        }
        $sizeSort = $request->get('size_sort', 'SIZE_ID');
        $sizeOrder = $request->get('size_order', 'desc');
        $sizes = $sizeQuery->orderBy($sizeSort, $sizeOrder)
            ->paginate(5, ['*'], 'SIZES_PAGE')
            ->appends($request->all());

        // COLOR
        $colorQuery = Color::query();
        if ($request->filled('color_search')) {
            $colorQuery->where('COLOR_NAME', 'like', '%' . $request->color_search . '%');
        }
        $colorSort = $request->get('color_sort', 'COLOR_ID');
        $colorOrder = $request->get('color_order', 'desc');
        $colors = $colorQuery->orderBy($colorSort, $colorOrder)
            ->paginate(5, ['*'], 'COLORS_PAGE')
            ->appends($request->all());

        return view('admin.pages.product-attributes', compact('brands', 'tags', 'sizes', 'colors'));
    }

    // BRAND CRUD
    public function storeBrand(Request $request)
    {
        $request->validate(['BRAND_NAME' => 'required|string|max:255']);
        Brand::create(['BRAND_NAME' => $request->BRAND_NAME]);
        return back()->with('success', 'Thêm thương hiệu thành công!');
    }
    public function updateBrand(Request $request, $id)
    {
        $request->validate(['BRAND_NAME' => 'required|string|max:255']);
        $brand = Brand::findOrFail($id);
        $brand->update(['BRAND_NAME' => $request->BRAND_NAME]);
        return back()->with('success', 'Cập nhật thương hiệu thành công!');
    }
    public function destroyBrand($id)
    {
        Brand::destroy($id);
        return back()->with('success', 'Xóa thương hiệu thành công!');
    }

    // TAG CRUD
    public function storeTag(Request $request)
    {
        $request->validate(['TAG_NAME' => 'required|string|max:100|unique:TAGS,TAG_NAME']);
        Tag::create(['TAG_NAME' => $request->TAG_NAME]);
        return back()->with('success', 'Thêm tag thành công!');
    }
    public function updateTag(Request $request, $id)
    {
        $request->validate(['TAG_NAME' => 'required|string|max:100|unique:TAGS,TAG_NAME,'.$id.',TAG_ID']);
        $tag = Tag::findOrFail($id);
        $tag->update(['TAG_NAME' => $request->TAG_NAME]);
        return back()->with('success', 'Cập nhật tag thành công!');
    }
    public function destroyTag($id)
    {
        Tag::destroy($id);
        return back()->with('success', 'Xóa tag thành công!');
    }

    // SIZE CRUD
    public function storeSize(Request $request)
    {
        $request->validate(['SIZE_NAME' => 'required|string|max:50|unique:SIZES,SIZE_NAME']);
        Size::create(['SIZE_NAME' => $request->SIZE_NAME]);
        return back()->with('success', 'Thêm size thành công!');
    }
    public function updateSize(Request $request, $id)
    {
        $request->validate(['SIZE_NAME' => 'required|string|max:50|unique:SIZES,SIZE_NAME,'.$id.',SIZE_ID']);
        $size = Size::findOrFail($id);
        $size->update(['SIZE_NAME' => $request->SIZE_NAME]);
        return back()->with('success', 'Cập nhật size thành công!');
    }
    public function destroySize($id)
    {
        Size::destroy($id);
        return back()->with('success', 'Xóa size thành công!');
    }

    // COLOR CRUD
    public function storeColor(Request $request)
    {
        $request->validate([
            'COLOR_NAME' => 'required|string|max:100',
            'COLOR_CODE' => 'nullable|string|max:7'
        ]);
        Color::create([
            'COLOR_NAME' => $request->COLOR_NAME,
            'COLOR_CODE' => $request->COLOR_CODE
        ]);
        return back()->with('success', 'Thêm màu thành công!');
    }
    public function updateColor(Request $request, $id)
    {
        $request->validate([
            'COLOR_NAME' => 'required|string|max:100',
            'COLOR_CODE' => 'nullable|string|max:7'
        ]);
        $color = Color::findOrFail($id);
        $color->update([
            'COLOR_NAME' => $request->COLOR_NAME,
            'COLOR_CODE' => $request->COLOR_CODE
        ]);
        return back()->with('success', 'Cập nhật màu thành công!');
    }
    public function destroyColor($id)
    {
        Color::destroy($id);
        return back()->with('success', 'Xóa màu thành công!');
    }
}
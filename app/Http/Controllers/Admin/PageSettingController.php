<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageSettingController extends Controller
{
    public function index()
    {
        $pages = PageSetting::orderBy('on_menu_order')
                    ->orderBy('title')
                    ->get();

        return view('admin.pages.page_setting.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.page_setting.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:page_setting,slug',
                'page_type' => 'required|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'on_menu_order' => 'required|integer|min:0',
                'editor_content' => 'required|string',
                'plain_content' => 'required|string'
            ]);

            $page = new PageSetting();
            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);
            $page->keywords = $request->keywords;
            $page->page_type = $request->page_type;
            $page->on_menu = $request->has('on_menu');
            $page->on_menu_order = $request->on_menu_order;
            $page->editor_content = $request->editor_content;
            $page->plain_content = $request->plain_content;
            $page->is_active = $request->has('is_active');
            
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the page.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $page = PageSetting::findOrFail($id);

        return view('admin.pages.page_setting.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        try {
            $page = PageSetting::findOrFail($id);
            
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:page_setting,slug,' . $id,
                'page_type' => 'required|string|max:255',
                'keywords' => 'nullable|string|max:255',
                'on_menu_order' => 'required|integer|min:0',
                'editor_content' => 'required|string',
                'plain_content' => 'required|string'
            ]);

            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);
            $page->keywords = $request->keywords;
            $page->page_type = $request->page_type;
            $page->on_menu = $request->has('on_menu');
            $page->on_menu_order = $request->on_menu_order;
            $page->editor_content = $request->editor_content;
            $page->plain_content = $request->plain_content;
            $page->is_active = $request->has('is_active');
            
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the page.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $page = PageSetting::findOrFail($id);
            $page->delete();

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the page.'
            ], 500);
        }
    }
}

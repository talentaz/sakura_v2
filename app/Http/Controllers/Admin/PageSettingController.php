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
        $pages = PageSetting::with('parent', 'children')
                    ->orderBy('on_menu_order')
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
                'parent_id' => 'nullable|integer|exists:page_setting,id',
                'url' => 'nullable|string|max:255',
                'on_menu_order' => 'required|integer|min:0',
                'editor_content' => 'required|string',
                'plain_content' => 'required|string'
            ]);

            $page = new PageSetting();
            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);
            $page->keywords = $request->keywords;
            $page->page_type = $request->page_type;
            $page->parent_id = $request->parent_id;
            $page->url = $request->url;
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
                'parent_id' => 'nullable|integer|exists:page_setting,id',
                'url' => 'nullable|string|max:255',
                'on_menu_order' => 'required|integer|min:0',
                'editor_content' => 'required|string',
                'plain_content' => 'required|string'
            ]);

            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);
            $page->keywords = $request->keywords;
            $page->page_type = $request->page_type;
            $page->parent_id = $request->parent_id;
            $page->url = $request->url;
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

    /**
     * Create About Us pages for frontend menu
     */
    public function createAboutUsPages()
    {
        try {
            // Define the About Us pages that match frontend routes exactly
            $aboutUsPages = [
                [
                    'title' => 'About Company',
                    'slug' => 'company',
                    'page_type' => 'about_page',
                    'on_menu' => true,
                    'on_menu_order' => 1,
                    'url' => '/company',
                    'is_active' => true,
                    'editor_content' => '<div class="container">
                        <h1>About Our Company</h1>
                        <p>Welcome to Sakura Motors, your trusted partner in the automotive industry.</p>

                        <h2>Our Mission</h2>
                        <p>We are committed to providing the best service in the automotive industry, connecting customers with high-quality vehicles and exceptional service.</p>

                        <h2>Our History</h2>
                        <p>Founded with a vision to revolutionize the automotive experience, Sakura Motors has grown to become a leading name in vehicle sales and services.</p>

                        <h2>Our Values</h2>
                        <ul>
                            <li>Quality and reliability in every vehicle</li>
                            <li>Customer satisfaction as our top priority</li>
                            <li>Transparency in all our dealings</li>
                            <li>Innovation in automotive solutions</li>
                        </ul>
                    </div>',
                    'plain_content' => 'About Our Company - Welcome to Sakura Motors, your trusted partner in the automotive industry. We are committed to providing the best service in the automotive industry.',
                    'keywords' => 'sakura motors, company, about us, history, mission, values, automotive'
                ],
                [
                    'title' => 'Gallery',
                    'slug' => 'gallery',
                    'page_type' => 'about_page',
                    'on_menu' => true,
                    'on_menu_order' => 2,
                    'url' => '/gallery',
                    'is_active' => true,
                    'editor_content' => '<div class="container">
                        <h1>Photo Gallery</h1>
                        <p>Explore our collection of high-quality images showcasing our facilities, team, and vehicles.</p>

                        <h2>Our Facilities</h2>
                        <p>Take a virtual tour of our state-of-the-art facilities and showrooms.</p>

                        <h2>Vehicle Collection</h2>
                        <p>Browse through our extensive collection of premium vehicles.</p>

                        <h2>Our Team</h2>
                        <p>Meet the dedicated professionals who make Sakura Motors exceptional.</p>

                        <div class="gallery-placeholder">
                            <p><em>Gallery images will be displayed here.</em></p>
                        </div>
                    </div>',
                    'plain_content' => 'Photo Gallery - Explore our collection of high-quality images showcasing our facilities, team, and vehicles.',
                    'keywords' => 'gallery, photos, images, facilities, vehicles, showroom, team'
                ],
                [
                    'title' => 'Payment Information',
                    'slug' => 'payment',
                    'page_type' => 'about_page',
                    'on_menu' => true,
                    'on_menu_order' => 3,
                    'url' => '/payment',
                    'is_active' => true,
                    'editor_content' => '<div class="container">
                        <h1>Payment Methods</h1>
                        <p>Learn about our secure payment methods and procedures. We accept various payment options for your convenience.</p>

                        <h2>Accepted Payment Methods</h2>
                        <ul>
                            <li>Bank Transfer</li>
                            <li>Credit Cards (Visa, MasterCard, American Express)</li>
                            <li>PayPal</li>
                            <li>Wire Transfer</li>
                            <li>Cash (for local transactions)</li>
                        </ul>

                        <h2>Payment Security</h2>
                        <p>All transactions are secured with industry-standard encryption and security protocols.</p>

                        <h2>Payment Process</h2>
                        <ol>
                            <li>Select your preferred payment method</li>
                            <li>Complete the secure payment form</li>
                            <li>Receive confirmation of your payment</li>
                            <li>Get your receipt and transaction details</li>
                        </ol>

                        <h2>Contact for Payment Support</h2>
                        <p>If you have any questions about payments, please contact our support team.</p>
                    </div>',
                    'plain_content' => 'Payment Methods - Learn about our secure payment methods and procedures. We accept various payment options including bank transfer, credit cards, PayPal, and wire transfer.',
                    'keywords' => 'payment, methods, secure, bank transfer, credit cards, paypal, wire transfer'
                ]
            ];

            // Create or update About Us pages
            foreach ($aboutUsPages as $pageData) {
                PageSetting::updateOrCreate(
                    ['slug' => $pageData['slug']],
                    $pageData
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'About Us pages created successfully!',
                'pages_created' => count($aboutUsPages)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating About Us pages: ' . $e->getMessage()
            ], 500);
        }
    }
}

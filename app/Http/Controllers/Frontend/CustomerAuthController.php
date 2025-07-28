<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\Customer;
use App\Models\Port;
use Location;

class CustomerAuthController extends Controller
{
    /**
     * Show the customer login form.
     */
    public function showLoginForm(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        return view('front.pages.customer.login', [
            'vehicle_id' => $vehicle_id,
        ]);
    }

    /**
     * Handle customer login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        
        // Attempt to authenticate using customer guard
        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            $customer = Auth::guard('customer')->user();
            
            // Check if customer is active
            if ($customer->status !== 'Active') {
                Auth::guard('customer')->logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is not active. Please contact support.'
                ], 403);
            }

            // Handle vehicle notification if vehicle_id is provided
            $vehicle_id = $request->vehicle_id;
            if ($vehicle_id) {
                // You can add vehicle notification logic here if needed
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => 'notify'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => route('front.customer.dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'The provided credentials do not match our records.'
        ], 401);
    }

    /**
     * Show the customer registration form.
     */
    public function showRegistrationForm(Request $request)
    {
        $countries = config('config.country');
        $ip = $request->getClientIp();
        
        if ($ip == '127.0.0.1') {
            $ip = '188.43.235.177'; // Russia IP address for testing
        }
        
        $country_ip = Location::get($ip);
        $current_country = null;
        
        if ($country_ip && $country_ip->countryName) {
            $port = Port::where('country', 'LIKE', "%{$country_ip->countryName}%")->first();
            $current_country = $port ? $port->country : null;
        }
        
        $vehicle_id = $request->input('vehicle_id');
        
        return view('front.pages.customer.signup', [
            'countries' => $countries,
            'current_country' => $current_country,
            'vehicle_id' => $vehicle_id,
        ]);
    }

    /**
     * Handle customer registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'country' => 'required|string',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find country_id from ports table
        $port = Port::where('country', $request->country)->first();
        $country_id = $port ? $port->id : null;

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_id' => $country_id,
            'status' => 'Active',
        ]);

        // Auto login after registration
        Auth::guard('customer')->login($customer, $request->filled('remember'));

        // Handle vehicle notification if vehicle_id is provided
        $vehicle_id = $request->vehicle_id;
        if ($vehicle_id) {
            // You can add vehicle notification logic here if needed
            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'redirect' => 'notify'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'redirect' => route('front.customer.dashboard')
        ]);
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('front.pages.customer.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Send password reset link
        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email address.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to send password reset link. Please try again.'
        ], 500);
    }

    /**
     * Show the password reset form.
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('front.pages.customer.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($customer, $password) {
                $customer->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $customer->save();

                event(new PasswordReset($customer));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully.',
                'redirect' => route('front.customer.login')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to reset password. Please try again.'
        ], 500);
    }

    /**
     * Handle customer logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('front.home');
    }

    /**
     * Show customer dashboard.
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();

        // Get submitted inquiries count and latest inquiry date
        $submittedInquiries = $customer->submittedInquiries();
        $submittedInquiriesCount = $submittedInquiries->count();
        $latestInquiry = $submittedInquiries->latest()->first();
        $latestInquiryDays = $latestInquiry ? $latestInquiry->created_at->diffInDays(now()) : 0;

        // Get purchased vehicles count and latest purchase date
        $purchases = $customer->purchases();
        $totalPurchasedCount = $purchases->count();
        $latestPurchase = $purchases->latest()->first();
        $latestPurchaseDays = $latestPurchase ? $latestPurchase->created_at->diffInDays(now()) : 0;

        // Get customer country (if available)
        $customerCountry = null;
        if ($customer->country_id) {
            // When country relationship is available, uncomment this:
            // $customerCountry = $customer->country->name ?? null;
        }

        return view('front.pages.customer.dashboard', compact(
            'customer',
            'submittedInquiriesCount',
            'latestInquiryDays',
            'totalPurchasedCount',
            'latestPurchaseDays',
            'customerCountry'
        ));
    }
}

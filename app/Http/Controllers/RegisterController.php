<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling user registration and link display.
 */
class RegisterController extends Controller
{
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('register', ['user' => null, 'activeLink' => null]);
    }

    /**
     * Handle user registration or display existing active link if credentials match.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
        ]);

        $user = User::where('phonenumber', $request->phonenumber)
            ->where('username', $request->username)
            ->with(['links' => function ($query) {
                $query->where('is_active', true)
                      ->where('created_at', '>=', now()->subDays(config('game.link_active_days')));
            }])
            ->first();

        if ($user && $user->links->isNotEmpty()) {
            $activeLink = $user->links->first();
            Log::info('User accessed existing active link', [
                'phonenumber' => $request->phonenumber,
                'username' => $request->username,
                'link_uuid' => $activeLink->uuid,
            ]);
            return view('register', [
                'user' => $user,
                'activeLink' => $activeLink,
            ]);
        }

        if (!$user) {
            $user = User::where('phonenumber', $request->phonenumber)->first();
            if ($user) {
                Log::warning('Registration attempt with incorrect username', [
                    'phonenumber' => $request->phonenumber,
                    'attempted_username' => $request->username,
                ]);
                return redirect()->route('register.show')
                    ->withErrors(['username' => 'Incorrect username for this phone number']);
            }

            $user = User::create([
                'username' => $request->username,
                'phonenumber' => $request->phonenumber,
            ]);
        }

        $uuid = Str::uuid();
        $link = Link::create([
            'user_id' => $user->id,
            'uuid' => $uuid,
            'is_active' => true,
            'created_at' => now(),
        ]);

        $linkUrl = route('link.show', ['uuid' => $uuid]);
        Log::info('User registered and link generated', [
            'phonenumber' => $request->phonenumber,
            'username' => $request->username,
            'link_uuid' => $uuid,
            'user_id' => $user->id,
        ]);

        return redirect()->route('link.show', ['uuid' => $uuid])
            ->with('success', "Registration successful! Your unique link: <a href=\"$linkUrl\" class=\"underline text-primary-accent hover:text-highlight\">$linkUrl</a>");
    }
}

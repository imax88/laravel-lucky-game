<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling link-related actions, including displaying, generating, and deactivating links.
 */
class LinkController extends Controller
{
    /**
     * Display the link page for a given UUID.
     *
     * @param string $uuid The UUID of the link.
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the link is not found, inactive, or expired.
     */
    public function show(string $uuid)
    {
        $link = Link::where('uuid', $uuid)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(config('game.link_active_days')))
            ->with('user')
            ->firstOrFail();

        Log::info('Link accessed', ['uuid' => $uuid, 'user_id' => $link->user_id]);

        return view('link', ['uuid' => $uuid]);
    }

    /**
     * Generate a new link for the user associated with the given UUID and deactivate the old one.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param string $uuid The UUID of the current link.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the link is not found, inactive, or expired.
     */
    public function generateNewLink(Request $request, string $uuid)
    {
        $currentLink = Link::where('uuid', $uuid)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(config('game.link_active_days')))
            ->firstOrFail();

        $currentLink->update(['is_active' => false]);

        $newUuid = Str::uuid();
        $newLink = Link::create([
            'user_id' => $currentLink->user_id,
            'uuid' => $newUuid,
            'is_active' => true,
            'created_at' => now(),
        ]);

        $linkUrl = route('link.show', ['uuid' => $newUuid]);
        Log::info('New link generated', [
            'old_uuid' => $uuid,
            'new_uuid' => $newUuid,
            'user_id' => $currentLink->user_id,
        ]);

        return redirect()->route('link.show', ['uuid' => $newUuid])
            ->with('success', "New link generated successfully. <a href=\"$linkUrl\" class=\"underline text-primary-accent hover:text-highlight\">$linkUrl</a>");
    }

    /**
     * Deactivate the link with the given UUID.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param string $uuid The UUID of the link.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the link is not found, inactive, or expired.
     */
    public function deactivateLink(Request $request, string $uuid)
    {
        $link = Link::where('uuid', $uuid)
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(config('game.link_active_days')))
            ->firstOrFail();

        $link->update(['is_active' => false]);

        Log::info('Link deactivated', ['uuid' => $uuid, 'user_id' => $link->user_id]);

        return redirect()->route('register.show')
            ->with('success', 'Link deactivated successfully.');
    }
}

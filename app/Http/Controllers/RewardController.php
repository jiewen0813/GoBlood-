<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\Point;
use App\Models\Redemption;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    /**
     * Show all available rewards.
     */
    public function index()
    {
        $rewards = Reward::all();
        $points = auth()->user()->points->total_points ?? 0;

        return view('rewards.index', compact('rewards', 'points'));
    }

    /**
     * Redeem a reward.
     */
    public function redeem(Request $request, Reward $reward)
    {
        $user = auth()->user();
        $points = $user->points;

        if ($points && $points->total_points >= $reward->points_required) {
            // Deduct points
            $points->total_points -= $reward->points_required;
            $points->save();

            // Reduce remaining vouchers
            $reward->decrement('remaining_vouchers');

            // Generate unique QR code identifier
            $uniqueCode = uniqid('reward_');

            // Generate QR code data
            $qrCodeUrl = route('reward.markAsUsed', ['code' => $uniqueCode]);

            // Generate QR code and save as SVG
            $renderer = new ImageRenderer(
                new RendererStyle(200),
                new SvgImageBackEnd()
            );
            $writer = new Writer($renderer);
            $qrCodeImage = $writer->writeString($qrCodeUrl);

            // Save QR code image to storage as SVG
            $fileName = "qr_codes/{$uniqueCode}.svg";
            Storage::disk('public')->put($fileName, $qrCodeImage);

            // Save the redemption record with the QR code
            $redemption = Redemption::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'points_used' => $reward->points_required,
                'qr_code' => $uniqueCode, // Save the unique identifier
                'qr_code_path' => $fileName, // Save the file path
            ]);

            return redirect()->route('rewards.myrewards')->with('success', 'Reward redeemed successfully!');
        }

        return redirect()->back()->with('error', 'Not enough points to redeem this reward.');
    }

    /**
     * Mark a QR code as used when scanned.
     */
    public function markAsUsed(Request $request)
    {
        $code = $request->input('code'); // Retrieve the unique QR code identifier

        // Find the redemption associated with the unique code
        $redemption = Redemption::where('qr_code', $code)->first();

        if (!$redemption) {
            return redirect()->route('rewards.myrewards')->with('error', 'Invalid QR code.');
        }

        if ($redemption->is_used) {
            return redirect()->route('rewards.myrewards')->with('error', 'This reward has already been used.');
        }

        // Mark the redemption as used
        $redemption->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return redirect()->route('rewards.myrewards')->with('success', 'Reward marked as used successfully!');
    }

    /**
     * Show a reward and allow the user to confirm redemption.
     */
    public function show(Reward $reward)
    {
        $user = auth()->user();
        $points = $user->points->total_points ?? 0;

        return view('rewards.show', compact('reward', 'points'));
    }

    /**
     * Show the user's redemption history.
     */
    public function history()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Fetch the user's redemptions along with the associated rewards
        $redemptions = $user->redemptions()->with('reward')->get();

        // Retrieve the user's total points
        $points = $user->points->total_points ?? 0;

        // Return the view with the redemption data
        return view('rewards.myrewards', compact('redemptions', 'points'));
    }
}

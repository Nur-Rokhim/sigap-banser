<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola sistem notifikasi SIGAP BANSER.
 * Menampilkan daftar, detail, dan penandaan baca notifikasi per user.
 */
class NotifikasiController extends Controller
{
    /**
     * Tampilkan daftar semua notifikasi milik user yang sedang login.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        $query = Notifikasi::with(['pengirim'])
            ->untukUser($userId)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status baca
        if ($request->filled('status')) {
            if ($request->input('status') === 'belum') {
                $query->where('dibaca', false);
            } elseif ($request->input('status') === 'sudah') {
                $query->where('dibaca', true);
            }
        }

        // Filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->input('tipe'));
        }

        $notifikasi = $query->paginate(15)->withQueryString();

        // Hitung total belum dibaca untuk badge
        $totalBelumDibaca = Notifikasi::untukUser($userId)->belumDibaca()->count();

        return view('notifikasi.index', [
            'notifikasi'       => $notifikasi,
            'totalBelumDibaca' => $totalBelumDibaca,
        ]);
    }

    /**
     * Tampilkan detail notifikasi dan tandai sebagai sudah dibaca.
     */
    public function show($id)
    {
        $userId = Auth::id();

        $notif = Notifikasi::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })->findOrFail($id);

        // Tandai sebagai sudah dibaca jika belum
        if (!$notif->dibaca) {
            $notif->update([
                'dibaca'     => true,
                'dibaca_pada' => Carbon::now(),
            ]);
        }

        return view('notifikasi.show', [
            'notif' => $notif,
        ]);
    }

    /**
     * Tandai notifikasi tertentu sebagai sudah dibaca (AJAX / redirect).
     */
    public function tandaiBaca($id)
    {
        $userId = Auth::id();

        $notif = Notifikasi::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })->findOrFail($id);

        $notif->update([
            'dibaca'      => true,
            'dibaca_pada' => Carbon::now(),
        ]);

        // Jika ada URL tujuan, redirect ke sana; jika tidak, redirect ke index
        if ($notif->url_tujuan) {
            return redirect($notif->url_tujuan);
        }

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Tandai SEMUA notifikasi user ini sebagai sudah dibaca.
     */
    public function tandaiSemuaBaca()
    {
        $userId = Auth::id();

        Notifikasi::where(function ($q) use ($userId) {
            $q->where('user_id', $userId)->orWhereNull('user_id');
        })
        ->where('dibaca', false)
        ->update([
            'dibaca'      => true,
            'dibaca_pada' => Carbon::now(),
        ]);

        return redirect()->route('notifikasi.index')
            ->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }

    /**
     * Hapus satu notifikasi.
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $notif = Notifikasi::where('user_id', $userId)->findOrFail($id);
        $notif->delete();

        return redirect()->route('notifikasi.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * API endpoint: ambil jumlah notifikasi belum dibaca (untuk polling AJAX).
     */
    public function count()
    {
        $userId = Auth::id();
        $count  = Notifikasi::untukUser($userId)->belumDibaca()->count();

        return response()->json(['count' => $count]);
    }
}

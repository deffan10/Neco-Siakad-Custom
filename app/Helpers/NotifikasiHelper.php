<?php

namespace App\Helpers;

use App\Models\NotifikasiInApp;

class NotifikasiHelper
{
    /**
     * Kirim notifikasi in-app ke user tertentu.
     */
    public static function send($userId, string $judul, string $pesan, string $tipe = 'info', string $ikon = 'bell', string $url = null)
    {
        return NotifikasiInApp::create([
            'user_id' => $userId,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'tipe'    => $tipe,
            'ikon'    => $ikon,
            'url'     => $url,
            'is_read' => false,
        ]);
    }

    /**
     * Kirim notifikasi ke semua user dengan role tertentu.
     */
    public static function sendToRole(string $roleName, string $judul, string $pesan, string $tipe = 'info', string $ikon = 'bell', string $url = null)
    {
        $users = \App\Models\User::role($roleName)->get(['id']);
        foreach ($users as $user) {
            self::send($user->id, $judul, $pesan, $tipe, $ikon, $url);
        }
    }
}

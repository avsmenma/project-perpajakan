<?php

namespace App\Http\Controllers;

use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Display the login page.
     */
    public function showLogin()
    {
        // If already logged in, redirect to home
        if (Session::has('user')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nik_sap' => 'required|string',
            'password' => 'required|string',
        ]);

        $nikSap = $request->input('nik_sap');
        $password = $request->input('password');

        // Check if NIK SAP exists in database
        $karyawan = DataKaryawan::where('nik_sap', $nikSap)->first();

        if (!$karyawan) {
            return back()
                ->withInput($request->only('nik_sap'))
                ->withErrors(['nik_sap' => 'NIK SAP tidak ditemukan dalam sistem.']);
        }

        // Check password (simple: 123)
        if ($password !== '123') {
            return back()
                ->withInput($request->only('nik_sap'))
                ->withErrors(['password' => 'Password salah.']);
        }

        // Login successful - store user in session
        Session::put('user', [
            'npwp' => $karyawan->npwp,
            'nama' => $karyawan->nama,
            'nik_sap' => $karyawan->nik_sap,
            'nama_kebun' => $karyawan->nama_kebun,
        ]);

        return redirect()->route('home')->with('success', 'Selamat datang, ' . $karyawan->nama . '!');
    }

    /**
     * Handle logout request.
     */
    public function logout()
    {
        Session::forget('user');
        Session::flush();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}

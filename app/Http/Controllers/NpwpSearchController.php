<?php

namespace App\Http\Controllers;

use App\Models\DataKaryawan;
use Illuminate\Http\Request;

class NpwpSearchController extends Controller
{
    /**
     * Display the search page.
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Handle NPWP search request.
     */
    public function search(Request $request)
    {
        $npwp = $request->input('npwp');
        $result = null;
        $error = null;

        // Validate input exists
        if (empty($npwp)) {
            $error = 'Mohon masukkan NPWP untuk mencari data.';
            return view('index', compact('error'));
        }

        // Sanitize input - remove non-numeric characters except dots and dashes
        $npwpClean = preg_replace('/[^0-9.\-]/', '', $npwp);

        // Extract only numbers for validation
        $npwpNumbers = preg_replace('/[^0-9]/', '', $npwpClean);

        // Validate NPWP format (should be 15-16 digits)
        if (strlen($npwpNumbers) < 15 || strlen($npwpNumbers) > 16) {
            $error = 'Format NPWP tidak valid. NPWP harus terdiri dari 15-16 digit angka.';
            return view('index', compact('error', 'npwp'));
        }

        // Search in database - try both with and without formatting
        $result = DataKaryawan::where('npwp', $npwpClean)
            ->orWhere('npwp', $npwpNumbers)
            ->first();

        if (!$result) {
            $error = 'Data dengan NPWP "' . htmlspecialchars($npwp) . '" tidak ditemukan.';
        }

        return view('index', compact('result', 'error', 'npwp'));
    }

    /**
     * Handle PDF download request.
     */
    public function downloadPdf($path)
    {
        // Security: Prevent directory traversal attacks
        // Remove any parent directory references (..)
        $path = str_replace('..', '', $path);

        // Normalize path separators to forward slash
        $path = str_replace('\\', '/', $path);

        // Construct the full path
        $fullPath = storage_path('app/public/' . $path);

        // Check if file exists
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Return file as download with proper headers
        return response()->download($fullPath);
    }
}

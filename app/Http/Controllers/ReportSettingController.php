<?php

namespace App\Http\Controllers;

use App\Models\ReportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportSettingController extends Controller
{
    /**
     * Display the report settings page
     */
    public function index()
    {
        $settings = ReportSetting::getActive();
        return view('laporan.settings', compact('settings'));
    }

    /**
     * Update the report settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // School Information
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:255',
            'school_phone' => 'required|string|max:255',
            'school_email' => 'nullable|string',
            'school_website' => 'required|string|max:255',
            
            // Report Configuration
            'report_title' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            
            // Head of School
            'head_of_school_name' => 'required|string|max:255',
            'head_of_school_nip' => 'nullable|string|max:255',
            'head_of_school_position' => 'required|string|max:255',
            
            // Head of Department
            'head_of_department_name' => 'required|string|max:255',
            'head_of_department_nip' => 'nullable|string|max:255',
            'head_of_department_position' => 'required|string|max:255',
            
            // Staff
            'staff_name' => 'required|string|max:255',
            'staff_nip' => 'nullable|string|max:255',
            'staff_position' => 'required|string|max:255',
            
            // Logo
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Signatures
            'head_of_school_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'head_of_department_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'staff_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $settings = ReportSetting::first() ?? new ReportSetting();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $logoPath = $request->file('logo')->store('report-logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Handle signature uploads
        if ($request->hasFile('head_of_school_signature')) {
            if ($settings->head_of_school_signature && Storage::disk('public')->exists($settings->head_of_school_signature)) {
                Storage::disk('public')->delete($settings->head_of_school_signature);
            }
            $signaturePath = $request->file('head_of_school_signature')->store('signatures', 'public');
            $validated['head_of_school_signature'] = $signaturePath;
        }

        if ($request->hasFile('head_of_department_signature')) {
            if ($settings->head_of_department_signature && Storage::disk('public')->exists($settings->head_of_department_signature)) {
                Storage::disk('public')->delete($settings->head_of_department_signature);
            }
            $signaturePath = $request->file('head_of_department_signature')->store('signatures', 'public');
            $validated['head_of_department_signature'] = $signaturePath;
        }

        if ($request->hasFile('staff_signature')) {
            if ($settings->staff_signature && Storage::disk('public')->exists($settings->staff_signature)) {
                Storage::disk('public')->delete($settings->staff_signature);
            }
            $signaturePath = $request->file('staff_signature')->store('signatures', 'public');
            $validated['staff_signature'] = $signaturePath;
        }

        // Set default email if not provided
        if (empty($validated['school_email'])) {
            $validated['school_email'] = 'Jl. Tanimbar No. 22 Malang, Telp. (0341) 322515, Fax (0341) 351940';
        }

        $settings->fill($validated);
        $settings->is_active = true;
        $settings->save();

        return redirect()->route('laporan.settings')
            ->with('success', 'Pengaturan laporan berhasil diperbarui.');
    }

    /**
     * Reset settings to default
     */
    public function reset()
    {
        $settings = ReportSetting::first();
        if ($settings) {
            // Delete uploaded files
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            if ($settings->head_of_school_signature) {
                Storage::disk('public')->delete($settings->head_of_school_signature);
            }
            if ($settings->head_of_department_signature) {
                Storage::disk('public')->delete($settings->head_of_department_signature);
            }
            if ($settings->staff_signature) {
                Storage::disk('public')->delete($settings->staff_signature);
            }
            $settings->delete();
        }

        return redirect()->route('laporan.settings')
            ->with('success', 'Pengaturan laporan berhasil direset ke default.');
    }

    /**
     * Delete a signature image
     */
    public function deleteSignature(Request $request, $type)
    {
        $settings = ReportSetting::first();
        if (!$settings) {
            return response()->json(['success' => false, 'message' => 'Pengaturan tidak ditemukan.'], 404);
        }

        $validTypes = ['head_of_school', 'head_of_department', 'staff'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['success' => false, 'message' => 'Tipe tanda tangan tidak valid.'], 400);
        }

        $signatureField = $type . '_signature';
        if ($settings->$signatureField && Storage::disk('public')->exists($settings->$signatureField)) {
            Storage::disk('public')->delete($settings->$signatureField);
            $settings->$signatureField = null;
            $settings->save();
        }

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus.']);
    }

    /**
     * Delete logo
     */
    public function deleteLogo()
    {
        $settings = ReportSetting::first();
        if (!$settings) {
            return response()->json(['success' => false, 'message' => 'Pengaturan tidak ditemukan.'], 404);
        }

        if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->logo_path = null;
            $settings->save();
        }

        return response()->json(['success' => true, 'message' => 'Logo berhasil dihapus.']);
    }
}
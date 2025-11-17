<?php

namespace App\Services;

use App\Models\Application;
use App\Models\MbkmRegistration;
use App\Models\SkripsiRegistration;
use App\Models\MbkmSeminar;
use App\Models\SkripsiSeminar;
use App\Models\SkripsiDefense;
use App\Models\ApplicationResultSeminar;
use App\Models\ApplicationResultDefense;
use App\Models\Mahasiswa;

class FormAccessService
{
    /**
     * Check if student can access MBKM Registration
     */
    public function canAccessMbkmRegistration($mahasiswaId)
    {
        // Check if student has any existing applications
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
            return [
                'allowed' => true,
                'message' => null
            ];
        }

        // Check if there's an existing MBKM registration that was rejected as "ineligible"
        $rejectedMbkm = $applications->where('type', 'mbkm')
            ->where('status', 'rejected')
            ->first();

        if ($rejectedMbkm) {
            // If rejected from MBKM, cannot access MBKM registration anymore
            return [
                'allowed' => false,
                'message' => 'Anda tidak eligible untuk jalur MBKM. Silakan daftar melalui jalur Skripsi Reguler.'
            ];
        }

        // Check if student has active or approved MBKM registration
        $activeMbkm = $applications->where('type', 'mbkm')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeMbkm) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memiliki pendaftaran MBKM yang aktif. Tunggu proses persetujuan.'
            ];
        }

        // Check if student has active Skripsi registration
        $activeSkripsi = $applications->where('type', 'skripsi')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeSkripsi) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memilih jalur Skripsi Reguler dan tidak dapat beralih ke jalur MBKM.'
            ];
        }

        return [
            'allowed' => true,
            'message' => null
        ];
    }

    /**
     * Check if student can access Skripsi Registration
     */
    public function canAccessSkripsiRegistration($mahasiswaId)
    {
        $applications = Application::where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // New student - can register
        if ($applications->isEmpty()) {
            return [
                'allowed' => true,
                'message' => null
            ];
        }

        // Check if student has active or approved Skripsi registration
        $activeSkripsi = $applications->where('type', 'skripsi')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($activeSkripsi) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memiliki pendaftaran Skripsi yang aktif.'
            ];
        }

        // Check if student has active MBKM registration that is approved
        $activeMbkm = $applications->where('type', 'mbkm')
            ->whereIn('status', ['approved', 'scheduled'])
            ->first();

        if ($activeMbkm) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah memilih jalur MBKM dan tidak dapat beralih ke jalur Skripsi Reguler.'
            ];
        }

        return [
            'allowed' => true,
            'message' => null
        ];
    }

    /**
     * Check if student can access MBKM Seminar
     */
    public function canAccessMbkmSeminar($mahasiswaId)
    {
        // Must have approved MBKM registration
        $registrationApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'registration')
            ->where('status', 'approved')
            ->first();

        if (!$registrationApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan pendaftaran MBKM terlebih dahulu dan mendapat persetujuan.',
                'application' => null
            ];
        }

        // Check if there's already a seminar application
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', 'seminar')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah mendaftar seminar MBKM. Tunggu proses persetujuan.',
                'application' => $registrationApp
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $registrationApp
        ];
    }

    /**
     * Check if student can access Skripsi Seminar
     */
    public function canAccessSkripsiSeminar($mahasiswaId)
    {
        // Must have approved Skripsi registration
        $registrationApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'skripsi')
            ->where('stage', 'registration')
            ->where('status', 'approved')
            ->first();

        if (!$registrationApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan pendaftaran Skripsi terlebih dahulu dan mendapat persetujuan.',
                'application' => null
            ];
        }

        // Check if there's already a seminar application
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'skripsi')
            ->where('stage', 'seminar')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah mendaftar seminar proposal. Tunggu proses persetujuan.',
                'application' => $registrationApp
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $registrationApp
        ];
    }

    /**
     * Check if student can access Skripsi Defense
     */
    public function canAccessSkripsiDefense($mahasiswaId)
    {
        // Must have completed and approved seminar
        $seminarApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->where('stage', 'seminar')
            ->where('status', 'approved')
            ->first();

        if (!$seminarApp) {
            return [
                'allowed' => false,
                'message' => 'Anda harus menyelesaikan seminar proposal terlebih dahulu dan mendapat persetujuan.',
                'application' => null
            ];
        }

        // Check if seminar result is approved
        $seminarResult = ApplicationResultSeminar::where('application_id', $seminarApp->id)
            ->first();

        if (!$seminarResult) {
            return [
                'allowed' => false,
                'message' => 'Hasil seminar proposal Anda belum diinput oleh admin.',
                'application' => $seminarApp
            ];
        }

        // If seminar result is "failed", cannot proceed to defense
        if ($seminarResult->result === 'failed') {
            return [
                'allowed' => false,
                'message' => 'Anda harus mengulang seminar proposal terlebih dahulu.',
                'application' => $seminarApp
            ];
        }

        // Check if defense already exists
        $defenseApp = Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('type', ['skripsi', 'mbkm'])
            ->where('stage', 'defense')
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->first();

        if ($defenseApp) {
            return [
                'allowed' => false,
                'message' => 'Anda sudah mendaftar sidang skripsi. Tunggu proses persetujuan.',
                'application' => $seminarApp
            ];
        }

        return [
            'allowed' => true,
            'message' => null,
            'application' => $seminarApp
        ];
    }

    /**
     * Get allowed forms for a student
     */
    public function getAllowedForms($mahasiswaId)
    {
        return [
            'mbkm_registration' => $this->canAccessMbkmRegistration($mahasiswaId),
            'skripsi_registration' => $this->canAccessSkripsiRegistration($mahasiswaId),
            'mbkm_seminar' => $this->canAccessMbkmSeminar($mahasiswaId),
            'skripsi_seminar' => $this->canAccessSkripsiSeminar($mahasiswaId),
            'skripsi_defense' => $this->canAccessSkripsiDefense($mahasiswaId),
        ];
    }

    /**
     * Check if mahasiswa has any active application
     */
    public function hasActiveApplication($mahasiswaId)
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->exists();
    }

    /**
     * Get active application for mahasiswa
     */
    public function getActiveApplication($mahasiswaId)
    {
        return Application::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

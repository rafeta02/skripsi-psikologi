<?php

namespace App\Services;

use App\Models\Application;
use App\Models\MbkmGroupMember;
use App\Models\MbkmRegistration;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MbkmGroupProgressService
{
    /**
     * Sync anggota kelompok + mirror Application (registration) dari form ketua.
     *
     * @param  array<int, array{mahasiswa_id?: mixed, role?: string}>  $membersInput
     */
    public function syncGroupMembers(MbkmRegistration $registration, array $membersInput, int $ketuaMahasiswaId): void
    {
        $ownerApp = $registration->application;
        if (!$ownerApp || $ownerApp->type !== 'mbkm') {
            return;
        }

        $normalized = [];
        foreach ($membersInput as $row) {
            $mahasiswaId = (int) ($row['mahasiswa_id'] ?? 0);
            if ($mahasiswaId <= 0) {
                continue;
            }
            $role = ($row['role'] ?? 'anggota') === 'ketua' ? 'ketua' : 'anggota';
            $normalized[$mahasiswaId] = $role;
        }

        // Ketua selalu masuk sebagai anggota kelompok
        $normalized[$ketuaMahasiswaId] = 'ketua';

        foreach ($normalized as $mahasiswaId => $role) {
            if ($mahasiswaId === $ketuaMahasiswaId) {
                continue;
            }
            $this->assertMemberEligible($mahasiswaId, $registration->id);
        }

        $keepIds = array_keys($normalized);

        // Hapus anggota yang tidak lagi dipilih (kecuali ketua)
        $registration->groupMembers()
            ->where('mahasiswa_id', '!=', $ketuaMahasiswaId)
            ->whereNotIn('mahasiswa_id', $keepIds)
            ->get()
            ->each(function (MbkmGroupMember $member) use ($ownerApp) {
                $this->deleteMirrorsForMahasiswa($member->mahasiswa_id, $ownerApp);
                $member->delete();
            });

        foreach ($normalized as $mahasiswaId => $role) {
            $member = MbkmGroupMember::withTrashed()->updateOrCreate(
                [
                    'mbkm_registration_id' => $registration->id,
                    'mahasiswa_id' => $mahasiswaId,
                ],
                ['role' => $role]
            );

            if ($member->trashed()) {
                $member->restore();
            }

            if ($mahasiswaId === $ketuaMahasiswaId) {
                continue;
            }

            $this->ensureMirrorForOwnerApplication($ownerApp, $mahasiswaId);
        }

        // Pastikan mirror untuk semua stage owner yang sudah ada
        $this->resyncAllMirrorsFromOwner($ketuaMahasiswaId);
    }

    public function assertMemberEligible(int $mahasiswaId, ?int $exceptRegistrationId = null): void
    {
        if (!Mahasiswa::where('id', $mahasiswaId)->exists()) {
            throw ValidationException::withMessages([
                'group_members' => "Mahasiswa #{$mahasiswaId} tidak ditemukan.",
            ]);
        }

        if (Application::where('mahasiswa_id', $mahasiswaId)
            ->where('is_group_mirror', false)
            ->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision', 'result'])
            ->exists()) {
            $mhs = Mahasiswa::find($mahasiswaId);
            throw ValidationException::withMessages([
                'group_members' => ($mhs?->nama ?? 'Mahasiswa') . ' sudah memiliki aplikasi skripsi/MBKM aktif.',
            ]);
        }

        $otherMembership = MbkmGroupMember::query()
            ->where('mahasiswa_id', $mahasiswaId)
            ->when($exceptRegistrationId, fn ($q) => $q->where('mbkm_registration_id', '!=', $exceptRegistrationId))
            ->whereHas('mbkm_registration.application', function ($q) {
                $q->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision', 'result'])
                    ->where('is_group_mirror', false);
            })
            ->exists();

        if ($otherMembership) {
            $mhs = Mahasiswa::find($mahasiswaId);
            throw ValidationException::withMessages([
                'group_members' => ($mhs?->nama ?? 'Mahasiswa') . ' sudah tergabung di kelompok MBKM lain.',
            ]);
        }
    }

    public function ensureMirrorForOwnerApplication(Application $ownerApp, int $mahasiswaId): Application
    {
        if ($ownerApp->is_group_mirror || $ownerApp->type !== 'mbkm') {
            return $ownerApp;
        }

        // Defense tidak di-mirror — tiap mahasiswa mendaftar sendiri
        if ($ownerApp->stage === 'defense') {
            return $ownerApp;
        }

        return Application::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'parent_application_id' => $ownerApp->id,
                'is_group_mirror' => true,
            ],
            [
                'type' => 'mbkm',
                'stage' => $ownerApp->stage,
                'status' => $ownerApp->status,
                'submitted_at' => $ownerApp->getRawOriginal('submitted_at') ?? now(),
                'notes' => 'Mirror progres kelompok MBKM (owner #' . $ownerApp->id . ')',
            ]
        );
    }

    /**
     * Saat Application owner berubah status/stage, sync semua mirror.
     */
    public function syncMirrorsFromOwner(Application $ownerApp): void
    {
        if ($ownerApp->is_group_mirror || $ownerApp->type !== 'mbkm' || $ownerApp->stage === 'defense') {
            return;
        }

        Application::where('parent_application_id', $ownerApp->id)
            ->where('is_group_mirror', true)
            ->update([
                'stage' => $ownerApp->stage,
                'status' => $ownerApp->status,
            ]);
    }

    /**
     * Buat mirror untuk anggota saat ketua membuat Application stage baru (mis. seminar).
     */
    public function ensureMirrorsForNewOwnerApplication(Application $ownerApp): void
    {
        if ($ownerApp->is_group_mirror || $ownerApp->type !== 'mbkm' || $ownerApp->stage === 'defense') {
            return;
        }

        $memberIds = $this->getAnggotaMahasiswaIdsForOwner($ownerApp->mahasiswa_id);
        foreach ($memberIds as $mahasiswaId) {
            $this->ensureMirrorForOwnerApplication($ownerApp, $mahasiswaId);
        }
    }

    public function resyncAllMirrorsFromOwner(int $ownerMahasiswaId): void
    {
        $ownerApps = Application::where('mahasiswa_id', $ownerMahasiswaId)
            ->where('type', 'mbkm')
            ->where('is_group_mirror', false)
            ->whereIn('stage', ['registration', 'seminar'])
            ->get();

        $memberIds = $this->getAnggotaMahasiswaIdsForOwner($ownerMahasiswaId);

        foreach ($ownerApps as $ownerApp) {
            foreach ($memberIds as $mahasiswaId) {
                $this->ensureMirrorForOwnerApplication($ownerApp, $mahasiswaId);
            }
            $this->syncMirrorsFromOwner($ownerApp);
        }
    }

    /**
     * @return array<int>
     */
    public function getAnggotaMahasiswaIdsForOwner(int $ownerMahasiswaId): array
    {
        $registration = MbkmRegistration::whereHas('application', function ($q) use ($ownerMahasiswaId) {
            $q->where('mahasiswa_id', $ownerMahasiswaId)
                ->where('type', 'mbkm')
                ->where('stage', 'registration')
                ->where('is_group_mirror', false);
        })->latest('id')->first();

        if (!$registration) {
            return [];
        }

        return $registration->groupMembers()
            ->where('mahasiswa_id', '!=', $ownerMahasiswaId)
            ->pluck('mahasiswa_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function findMembership(int $mahasiswaId): ?MbkmGroupMember
    {
        return MbkmGroupMember::with(['mbkm_registration.application'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereHas('mbkm_registration.application', function ($q) {
                $q->where('type', 'mbkm')
                    ->where('is_group_mirror', false)
                    ->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision', 'result', 'done']);
            })
            ->latest('id')
            ->first();
    }

    public function isKetua(int $mahasiswaId): bool
    {
        $membership = $this->findMembership($mahasiswaId);
        if (!$membership) {
            // Pemilik application registration tanpa row member = ketua
            return Application::where('mahasiswa_id', $mahasiswaId)
                ->where('type', 'mbkm')
                ->where('stage', 'registration')
                ->where('is_group_mirror', false)
                ->exists();
        }

        $ownerId = (int) ($membership->mbkm_registration?->application?->mahasiswa_id ?? 0);

        return $membership->role === 'ketua' || $ownerId === $mahasiswaId;
    }

    public function isFollowerAnggota(int $mahasiswaId): bool
    {
        $membership = $this->findMembership($mahasiswaId);
        if (!$membership) {
            return Application::where('mahasiswa_id', $mahasiswaId)
                ->where('is_group_mirror', true)
                ->whereIn('status', ['submitted', 'approved', 'scheduled', 'revision', 'result'])
                ->exists();
        }

        $ownerId = (int) ($membership->mbkm_registration?->application?->mahasiswa_id ?? 0);

        return $ownerId !== $mahasiswaId && $membership->role !== 'ketua';
    }

    public function getOwnerMahasiswaId(int $mahasiswaId): ?int
    {
        if (!$this->isFollowerAnggota($mahasiswaId) && $this->isKetua($mahasiswaId)) {
            return $mahasiswaId;
        }

        $membership = $this->findMembership($mahasiswaId);
        $ownerId = (int) ($membership?->mbkm_registration?->application?->mahasiswa_id ?? 0);

        return $ownerId > 0 ? $ownerId : null;
    }

    /**
     * Resolve Application milik ketua untuk stage tertentu (untuk akses/view anggota).
     */
    public function resolveOwnerApplication(int $mahasiswaId, string $stage): ?Application
    {
        $own = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', $stage)
            ->where('is_group_mirror', false)
            ->orderByDesc('id')
            ->first();

        if ($own) {
            return $own;
        }

        $mirror = Application::where('mahasiswa_id', $mahasiswaId)
            ->where('type', 'mbkm')
            ->where('stage', $stage)
            ->where('is_group_mirror', true)
            ->orderByDesc('id')
            ->first();

        if ($mirror?->parent_application_id) {
            return Application::find($mirror->parent_application_id);
        }

        $ownerId = $this->getOwnerMahasiswaId($mahasiswaId);
        if (!$ownerId || $ownerId === $mahasiswaId) {
            return null;
        }

        return Application::where('mahasiswa_id', $ownerId)
            ->where('type', 'mbkm')
            ->where('stage', $stage)
            ->where('is_group_mirror', false)
            ->orderByDesc('id')
            ->first();
    }

    public function canViewOwnerApplication(int $mahasiswaId, Application $application): bool
    {
        if ((int) $application->mahasiswa_id === $mahasiswaId) {
            return true;
        }

        if ($application->type !== 'mbkm' || $application->is_group_mirror) {
            return false;
        }

        return MbkmGroupMember::where('mahasiswa_id', $mahasiswaId)
            ->whereHas('mbkm_registration', function ($q) use ($application) {
                $q->where('application_id', $application->id)
                    ->orWhereHas('application', function ($aq) use ($application) {
                        // anggota boleh lihat semua stage ketua dalam rantai yang sama
                        $aq->where('mahasiswa_id', $application->mahasiswa_id)
                            ->where('type', 'mbkm')
                            ->where('is_group_mirror', false);
                    });
            })
            ->exists();
    }

    private function deleteMirrorsForMahasiswa(int $mahasiswaId, Application $ownerRegistrationApp): void
    {
        $ownerIds = Application::where('mahasiswa_id', $ownerRegistrationApp->mahasiswa_id)
            ->where('type', 'mbkm')
            ->where('is_group_mirror', false)
            ->pluck('id');

        Application::where('mahasiswa_id', $mahasiswaId)
            ->where('is_group_mirror', true)
            ->whereIn('parent_application_id', $ownerIds)
            ->delete();
    }
}

<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationSchedule extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'application_schedules';

    protected $dates = [
        'waktu',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SCHEDULE_TYPE_SELECT = [
        'seminar'         => 'Review Kelayakan Proposal (Reguler)',
        'defense'         => 'Sidang Skripsi',
        'skripsi_seminar' => 'Review Kelayakan Proposal (Reguler)',
        'mbkm_seminar'    => 'Review Kelayakan Proposal (MBKM)',
        'skripsi_defense' => 'Sidang Skripsi',
    ];

    protected $fillable = [
        'application_id',
        'schedule_type',
        'waktu',
        'ruang_id',
        'custom_place',
        'online_meeting',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getWaktuAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            $date = $value instanceof \DateTimeInterface
                ? Carbon::instance($value)
                : Carbon::createFromFormat('Y-m-d H:i:s', $value);
        } catch (\Throwable $e) {
            $date = Carbon::parse($value);
        }

        return $date->format(config('panel.date_format') . ' ' . config('panel.time_format'));
    }

    public function setWaktuAttribute($value)
    {
        if (!$value) {
            $this->attributes['waktu'] = null;
            return;
        }
        
        // If value is already a Carbon instance or DateTime
        if ($value instanceof \DateTimeInterface) {
            $this->attributes['waktu'] = $value->format('Y-m-d H:i:s');
            return;
        }
        
        // If value is a string, try to parse it
        try {
            $this->attributes['waktu'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // If parsing fails, try standard parse
            $this->attributes['waktu'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function isApprovedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'schedule_approved')
            ->where('metadata->schedule_id', $this->id)
            ->exists();
    }

    public function isRejectedByAdmin(): bool
    {
        if ($this->isApprovedByAdmin()) {
            return false;
        }

        if (ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'schedule_rejected')
            ->where('metadata->schedule_id', $this->id)
            ->exists()) {
            return true;
        }

        $legacyReject = ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'schedule_rejected')
            ->orderByDesc('created_at')
            ->get()
            ->first(fn ($action) => blank(data_get($action->metadata, 'schedule_id')));

        if (!$legacyReject) {
            return false;
        }

        if (static::where('application_id', $this->application_id)
            ->where('id', '>', $this->id)
            ->exists()) {
            return true;
        }

        $rejectedScheduleId = static::where('application_id', $this->application_id)
            ->where('created_at', '<=', $legacyReject->created_at)
            ->orderByDesc('id')
            ->value('id');

        return (int) $rejectedScheduleId === (int) $this->id;
    }

    /**
     * Jadwal masih aktif (menunggu verifikasi atau sudah disetujui) sehingga mahasiswa belum boleh ajukan baru.
     */
    public function blocksNewSubmission(): bool
    {
        return !$this->isRejectedByAdmin();
    }

    public static function hasBlockingScheduleFor(int $applicationId): bool
    {
        $latest = static::where('application_id', $applicationId)
            ->orderByDesc('id')
            ->first();

        if (!$latest) {
            return false;
        }

        return $latest->blocksNewSubmission();
    }

    public static function applicationEligibleForNewSchedule(int $applicationId): bool
    {
        return !static::hasBlockingScheduleFor($applicationId);
    }

    /**
     * Jadwal sidang sudah diverifikasi admin.
     */
    public function isDefenseScheduleVerified(): bool
    {
        if ($this->isApprovedByAdmin()) {
            return true;
        }

        if ($this->application?->status !== 'scheduled') {
            return false;
        }

        return in_array($this->schedule_type, ['skripsi_defense', 'defense'], true);
    }

    /**
     * Jadwal seminar/sidang sudah diverifikasi admin dan siap dilanjutkan ke pelaporan hasil.
     */
    public function isReadyForResultReport(): bool
    {
        if ($this->isApprovedByAdmin()) {
            return true;
        }

        if ($this->application?->status !== 'scheduled') {
            return false;
        }

        return (int) static::where('application_id', $this->application_id)
            ->whereIn('schedule_type', ['mbkm_seminar', 'skripsi_seminar', 'seminar'])
            ->orderByDesc('id')
            ->value('id') === (int) $this->id;
    }

    /**
     * Waktu pelaksanaan seminar/sidang sudah lewat.
     */
    public function isSeminarHeld(): bool
    {
        $rawWaktu = $this->getRawOriginal('waktu');

        if (!$rawWaktu) {
            return false;
        }

        return Carbon::parse($rawWaktu)->lte(now());
    }

    /**
     * Status validasi jadwal oleh admin (bukan status aplikasi tahap sebelumnya).
     */
    public function adminValidationStatus(): array
    {
        if ($this->isRejectedByAdmin()) {
            return [
                'label' => 'Ditolak',
                'badge' => 'danger',
                'icon' => 'times-circle',
                'detail' => 'Jadwal ditolak admin. Silakan ajukan jadwal baru.',
            ];
        }

        if ($this->isApprovedByAdmin()) {
            return [
                'label' => 'Dijadwalkan',
                'badge' => 'success',
                'icon' => 'calendar-check',
                'detail' => 'Jadwal telah diverifikasi dan dikonfirmasi admin.',
            ];
        }

        return [
            'label' => 'Menunggu Verifikasi',
            'badge' => 'warning',
            'icon' => 'clock',
            'detail' => 'Jadwal diajukan dan menunggu persetujuan admin.',
        ];
    }

    public function isDefenseSchedule(): bool
    {
        return in_array($this->schedule_type, ['skripsi_defense', 'defense'], true);
    }

    public function whatsappMahasiswaScheduleVerifiedUrl(): ?string
    {
        if (! $this->isDefenseSchedule() || ! $this->isDefenseScheduleVerified()) {
            return null;
        }

        $this->loadMissing(['application.mahasiswa.user']);

        $phone = $this->application?->mahasiswa?->user?->whatsappNumberForLink();
        if (! $phone) {
            return null;
        }

        $nama = $this->application?->mahasiswa?->user?->name
            ?? $this->application?->mahasiswa?->nama
            ?? 'Mahasiswa';

        return 'https://wa.me/'.$phone.'?text='.rawurlencode(self::buildScheduleVerifiedWhatsappMessage($nama));
    }

    public static function buildScheduleVerifiedWhatsappMessage(string $nama): string
    {
        return "Oke, {$nama} tanggal telah dicatat, silakan cek alur ini:\n"
            ."Mahasiswa sudah Input MK Skripsi di SIAKAD pada Semester Dimana akan melakukan Sidang Skripsi. (sudah dilakukan)\n"
            ."Menunjukkan bukti telah disetujui untuk Sidang Skripsi kepada Koordinator Skripsi berupa lembar Persetujuan Sidang yang sudah ditandatangani. (sudah dilakukan)\n"
            ."Melengkapi berkas-berkas Persyaratan Sidang kepada Koordinator Skripsi. (sudah dilakukan)\n"
            ."Menentukan tanggal Sidang Skripsi berdasarkan kesepakatan dengan Dosen Penguji dan Dosen Pembimbing serta ketersediaan ruangan serta menginformasikan kepada Koordinator Skripsi. (sudah dilakukan)\n"
            ."Mahasiswa WAJIB melakukan Pengajuan Sidang Skripsi melalui https://siakad.uns.ac.id/ > Login menggunakan Akun SSO > KRS & KHS > Pendaftaran Ujian.\n"
            ."Klik disini pada menu \"Konsultasi Skripsi/Thesis/Disertasi kurang dari 12. Tambahkan konsultasi Skripsi/Thesis/Disertasi terlebih dahulu\" > Pada tampilan Pembimbing pilih menu \"Konsultasi\" > Isikan Materi Bimbingan minimal 12x Pertemuan pada masing-masing Pembimbing berdasarkan Buku Bimbingan > Selanjutnya opsi \"Ajukan Ujian Skripsi\" akan muncul lalu silahkan dipilih.\n"
            ."Selanjutnya Mahasiswa wajib menghubungi Dosen Pembimbing meminta untuk dilakukan Validasi Panel Konsultasi pada poin nomor 6 diatas.\n"
            ."WhatsApp Pak Ryan di 08566334444 dengan menunjukkan tangkapan layar email ini untuk laporan telah melakukan Pengajuan Sidang Skripsi dengan menuliskan : Nama Pembimbing, Penguji 1 + 2, Hari, Tanggal, dan Jam Sidang dilaksanakan baru nanti akan dilakukan Verifikasi di SIAKAD serta mengirimkan File Berkas Undangan\n"
            ."Setelah itu Pak Ryan akan menginformasikan bahwa Pengajuan telah diverifikasi.\n"
            ."Berkas SIdang Skripsi dapat diunduh oleh Mahasiswa melalui http://linktr.ee/skripsipsikologiuns yang isinya meliputi Form Penilaian Sidang, Lembar Umpan Balik, dan Notulensi.\n"
            ."Sidang Skripsi yang dilaksanakan secara luring, Berkas Sidang Skripsi yang telah terisi silahkan diserahkan ke Biro Skripsi.\n"
            ."Draft Skripsi beserta Lembar Umpan Balik yang telah dicetak diserahkan kepada Dewan Penguji selambat-lambatnya H-5.\n"
            ."Berkas skripsi (Berita Acara diserahkan ke pembimbing), Umpan Balik) dipersiapkan oleh Mahasiswa, dengan catatan sebagai berikut.\n"
            ."Berita Acara dan Notulensi Sidang (dari http://linktr.ee/skripsipsikologiuns) dicetak 1 lembar, diserahkan kepada pembimbing pada Hari H Ujian.\n"
            ."Umpan Balik dibuat 3 lembar sesuai dengan nama dewan penguji.\n"
            ."Semua berkas terlebih dahulu disesuaikan dengan identitas pemohon sidang skripsi beserta dewan penguji yang terlibat diserahkan bersama dengan berkas skripsi untuk keperluan sidang.\n"
            ."Pembimbing berperan sebagai Ketua Sidang.\n"
            ."Penguji 1 berperan sebagai Anggota Penguji Utama.\n"
            ."Penguji 2 berperan sebagai Anggota Penguji Pendamping.\n"
            ."Jika Sidang Skripsi dilaksanakan secara daring, mahasiswa diwajibkan membuat WAG untuk membantu memperlancar komunikasi untuk keperluan sidang.\n"
            ."Penilaian dapat dilakukan dengan mengirimkan form penilaian yang telah diisi oleh dewan penguji melalui email skripsi.psikologi@mail.uns.ac.id atau link penilaian khusus yang akan dibagikan oleh Pembimbing.\n"
            ."Zoom meeting disediakan oleh pembimbing.\n\n"
            ."Lampiran Ketentuan Pelaksanaan Sidang Skripsi: https://uns.id/ketentuansidang";
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Faculty;
use App\Models\Jenjang;
use App\Models\Prodi;
use App\Models\Keilmuan;
use App\Models\ResearchGroup;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function index()
    {
        return view('frontend.profile');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();

        $user->update($request->validated());

        return redirect()->route('frontend.profile.index')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }

    public function password(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('frontend.profile.index')->with('message', __('global.change_password_success'));
    }
    
    public function createMahasiswaProfile()
    {
        $user = auth()->user();
        
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized action.');
        }
        
        // If profile already exists, redirect to edit
        if ($user->mahasiswa_id && $user->mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.edit');
        }
        
        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('frontend.mahasiswa-profile-create', compact('prodis', 'jenjangs', 'fakultas'));
    }
    
    public function storeMahasiswaProfile(Request $request)
    {
        $user = auth()->user();
        
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized action.');
        }
        
        // If profile already exists, redirect to edit
        if ($user->mahasiswa_id && $user->mahasiswa) {
            return redirect()->route('frontend.mahasiswa-profile.edit')->with('error', 'Profil sudah ada.');
        }
        
        $request->validate([
            'nim' => 'required|string|max:255|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer',
            'kelas' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'fakultas_id' => 'required|exists:faculties,id',
            'tanggal_lahir' => 'required|date_format:' . config('panel.date_format'),
            'tempat_lahir' => 'required|string|max:255',
            'gender' => 'required|string',
            'alamat' => 'required|string',
        ]);
        
        // Create mahasiswa profile
        $mahasiswa = Mahasiswa::create($request->all());
        
        // Update user with mahasiswa_id
        $user->update([
            'mahasiswa_id' => $mahasiswa->id,
            'name' => $request->nama, // Update user name with mahasiswa name
        ]);
        
        return redirect()->route('frontend.home')->with('success', 'Profil mahasiswa berhasil dibuat!');
    }
    
    public function editMahasiswaProfile()
    {
        $user = auth()->user();
        
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$user->mahasiswa_id || !$user->mahasiswa) {
            return redirect()->route('frontend.home')->with('error', 'Profil mahasiswa Anda belum dibuat. Silakan hubungi administrator.');
        }
        
        $mahasiswa = $user->mahasiswa;
        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('frontend.mahasiswa-profile', compact('mahasiswa', 'prodis', 'jenjangs', 'fakultas'));
    }
    
    public function updateMahasiswaProfile(Request $request)
    {
        $user = auth()->user();
        
        if ($user->level !== 'MAHASISWA') {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$user->mahasiswa_id || !$user->mahasiswa) {
            return redirect()->route('frontend.home')->with('error', 'Profil mahasiswa Anda belum dibuat. Silakan hubungi administrator.');
        }
        
        $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer',
            'kelas' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'fakultas_id' => 'required|exists:faculties,id',
            'tanggal_lahir' => 'required|date_format:' . config('panel.date_format'),
            'tempat_lahir' => 'required|string|max:255',
            'gender' => 'required|string',
            'alamat' => 'required|string',
        ]);
        
        $mahasiswa = $user->mahasiswa;
        $mahasiswa->update($request->all());
        
        return redirect()->route('frontend.home')->with('success', 'Profil berhasil diperbarui!');
    }
    
    public function createDosenProfile()
    {
        $user = auth()->user();
        
        if ($user->level !== 'DOSEN') {
            abort(403, 'Unauthorized action.');
        }
        
        // If profile already exists, redirect to edit
        if ($user->dosen_id && $user->dosen) {
            return redirect()->route('frontend.dosen-profile.edit');
        }
        
        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $keilmuans = Keilmuan::pluck('name', 'id');
        $riset_grups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('frontend.dosen-profile-create', compact('prodis', 'jenjangs', 'fakultas', 'keilmuans', 'riset_grups'));
    }
    
    public function storeDosenProfile(Request $request)
    {
        $user = auth()->user();
        
        if ($user->level !== 'DOSEN') {
            abort(403, 'Unauthorized action.');
        }
        
        // If profile already exists, redirect to edit
        if ($user->dosen_id && $user->dosen) {
            return redirect()->route('frontend.dosen-profile.edit')->with('error', 'Profil sudah ada.');
        }
        
        $request->validate([
            'nip' => 'required|string|max:255|unique:dosens,nip',
            'nidn' => 'required|string|max:255|unique:dosens,nidn',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:' . config('panel.date_format'),
            'gender' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'fakultas_id' => 'required|exists:faculties,id',
            'riset_grup_id' => 'required|exists:research_groups,id',
        ]);
        
        // Create dosen profile
        $dosen = Dosen::create($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));
        
        // Update user with dosen_id
        $user->update([
            'dosen_id' => $dosen->id,
            'name' => $request->nama, // Update user name with dosen name
        ]);
        
        return redirect()->route('frontend.home')->with('success', 'Profil dosen berhasil dibuat!');
    }
    
    public function editDosenProfile()
    {
        $user = auth()->user();
        
        if ($user->level !== 'DOSEN') {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$user->dosen_id || !$user->dosen) {
            return redirect()->route('frontend.home')->with('error', 'Profil dosen Anda belum dibuat. Silakan hubungi administrator.');
        }
        
        $dosen = $user->dosen;
        $prodis = Prodi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $fakultas = Faculty::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $keilmuans = Keilmuan::pluck('name', 'id');
        $riset_grups = ResearchGroup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('frontend.dosen-profile', compact('dosen', 'prodis', 'jenjangs', 'fakultas', 'keilmuans', 'riset_grups'));
    }
    
    public function updateDosenProfile(Request $request)
    {
        $user = auth()->user();
        
        if ($user->level !== 'DOSEN') {
            abort(403, 'Unauthorized action.');
        }
        
        if (!$user->dosen_id || !$user->dosen) {
            return redirect()->route('frontend.home')->with('error', 'Profil dosen Anda belum dibuat. Silakan hubungi administrator.');
        }
        
        $request->validate([
            'nip' => 'required|string|max:255',
            'nidn' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date_format:' . config('panel.date_format'),
            'gender' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'jenjang_id' => 'required|exists:jenjangs,id',
            'fakultas_id' => 'required|exists:faculties,id',
            'riset_grup_id' => 'required|exists:research_groups,id',
        ]);
        
        $dosen = $user->dosen;
        $dosen->update($request->all());
        $dosen->keilmuans()->sync($request->input('keilmuans', []));
        
        return redirect()->route('frontend.home')->with('success', 'Profil berhasil diperbarui!');
    }
}

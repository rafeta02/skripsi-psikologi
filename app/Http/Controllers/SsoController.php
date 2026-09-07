<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Overtrue\LaravelSaml\Saml;

class SsoController extends Controller
{
    private $afterLogoutRoute = 'home';

    public function login()
    {
        if (Auth::check()) {
            return redirect()->to($this->redirectPathFor(Auth::user()));
        }

        return Saml::redirect();
    }

    public function acs(Request $request)
    {
        try {
            // Overtrue\LaravelSaml\SamlUser
            $samlUser = Saml::getAuthenticatedUser();

            $parsedAttributes = $this->parseSamlAttributes($samlUser->getAttributes());

            Log::info('SSO ACS debug', [
                'saml_user' => [
                    'name_id' => $samlUser->getNameId(),
                    'attributes_raw' => $samlUser->getAttributes(),
                ],
                'parsed_attributes' => $parsedAttributes,
            ]);
            
            // Validate required email attribute
            if (empty($parsedAttributes['email'])) {
                Log::error('SSO Login Failed: Email attribute is missing', [
                    'attributes' => $parsedAttributes
                ]);
                return redirect()->route('login')->with('error', 'Login gagal: Email tidak ditemukan dalam response SSO.');
            }

            $identityNumber = trim((string) (
                $parsedAttributes['identity_numbers']
                ?? $parsedAttributes['identity_number']
                ?? ''
            ));
            $ssoLevel = strtolower(trim((string) ($parsedAttributes['level'] ?? '')));

            if ($identityNumber === '') {
                Log::warning('SSO access denied: identity number missing', [
                    'email' => $parsedAttributes['email'] ?? null,
                    'sso_level' => $ssoLevel,
                ]);

                return redirect()->route('login')->with(
                    'error',
                    'Login gagal: Nomor identitas tidak ditemukan dalam response SSO.'
                );
            }

            $dosen = Dosen::where('nip', $identityNumber)->first();

            if ($dosen) {
                $user = DB::transaction(function () use ($parsedAttributes, $identityNumber, $dosen) {
                    $user = $this->findOrCreateSsoUser($parsedAttributes, $identityNumber);
                    $this->grantDosenAccess($user, $dosen, $identityNumber);

                    return $user;
                });

                return $this->loginSsoUser($user);
            }

            $isStudent = in_array($ssoLevel, ['student', 'mahasiswa'], true);
            $mahasiswa = Mahasiswa::where('nim', $identityNumber)->first();
            $isPsikologi = Mahasiswa::isPsikologiNim($identityNumber)
                || ($mahasiswa && Mahasiswa::isPsikologiNim($mahasiswa->nim));

            if ($isStudent && $isPsikologi) {
                $user = DB::transaction(function () use ($parsedAttributes, $identityNumber) {
                    $user = $this->findOrCreateSsoUser($parsedAttributes, $identityNumber);
                    $this->grantMahasiswaAccess($user, $identityNumber);

                    return $user;
                });

                return $this->loginSsoUser($user);
            }

            Log::warning('SSO access denied', [
                'email' => $parsedAttributes['email'] ?? null,
                'identity_number' => $identityNumber,
                'sso_level' => $ssoLevel,
            ]);

            return redirect()->route('login')->with(
                'error',
                'Login gagal: Anda tidak memiliki akses ke aplikasi ini. Akses hanya untuk dosen yang terdaftar atau mahasiswa Psikologi.'
            );
            
        } catch (\Exception $e) {
            Log::error('SSO Login Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')->with('error', 'Login gagal: Terjadi kesalahan saat memproses data SSO. Silakan coba lagi atau hubungi administrator.');
        }
    }

    public function logout(Request $request)
    {
        return Saml::redirectToLogout();
    }

    public function sls(Request $request)
    {
        $auth = Saml::handleLogoutRequest();

        Auth::logout();

        return redirect()->route($this->afterLogoutRoute);
    }

    public function metadata(Request $request)
    {
        if ($request->has('download')) {
            return Saml::getMetadataXMLAsStreamResponse();
        }

        return Saml::getMetadataXML();
    }

    /**
     * Convert SAML attributes (which is a 2 dimensional array) to a 1 dimensional array
    * @param  array  $samlAttributes
    * @return array
    */
    private function parseSamlAttributes(array $samlAttributes) : array {
        $result = [];

        foreach ($samlAttributes as $key => $value) {
            // Check if value is an array and has at least one element
            if (is_array($value) && !empty($value)) {
                $result[$key] = $value[0];
            } else {
                // If not an array or empty, set as null
                $result[$key] = null;
            }
        }

        return $result;
    }

    private function formatPhoneNumber(?string $phone)
    {
        // Return null if phone is empty
        if (empty($phone)) {
            return null;
        }

        $phone = ltrim($phone, " +");
        
        // Additional validation to ensure phone is not empty after trim
        if (empty($phone)) {
            return null;
        }

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        if (in_array(substr($phone, 0, 2), ['81', '82', '83', '85', '88', '89'])) {
            $phone = '62' . $phone;
        }
        return $phone;
    }

    private function findOrCreateSsoUser(array $parsedAttributes, string $identityNumber): User
    {
        $email = $parsedAttributes['email'];
        $name = $parsedAttributes['cn']
            ?? $parsedAttributes['displayName']
            ?? $parsedAttributes['name']
            ?? $email;
        $username = $parsedAttributes['uid']
            ?? $parsedAttributes['username']
            ?? $identityNumber;
        $phone = $this->formatPhoneNumber(
            $parsedAttributes['mobile']
            ?? $parsedAttributes['phone']
            ?? $parsedAttributes['telephoneNumber']
            ?? $parsedAttributes['no_hp']
            ?? null
        );

        $user = User::withTrashed()->where('email', $email)->first();

        if (! $user && $identityNumber !== '') {
            $user = User::withTrashed()->where('identity_number', $identityNumber)->first();
        }

        if ($user) {
            if ($user->trashed()) {
                $user->restore();
            }

            $user->update([
                'name' => $name,
                'email' => $email,
                'username' => $username ?: $user->username,
                'identity_number' => $identityNumber,
                'no_hp' => $phone ?? $user->no_hp,
                'whatshapp' => $phone ?? $user->whatshapp,
            ]);

            $this->ensureUserRole($user);

            return $user->fresh();
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'identity_number' => $identityNumber,
            'password' => Str::random(32),
            'no_hp' => $phone,
            'whatshapp' => $phone,
        ]);

        $this->ensureUserRole($user);

        return $user;
    }

    private function grantDosenAccess(User $user, Dosen $dosen, string $identityNumber): void
    {
        $user->update([
            'level' => 'DOSEN',
            'identity_number' => $identityNumber,
            'dosen_id' => $dosen->id,
            'mahasiswa_id' => null,
        ]);
    }

    private function grantMahasiswaAccess(User $user, string $identityNumber): void
    {
        $mahasiswa = Mahasiswa::syncFromSsoUser($user, $identityNumber);

        $user->update([
            'level' => 'MAHASISWA',
            'identity_number' => $identityNumber,
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => null,
        ]);
    }

    private function loginSsoUser(User $user)
    {
        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended($this->redirectPathFor($user));
    }

    private function redirectPathFor(User $user): string
    {
        if ($user->is_admin) {
            return route('admin.home');
        }

        return match ($user->level) {
            'MAHASISWA', 'student' => route('mahasiswa.dashboard'),
            'DOSEN', 'dosen' => route('dosen.dashboard'),
            'STAFF', 'staff' => route('admin.home'),
            default => route($this->afterLogoutRoute),
        };
    }

    private function ensureUserRole(User $user): void
    {
        $userRole = Role::find(2);

        if ($userRole && ! $user->roles()->where('roles.id', $userRole->id)->exists()) {
            $user->roles()->attach($userRole->id);
        }
    }
}

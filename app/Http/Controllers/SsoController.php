<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelSaml\Saml;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    // Sesuaikan dengan aplikasi Anda
    private $afterLoginRoute = 'dashboard';
    private $afterLogoutRoute = 'home';

    public function login() {
        if (Auth::check()) {
            return redirect()->route($this->afterLoginRoute);
        }

        return Saml::redirect();
    }

    public function acs(Request $request)
    {
        try {
            // Overtrue\LaravelSaml\SamlUser
            $samlUser = Saml::getAuthenticatedUser();

            $parsedAttributes = $this->parseSamlAttributes($samlUser->getAttributes());
            
            // Validate required email attribute
            if (empty($parsedAttributes['email'])) {
                Log::error('SSO Login Failed: Email attribute is missing', [
                    'attributes' => $parsedAttributes
                ]);
                return redirect()->route('login')->with('error', 'Login gagal: Email tidak ditemukan dalam response SSO.');
            }

        $user = User::where('email', $parsedAttributes['email'])->first();

        if (!$user) {
            // Create new user if doesn't exist
            $userData = [
                'email' => $parsedAttributes['email'],
                'name' => $parsedAttributes['nama'] ?? 'User',
                'approved' => 1,
                'verified' => 1,
                'verified_at' => now()->format('d-m-Y H:i:s'),
                'email_verified_at' => now()->format('d-m-Y H:i:s'),
                'password' => bcrypt(random_int(1000000, 99999999)),
            ];

            // Add optional fields only if they exist and are not empty
            if (!empty($parsedAttributes['no_hp'])) {
                $userData['no_hp'] = $this->formatPhoneNumber($parsedAttributes['no_hp']);
            }

            if (!empty($parsedAttributes['username'])) {
                $userData['username'] = $parsedAttributes['username'];
            }

            if (!empty($parsedAttributes['level'])) {
                $userData['level'] = $parsedAttributes['level'];
            }

            if (!empty($parsedAttributes['identity_numbers'])) {
                $userData['identity_number'] = $parsedAttributes['identity_numbers'];
            }

            if (!empty($parsedAttributes['alamat'])) {
                $userData['alamat'] = $parsedAttributes['alamat'];
            }

            if (!empty($parsedAttributes['id_simpeg'])) {
                $userData['id_simpeg'] = $parsedAttributes['id_simpeg'];
            }

            $user = User::create($userData);
            
            // Sync roles for new user
            $user->roles()->sync(2);

        } else {
            // For existing user, only update empty fields
            $fieldsToUpdate = [];
            
            if (empty($user->name) && !empty($parsedAttributes['nama'])) {
                $fieldsToUpdate['name'] = $parsedAttributes['nama'];
            }
            
            if (empty($user->no_hp) && !empty($parsedAttributes['no_hp'])) {
                $fieldsToUpdate['no_hp'] = $this->formatPhoneNumber($parsedAttributes['no_hp']);
            }
            
            if (empty($user->username) && !empty($parsedAttributes['username'])) {
                $fieldsToUpdate['username'] = $parsedAttributes['username'];
            }
            
            if (empty($user->alamat) && !empty($parsedAttributes['alamat'])) {
                $fieldsToUpdate['alamat'] = $parsedAttributes['alamat'];
            }

            if (empty($user->id_simpeg) && !empty($parsedAttributes['id_simpeg'])) {
                $fieldsToUpdate['id_simpeg'] = $parsedAttributes['id_simpeg'];
            }

            if (empty($user->identity_number) && !empty($parsedAttributes['identity_numbers'])) {
                $fieldsToUpdate['identity_number'] = $parsedAttributes['identity_numbers'];
            }

            if (empty($user->level) && !empty($parsedAttributes['level'])) {
                $fieldsToUpdate['level'] = $parsedAttributes['level'];
            }
            
            // Only update if there are fields to update
            if (!empty($fieldsToUpdate)) {
                $user->update($fieldsToUpdate);
            }
            
            // No need to sync roles for existing users
        }
        
            // Login the user
            Auth::login($user);

            // Redirect ke halaman dashboard
            return redirect()->route('frontend.home');
            
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
}

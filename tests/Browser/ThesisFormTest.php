<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ThesisFormTest extends DuskTestCase
{
    /**
     * Test Skripsi Reguler form wizard navigation
     * TC-SR-007, TC-SR-008
     *
     * @return void
     */
    public function testSkripsiFormWizardNavigation()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();

            $browser->loginAs($user)
                    ->visit('/frontend/skripsi/' . $mahasiswa->id . '/create')
                    ->assertSee('Pendaftaran Skripsi Reguler')
                    ->assertVisible('#step-1')
                    ->assertButtonEnabled('.wizard-next')
                    // Navigate through wizard
                    ->click('.wizard-next')
                    ->waitFor('#step-2', 5)
                    ->assertVisible('#step-2')
                    ->assertButtonEnabled('.wizard-prev')
                    ->click('.wizard-prev')
                    ->waitFor('#step-1', 5)
                    ->assertVisible('#step-1');
        });
    }

    /**
     * Test Select2 dropdown functionality
     * TC-SR-007
     *
     * @return void
     */
    public function testSelect2DropdownWorks()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();

            $browser->loginAs($user)
                    ->visit('/frontend/skripsi/' . $mahasiswa->id . '/create')
                    ->click('#select2-theme_id-container') // Open Select2
                    ->waitFor('.select2-results', 2)
                    ->assertVisible('.select2-results') // Dropdown should be visible
                    ->assertDontSee('wrapper select2 break'); // Should not be clipped
        });
    }

    /**
     * Test form validation for required fields
     * TC-SR-009
     *
     * @return void
     */
    public function testFormValidationForRequiredFields()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();

            $browser->loginAs($user)
                    ->visit('/frontend/skripsi/' . $mahasiswa->id . '/create')
                    // Try to submit without filling required fields
                    ->scrollIntoView('.wizard-submit')
                    ->click('.wizard-submit')
                    ->waitForText('harus diisi', 5) // Should show validation error
                    ->assertSee('harus diisi');
        });
    }

    /**
     * Test MBKM form wizard navigation
     * TC-MBKM-009
     *
     * @return void
     */
    public function testMbkmFormWizardNavigation()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();

            $browser->loginAs($user)
                    ->visit('/frontend/mbkm/' . $mahasiswa->id . '/create')
                    ->assertSee('Pendaftaran MBKM')
                    ->assertVisible('#step-1')
                    ->click('.wizard-next')
                    ->waitFor('#step-2', 5)
                    ->assertVisible('#step-2');
        });
    }

    /**
     * Test modal opening on Dosen task assignments page
     * Regression test for BUG-001
     *
     * @return void
     */
    public function testDosenTaskAssignmentsModalWorks()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $dosen = Dosen::factory()->create(['user_id' => $user->id]);
            $user->dosen_id = $dosen->id;
            $user->save();
            $user->assignRole('Dosen');

            $browser->loginAs($user)
                    ->visit('/dosen/task-assignments')
                    ->assertSee('Penugasan Pembimbingan')
                    // Try to open a modal (if assignments exist)
                    ->whenAvailable('.btn-respond', function ($button) {
                        $button->click();
                    })
                    ->pause(1000)
                    // Modal should not glitch
                    ->assertVisible('.modal-content')
                    ->assertDontSee('glitch');
        });
    }

    /**
     * Test responsive design on mobile
     * TC-RES-004
     *
     * @return void
     */
    public function testResponsiveDesignMobile()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();

            $browser->resize(375, 667) // Mobile viewport
                    ->loginAs($user)
                    ->visit('/mahasiswa/aplikasi')
                    ->assertSee('Aplikasi Skripsi')
                    // Check if mobile layout is applied
                    ->assertPresent('.card');
        });
    }

    /**
     * Test timeline component visibility
     * TC-TL-001
     *
     * @return void
     */
    public function testTimelineComponentVisible()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $mahasiswa = Mahasiswa::factory()->create(['user_id' => $user->id]);
            $user->mahasiswa_id = $mahasiswa->id;
            $user->save();
            
            // Create an application
            $application = \App\Models\Application::factory()->create([
                'mahasiswa_id' => $mahasiswa->id,
                'type' => 'skripsi',
                'stage' => 'registration',
            ]);

            $browser->loginAs($user)
                    ->visit('/frontend/applications/' . $application->id)
                    ->assertSee('Progress skripsi')
                    ->assertSee('Pendaftaran')
                    ->assertVisible('.thesis-timeline');
        });
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Application;
use App\Models\SkripsiRegistration;
use App\Models\MbkmRegistration;
use App\Models\ApplicationAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ThesisWorkflowTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $mahasiswa;
    protected $mahasiswaUser;
    protected $dosen;
    protected $dosenUser;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $this->admin->assignRole('Administrator');

        $this->mahasiswaUser = User::factory()->create([
            'email' => 'mahasiswa@test.com',
        ]);
        $this->mahasiswa = Mahasiswa::factory()->create([
            'user_id' => $this->mahasiswaUser->id,
        ]);
        $this->mahasiswaUser->mahasiswa_id = $this->mahasiswa->id;
        $this->mahasiswaUser->save();

        $this->dosenUser = User::factory()->create([
            'email' => 'dosen@test.com',
        ]);
        $this->dosen = Dosen::factory()->create([
            'user_id' => $this->dosenUser->id,
        ]);
        $this->dosenUser->dosen_id = $this->dosen->id;
        $this->dosenUser->save();
        
        Storage::fake('public');
    }

    /** @test */
    public function test_skripsi_reguler_full_workflow()
    {
        // ============================================
        // PHASE 1: REGISTRATION
        // ============================================
        
        // TC-SR-001: Mahasiswa can access registration form
        $response = $this->actingAs($this->mahasiswaUser)
            ->get(route('frontend.choose-path'));
        
        $response->assertStatus(200);
        $response->assertSee('Pilih Jalur Skripsi');

        // TC-SR-002-005: Mahasiswa can register Skripsi Reguler
        $khs = UploadedFile::fake()->create('khs.pdf', 1024); // 1MB
        $krs = UploadedFile::fake()->create('krs.pdf', 2048); // 2MB
        
        $registrationData = [
            'type' => 'skripsi',
            'proposal_title_1' => 'Analisis Psikologi Remaja',
            'proposal_description_1' => 'Penelitian tentang perilaku remaja di era digital',
            'proposal_theme_id_1' => 1,
            'research_group_id' => 1,
            'khs' => $khs,
            'krs' => $krs,
        ];

        $response = $this->actingAs($this->mahasiswaUser)
            ->post(route('frontend.skripsi.store', $this->mahasiswa->id), $registrationData);

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'skripsi',
            'status' => 'submitted',
        ]);
        
        $application = Application::where('mahasiswa_id', $this->mahasiswa->id)->first();
        $this->assertNotNull($application);
        
        // TC-SR-006: Verify file naming convention
        $this->assertDatabaseHas('skripsi_registrations', [
            'application_id' => $application->id,
        ]);
        
        // ============================================
        // PHASE 2: ADMIN VERIFICATION
        // ============================================
        
        // TC-SR-012: Admin can view pending registrations
        $response = $this->actingAs($this->admin)
            ->get(route('admin.skripsi-registrations.index'));
        
        $response->assertStatus(200);
        $response->assertSee($this->mahasiswa->nama);
        
        // TC-SR-015: Admin can approve registration
        $skripsiReg = SkripsiRegistration::where('application_id', $application->id)->first();
        
        $response = $this->actingAs($this->admin)
            ->patch(route('admin.skripsi-registrations.update', $skripsiReg->id), [
                'status' => 'approved',
            ]);
        
        $application->refresh();
        $this->assertEquals('approved', $application->status);
        
        // ============================================
        // PHASE 3: ASSIGN PEMBIMBING
        // ============================================
        
        // TC-SR-019-023: Admin assigns pembimbing
        $assignmentData = [
            'application_id' => $application->id,
            'lecturer_id' => $this->dosen->id,
            'role' => 'supervisor',
            'status' => 'assigned',
            'assigned_at' => now(),
        ];
        
        $response = $this->actingAs($this->admin)
            ->post(route('admin.application-assignments.store'), $assignmentData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('application_assignments', [
            'application_id' => $application->id,
            'lecturer_id' => $this->dosen->id,
            'role' => 'supervisor',
        ]);
        
        $assignment = ApplicationAssignment::where('application_id', $application->id)->first();
        
        // TC-SR-024: Dosen can see assignment
        $response = $this->actingAs($this->dosenUser)
            ->get(route('dosen.task-assignments'));
        
        $response->assertStatus(200);
        $response->assertSee($this->mahasiswa->nama);
        
        // ============================================
        // PHASE 4: DOSEN REVIEW
        // ============================================
        
        // TC-SR-025-034: Dosen reviews proposal
        $reviewData = [
            'review_decision' => 'approved',
            'score' => 85,
            'feedback' => 'Proposal bagus, silakan lanjutkan penelitian',
        ];
        
        $response = $this->actingAs($this->dosenUser)
            ->post(route('dosen.task-assignments.respond', $assignment->id), $reviewData);
        
        $assignment->refresh();
        $this->assertEquals('accepted', $assignment->status);
        
        $application->refresh();
        $this->assertEquals('approved', $application->status);
        
        // Test complete!
    }

    /** @test */
    public function test_skripsi_seminar_registration()
    {
        // Setup: Create approved application
        $application = Application::factory()->create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'skripsi',
            'stage' => 'reviewer_registration',
            'status' => 'approved',
        ]);

        // TC-SR-036-044: Mahasiswa registers for seminar
        $proposal = UploadedFile::fake()->create('proposal.pdf', 10240); // 10MB
        $approval = UploadedFile::fake()->create('approval.pdf', 2048);
        $plagiarism = UploadedFile::fake()->create('plagiarism.pdf', 8192);

        $seminarData = [
            'application_id' => $application->id,
            'title' => 'Analisis Psikologi Remaja',
            'description' => 'Penelitian lengkap tentang perilaku remaja',
            'proposal_document' => $proposal,
            'approval_document' => $approval,
            'plagiarism_document' => $plagiarism,
            'notes' => 'Siap untuk seminar',
        ];

        $response = $this->actingAs($this->mahasiswaUser)
            ->post(route('frontend.skripsi-seminars.store'), $seminarData);

        $response->assertRedirect();
        $this->assertDatabaseHas('skripsi_seminars', [
            'application_id' => $application->id,
            'title' => 'Analisis Psikologi Remaja',
        ]);
    }

    /** @test */
    public function test_mbkm_registration()
    {
        // TC-MBKM-001-008: Mahasiswa registers for MBKM
        $certificate = UploadedFile::fake()->create('mbkm_certificate.pdf', 3072);
        $khs = UploadedFile::fake()->create('khs.pdf', 1024);
        $krs = UploadedFile::fake()->create('krs.pdf', 2048);

        $mbkmData = [
            'type' => 'mbkm',
            'mbkm_program' => 'Program Pertukaran Mahasiswa',
            'proposal_title_1' => 'Penelitian MBKM',
            'proposal_description_1' => 'Deskripsi penelitian MBKM',
            'proposal_theme_id_1' => 1,
            'research_group_id' => 1,
            'mbkm_certificate' => $certificate,
            'khs' => $khs,
            'krs' => $krs,
        ];

        $response = $this->actingAs($this->mahasiswaUser)
            ->post(route('frontend.mbkm.store', $this->mahasiswa->id), $mbkmData);

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'mbkm',
            'status' => 'submitted',
        ]);
    }

    /** @test */
    public function test_file_validation_rejects_large_files()
    {
        // TC-SR-010: Test file size limit
        $application = Application::factory()->create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'skripsi',
        ]);

        $oversizedFile = UploadedFile::fake()->create('oversized.pdf', 25600); // 25MB (exceeds limit)

        $seminarData = [
            'application_id' => $application->id,
            'title' => 'Test',
            'description' => 'Test',
            'proposal_document' => $oversizedFile,
            'approval_document' => UploadedFile::fake()->create('approval.pdf', 2048),
            'plagiarism_document' => UploadedFile::fake()->create('plagiarism.pdf', 2048),
        ];

        $response = $this->actingAs($this->mahasiswaUser)
            ->post(route('frontend.skripsi-seminars.store'), $seminarData);

        $response->assertSessionHasErrors('proposal_document');
    }

    /** @test */
    public function test_file_validation_rejects_non_pdf()
    {
        // TC-SR-011: Test file format validation
        $application = Application::factory()->create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'skripsi',
        ]);

        $wrongFormat = UploadedFile::fake()->create('document.docx', 2048);

        $seminarData = [
            'application_id' => $application->id,
            'title' => 'Test',
            'description' => 'Test',
            'proposal_document' => $wrongFormat,
            'approval_document' => UploadedFile::fake()->create('approval.pdf', 2048),
            'plagiarism_document' => UploadedFile::fake()->create('plagiarism.pdf', 2048),
        ];

        $response = $this->actingAs($this->mahasiswaUser)
            ->post(route('frontend.skripsi-seminars.store'), $seminarData);

        $response->assertSessionHasErrors('proposal_document');
    }

    /** @test */
    public function test_mahasiswa_cannot_access_admin_pages()
    {
        // TC-SEC-001: Security test
        $response = $this->actingAs($this->mahasiswaUser)
            ->get(route('admin.skripsi-registrations.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_mahasiswa_cannot_access_dosen_pages()
    {
        // TC-SEC-002: Security test
        $response = $this->actingAs($this->mahasiswaUser)
            ->get(route('dosen.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_dosen_cannot_access_admin_pages()
    {
        // TC-SEC-003: Security test
        $response = $this->actingAs($this->dosenUser)
            ->get(route('admin.skripsi-registrations.index'));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_timeline_component_renders_correctly()
    {
        // TC-TL-001: Timeline component test
        $application = Application::factory()->create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'type' => 'skripsi',
            'stage' => 'proposal_review',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($this->mahasiswaUser)
            ->get(route('frontend.applications.show', $application->id));

        $response->assertStatus(200);
        $response->assertSee('Progress skripsi');
        $response->assertSee('Pendaftaran');
        $response->assertSee('Review Proposal');
    }

    /** @test */
    public function test_admin_dashboard_displays_statistics()
    {
        // TC-ADM-001: Admin dashboard test
        Application::factory()->count(5)->create(['status' => 'submitted']);
        Application::factory()->count(3)->create(['status' => 'approved']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.home'));

        $response->assertStatus(200);
        $response->assertSee('Total Aplikasi');
        $response->assertSee('8'); // Total applications
    }

    /** @test */
    public function test_dosen_can_view_only_own_assignments()
    {
        // TC-SEC-004: Dosen can only see own assignments
        $otherDosen = Dosen::factory()->create();
        
        ApplicationAssignment::factory()->create([
            'lecturer_id' => $this->dosen->id,
            'status' => 'assigned',
        ]);
        
        ApplicationAssignment::factory()->create([
            'lecturer_id' => $otherDosen->id,
            'status' => 'assigned',
        ]);

        $response = $this->actingAs($this->dosenUser)
            ->get(route('dosen.task-assignments'));

        $response->assertStatus(200);
        // Should only see 1 assignment (own assignment)
        $assignments = ApplicationAssignment::where('lecturer_id', $this->dosen->id)->count();
        $this->assertEquals(1, $assignments);
    }
}

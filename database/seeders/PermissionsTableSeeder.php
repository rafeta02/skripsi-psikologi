<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'blog_master_access',
            ],
            [
                'id'    => 20,
                'title' => 'article_category_create',
            ],
            [
                'id'    => 21,
                'title' => 'article_category_edit',
            ],
            [
                'id'    => 22,
                'title' => 'article_category_show',
            ],
            [
                'id'    => 23,
                'title' => 'article_category_delete',
            ],
            [
                'id'    => 24,
                'title' => 'article_category_access',
            ],
            [
                'id'    => 25,
                'title' => 'article_tag_create',
            ],
            [
                'id'    => 26,
                'title' => 'article_tag_edit',
            ],
            [
                'id'    => 27,
                'title' => 'article_tag_show',
            ],
            [
                'id'    => 28,
                'title' => 'article_tag_delete',
            ],
            [
                'id'    => 29,
                'title' => 'article_tag_access',
            ],
            [
                'id'    => 30,
                'title' => 'post_create',
            ],
            [
                'id'    => 31,
                'title' => 'post_edit',
            ],
            [
                'id'    => 32,
                'title' => 'post_show',
            ],
            [
                'id'    => 33,
                'title' => 'post_delete',
            ],
            [
                'id'    => 34,
                'title' => 'post_access',
            ],
            [
                'id'    => 35,
                'title' => 'jenjang_create',
            ],
            [
                'id'    => 36,
                'title' => 'jenjang_edit',
            ],
            [
                'id'    => 37,
                'title' => 'jenjang_show',
            ],
            [
                'id'    => 38,
                'title' => 'jenjang_delete',
            ],
            [
                'id'    => 39,
                'title' => 'jenjang_access',
            ],
            [
                'id'    => 40,
                'title' => 'master_access',
            ],
            [
                'id'    => 41,
                'title' => 'faculty_create',
            ],
            [
                'id'    => 42,
                'title' => 'faculty_edit',
            ],
            [
                'id'    => 43,
                'title' => 'faculty_show',
            ],
            [
                'id'    => 44,
                'title' => 'faculty_delete',
            ],
            [
                'id'    => 45,
                'title' => 'faculty_access',
            ],
            [
                'id'    => 46,
                'title' => 'prodi_create',
            ],
            [
                'id'    => 47,
                'title' => 'prodi_edit',
            ],
            [
                'id'    => 48,
                'title' => 'prodi_show',
            ],
            [
                'id'    => 49,
                'title' => 'prodi_delete',
            ],
            [
                'id'    => 50,
                'title' => 'prodi_access',
            ],
            [
                'id'    => 51,
                'title' => 'mahasiswa_create',
            ],
            [
                'id'    => 52,
                'title' => 'mahasiswa_edit',
            ],
            [
                'id'    => 53,
                'title' => 'mahasiswa_show',
            ],
            [
                'id'    => 54,
                'title' => 'mahasiswa_delete',
            ],
            [
                'id'    => 55,
                'title' => 'mahasiswa_access',
            ],
            [
                'id'    => 56,
                'title' => 'dosen_create',
            ],
            [
                'id'    => 57,
                'title' => 'dosen_edit',
            ],
            [
                'id'    => 58,
                'title' => 'dosen_show',
            ],
            [
                'id'    => 59,
                'title' => 'dosen_delete',
            ],
            [
                'id'    => 60,
                'title' => 'dosen_access',
            ],
            [
                'id'    => 61,
                'title' => 'keilmuan_create',
            ],
            [
                'id'    => 62,
                'title' => 'keilmuan_edit',
            ],
            [
                'id'    => 63,
                'title' => 'keilmuan_show',
            ],
            [
                'id'    => 64,
                'title' => 'keilmuan_delete',
            ],
            [
                'id'    => 65,
                'title' => 'keilmuan_access',
            ],
            [
                'id'    => 66,
                'title' => 'research_group_create',
            ],
            [
                'id'    => 67,
                'title' => 'research_group_edit',
            ],
            [
                'id'    => 68,
                'title' => 'research_group_show',
            ],
            [
                'id'    => 69,
                'title' => 'research_group_delete',
            ],
            [
                'id'    => 70,
                'title' => 'research_group_access',
            ],
            [
                'id'    => 71,
                'title' => 'civitum_access',
            ],
            [
                'id'    => 72,
                'title' => 'application_create',
            ],
            [
                'id'    => 73,
                'title' => 'application_edit',
            ],
            [
                'id'    => 74,
                'title' => 'application_show',
            ],
            [
                'id'    => 75,
                'title' => 'application_delete',
            ],
            [
                'id'    => 76,
                'title' => 'application_access',
            ],
            [
                'id'    => 77,
                'title' => 'form_access',
            ],
            [
                'id'    => 78,
                'title' => 'skripsi_defense_create',
            ],
            [
                'id'    => 79,
                'title' => 'skripsi_defense_edit',
            ],
            [
                'id'    => 80,
                'title' => 'skripsi_defense_show',
            ],
            [
                'id'    => 81,
                'title' => 'skripsi_defense_delete',
            ],
            [
                'id'    => 82,
                'title' => 'skripsi_defense_access',
            ],
            [
                'id'    => 83,
                'title' => 'skripsi_registration_create',
            ],
            [
                'id'    => 84,
                'title' => 'skripsi_registration_edit',
            ],
            [
                'id'    => 85,
                'title' => 'skripsi_registration_show',
            ],
            [
                'id'    => 86,
                'title' => 'skripsi_registration_delete',
            ],
            [
                'id'    => 87,
                'title' => 'skripsi_registration_access',
            ],
            [
                'id'    => 88,
                'title' => 'skripsi_seminar_create',
            ],
            [
                'id'    => 89,
                'title' => 'skripsi_seminar_edit',
            ],
            [
                'id'    => 90,
                'title' => 'skripsi_seminar_show',
            ],
            [
                'id'    => 91,
                'title' => 'skripsi_seminar_delete',
            ],
            [
                'id'    => 92,
                'title' => 'skripsi_seminar_access',
            ],
            [
                'id'    => 93,
                'title' => 'mbkm_registration_create',
            ],
            [
                'id'    => 94,
                'title' => 'mbkm_registration_edit',
            ],
            [
                'id'    => 95,
                'title' => 'mbkm_registration_show',
            ],
            [
                'id'    => 96,
                'title' => 'mbkm_registration_delete',
            ],
            [
                'id'    => 97,
                'title' => 'mbkm_registration_access',
            ],
            [
                'id'    => 98,
                'title' => 'mbkm_group_member_create',
            ],
            [
                'id'    => 99,
                'title' => 'mbkm_group_member_edit',
            ],
            [
                'id'    => 100,
                'title' => 'mbkm_group_member_show',
            ],
            [
                'id'    => 101,
                'title' => 'mbkm_group_member_delete',
            ],
            [
                'id'    => 102,
                'title' => 'mbkm_group_member_access',
            ],
            [
                'id'    => 103,
                'title' => 'mbkm_seminar_create',
            ],
            [
                'id'    => 104,
                'title' => 'mbkm_seminar_edit',
            ],
            [
                'id'    => 105,
                'title' => 'mbkm_seminar_show',
            ],
            [
                'id'    => 106,
                'title' => 'mbkm_seminar_delete',
            ],
            [
                'id'    => 107,
                'title' => 'mbkm_seminar_access',
            ],
            [
                'id'    => 108,
                'title' => 'application_report_create',
            ],
            [
                'id'    => 109,
                'title' => 'application_report_edit',
            ],
            [
                'id'    => 110,
                'title' => 'application_report_show',
            ],
            [
                'id'    => 111,
                'title' => 'application_report_delete',
            ],
            [
                'id'    => 112,
                'title' => 'application_report_access',
            ],
            [
                'id'    => 113,
                'title' => 'application_assignment_create',
            ],
            [
                'id'    => 114,
                'title' => 'application_assignment_edit',
            ],
            [
                'id'    => 115,
                'title' => 'application_assignment_show',
            ],
            [
                'id'    => 116,
                'title' => 'application_assignment_delete',
            ],
            [
                'id'    => 117,
                'title' => 'application_assignment_access',
            ],
            [
                'id'    => 118,
                'title' => 'result_access',
            ],
            [
                'id'    => 119,
                'title' => 'application_result_seminar_create',
            ],
            [
                'id'    => 120,
                'title' => 'application_result_seminar_edit',
            ],
            [
                'id'    => 121,
                'title' => 'application_result_seminar_show',
            ],
            [
                'id'    => 122,
                'title' => 'application_result_seminar_delete',
            ],
            [
                'id'    => 123,
                'title' => 'application_result_seminar_access',
            ],
            [
                'id'    => 124,
                'title' => 'application_result_defense_create',
            ],
            [
                'id'    => 125,
                'title' => 'application_result_defense_edit',
            ],
            [
                'id'    => 126,
                'title' => 'application_result_defense_show',
            ],
            [
                'id'    => 127,
                'title' => 'application_result_defense_delete',
            ],
            [
                'id'    => 128,
                'title' => 'application_result_defense_access',
            ],
            [
                'id'    => 129,
                'title' => 'application_score_create',
            ],
            [
                'id'    => 130,
                'title' => 'application_score_edit',
            ],
            [
                'id'    => 131,
                'title' => 'application_score_show',
            ],
            [
                'id'    => 132,
                'title' => 'application_score_delete',
            ],
            [
                'id'    => 133,
                'title' => 'application_score_access',
            ],
            [
                'id'    => 134,
                'title' => 'application_schedule_create',
            ],
            [
                'id'    => 135,
                'title' => 'application_schedule_edit',
            ],
            [
                'id'    => 136,
                'title' => 'application_schedule_show',
            ],
            [
                'id'    => 137,
                'title' => 'application_schedule_delete',
            ],
            [
                'id'    => 138,
                'title' => 'application_schedule_access',
            ],
            [
                'id'    => 139,
                'title' => 'ruang_create',
            ],
            [
                'id'    => 140,
                'title' => 'ruang_edit',
            ],
            [
                'id'    => 141,
                'title' => 'ruang_show',
            ],
            [
                'id'    => 142,
                'title' => 'ruang_delete',
            ],
            [
                'id'    => 143,
                'title' => 'ruang_access',
            ],
            [
                'id'    => 144,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}

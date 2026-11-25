<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\GenderData;
use App\Models\KaryaIlmiah;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render_and_admin_pages_require_login(): void
    {
        foreach ([
            '/', '/tentang-psga', '/visi-misi', '/karya-ilmiah', '/gender',
            '/pengaduan', '/pengaduan/create', '/tracking', '/kontak', '/login',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        foreach ([
            '/admin/dashboard', '/admin/berita', '/admin/berita/create', '/admin/berita/arsip',
            '/admin/karya-ilmiah', '/admin/karya-ilmiah/create',
            '/admin/gender', '/admin/gender/create', '/pengaduan/admin',
        ] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }

    public function test_admin_login_and_admin_pages(): void
    {
        $user = User::factory()->create([
            'username' => 'admin-qa',
            'password' => 'password123',
        ]);

        $this->post('/login', ['username' => 'admin-qa', 'password' => 'wrong-password'])
            ->assertSessionHasErrors('username');
        $this->post('/login', ['username' => 'admin-qa', 'password' => 'password123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($user);
        foreach ([
            '/admin/dashboard', '/admin/berita', '/admin/berita/create', '/admin/berita/arsip',
            '/admin/karya-ilmiah', '/admin/karya-ilmiah/create',
            '/admin/gender', '/admin/gender/create', '/pengaduan/admin',
        ] as $path) {
            $this->get($path)->assertOk();
        }

        $this->post('/logout')->assertRedirect('/login');
    }

    public function test_news_create_publication_archive_restore_and_delete(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/admin/berita', [
            'judul' => 'Kabar Uji Coba',
            'ringkasan' => 'Ringkasan berita uji coba',
            'isi' => 'Isi berita uji coba',
            'kategori' => 'Kampus',
            'status' => 'terbit',
        ])->assertRedirect(route('berita.index'));

        $berita = Berita::firstOrFail();
        $this->get('/')->assertSee('Kabar Uji Coba');
        $this->get('/berita/' . $berita->slug)->assertOk()->assertSee('Isi berita uji coba');
        $this->get('/admin/berita')->assertOk()->assertSee('Kabar Uji Coba');

        $this->patch('/admin/berita/' . $berita->id . '/arsipkan')
            ->assertRedirect(route('berita.index'));
        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'status' => 'arsip']);
        $this->get('/admin/berita/arsip')->assertOk()->assertSee('Kabar Uji Coba');

        $this->patch('/admin/berita/' . $berita->id . '/pulihkan')
            ->assertRedirect(route('berita.arsip'));
        $this->assertDatabaseHas('berita', ['id' => $berita->id, 'status' => 'terbit']);

        $this->delete('/admin/berita/' . $berita->id)->assertRedirect(route('berita.index'));
        $this->assertDatabaseMissing('berita', ['id' => $berita->id]);
    }

    public function test_karya_ilmiah_crud_search_and_exports(): void
    {
        $this->actingAs(User::factory()->create());
        $payload = [
            'nama_karya' => 'Riset Gender PSGA',
            'nama_penulis' => 'Penulis Uji',
            'jenis_publikasi_pilihan' => 'Jurnal',
            'tahun_publikasi' => 2024,
            'deskripsi' => 'Riset kesetaraan gender',
            'link' => 'https://example.org/riset',
        ];

        $this->post('/admin/karya-ilmiah', $payload)
            ->assertRedirect(route('admin.karya-ilmiah.index'));
        $karya = KaryaIlmiah::firstOrFail();

        $this->get('/karya-ilmiah?q=Riset')->assertOk()->assertSee('Riset Gender PSGA');
        $this->get('/admin/karya-ilmiah')->assertOk()->assertSee('Riset Gender PSGA');
        $this->get('/admin/karya-ilmiah/' . $karya->id . '/edit')->assertOk();

        $this->put('/admin/karya-ilmiah/' . $karya->id, [
            ...$payload,
            'nama_karya' => 'Riset Gender Terbaru',
        ])->assertRedirect(route('admin.karya-ilmiah.index'));
        $this->assertDatabaseHas('karya_ilmiah', [
            'id' => $karya->id,
            'nama_karya' => 'Riset Gender Terbaru',
        ]);

        $pdfResponse = $this->get('/admin/karya-ilmiah/export/pdf')->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('UTC', $pdfResponse->getContent());
        $this->assertStringContainsString('Tanggal Ditambahkan \\(WIB\\)', $pdfResponse->getContent());
        $this->get('/admin/karya-ilmiah/export/excel')->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->delete('/admin/karya-ilmiah/' . $karya->id)
            ->assertRedirect(route('admin.karya-ilmiah.index'));
        $this->assertDatabaseMissing('karya_ilmiah', ['id' => $karya->id]);
    }

    public function test_gender_hierarchy_publish_and_delete(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/admin/gender', [
            'name' => 'Universitas', 'male_count' => 100, 'female_count' => 100,
            'description' => 'Data utama', 'sort_order' => 0, 'is_published' => 0,
        ])->assertRedirect(route('admin.gender.index'));
        $university = GenderData::firstOrFail();

        $this->post('/admin/gender', [
            'name' => 'Fakultas Uji', 'parent_id' => $university->id,
            'male_count' => 20, 'female_count' => 30, 'description' => null,
            'sort_order' => 1, 'is_published' => 0,
        ])->assertRedirect(route('admin.gender.index'));
        $faculty = GenderData::where('name', 'Fakultas Uji')->firstOrFail();

        $this->get('/gender')->assertDontSee('Fakultas Uji');
        $this->get('/admin/gender')->assertOk()->assertSee('Publikasikan');
        $this->get('/admin/gender/' . $faculty->id . '/edit')->assertOk();
        $this->patch('/admin/gender/' . $faculty->id . '/publish')
            ->assertRedirect(route('admin.gender.index'));
        $this->assertDatabaseHas('gender_data', ['id' => $faculty->id, 'is_published' => 1]);
        $this->assertDatabaseHas('gender_data', ['id' => $university->id, 'is_published' => 1]);
        $this->get('/gender')->assertOk()->assertSee('Fakultas Uji');

        $this->put('/admin/gender/' . $faculty->id, [
            'name' => 'Fakultas Uji Diperbarui', 'parent_id' => $university->id,
            'male_count' => 25, 'female_count' => 35, 'description' => 'Updated',
            'sort_order' => 1, 'is_published' => 1,
        ])->assertRedirect(route('admin.gender.index'));
        $this->assertDatabaseHas('gender_data', ['id' => $faculty->id, 'name' => 'Fakultas Uji Diperbarui']);

        $this->delete('/admin/gender/' . $faculty->id)->assertRedirect(route('admin.gender.index'));
        $this->assertDatabaseMissing('gender_data', ['id' => $faculty->id]);
    }

    public function test_complaint_submission_tracking_status_update_and_exports(): void
    {
        $this->get('/tracking')->assertOk();
        $this->post('/pengaduan', [
            'nama_pelapor' => 'Pelapor Uji',
            'terlapor' => 'Terlapor Uji',
            'email_pelapor' => 'pelapor@example.test',
            'no_hp' => '08123456789',
            'fakultas' => 'Fakultas Uji',
            'kategori_pengaduan' => 'Kekerasan',
            'isi_pengaduan' => 'Isi laporan uji',
        ])->assertRedirect();

        $pengaduan = Pengaduan::firstOrFail();
        $this->assertSame('PSGA-' . str_pad((string) $pengaduan->id, 3, '0', STR_PAD_LEFT), $pengaduan->no_ticket);
        $this->get('/tracking?no_ticket=' . $pengaduan->no_ticket)->assertOk()->assertSee('Pelapor Uji');
        $this->get('/pengaduan')->assertOk()->assertSee('Form Pengaduan PSGA');

        $this->actingAs(User::factory()->create());
        $this->get('/pengaduan/admin')->assertOk()->assertSee('Pelapor Uji');
        $this->get('/pengaduan/' . $pengaduan->id . '/edit')->assertOk();
        $this->put('/pengaduan/' . $pengaduan->id, [
            'status' => 'Verifikasi', 'tanggapan' => 'Sedang diverifikasi',
        ])->assertRedirect(route('pengaduan.admin'));
        $this->assertDatabaseHas('pengaduans', ['id' => $pengaduan->id, 'status' => 'Verifikasi']);

        $this->get('/pengaduan/admin/export/pdf')->assertOk()
            ->assertHeader('content-type', 'application/pdf');
        $this->get('/pengaduan/admin/export/excel')->assertOk();
    }
}

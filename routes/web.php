<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

// =========================================================================
// CONTROLLER IMPORTS
// =========================================================================

// --- GENERAL ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\NotificationController;

// --- ADMIN ---
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManageUserController;
use App\Http\Controllers\Admin\AdminRekapitulasiProposalController;
use App\Http\Controllers\Admin\AdminListStatistikController;
use App\Http\Controllers\Admin\AdminUploadPanduanController;
use App\Http\Controllers\Admin\AdminDetailProposalController;
use App\Http\Controllers\Admin\AdminCustomizationController;


// --- DOSEN ---
use App\Http\Controllers\Dosen\DosenDashboardController;
use App\Http\Controllers\Dosen\DosenDetailProposalController;
use App\Http\Controllers\Dosen\DosenPengajuanSkemaDanForm;
use App\Http\Controllers\Dosen\DosenPengajuanProposalController;
use App\Http\Controllers\Dosen\DosenLampiranProposalController;
use App\Http\Controllers\Dosen\DosenCekAnggotaProposal;
use App\Http\Controllers\Dosen\DosenKonfirmasiAnggotaController;
use App\Http\Controllers\Dosen\DosenListStatistikController;

// --- WAKIL DEKAN 3 ---
use App\Http\Controllers\WakilDekan3\WakilDekan3DashboardController;
use App\Http\Controllers\WakilDekan3\WakilDekan3ValidasiController;
use App\Http\Controllers\WakilDekan3\WakilDekan3DetailProposalController;
use App\Http\Controllers\WakilDekan3\WakilDekan3ListStatistikController;

// --- DEKAN ---
use App\Http\Controllers\Dekan\DekanDashboardController;
use App\Http\Controllers\Dekan\DekanMonitoringController;
use App\Http\Controllers\Dekan\DekanListStatistikController;
use App\Http\Controllers\Dekan\DekanDetailProposalController;

// --- KEPALA PUSAT ---
use App\Http\Controllers\KepalaPusat\KepalaPusatDashboardController;
use App\Http\Controllers\KepalaPusat\KepalaPusatValidasiController;
use App\Http\Controllers\KepalaPusat\KepalaPusatDetailProposalController;
use App\Http\Controllers\KepalaPusat\KepalaPusatListStatistikController;

// --- WAKIL REKTOR 3 ---
use App\Http\Controllers\WakilRektor3\WakilRektor3DashboardController;
use App\Http\Controllers\WakilRektor3\WakilRektor3ValidasiController;
use App\Http\Controllers\WakilRektor3\WakilRektor3DetailProposalController;
use App\Http\Controllers\WakilRektor3\WakilRektor3ListStatistikController;


// =========================================================================
// PUBLIC ROUTES
// =========================================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================================
// AUTHENTICATED ROUTES
// =========================================================================
Route::middleware('auth')->group(function () {

    // --- UTILITY & PROFILE ---
    Route::get('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark_all_read');
    Route::get('/notification/read/{id}', [NotificationController::class, 'readAndRedirect'])->name('notification.read');
    Route::post('/profile/upload-signature-ajax', [ProfileController::class, 'uploadSignatureAjax'])
        ->name('profile.upload_signature_ajax');
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
    });
    Route::post('/notify-dekan-ttd', [WakilDekan3ValidasiController::class, 'notifyDekanUploadTTD'])
        ->name('wakil_dekan3.notify.dekan');

    // --- API CHECKER ---
    Route::get('/api/ldap-check', [DosenCekAnggotaProposal::class, 'checkId'])->name('api.ldap_check');

    // =====================================================================
    // SHARED ACTIONS (Digunakan oleh Admin/Wadek/Kapus/Warek untuk Approval)
    // =====================================================================
    Route::controller(ProposalController::class)->group(function () {
        Route::post('/proposal/{id}/approve', 'approve')->name('proposal.approve');
        Route::post('/proposal/{id}/reject', 'reject')->name('proposal.reject');
    });

    Route::get('/image-view/{filename}', function ($filename) {
        // Sesuaikan path ini dengan struktur folder di storage Anda
        // Biasanya ada di: storage/app/public/tanda_tangan/
        $path = storage_path('app/public/tanda_tangan/' . $filename);
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = File::get($path);
        $type = File::mimeType($path);
    
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
    
        return $response;
    })->name('storage.view');

    

    // =====================================================================
    // 1. ADMIN SECTION
    // =====================================================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Detail Proposal (View Only / Rekap)
        Route::get('/rekapitulasi/detail/{id}', [AdminDetailProposalController::class, 'show'])->name('rekapitulasi.detail');

        // CUSTOMIZATION HUB
        Route::get('/customization', [AdminCustomizationController::class, 'index'])->name('customization');

        // 1. General Settings
        Route::get('/customization/general', [AdminCustomizationController::class, 'general'])->name('customization.general');
        Route::post('/customization/general', [AdminCustomizationController::class, 'update'])->name('customization.update');
        
        // 2. Skema & Skala Settings (UPDATE BAGIAN INI)
        Route::get('/customization/skema', [AdminCustomizationController::class, 'skema'])->name('customization.skema');
        Route::put('/customization/skala/{id}', [AdminCustomizationController::class, 'updateSkala'])->name('customization.skala.update');
        Route::delete('/customization/skala/{id}', [AdminCustomizationController::class, 'destroySkala'])->name('customization.skala.delete');
        
        // Route untuk Tambah SKALA (Kategori) - INI YANG HILANG
        Route::post('/customization/skala', [AdminCustomizationController::class, 'storeSkala'])->name('customization.skala.store'); 

        // Route untuk Tambah SKEMA (Item)
        Route::post('/customization/skema', [AdminCustomizationController::class, 'storeSkema'])->name('customization.skema.store');
        
        // Route Hapus
        Route::delete('/customization/skema/{id}', [AdminCustomizationController::class, 'destroySkema'])->name('customization.skema.delete');
        Route::put('/customization/skema/{id}', [AdminCustomizationController::class, 'updateSkema'])->name('customization.skema.update');

        // ROUTE Custom PRODI
        Route::get('/customization/prodi', [AdminCustomizationController::class, 'prodi'])->name('customization.prodi');
        Route::post('/customization/prodi', [AdminCustomizationController::class, 'storeProdi'])->name('customization.prodi.store');
        Route::put('/customization/prodi/{id}', [AdminCustomizationController::class, 'updateProdi'])->name('customization.prodi.update');
        Route::delete('/customization/prodi/{id}', [AdminCustomizationController::class, 'destroyProdi'])->name('customization.prodi.delete');

        // Customization Profile Attributes (Fakultas & Jabatan)
        Route::get('/customization/profile', [AdminCustomizationController::class, 'profileAttributes'])->name('customization.profile');
        
        // Fakultas
        Route::post('/customization/fakultas', [AdminCustomizationController::class, 'storeFakultas'])->name('customization.fakultas.store');
        Route::delete('/customization/fakultas/{id}', [AdminCustomizationController::class, 'destroyFakultas'])->name('customization.fakultas.delete');
        
        // Prodi (Anak Fakultas)
        Route::post('/customization/fakultas-prodi', [AdminCustomizationController::class, 'storeFakultasProdi'])->name('customization.fakultas_prodi.store');
        Route::delete('/customization/fakultas-prodi/{id}', [AdminCustomizationController::class, 'destroyFakultasProdi'])->name('customization.fakultas_prodi.delete');

        // Jabatan
        Route::post('/customization/jabatan', [AdminCustomizationController::class, 'storeJabatan'])->name('customization.jabatan.store');
        Route::delete('/customization/jabatan/{id}', [AdminCustomizationController::class, 'destroyJabatan'])->name('customization.jabatan.delete');

        // Manajemen User
        Route::controller(AdminManageUserController::class)->group(function () {
            Route::get('/manajemen-user', 'index')->name('manajemen_user');
            Route::post('/manajemen-user', 'store')->name('manajemen_user.store');
            Route::put('/manajemen-user/{user}', 'update')->name('manajemen_user.update');
            Route::delete('/manajemen-user/{user}', 'destroy')->name('manajemen_user.destroy');
        });

        // Rekapitulasi & Export
        Route::controller(AdminRekapitulasiProposalController::class)->group(function () {
            Route::get('/rekapitulasi', 'index')->name('rekapitulasi');
            Route::patch('/rekapitulasi/{id}/keputusan', 'updateKeputusan')->name('rekapitulasi.keputusan');
            Route::get('/rekapitulasi/export-excel', 'exportExcel')->name('rekap.export');
        });

        // Statistik & Panduan
        Route::get('/statistik/{kategori}', [AdminListStatistikController::class, 'list'])->name('statistik.list');
        Route::post('/panduan/store', [AdminUploadPanduanController::class, 'store'])->name('panduan.store');
    });

    // =====================================================================
    // 2. DOSEN SECTION
    // =====================================================================
    Route::prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');

        // Pengajuan
        Route::controller(DosenPengajuanSkemaDanForm::class)->group(function () {
            Route::get('/pengajuan/skema', 'showSkema')->name('pengajuan.skema');
            Route::get('/pengajuan/form/{year}/{skemaId}/{role}', 'showForm')->name('pengajuan.form');
        });
        Route::post('/tesproposal/store', [DosenPengajuanProposalController::class, 'store'])->name('tesproposal.store');
        Route::post('/pengajuan/lampiran/{proposal_id}', [DosenLampiranProposalController::class, 'store'])->name('lampiran.store');

        // Konfirmasi Anggota
        Route::controller(DosenKonfirmasiAnggotaController::class)->group(function () {
            Route::get('/anggota_request', 'index')->name('anggota_request');
            Route::post('/anggota_request/{id}/terima', 'terima')->name('anggota.terima');
            Route::post('/anggota_request/{id}/tolak', 'tolak')->name('anggota.tolak');
        });

        // Detail & Export
        Route::controller(DosenDetailProposalController::class)->group(function () {
            Route::get('/proposal/{id}/detail', 'detail')->name('detail_proposal');
            Route::get('/proposal/{id}/export-pdf', 'exportPdf')->name('proposal.export_pdf');
        });

        // Statistik
        Route::get('/statistik/{kategori}', [DosenListStatistikController::class, 'showList'])->name('statistik.list');
    });

    // =====================================================================
    // 3. WAKIL DEKAN 3 SECTION (FIXED & STANDARDIZED)
    // =====================================================================
    Route::prefix('wakil_dekan3')->name('wakil_dekan3.')->group(function () {
        
        // Dashboard
    Route::get('/dashboard', [WakilDekan3DashboardController::class, 'index'])->name('dashboard');

    // Group Route Controller Validasi
    Route::controller(WakilDekan3ValidasiController::class)->group(function () {
        
        // 1. Halaman List Validasi (index)
        Route::get('/validasi', 'index')->name('validasi');
        
        // 2. Aksi Terima/Tolak Proposal
        Route::patch('/validasi/{id}/keputusan', 'updateKeputusan')->name('validasi.keputusan');

        // 3. Aksi Ingatkan Dekan (Notifikasi)
        Route::post('/notify/dekan', 'notifyDekanUploadTTD')->name('notify.dekan');
    });

    // Detail Proposal (Controller Terpisah)
    Route::get('/validasi/{id}/detail', [WakilDekan3DetailProposalController::class, 'show'])->name('validasi.detail');

    // Statistik (Controller Terpisah)
    Route::get('/statistik/{kategori}', [WakilDekan3ListStatistikController::class, 'showList'])->name('statistik.list');
        // Notifikasi ke Dekan (Opsional, jika tombol 'Ingatkan Dekan' digunakan)
        // Route::post('/notify-dekan', [WakilDekan3DetailProposalController::class, 'notifyDekan'])->name('notify.dekan');
    });

    // =====================================================================
    // 4. KEPALA PUSAT SECTION (FIXED & STANDARDIZED)
    // =====================================================================
    Route::prefix('kepala_pusat')->name('kepala_pusat.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [KepalaPusatDashboardController::class, 'index'])->name('dashboard');

        // Validasi (List & Aksi)
        Route::controller(KepalaPusatValidasiController::class)->group(function () {
            Route::get('/validasi', 'index')->name('validasi'); // Pastikan method di controller bernama 'index' atau sesuaikan
            Route::patch('/validasi/{id}/keputusan', 'updateKeputusan')->name('validasi.keputusan');
        });

        // Detail Proposal (Controller Khusus)
        Route::get('/validasi/{id}/detail', [KepalaPusatDetailProposalController::class, 'show'])->name('validasi.detail');

        // Statistik (Controller Khusus)
        Route::get('/statistik/{kategori}', [KepalaPusatListStatistikController::class, 'showList'])->name('statistik.list');
    });

    // =====================================================================
    // 5. WAKIL REKTOR 3 SECTION (FIXED & STANDARDIZED)
    // =====================================================================
    Route::prefix('wakil_rektor')->name('wakil_rektor.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [WakilRektor3DashboardController::class, 'index'])->name('dashboard');

        // Validasi (List & Aksi)
        Route::controller(WakilRektor3ValidasiController::class)->group(function () {
            Route::get('/validasi', 'index')->name('validasi'); // Pastikan method di controller bernama 'index'
            Route::patch('/validasi/{id}/keputusan', 'updateKeputusan')->name('validasi.keputusan');
        });

        // Detail Proposal (Controller Khusus)
        Route::get('/validasi/{id}/detail', [WakilRektor3DetailProposalController::class, 'show'])->name('validasi.detail');

        // Statistik (Controller Khusus)
        Route::get('/statistik/{kategori}', [WakilRektor3ListStatistikController::class, 'showList'])->name('statistik.list');
    });

    // =====================================================================
    // 6. DEKAN SECTION
    // =====================================================================
    Route::prefix('dekan')->name('dekan.')->group(function () {
        Route::get('/dashboard', [DekanDashboardController::class, 'index'])->name('dashboard');
        
        Route::controller(DekanMonitoringController::class)->group(function () {
            Route::get('/monitoring', 'index')->name('monitoring');
            Route::get('/monitoring/{id}/detail', 'detail')->name('monitoring.detail');
        });

        Route::get('/monitoring/{id}/detail', [DekanDetailProposalController::class, 'show'])->name('monitoring.detail');
        // [PERBAIKAN]: Arahkan ke DekanListStatistikController
        Route::get('/statistik/{kategori}', [DekanListStatistikController::class, 'showList'])->name('statistik.list');
    });

});

require __DIR__ . '/auth.php';
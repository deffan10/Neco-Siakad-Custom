<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Akademik\DataMahasiswa;
use App\Models\Akademik\JadwalPertemuan;
use App\Models\Akademik\KegiatanWisuda;
use App\Models\Akademik\PendaftaranWisuda;
use App\Models\Akademik\ProgramStudi;
use App\Models\Akademik\TahunAkademik;
use App\Models\Akademik\EventSeminarPeserta;
use App\Models\Keuangan\TagihanMahasiswa;
use App\Models\Keuangan\PembayaranMahasiswa;
use App\Models\User;

class ExportController extends Controller
{
    /* =========================================================
     *  EXPORT DATA MAHASISWA (EXCEL)
     * ========================================================= */

    public function mahasiswaExcel(Request $request)
    {
        $query = User::role('mahasiswa')
            ->with(['dataMahasiswa.programStudi', 'dataMahasiswa.statusMahasiswa'])
            ->latest();

        if ($request->filled('prodi_id')) {
            $query->whereHas('dataMahasiswa', fn($q) => $q->where('program_studi_id', $request->prodi_id));
        }
        if ($request->filled('angkatan')) {
            $query->whereHas('dataMahasiswa', fn($q) => $q->where('angkatan', $request->angkatan));
        }

        $rows = $query->get()->map(fn($u) => [
            'NIM'             => $u->dataMahasiswa->nim ?? '-',
            'Nama Lengkap'    => $u->name,
            'Email'           => $u->email,
            'Program Studi'   => $u->dataMahasiswa?->programStudi?->name ?? '-',
            'Angkatan'        => $u->dataMahasiswa?->angkatan ?? '-',
            'Status'          => $u->dataMahasiswa?->statusMahasiswa?->name ?? '-',
            'IPK'             => $u->dataMahasiswa?->ipk ?? '-',
            'SKS Lulus'       => $u->dataMahasiswa?->sks_lulus ?? '-',
            'Tanggal Masuk'   => $u->dataMahasiswa?->tanggal_masuk ?? '-',
        ]);

        $filename = 'data_mahasiswa_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  EXPORT TAGIHAN & KEUANGAN (EXCEL)
     * ========================================================= */

    public function keuanganExcel(Request $request)
    {
        $query = TagihanMahasiswa::with(['mahasiswa', 'tahunAkademik'])
            ->latest();

        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn($t) => [
            'Nama Mahasiswa'    => $t->mahasiswa?->name ?? '-',
            'Email'             => $t->mahasiswa?->email ?? '-',
            'Tahun Akademik'    => $t->tahunAkademik?->name ?? '-',
            'Nama Tagihan'      => $t->nama_tagihan ?? '-',
            'Nominal (Rp)'      => number_format($t->nominal ?? 0, 0, ',', '.'),
            'Status'            => $t->status ?? '-',
            'Jatuh Tempo'       => $t->tanggal_jatuh_tempo ?? '-',
            'Tanggal Bayar'     => $t->tanggal_bayar ?? '-',
        ]);

        $filename = 'rekap_keuangan_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  EXPORT JURNAL/ABSENSI DOSEN (EXCEL)
     * =========================================================  */

    public function jurnalDosenExcel(Request $request)
    {
        $query = JadwalPertemuan::with([
            'jadwalPerkuliahan.mataKuliah',
            'jadwalPerkuliahan.dosen',
        ])->latest();

        if ($request->filled('tahun_akademik_id')) {
            $query->whereHas('jadwalPerkuliahan', fn($q) => $q->where('tahun_akademik_id', $request->tahun_akademik_id));
        }

        $rows = $query->get()->map(fn($jp) => [
            'Mata Kuliah'      => $jp->jadwalPerkuliahan?->mataKuliah?->name ?? '-',
            'Dosen'            => $jp->jadwalPerkuliahan?->dosen?->name ?? '-',
            'Pertemuan Ke'     => $jp->pertemuan_ke ?? '-',
            'Tanggal'          => $jp->tanggal ?? '-',
            'Materi'           => $jp->materi ?? '-',
            'Status'           => $jp->is_locked ? 'Dikunci' : 'Aktif',
            'Diisi Pada'       => $jp->updated_at?->format('d/m/Y H:i') ?? '-',
        ]);

        $filename = 'jurnal_dosen_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  EXPORT PESERTA WISUDA (EXCEL)
     * ========================================================= */

    public function wisudaExcel(Request $request)
    {
        $query = PendaftaranWisuda::with([
            'mahasiswa',
            'kegiatanWisuda',
        ])->latest();

        if ($request->filled('kegiatan_wisuda_id')) {
            $query->where('kegiatan_wisuda_id', $request->kegiatan_wisuda_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn($p) => [
            'Nama Mahasiswa'  => $p->mahasiswa?->name ?? '-',
            'Email'           => $p->mahasiswa?->email ?? '-',
            'Kegiatan Wisuda' => $p->kegiatanWisuda?->nama_wisuda ?? '-',
            'Status'          => $p->status ?? '-',
            'IPK'             => $p->ipk ?? '-',
            'Judul Skripsi'   => $p->judul_skripsi ?? '-',
            'Tanggal Daftar'  => $p->created_at?->format('d/m/Y') ?? '-',
        ]);

        $filename = 'peserta_wisuda_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  EXPORT PESERTA SEMINAR (EXCEL)
     * ========================================================= */

    public function seminarPesertaExcel(Request $request, $eventId)
    {
        $rows = EventSeminarPeserta::where('event_id', $eventId)
            ->with('mahasiswa')
            ->get()
            ->map(fn($p) => [
                'Nama Mahasiswa' => $p->mahasiswa?->name ?? '-',
                'Email'          => $p->mahasiswa?->email ?? '-',
                'Status Hadir'   => $p->status ?? '-',
                'Catatan'        => $p->catatan ?? '-',
                'Waktu Daftar'   => $p->created_at?->format('d/m/Y H:i') ?? '-',
            ]);

        $filename = 'peserta_seminar_event' . $eventId . '_' . now()->format('Ymd') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  LAPORAN AKADEMIK MAHASISWA (PDF)
     * ========================================================= */

    public function mahasiswaPdf(Request $request)
    {
        $query = User::role('mahasiswa')
            ->with(['dataMahasiswa.programStudi', 'dataMahasiswa.statusMahasiswa'])
            ->latest();

        if ($request->filled('prodi_id')) {
            $query->whereHas('dataMahasiswa', fn($q) => $q->where('program_studi_id', $request->prodi_id));
        }
        if ($request->filled('angkatan')) {
            $query->whereHas('dataMahasiswa', fn($q) => $q->where('angkatan', $request->angkatan));
        }

        $mahasiswas   = $query->get();
        $prodis       = ProgramStudi::all();
        $filterProdi  = $request->prodi_id ? ProgramStudi::find($request->prodi_id)?->name : 'Semua Prodi';
        $filterAngkatan = $request->angkatan ?: 'Semua Angkatan';

        $pdf = Pdf::loadView('exports.mahasiswa-pdf', compact('mahasiswas', 'filterProdi', 'filterAngkatan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan_mahasiswa_' . now()->format('Ymd_His') . '.pdf');
    }

    /* =========================================================
     *  EXPORT PMB (EXCEL)
     * ========================================================= */

    public function pmbExcel(Request $request)
    {
        $query = \App\Models\Akademik\DataPestaPMB::with('user')->latest();

        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        $rows = $query->get()->map(fn($p) => [
            'No. Pendaftaran'   => $p->nomor_pendaftaran ?? '-',
            'Nama'              => $p->user?->name ?? '-',
            'Email'             => $p->user?->email ?? '-',
            'Pilihan Prodi 1'   => $p->program_pilihan_1 ?? '-',
            'Pilihan Prodi 2'   => $p->program_pilihan_2 ?? '-',
            'Jalur Masuk'       => $p->jalur_masuk ?? '-',
            'Tahun Masuk'       => $p->tahun_masuk ?? '-',
            'Status Pendaftaran'=> $p->status_pendaftaran ?? '-',
            'Nilai Tes Tulis'   => $p->nilai_tes_tulis ?? '-',
            'Nilai Wawancara'   => $p->nilai_wawancara ?? '-',
            'Nilai Akhir'       => $p->nilai_akhir ?? '-',
            'Tanggal Daftar'    => $p->tanggal_daftar ?? '-',
        ]);

        $filename = 'data_pmb_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  EXPORT SERTIFIKASI (EXCEL)
     * ========================================================= */

    public function sertifikasiExcel(Request $request)
    {
        $query = \App\Models\Akademik\SertifikasiMahasiswa::with('mahasiswa')->latest();

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $rows = $query->get()->map(fn($s) => [
            'Nama Mahasiswa'   => $s->mahasiswa?->name ?? '-',
            'Email'            => $s->mahasiswa?->email ?? '-',
            'Nama Sertifikat'  => $s->nama_sertifikat,
            'Lembaga Penerbit' => $s->lembaga_penerbit,
            'Nomor Sertifikat' => $s->nomor_sertifikat ?? '-',
            'Kategori'         => $s->kategori,
            'Tanggal Terbit'   => $s->tanggal_terbit ?? '-',
            'Kadaluarsa'       => $s->tanggal_kadaluarsa ?? '-',
            'Status Verifikasi'=> $s->status_verifikasi,
        ]);

        $filename = 'sertifikasi_mahasiswa_' . now()->format('Ymd_His') . '.xlsx';
        return (new FastExcel($rows))->download($filename);
    }

    /* =========================================================
     *  HALAMAN EXPORT TERPUSAT (Admin)
     * ========================================================= */

    public function index()
    {
        $user = Auth::user();
        $data['activeRole'] = session('active_role') ?? 'admin';
        $data['menus']   = 'Export & Laporan';
        $data['pages']   = 'Export Data & Cetak Laporan';

        $data['tahunAkademiks']   = TahunAkademik::orderBy('tanggal_mulai', 'desc')->get();
        $data['prodis']           = ProgramStudi::orderBy('name')->get();
        $data['angkatans']        = DataMahasiswa::distinct()->whereNotNull('angkatan')->orderBy('angkatan', 'desc')->pluck('angkatan');
        $data['kegiatanWisudas']  = KegiatanWisuda::orderBy('tanggal_pelaksanaan', 'desc')->get();

        return view('master.akademik.export-index', $data, compact('user'));
    }
}

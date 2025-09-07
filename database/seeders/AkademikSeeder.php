<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Str;

class AkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataTahunAkademik = 
        [
            [
                'name' => 'Tahun Akademik 2023/2024 Ganjil',
                'code' => 'TA20232024G',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2023-09-01',
                'tanggal_selesai' => Carbon::parse('2023-09-01')->addMonths(6)->format('Y-m-d'),
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Tahun Akademik 2023/2024 Genap',
                'code' => 'TA20232024G',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2024-02-01',
                'tanggal_selesai' => Carbon::parse('2024-02-01')->addMonths(6)->format('Y-m-d'),
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Tahun Akademik 2024/2025 Ganjil',
                'code' => 'TA20232024G',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => Carbon::parse('2024-09-01')->addMonths(6)->format('Y-m-d'),
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Tahun Akademik 2024/2025 Ganjil',
                'code' => 'TA20232024G',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2025-02-01',
                'tanggal_selesai' => Carbon::parse('2025-02-01')->addMonths(6)->format('Y-m-d'),
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

        ];

        $takaIds = [];
        foreach($dataTahunAkademik as $data){
            $taka = \App\Models\Akademik\TahunAkademik::create($data);
            $takaIds[] = $taka->id;
        }

        $dataFakultas = [
            [
                'name' => 'Fakultas Ilmu Komputer',
                'code' => 'FIK',
                'nama_singkat' => 'FIK',
                'akreditasi' => 'A',
                'tanggal_akreditasi' => '2023-01-01',
                'sk_pendirian' => 'SK-FIK-001',
                'tanggal_sk_pendirian' => '2020-01-01',
                'dekan_id' => null,
                'sekretaris_id' => null,
                'email' => 'fik@universitas.ac.id',
                'telepon' => '021-123456',
                'alamat' => 'Gedung Utama Lt. 1',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'code' => 'FEB',
                'nama_singkat' => 'FEB',
                'akreditasi' => 'A',
                'tanggal_akreditasi' => '2023-01-01',
                'sk_pendirian' => 'SK-FEB-001',
                'tanggal_sk_pendirian' => '2020-01-01',
                'dekan_id' => null,
                'sekretaris_id' => null,
                'email' => 'feb@universitas.ac.id',
                'telepon' => '021-123456',
                'alamat' => 'Gedung Utama Lt. 1',
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        $fakultasIds = [];
        foreach($dataFakultas as $data){
            $fakultas = \App\Models\Akademik\Fakultas::create($data);
            $fakultasIds[] = $fakultas->id;
        }

        $datafakultasProfile = [
            [
                'fakultas_id' => $fakultasIds[0],
                'slug' => Str::slug(\App\Models\Akademik\Fakultas::find($fakultasIds[0])->name),
            ],
            [
                'fakultas_id' => $fakultasIds[1],
                'slug' => Str::slug(\App\Models\Akademik\Fakultas::find($fakultasIds[1])->name),
            ],
        ];

        foreach($datafakultasProfile as $data){
            $fakultasProfile = \App\Models\Akademik\FakultasProfile::create($data);
        }

        $dataProdi = [
            [
                'fakultas_id' => $fakultasIds[0],
                'name' => 'Teknik Informatika',
                'code' => 'TI',
                'nama_singkat' => 'TI',
                'akreditasi' => 'A',
                'tanggal_akreditasi' => '2023-05-15',
                'sk_pendirian' => 'SK-TI-001',
                'tanggal_sk_pendirian' => '2018-08-20',
                'jenjang' => 'S1',
                'gelar_depan' => null,
                'gelar_belakang' => 'S.Kom',
                'kaprodi_id' => null,
                'sekretaris_id' => null,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'fakultas_id' => $fakultasIds[0],
                'name' => 'Sistem Informasi',
                'code' => 'SI',
                'nama_singkat' => 'SI',
                'akreditasi' => 'A',
                'tanggal_akreditasi' => '2023-05-15',
                'sk_pendirian' => 'SK-SI-001',
                'tanggal_sk_pendirian' => '2018-08-20',
                'jenjang' => 'S1',
                'gelar_depan' => null,
                'gelar_belakang' => 'S.Kom',
                'kaprodi_id' => null,
                'sekretaris_id' => null,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'fakultas_id' => $fakultasIds[1],
                'name' => 'Ekonomi Syariah',
                'code' => 'ES',
                'nama_singkat' => 'ES',
                'akreditasi' => 'A',
                'tanggal_akreditasi' => '2023-05-15',
                'sk_pendirian' => 'SK-ES-001',
                'tanggal_sk_pendirian' => '2018-08-20',
                'jenjang' => 'S1',
                'gelar_depan' => null,
                'gelar_belakang' => 'S.E.I',
                'kaprodi_id' => null,
                'sekretaris_id' => null,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'fakultas_id' => $fakultasIds[1],
                'name' => 'Bisnis Digital',
                'code' => 'BD',
                'nama_singkat' => 'BD',
                'akreditasi' => 'B',
                'tanggal_akreditasi' => '2023-05-15',
                'sk_pendirian' => 'SK-BD-001',
                'tanggal_sk_pendirian' => '2018-08-20',
                'jenjang' => 'S1',
                'gelar_depan' => null,
                'gelar_belakang' => 'S.E',
                'kaprodi_id' => null,
                'sekretaris_id' => null,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        $prodiIds = [];
        foreach($dataProdi as $data){
            $prodi = \App\Models\Akademik\ProgramStudi::create($data);
            $prodiIds[] = $prodi->id;
        }

        $dataprodiProfile = [
            [
                'program_studi_id' => $prodiIds[0],
                'slug' => Str::slug(\App\Models\Akademik\ProgramStudi::find($prodiIds[0])->name),
            ],
            [
                'program_studi_id' => $prodiIds[1],
                'slug' => Str::slug(\App\Models\Akademik\ProgramStudi::find($prodiIds[1])->name),
            ],
            [
                'program_studi_id' => $prodiIds[2],
                'slug' => Str::slug(\App\Models\Akademik\ProgramStudi::find($prodiIds[2])->name),
            ],
            [
                'program_studi_id' => $prodiIds[3],
                'slug' => Str::slug(\App\Models\Akademik\ProgramStudi::find($prodiIds[3])->name),
            ],
        ];

        foreach($dataprodiProfile as $data){
            $prodiProfile = \App\Models\Akademik\ProgramStudiProfile::create($data);
        }

        $dataKurikulum = [
            [
                'program_studi_id' => $prodiIds[0],
                'name' => 'Kurikulum TI 2023',
                'code' => 'KUR-TI-2023',
                'deskripsi' => 'Kurikulum Teknik Informatika yang disesuaikan dengan perkembangan teknologi terkini.',
                'tahun_berlaku' => 2023,
                'tahun_berakhir' => null,
                'awal_tahun_akademik_id' => 1,
                'akhir_tahun_akademik_id' => 1,
                'total_sks_lulus' => 144,
                'sks_wajib' => 120,
                'sks_pilihan' => 24,
                'semester_normal' => 8,
                'ipk_minimal' => 2.00,
                'sk_penetapan' => 'SK-KUR-TI-2023',
                'tanggal_sk' => '2023-01-15',
                'status' => 'Masih Berlaku',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'program_studi_id' => $prodiIds[1],
                'name' => 'Kurikulum SI 2023',
                'code' => 'KUR-SI-2023',
                'deskripsi' => 'Kurikulum Sistem Informasi yang disesuaikan dengan perkembangan teknologi terkini.',
                'tahun_berlaku' => 2023,
                'tahun_berakhir' => null,
                'awal_tahun_akademik_id' => 1,
                'akhir_tahun_akademik_id' => 1,
                'total_sks_lulus' => 144,
                'sks_wajib' => 120,
                'sks_pilihan' => 24,
                'semester_normal' => 8,
                'ipk_minimal' => 2.00,
                'sk_penetapan' => 'SK-KUR-SI-2023',
                'tanggal_sk' => '2023-01-15',
                'status' => 'Masih Berlaku',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'program_studi_id' => $prodiIds[2],
                'name' => 'Kurikulum Ekonomi Bisnis 2023',
                'code' => 'KUR-EB-2023',
                'deskripsi' => 'Kurikulum Ekonomi Bisnis yang disesuaikan dengan perkembangan teknologi terkini.',
                'tahun_berlaku' => 2023,
                'tahun_berakhir' => null,
                'awal_tahun_akademik_id' => 1,
                'akhir_tahun_akademik_id' => 1,
                'total_sks_lulus' => 144,
                'sks_wajib' => 120,
                'sks_pilihan' => 24,
                'semester_normal' => 8,
                'ipk_minimal' => 2.00,
                'sk_penetapan' => 'SK-KUR-EB-2023',
                'tanggal_sk' => '2023-01-15',
                'status' => 'Masih Berlaku',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'program_studi_id' => $prodiIds[3], 
                'name' => 'Kurikulum Bisnis Digital 2023',
                'code' => 'KUR-BD-2023',
                'deskripsi' => 'Kurikulum Bisnis Digital yang disesuaikan dengan perkembangan teknologi terkini.',
                'tahun_berlaku' => 2023,
                'tahun_berakhir' => null,
                'awal_tahun_akademik_id' => 1,
                'akhir_tahun_akademik_id' => 1,
                'total_sks_lulus' => 144,
                'sks_wajib' => 120,
                'sks_pilihan' => 24,
                'semester_normal' => 8,
                'ipk_minimal' => 2.00,
                'sk_penetapan' => 'SK-KUR-BD-2023',
                'tanggal_sk' => '2023-01-15',
                'status' => 'Masih Berlaku',
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        $kurikulumIds = [];
        foreach($dataKurikulum as $data){
            $kurikulum = \App\Models\Akademik\Kurikulum::create($data);
            $kurikulumIds[] = $kurikulum->id;
        }

        $dataMataKuliah = [
            [
                'semester_id' => 1,
                'name' => 'Algoritma dan Pemrograman',
                'name_en' => 'Algorithm and Programming',
                'code' => 'TI101',
                'beban_sks' => 4,
                'sks_teori' => 2,
                'sks_praktik' => 2,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 1,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 2,
                'name' => 'Struktur Data',
                'name_en' => 'Data Structures',
                'code' => 'TI102',
                'beban_sks' => 4,
                'sks_teori' => 3,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 2,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 3,
                'name' => 'Basis Data',
                'name_en' => 'Database Systems',
                'code' => 'TI201',
                'beban_sks' => 4,
                'sks_teori' => 3,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 3,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 4,
                'name' => 'Rekayasa Perangkat Lunak',
                'name_en' => 'Software Engineering',
                'code' => 'TI202',
                'beban_sks' => 3,
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 4,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 5,
                'name' => 'Jaringan Komputer',
                'name_en' => 'Computer Networks',
                'code' => 'TI301',
                'beban_sks' => 3,
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 5,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 6,
                'name' => 'Kecerdasan Buatan',
                'name_en' => 'Artificial Intelligence',
                'code' => 'TI302',
                'beban_sks' => 3,
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Wajib',
                'min_semester' => 6,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'semester_id' => 7,
                'name' => 'Pengolahan Citra Digital',
                'name_en' => 'Digital Image Processing',
                'code' => 'TI401',
                'beban_sks' => 3,
                'sks_teori' => 2,
                'sks_praktik' => 1,
                'sks_lapangan' => 0,
                'jenis' => 'Pilihan',
                'min_semester' => 7,
                'is_active' => true,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        $mataKuliahIds = [];
        foreach ($dataMataKuliah as $data) {
            $mataKuliah = \App\Models\Akademik\MataKuliah::create($data);
            $mataKuliahIds[] = $mataKuliah->id;
        }

        $dataKelasPerkuliahan = [
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[0],
                'name' => 'TI 2023 A',
                'code' => 'TI-2023-A',
                'kapasitas' => 40,
            ],
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[0],
                'name' => 'TI 2023 B',
                'code' => 'TI-2023-B',
                'kapasitas' => 40,
            ],
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[1],
                'name' => 'SI 2023 A',
                'code' => 'SI-2023-A',
                'kapasitas' => 40,
            ],
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[2],
                'name' => 'ES 2023 A',
                'code' => 'ES-2023-A',
                'kapasitas' => 40,
            ],
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[3],
                'name' => 'BD 2023 A',
                'code' => 'BD-2023-A',
                'kapasitas' => 40,
            ],
            [
                'tahun_akademik_id' => $takaIds[0],
                'program_studi_id' => $prodiIds[3],
                'name' => 'BD 2023 B',
                'code' => 'BD-2023-B',
                'kapasitas' => 40,
            ],
        ];

        $kelasPerkuliahanIds = [];
        foreach ($dataKelasPerkuliahan as $data) {
            $kelasPerkuliahan = \App\Models\Akademik\KelasPerkuliahan::create($data);
            $kelasPerkuliahanIds[] = $kelasPerkuliahan->id;
        }

        // // Sample data for mata_kuliah_detail table
        // DB::table('mata_kuliah_detail')->insert([
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[0],
        //         'deskripsi' => 'Mata kuliah yang membahas konsep dasar algoritma dan pemrograman komputer.',
        //         'capaian_pembelajaran' => 'Mahasiswa mampu memahami dan menerapkan konsep algoritma dalam pemrograman.',
        //         'materi_pokok' => 'Konsep algoritma, struktur data dasar, pemrograman prosedural dan berorientasi objek.',
        //         'metode_pembelajaran' => json_encode(['Ceramah', 'Praktikum', 'Diskusi']),
        //         'metode_penilaian' => json_encode(['UTS 30%', 'UAS 40%', 'Tugas 20%', 'Praktikum 10%']),
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[1],
        //         'deskripsi' => 'Mata kuliah yang membahas konsep dan implementasi basis data.',
        //         'capaian_pembelajaran' => 'Mahasiswa mampu merancang dan mengimplementasikan basis data.',
        //         'materi_pokok' => 'Model data, normalisasi, SQL, desain basis data, administrasi basis data.',
        //         'metode_pembelajaran' => json_encode(['Ceramah', 'Praktikum', 'Studi Kasus']),
        //         'metode_penilaian' => json_encode(['UTS 35%', 'UAS 35%', 'Tugas 20%', 'Praktikum 10%']),
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[2],
        //         'deskripsi' => 'Mata kuliah yang membahas strategi dan perencanaan dalam manajemen.',
        //         'capaian_pembelajaran' => 'Mahasiswa mampu menyusun dan mengimplementasikan strategi bisnis.',
        //         'materi_pokok' => 'Analisis lingkungan, formulasi strategi, implementasi strategi, evaluasi strategi.',
        //         'metode_pembelajaran' => json_encode(['Ceramah', 'Diskusi', 'Presentasi', 'Studi Kasus']),
        //         'metode_penilaian' => json_encode(['UTS 30%', 'UAS 40%', 'Tugas 20%', 'Presentasi 10%']),
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ]);

        // // Sample data for kurikulum_mata_kuliah table
        // DB::table('kurikulum_mata_kuliah')->insert([
        //     [
        //         'kurikulum_id' => $kurikulumIds[0],
        //         'mata_kuliah_id' => $mataKuliahIds[0],
        //         'semester_id' => 1,
        //         'is_wajib' => true,
        //         'urutan' => 1,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'kurikulum_id' => $kurikulumIds[0],
        //         'mata_kuliah_id' => $mataKuliahIds[1],
        //         'semester_id' => 3,
        //         'is_wajib' => true,
        //         'urutan' => 2,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'kurikulum_id' => $kurikulumIds[1],
        //         'mata_kuliah_id' => $mataKuliahIds[2],
        //         'semester_id' => 5,
        //         'is_wajib' => true,
        //         'urutan' => 1,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ]);

        // // Sample data for kelas_perkuliahan table
        // $kelasIds = [];
        // $kelasIds[] = DB::table('kelas_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'program_studi_id' => $programStudiIds[0],
        //     'mata_kuliah_id' => $mataKuliahIds[0],
        //     'name' => 'Algoritma dan Pemrograman A',
        //     'code' => 'TI101-A',
        //     'kapasitas' => 40,
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        
        // $kelasIds[] = DB::table('kelas_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'program_studi_id' => $programStudiIds[0],
        //     'mata_kuliah_id' => $mataKuliahIds[1],
        //     'name' => 'Basis Data A',
        //     'code' => 'TI201-A',
        //     'kapasitas' => 35,
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        
        // $kelasIds[] = DB::table('kelas_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'program_studi_id' => $programStudiIds[1],
        //     'mata_kuliah_id' => $mataKuliahIds[2],
        //     'name' => 'Manajemen Strategis A',
        //     'code' => 'MNJ301-A',
        //     'kapasitas' => 30,
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        // // Sample data for jadwal_perkuliahan table
        // $jadwalIds = [];
        // $jadwalIds[] = DB::table('jadwal_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'mata_kuliah_id' => $mataKuliahIds[0],
        //     'dosen_id' => 1,
        //     'ruang_id' => null,
        //     'hari' => 'Senin',
        //     'jam_mulai' => '08:00:00',
        //     'jam_selesai' => '10:00:00',
        //     'metode' => 'Tatap Muka',
        //     'code' => 'JDW-TI101-001',
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        
        // $jadwalIds[] = DB::table('jadwal_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'mata_kuliah_id' => $mataKuliahIds[1],
        //     'dosen_id' => 1,
        //     'ruang_id' => null,
        //     'hari' => 'Rabu',
        //     'jam_mulai' => '10:00:00',
        //     'jam_selesai' => '12:00:00',
        //     'metode' => 'Tatap Muka',
        //     'code' => 'JDW-TI201-001',
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        
        // $jadwalIds[] = DB::table('jadwal_perkuliahan')->insertGetId([
        //     'tahun_akademik_id' => 1,
        //     'mata_kuliah_id' => $mataKuliahIds[2],
        //     'dosen_id' => 1,
        //     'ruang_id' => null,
        //     'hari' => 'Jumat',
        //     'jam_mulai' => '13:00:00',
        //     'jam_selesai' => '15:00:00',
        //     'metode' => 'Tatap Muka',
        //     'code' => 'JDW-MNJ301-001',
        //     'created_by' => 1,
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        // // Sample data for jadwal_kelas table
        // DB::table('jadwal_kelas')->insert([
        //     [
        //         'jadwal_id' => $jadwalIds[0],
        //         'kelas_id' => $kelasIds[0],
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'jadwal_id' => $jadwalIds[1],
        //         'kelas_id' => $kelasIds[1],
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'jadwal_id' => $jadwalIds[2],
        //         'kelas_id' => $kelasIds[2],
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ]);

        // // Sample data for jadwal_pertemuan table - 16 pertemuan per jadwal
        // $jadwalPertemuanData = [];
        
        // // Data materi untuk setiap mata kuliah
        // $materiData = [
        //     $jadwalIds[0] => [ // Algoritma dan Pemrograman
        //         'Pengenalan Algoritma dan Flowchart',
        //         'Struktur Data Dasar',
        //         'Variabel dan Tipe Data',
        //         'Operator dan Ekspresi',
        //         'Struktur Kontrol - Percabangan',
        //         'Struktur Kontrol - Perulangan',
        //         'Array dan String',
        //         'Fungsi dan Prosedur',
        //         'Rekursi',
        //         'Sorting dan Searching',
        //         'Pointer dan Memory Management',
        //         'File Handling',
        //         'Struktur Data Lanjutan',
        //         'Project Development',
        //         'Testing dan Debugging',
        //         'Presentasi Project'
        //     ],
        //     $jadwalIds[1] => [ // Basis Data
        //         'Konsep Database dan ERD',
        //         'Model Data Relasional',
        //         'Normalisasi Database',
        //         'SQL Dasar - DDL',
        //         'SQL Dasar - DML',
        //         'SQL Lanjutan - Join',
        //         'SQL Lanjutan - Subquery',
        //         'Stored Procedure dan Function',
        //         'Trigger dan View',
        //         'Index dan Optimasi Query',
        //         'Transaction dan Concurrency',
        //         'Database Security',
        //         'NoSQL Database',
        //         'Database Administration',
        //         'Project Database Design',
        //         'Presentasi Project'
        //     ],
        //     $jadwalIds[2] => [ // Manajemen Strategis
        //         'Analisis SWOT dalam Manajemen Strategis',
        //         'Perencanaan Strategis',
        //         'Analisis Lingkungan Bisnis',
        //         'Competitive Advantage',
        //         'Porter Five Forces',
        //         'Blue Ocean Strategy',
        //         'Balanced Scorecard',
        //         'Strategic Implementation',
        //         'Change Management',
        //         'Leadership dalam Strategi',
        //         'Innovation Strategy',
        //         'Digital Transformation',
        //         'Strategic Control',
        //         'Case Study Analysis',
        //         'Strategic Presentation',
        //         'Final Evaluation'
        //     ]
        // ];
        
        // // Jam mulai untuk setiap jadwal
        // $jamMulaiData = [
        //     $jadwalIds[0] => '08:00:00',
        //     $jadwalIds[1] => '10:00:00', 
        //     $jadwalIds[2] => '13:00:00'
        // ];
        
        // // Jam selesai untuk setiap jadwal
        // $jamSelesaiData = [
        //     $jadwalIds[0] => '10:00:00',
        //     $jadwalIds[1] => '12:00:00',
        //     $jadwalIds[2] => '15:00:00'
        // ];
        
        // foreach ($jadwalIds as $index => $jadwalId) {
        //     for ($pertemuan = 1; $pertemuan <= 16; $pertemuan++) {
        //         // Hitung tanggal berdasarkan pertemuan (setiap minggu)
        //         $startDate = Carbon::create(2023, 9, 4 + ($index * 2)); // Mulai dari tanggal berbeda untuk setiap jadwal
        //         $tanggal = $startDate->addWeeks($pertemuan - 1)->format('Y-m-d');
                
        //         $jadwalPertemuanData[] = [
        //             'jadwal_id' => $jadwalId,
        //             'pertemuan_ke' => $pertemuan,
        //             'tanggal' => $tanggal,
        //             'jam_mulai' => $jamMulaiData[$jadwalId],
        //             'jam_selesai' => $jamSelesaiData[$jadwalId],
        //             'ruang_id' => null,
        //             'dosen_id' => 1,
        //             'metode' => $pertemuan <= 14 ? 'Tatap Muka' : 'Hybrid',
        //             'link' => null,
        //             'materi' => $materiData[$jadwalId][$pertemuan - 1],
        //             'is_realisasi' => false,
        //             'created_by' => 1,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now()
        //         ];
        //     }
        // }
        
        // DB::table('jadwal_pertemuan')->insert($jadwalPertemuanData);

        // Sample data for mata_kuliah_prasyarat table
        // DB::table('mata_kuliah_prasyarat')->insert([
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[1],
        //         'prasyarat_id' => $mataKuliahIds[0],
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ]);

        // Sample data for mata_kuliah_dosen table
        // DB::table('mata_kuliah_dosen')->insert([
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[0],
        //         'dosen_pengampu_id' => 1,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[1],
        //         'dosen_pengampu_id' => 1,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ],
        //     [
        //         'mata_kuliah_id' => $mataKuliahIds[2],
        //         'dosen_pengampu_id' => 1,
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now()
        //     ]
        // ]);

        // Sample data for kelas_mahasiswa table
        // Note: Uncomment this section after mahasiswa data is available
        /*
        DB::table('kelas_mahasiswa')->insert([
            [
                'kelas_id' => $kelasIds[0],
                'mahasiswa_id' => 1,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'kelas_id' => $kelasIds[0],
                'mahasiswa_id' => 2,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'kelas_id' => $kelasIds[1],
                'mahasiswa_id' => 3,
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
        */
    }
}
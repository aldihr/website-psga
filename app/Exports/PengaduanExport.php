<?php

namespace App\Exports;

use App\Models\Pengaduan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengaduanExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected $search = null,
        protected $status = null,
        protected $tanggalMulai = null,
        protected $tanggalAkhir = null
    ) {}

    public function collection()
    {
        $query = Pengaduan::query();

        // Filter pencarian
        if ($this->search) {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('nama_pelapor', 'like', "%{$search}%")
                    ->orWhere('email_pelapor', 'like', "%{$search}%")
                    ->orWhere('no_ticket', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter status yang valid
        $statusValid = [
            'Pengajuan',
            'Verifikasi',
            'Pendalaman',
            'Tindak Lanjut',
            'Selesai',
        ];

        if (
            $this->status &&
            in_array($this->status, $statusValid, true)
        ) {
            $query->where('status', $this->status);
        }

        // Filter tanggal mulai
        if ($this->tanggalMulai) {
            $query->whereDate(
                'created_at',
                '>=',
                $this->tanggalMulai
            );
        }

        // Filter tanggal akhir, termasuk seluruh hari tersebut
        if ($this->tanggalAkhir) {
            $query->whereDate(
                'created_at',
                '<=',
                $this->tanggalAkhir
            );
        }

        return $query->latest()->get();
    }
public function headings(): array
{
    return [
        'ID',
        'Nomor Tiket',
        'Nama Pelapor',
        'Email Pelapor',
        'Nomor HP',
        'Fakultas',
        'Terlapor',
        'Judul Pengaduan',
        'Isi Pengaduan',
        'Status',
        'Tanggapan',
        'Tanggal Pengaduan',
    ];
}

    public function map($pengaduan): array
    {
        return [
            $pengaduan->id,
            $pengaduan->no_ticket,
            $pengaduan->nama_pelapor,
            $pengaduan->email_pelapor,
            $pengaduan->no_hp,
            $pengaduan->fakultas,
            $pengaduan->terlapor,
            $pengaduan->judul_pengaduan,
            $pengaduan->isi_pengaduan,
            $pengaduan->status,
            $pengaduan->tanggapan,
            $pengaduan->created_at
                ? $pengaduan->created_at->format('d-m-Y H:i')
                : '',
        ];
    }
}

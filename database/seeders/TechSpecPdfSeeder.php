<?php

namespace Database\Seeders;

use App\Models\TechSpecPdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TechSpecPdfSeeder extends Seeder
{
    public function run(): void
    {
        $source       = base_path('datos/2026-05-06-La-Sangre_Tech-Riders.pdf');
        $storagePath  = 'riders/2026-05-06-La-Sangre_Tech-Riders.pdf';
        $downloadName = 'La-Sangre_Tech-Riders.pdf';

        if (!is_file($source)) {
            $this->command?->warn("TechSpecPdfSeeder: source PDF not found at {$source} — skipping file copy. DB row will still be inserted/updated.");
        } else {
            Storage::disk('public')->put($storagePath, file_get_contents($source));
        }

        TechSpecPdf::updateOrCreate(
            ['path' => $storagePath],
            [
                'language' => 'all',
                'filename' => $downloadName,
            ]
        );
    }
}

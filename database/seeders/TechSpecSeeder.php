<?php

namespace Database\Seeders;

use App\Models\TechSpec;
use Illuminate\Database\Seeder;

class TechSpecSeeder extends Seeder
{
    public function run(): void
    {
        $specs = [
            ['category' => 'PA',              'key' => 'Potencia mínima',     'value_es' => 'Mínimo 4kW',                   'value_de' => 'Mindestens 4kW',                  'value_en' => 'Minimum 4kW',                  'sort_order' => 1],
            ['category' => 'PA',              'key' => 'Subwoofer',           'value_es' => 'Requerido',                    'value_de' => 'Erforderlich',                    'value_en' => 'Required',                     'sort_order' => 2],
            ['category' => 'Mesa de mezclas', 'key' => 'Tipo',                'value_es' => 'Digital, 32 canales mínimo',   'value_de' => 'Digital, mindestens 32 Kanäle',   'value_en' => 'Digital, 32 channels minimum', 'sort_order' => 3],
            ['category' => 'Monitores',       'key' => 'Cuñas',               'value_es' => '6 cuñas independientes',       'value_de' => '6 unabhängige Wedge-Monitore',    'value_en' => '6 independent wedge monitors', 'sort_order' => 4],
            ['category' => 'Backline',        'key' => 'Amplificador guitarra','value_es' => 'Marshall JCM 800 o similar',  'value_de' => 'Marshall JCM 800 oder ähnliches', 'value_en' => 'Marshall JCM 800 or similar',  'sort_order' => 5],
            ['category' => 'Backline',        'key' => 'Amplificador bajo',   'value_es' => 'Ampeg SVT o similar',          'value_de' => 'Ampeg SVT oder ähnliches',        'value_en' => 'Ampeg SVT or similar',         'sort_order' => 6],
            ['category' => 'Batería',         'key' => 'Kit',                 'value_es' => 'Pearl, Tama o DW — 5 piezas',  'value_de' => 'Pearl, Tama oder DW — 5-teilig',  'value_en' => 'Pearl, Tama or DW — 5-piece',  'sort_order' => 7],
            ['category' => 'Micrófonos',      'key' => 'Voz principal',       'value_es' => 'Shure SM58 o Beta 58A',        'value_de' => 'Shure SM58 oder Beta 58A',        'value_en' => 'Shure SM58 or Beta 58A',       'sort_order' => 8],
        ];

        foreach ($specs as $row) {
            TechSpec::updateOrCreate(
                ['category' => $row['category'], 'key' => $row['key']],
                $row
            );
        }
    }
}

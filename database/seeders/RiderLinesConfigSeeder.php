<?php

namespace Database\Seeders;

use App\Models\RiderLinesConfig;
use Illuminate\Database\Seeder;

class RiderLinesConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'config'   => 'duo',
                'channels' => [
                    ['ch' => 1, 'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',         'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo', 'notes' => ''],
                    ['ch' => 2, 'instrument' => 'e-Acoustic guitar',            'mic' => 'Shure SM81 / AKG C451 OR DI Line',  'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo', 'notes' => ''],
                    ['ch' => 3, 'instrument' => 'Kick drum (suitcase design)',  'mic' => 'Behringer BA 19a (XLR)',            'effects' => '',                          'artist' => 'Vitxo', 'notes' => '+48v Phantom power'],
                    ['ch' => 4, 'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',         'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vini',  'notes' => ''],
                    ['ch' => 5, 'instrument' => 'e-Acoustic bass',              'mic' => 'Shure SM81 / AKG C451 OR DI Line',  'effects' => '',                          'artist' => 'Vini',  'notes' => ''],
                    ['ch' => 6, 'instrument' => 'Electric bass',                'mic' => 'DI Line OR Pedal',                  'effects' => '',                          'artist' => 'Vini',  'notes' => '(optional)'],
                ],
                'tech_notes_es' => implode("\n", [
                    'Micrófonos t.bone GM 55 (#1 y #4); micrófono Behringer BA 19a (XLR, #3), trono/asiento de batería y pedal, y todos los instrumentos los aporta la banda.',
                    'Se necesitan 2 atriles de micrófono y 1 taburete alto (bajista).',
                    'Se necesitan 2 cables TRS (6,35 mm) de guitarra/bajo y 3 cables XLR.',
                    'La banda puede aportar un mezclador de 8 canales (USB-batería) si es necesario (p. ej., para controlar el sonido en escena).',
                ]),
                'tech_notes_de' => implode("\n", [
                    't.bone GM 55 Mikrofone (#1 & #4); Behringer BA 19a Mikrofon (XLR, #3), Schlagzeughocker/-sitz & -pedal sowie alle Instrumente werden von der Band gestellt.',
                    '2 Mikrofonstative und 1 hoher Hocker (Bassist) sind erforderlich.',
                    '2 Gitarren-/Basskabel TRS (6,35 mm) und 3 XLR-Kabel sind erforderlich.',
                    'Ein 8-Kanal-Mixer (USB-Akku) kann bei Bedarf von der Band gestellt werden (z. B. zur Soundkontrolle auf der Bühne).',
                ]),
                'tech_notes_en' => implode("\n", [
                    't.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (XLR, #3), drum throne/seat & pedal, and all instruments are band-supplied.',
                    '2 mic stands and 1 high stool (bass player) are necessary.',
                    '2 guitar/bass TRS cables (6.35mm) and 3 XLR cables are necessary.',
                    'An 8-channels (usb-battery) mixer can be band-provided if necessary (e.g., to control sound on stage).',
                ]),
            ],
            [
                'config'   => 'pieter',
                'channels' => [
                    ['ch' => 1, 'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',     'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo',  'notes' => ''],
                    ['ch' => 2, 'instrument' => 'e-Acoustic guitar',            'mic' => 'Shure SM81 / AKG C451 — DI Line', 'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo',  'notes' => ''],
                    ['ch' => 3, 'instrument' => 'Kick drum (suitcase design)',  'mic' => 'Behringer BA 19a (XLR)',         'effects' => '',                          'artist' => 'Vitxo',  'notes' => '+48v Phantom power'],
                    ['ch' => 4, 'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',     'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vini',   'notes' => ''],
                    ['ch' => 5, 'instrument' => 'e-Acoustic bass',              'mic' => 'Shure SM81 / AKG C451 — DI Line', 'effects' => '',                          'artist' => 'Vini',   'notes' => ''],
                    ['ch' => 6, 'instrument' => 'Electric bass',                'mic' => 'Pedal — DI Line',                'effects' => '',                          'artist' => 'Vini',   'notes' => '(optional)'],
                    ['ch' => 7, 'instrument' => 'Vocals',                       'mic' => 'Shure SM58',                     'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                    ['ch' => 8, 'instrument' => 'Acoustic ukelele',             'mic' => 'Shure SM81 / AKG C451',          'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                    ['ch' => 9, 'instrument' => 'Electric guitar',              'mic' => 'Pedal — DI Line',                'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                ],
                'tech_notes_es' => implode("\n", [
                    'Dos micrófonos t.bone GM 55 (#1 y #4); micrófono Behringer BA 19a (XLR, #3), trono/asiento de batería y pedal, atril de piano y todos los instrumentos los aporta la banda.',
                    '3 atriles de micrófono, 1 taburete alto (bajista).',
                    'Se necesitan 3 cables TRS (6,35 mm) de guitarras/bajo y 3 cables XLR.',
                    'La banda puede aportar un mezclador de 8 canales (USB-batería) si es necesario (p. ej., para controlar el sonido en escena).',
                ]),
                'tech_notes_de' => implode("\n", [
                    'Zwei t.bone GM 55 Mikrofone (#1 & #4); Behringer BA 19a Mikrofon (XLR, #3), Schlagzeughocker/-sitz & -pedal, Klavierständer und alle Instrumente werden von der Band gestellt.',
                    '3 Mikrofonstative, 1 hoher Hocker (Bassist).',
                    '3 Gitarren-/Basskabel TRS (6,35 mm) und 3 XLR-Kabel sind erforderlich.',
                    'Ein 8-Kanal-Mixer (USB-Akku) kann bei Bedarf von der Band gestellt werden (z. B. zur Soundkontrolle auf der Bühne).',
                ]),
                'tech_notes_en' => implode("\n", [
                    'Two t.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (XLR, #3), drum throne/seat & pedal, piano stand, and all instruments are band-supplied.',
                    '3 mic stands, 1 high stool (bass player).',
                    '3 guitars/bass TRS cables (6.35mm) and 3 XLR cables are necessary.',
                    'An 8-channel (usb-battery) mixer can be band-provided if necessary (e.g., to control sound on stage).',
                ]),
            ],
            [
                'config'   => 'full',
                'channels' => [
                    ['ch' => 1,  'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',     'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo',  'notes' => ''],
                    ['ch' => 2,  'instrument' => 'e-Acoustic guitar',            'mic' => 'Shure SM81 / AKG C451 — DI Line', 'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vitxo',  'notes' => ''],
                    ['ch' => 3,  'instrument' => 'Kick drum (suitcase design)',  'mic' => 'Behringer BA 19a (XLR)',         'effects' => '',                          'artist' => 'Vitxo',  'notes' => '+48v Phantom power'],
                    ['ch' => 4,  'instrument' => 'Vocals',                       'mic' => 't.bone GM 55 / Shure SM58',     'effects' => 'Reverb 10%; Echo/delay 5%', 'artist' => 'Vini',   'notes' => ''],
                    ['ch' => 5,  'instrument' => 'e-Acoustic bass',              'mic' => 'Shure SM81 / AKG C451 — DI Line', 'effects' => '',                          'artist' => 'Vini',   'notes' => ''],
                    ['ch' => 6,  'instrument' => 'Electric bass',                'mic' => 'Pedal — DI Line',                'effects' => '',                          'artist' => 'Vini',   'notes' => '(optional)'],
                    ['ch' => 7,  'instrument' => 'Vocals',                       'mic' => 'Shure SM58',                     'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                    ['ch' => 8,  'instrument' => 'Acoustic ukelele',             'mic' => 'Shure SM81 / AKG C451',          'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                    ['ch' => 9,  'instrument' => 'Electric guitar',              'mic' => 'Pedal — DI Line',                'effects' => '',                          'artist' => 'Pieter', 'notes' => ''],
                    ['ch' => 10, 'instrument' => 'Electric piano',               'mic' => 'DI Line',                        'effects' => '',                          'artist' => 'Rosita', 'notes' => ''],
                ],
                'tech_notes_es' => implode("\n", [
                    'Dos micrófonos t.bone GM 55 (#1 y #4); micrófono Behringer BA 19a (XLR, #3), trono/asiento de batería y pedal, atril de piano y todos los instrumentos los aporta la banda.',
                    '3 atriles de micrófono, 1 taburete alto (bajista), 1 banqueta de piano necesarios.',
                    'Se necesitan 4 cables TRS (6,35 mm) de guitarras/bajo/piano y 3 cables XLR.',
                    'La banda puede aportar un mezclador de 8 canales (USB-batería) si es necesario (p. ej., para controlar el sonido en escena).',
                ]),
                'tech_notes_de' => implode("\n", [
                    'Zwei t.bone GM 55 Mikrofone (#1 & #4); Behringer BA 19a Mikrofon (XLR, #3), Schlagzeughocker/-sitz & -pedal, Klavierständer und alle Instrumente werden von der Band gestellt.',
                    '3 Mikrofonstative, 1 hoher Hocker (Bassist), 1 Klavierhocker erforderlich.',
                    '4 Gitarren-/Bass-/Klavierkabel TRS (6,35 mm) und 3 XLR-Kabel sind erforderlich.',
                    'Ein 8-Kanal-Mixer (USB-Akku) kann bei Bedarf von der Band gestellt werden (z. B. zur Soundkontrolle auf der Bühne).',
                ]),
                'tech_notes_en' => implode("\n", [
                    'Two t.bone GM 55 mics (#1 & #4); Behringer BA 19a mic (XLR, #3), drum throne/seat & pedal, piano stand, and all instruments are band-supplied.',
                    '3 mic stands, 1 high stool (bass player). 1 piano stool are necessary.',
                    '4 guitars/bass/piano TRS cables (6.35mm) and 3 XLR cables are necessary.',
                    'An 8-channel (usb-battery) mixer can be band-provided if necessary (e.g., to control sound on stage).',
                ]),
            ],
        ];

        foreach ($configs as $data) {
            RiderLinesConfig::updateOrCreate(
                ['config' => $data['config']],
                $data
            );
        }
    }
}

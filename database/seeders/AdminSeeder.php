<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@lasangreberlin.com')],
            [
                'name'     => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'change-me-now')),
                'is_admin' => true,
            ]
        );

        SiteSetting::updateOrCreate(
            ['key' => 'about_text'],
            [
                'value_es' => '¡Música acústica con vibe punk rock! La Sangre es un dúo de punk rock latino acústico que fusiona la rica herencia del rock latinoamericano con la energía cruda y rebelde del punk. Con guitarra acústica y bajo, sus letras arden de pasión y oscuridad, diciendo la verdad al poder y luchando por la justicia social y la libertad.' . "\n\n" . 'Originarios de Chile, La Sangre comenzó a formarse en 2023 en Friedrichshain-Kreuzberg, Berlín. El dúo está formado por Vitxo (guitarra, voz y percusión) y Vini (bajo y voz).' . "\n\n" . 'El proyecto revive la energía cruda del punk clásico y el horror punk, fusionándola con el espíritu del punk rock latino. Junto a mashups, versiones y covers, La Sangre ofrece canciones originales sobre temas poderosos: las luchas de la migración (Hombre Lobo en Berlín), las relaciones atormentadas (Sangre y Huesos) y el sufrimiento de los animales abandonados (Abandonado), entre otras.',
                'value_en' => "Acoustic music with a punk rock vibe! La Sangre is an acoustic Latin punk rock duo that fuses the rich heritage of Latin rock music with punk's raw, rebellious energy. With an acoustic guitar and bass, their lyrics ignite with passion and darkness, speaking truth to power and fighting for social justice and freedom." . "\n\n" . 'Originally from Chile, La Sangre began forming in 2023 in Friedrichshain-Kreuzberg, Berlin. The duo consists of Vitxo (guitar, vocals, and percussion) and Vini (bass and vocals).' . "\n\n" . 'The project revives the raw energy of classic punk and horror punk, blending it with the spirit of Latin punk rock. Alongside mashups, versions, and covers, La Sangre offers original songs exploring powerful themes — the struggles of migration (Hombre Lobo en Berlín), tormented relationships (Sangre y Huesos), and the suffering of abandoned animals (Abandonado), among others.',
                'value_de' => 'Akustische Musik mit einem Punk-Rock-Vibe! La Sangre ist ein akustisches lateinamerikanisches Punk-Rock-Duo, das das reiche Erbe der lateinamerikanischen Rockmusik mit der rohen, rebellischen Energie des Punk verbindet. Mit akustischer Gitarre und Bass entflammen ihre Texte vor Leidenschaft und Dunkelheit — sie sprechen die Wahrheit aus und kämpfen für soziale Gerechtigkeit und Freiheit.' . "\n\n" . 'Ursprünglich aus Chile stammend, begann La Sangre 2023 in Friedrichshain-Kreuzberg, Berlin. Das Duo besteht aus Vitxo (Gitarre, Gesang und Perkussion) und Vini (Bass und Gesang).' . "\n\n" . 'Das Projekt belebt die rohe Energie des klassischen Punk und Horror-Punk und verbindet sie mit dem Geist des lateinamerikanischen Punk-Rock. Neben Mashups, Versionen und Covers bietet La Sangre Originalsongs über kraftvolle Themen — den Kampf der Migration (Hombre Lobo en Berlín), gequälte Beziehungen (Sangre y Huesos) und das Leiden verlassener Tiere (Abandonado), unter anderem.',
            ]
        );
    }
}

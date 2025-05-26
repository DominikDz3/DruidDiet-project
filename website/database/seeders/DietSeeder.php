<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Diet;

class DietSeeder extends Seeder
{
    public function run()
    {
        Diet::create([
            'title' => 'Dieta Vege Klasyczna',
            'description' => 'Zbilansowana dieta wegetariańska dla każdego.',
            'type' => 'dieta wegetariańska',
            'calories' => 2000,
            'elements' => 'Kukurydza z piekarnika, Gnocchi z batatów, Makaron z sosem brokułowym',
            'price' => 50.00,
            'photo' => '',
            'allergens' => 'gluten, soja'
        ]); //

        Diet::create([
            'title' => 'Dieta Wysokobiałkowa Sport',
            'description' => 'Idealna dla osób aktywnych fizycznie, budujących masę mięśniową.',
            'type' => 'dieta białkowa',
            'calories' => 3000,
            'elements' => 'Twaróg z dodatkiem owoców i orzechów, Chrupiący kurczak w sosie Sweet Chili, Zupa Tikka Masala',
            'price' => 65.00,
            'photo' => '',
            'allergens' => 'laktoza, orzechy'
        ]); //

        Diet::create([
            'title' => 'Dieta Keto Wege',
            'description' => 'Połączenie diety ketogenicznej z wegetarianizmem.',
            'type' => 'dieta ketogeniczna',
            'calories' => 2200,
            'elements' => 'Jajecznica na maśle klarowanym z awokado, Halloumi z warzywami, Zapiekanka z kalafiora z serem',
            'price' => 70.00,
            'photo' => '',
            'allergens' => 'jaja, laktoza'
        ]);

        Diet::create([
            'title' => 'Dieta Niskokaloryczna Redukcyjna',
            'description' => 'Wspomaga redukcję masy ciała przy zachowaniu wartości odżywczych.',
            'type' => 'dieta redukcyjna',
            'calories' => 1500,
            'elements' => 'Sałatka z grillowanym indykiem, Zupa krem z pomidorów, Ryba pieczona z ziołami i kaszą gryczaną',
            'price' => 55.00,
            'photo' => '',
            'allergens' => 'ryby'
        ]);

        Diet::create([
            'title' => 'Dieta Bezglutenowa',
            'description' => 'Dla osób z nietolerancją glutenu lub celiakią.',
            'type' => 'dieta bezglutenowa',
            'calories' => 1800,
            'elements' => 'Naleśniki gryczane z owocami, Kurczak curry z ryżem jaśminowym, Sałatka quinoa z warzywami',
            'price' => 62.00,
            'photo' => '',
            'allergens' => '' // Zakładamy, że główne składniki są bezglutenowe
        ]);

        Diet::create([
            'title' => 'Dieta Wegańska Pełnowartościowa',
            'description' => '100% roślinna, bogata w białko i witaminy.',
            'type' => 'dieta wegańska',
            'calories' => 2300,
            'elements' => 'Tofucznica ze szczypiorkiem, Burger z ciecierzycy, Chili sin carne z soczewicą',
            'price' => 58.00,
            'photo' => '',
            'allergens' => 'soja, orzechy (opcjonalnie)'
        ]);

        Diet::create([
            'title' => 'Dieta Śródziemnomorska',
            'description' => 'Zdrowie i smak prosto z basenu Morza Śródziemnego.',
            'type' => 'dieta zbilansowana',
            'calories' => 2100,
            'elements' => 'Sałatka grecka z fetą, Paella z owocami morza (lub wersja vege), Grillowane warzywa z oliwą',
            'price' => 68.00,
            'photo' => '',
            'allergens' => 'ryby, skorupiaki, laktoza (feta)'
        ]);

        Diet::create([
            'title' => 'Dieta Paleo',
            'description' => 'Inspirowana sposobem odżywiania naszych przodków.',
            'type' => 'dieta paleo',
            'calories' => 2500,
            'elements' => 'Jajka sadzone z boczkiem i warzywami, Pieczony łosoś ze szparagami, Gulasz wołowy z batatami',
            'price' => 75.00,
            'photo' => '',
            'allergens' => 'jaja, ryby'
        ]);

        Diet::create([
            'title' => 'Dieta Niskowęglowodanowa (Low Carb)',
            'description' => 'Ogranicza węglowodany, stabilizuje poziom cukru.',
            'type' => 'dieta niskowęglowodanowa',
            'calories' => 1900,
            'elements' => 'Omlet z serem i szpinakiem, Sałatka z kurczakiem, awokado i oliwą, Pieczona karkówka z kapustą kiszoną',
            'price' => 63.00,
            'photo' => '',
            'allergens' => 'jaja, laktoza'
        ]);

        Diet::create([
            'title' => 'Dieta Samuraja (węglowodany okołotreningowo)',
            'description' => 'Węglowodany skoncentrowane wokół treningów dla maksymalnej energii.',
            'type' => 'dieta sportowa',
            'calories' => 2800,
            'elements' => 'Ryż z kurczakiem i warzywami (po treningu), Stek wołowy z sałatą (posiłek białkowo-tłuszczowy), Owsianka z owocami (przed treningiem)',
            'price' => 72.00,
            'photo' => '',
            'allergens' => 'gluten (owsianka, jeśli nie certyfikowana)'
        ]);

        Diet::create([
            'title' => 'Dieta Dash (nadciśnienie)',
            'description' => 'Wspiera walkę z nadciśnieniem tętniczym.',
            'type' => 'dieta zdrowotna',
            'calories' => 1700,
            'elements' => 'Pełnoziarniste płatki z jogurtem naturalnym i owocami, Zupa jarzynowa, Dorsz pieczony z brązowym ryżem i surówką',
            'price' => 60.00,
            'photo' => '',
            'allergens' => 'ryby, laktoza'
        ]);

        Diet::create([
            'title' => 'Dieta Lekka (Low FODMAP - faza eliminacyjna)',
            'description' => 'Dla osób z zespołem jelita drażliwego - faza eliminacyjna.',
            'type' => 'dieta low fodmap',
            'calories' => 1600,
            'elements' => 'Ryżanka na wodzie z gotowaną marchewką, Pierś z kurczaka gotowana na parze z ziemniakami, Pieczone jabłko',
            'price' => 68.00,
            'photo' => '',
            'allergens' => '' // Specyficzne eliminacje FODMAP
        ]);

        Diet::create([
            'title' => 'Dieta Komfortowa Domowa',
            'description' => 'Smaki tradycyjnej kuchni w zdrowszym wydaniu.',
            'type' => 'dieta zbilansowana',
            'calories' => 2200,
            'elements' => 'Jajecznica na boczku, Rosół z makaronem, Kotlet schabowy pieczony z ziemniakami i mizerią',
            'price' => 52.00,
            'photo' => '',
            'allergens' => 'jaja, gluten, laktoza'
        ]);

        Diet::create([
            'title' => 'Dieta Bez Laktozy Wege',
            'description' => 'Wegetariańska opcja dla osób z nietolerancją laktozy.',
            'type' => 'dieta wegetariańska',
            'calories' => 1900,
            'elements' => 'Owsianka na napoju roślinnym z owocami, Tofu w sosie słodko-kwaśnym z ryżem, Zupa krem z dyni na mleku kokosowym',
            'price' => 59.00,
            'photo' => '',
            'allergens' => 'soja, gluten (owsianka)'
        ]);

        Diet::create([
            'title' => 'Dieta Sokowa Detoks (3 dni)',
            'description' => 'Krótkoterminowy plan oczyszczający na bazie soków.',
            'type' => 'dieta sokowa',
            'calories' => 1200,
            'elements' => 'Sok z jarmużu, jabłka i cytryny; Sok z buraka, marchwi i pomarańczy; Sok z selera naciowego, ogórka i ananasa',
            'price' => 80.00, // Cena za dzień lub cały pakiet
            'photo' => '',
            'allergens' => ''
        ]);

        Diet::create([
            'title' => 'Dieta Budżetowa Studencka',
            'description' => 'Proste i tanie posiłki, ale wciąż odżywcze.',
            'type' => 'dieta zbilansowana',
            'calories' => 2400,
            'elements' => 'Kanapki z pastą jajeczną, Makaron z tuńczykiem i sosem pomidorowym, Leczo warzywne z kaszą',
            'price' => 40.00,
            'photo' => '',
            'allergens' => 'jaja, gluten, ryby'
        ]);

        Diet::create([
            'title' => 'Dieta Moc Energia (dla pracujących umysłowo)',
            'description' => 'Składniki wspierające koncentrację i pracę mózgu.',
            'type' => 'dieta zbilansowana',
            'calories' => 2000,
            'elements' => 'Owsianka z orzechami włoskimi i borówkami, Łosoś pieczony z komosą ryżową i szpinakiem, Sałatka z awokado i jajkiem',
            'price' => 67.00,
            'photo' => '',
            'allergens' => 'ryby, orzechy, jaja'
        ]);

        Diet::create([
            'title' => 'Dieta Wege Dziecięca (powyżej 3 lat)',
            'description' => 'Zbilansowane posiłki wegetariańskie dla najmłodszych.',
            'type' => 'dieta wegetariańska',
            'calories' => 1600,
            'elements' => 'Placuszki z twarogu i banana, Zupa jarzynowa z lanymi kluseczkami, Naleśniki ze szpinakiem i serem feta',
            'price' => 48.00,
            'photo' => '',
            'allergens' => 'gluten, jaja, laktoza'
        ]);

        Diet::create([
            'title' => 'Dieta Siła Roślin (wegańska sportowa)',
            'description' => 'Wegańska dieta dla sportowców, bogata w białko roślinne.',
            'type' => 'dieta wegańska',
            'calories' => 2700,
            'elements' => 'Smoothie proteinowe z masłem orzechowym i bananem, Buddha bowl z tofu, ciecierzycą i warzywami, Gulasz z soczewicy z kaszą jaglaną',
            'price' => 69.00,
            'photo' => '',
            'allergens' => 'soja, orzechy'
        ]);

        Diet::create([
            'title' => 'Dieta Szybka Regeneracja (po wysiłku)',
            'description' => 'Posiłki przyspieszające regenerację mięśni i uzupełniające energię.',
            'type' => 'dieta sportowa',
            'calories' => 2600,
            'elements' => 'Koktajl bananowo-białkowy, Pierś z kurczaka z batatami i brokułami, Omlet z warzywami i pełnoziarnistym tostem',
            'price' => 64.00,
            'photo' => '',
            'allergens' => 'laktoza (białko serwatkowe), jaja, gluten'
        ]);
    }
}
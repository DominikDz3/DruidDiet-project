<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catering;

class CateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Catering::create([
            'title' => 'Katering osiemnstka',
            'description' => 'Pełny katering na imprezę osiemnastkową',
            'type' => 'Katering impreza',
            'elements' => 'Mini kanapki z różnymi pastami, wędlinami i serami, Pizza w różnych wariantach, np. z kurczakiem, pieczarkami, serem, Sałatka (grecka, cezar, z kurczakiem)',
            'price' => 2000,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, soja'
        ]);
        
        Catering::create([
            'title' => 'Katering Komunijny "Biały Anioł"',
            'description' => 'Elegancki i uroczysty katering na przyjęcie komunijne, dopasowany do charakteru uroczystości.',
            'type' => 'Katering impreza',
            'elements' => 'Rosół z makaronem domowym, Pieczeń z karkówki w sosie własnym, Ziemniaki opiekane z ziołami, Mix sałat z sosem winegret, Surówka z białej kapusty, Deser: Tort komunijny (np. śmietankowy z owocami), Mini serniczki, Owoce filetowane',
            'price' => 3000,
            'photo' => '',
            'allergens' => 'gluten, jaja, laktoza, seler'
        ]);

        Catering::create([
            'title' => 'Katering grill',
            'description' => 'Idealny katering na domowe spotkanie przy grillu z naszym kateringiem',
            'type' => 'Katering plenerowy',
            'elements' => 'Grillowane warzywa (papryka, cukinia, bakłażan, ziemniaki), Grillowana pierś kurczaka, Sosy (sos tatarski, sos z suszonymi pomidorami), Pieczone ziemniaki z ziołami i serem',
            'price' => 1000,
            'photo' => '',
            'allergens' => 'laktoza, gorczyca'
        ]);

        Catering::create([
            'title' => 'Lunch Biznesowy Standard',
            'description' => 'Elegancki i smaczny lunch na spotkania biznesowe i konferencje.',
            'type' => 'Katering biznesowy',
            'elements' => 'Kanapki bankietowe (z łososiem i serkiem chrzanowym, z szynką parmeńską i rukolą, wegetariańskie z hummusem), Wrapy z kurczakiem i świeżymi warzywami, Sałatka Caprese z bazyliowym pesto, Mini tarty owocowe, Woda mineralna niegazowana i gazowana, Soki owocowe (pomarańczowy, jabłkowy)',
            'price' => 1500,
            'photo' => '',
            'allergens' => 'gluten, laktoza, ryby, orzechy'
        ]);

        Catering::create([
            'title' => 'Uczta Wegetariańska Pełna Smaków',
            'description' => 'Bogactwo smaków dla miłośników kuchni roślinnej, idealne na każdą okazję. Wszystkie dania są w 100% wegetariańskie.',
            'type' => 'Katering wegetariański',
            'elements' => 'Kotleciki z ciecierzycy i marchewki z sosem jogurtowo-miętowym, Pieczone bataty z rozmarynem i czosnkiem, Hummus klasyczny z chlebkiem pita i świeżymi warzywami (marchew, ogórek, papryka), Sałatka z quinoa, pieczonymi burakami, rukolą i serem feta, Ciasto marchewkowe z bakaliami',
            'price' => 1250,
            'photo' => '',
            'allergens' => 'gluten, laktoza, orzechy'
        ]);

        Catering::create([
            'title' => 'Przyjęcie dla Dzieci "Bajkowa Kraina"',
            'description' => 'Kolorowe, smaczne i zdrowe przekąski, które zachwycą najmłodszych gości na urodzinach czy kinderbalu.',
            'type' => 'Katering dla dzieci',
            'elements' => 'Mini pizze margherita, Nuggetsy z kurczaka (pieczone, nie smażone), Kolorowe szaszłyki owocowe (winogrona, truskawki, melon, kiwi), Paluszki warzywne (marchewka, ogórek) z dipem jogurtowym, Muffinki czekoladowe z kolorową posypką, Sok jabłkowy 100%, Woda niegazowana',
            'price' => 850,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja'
        ]);

        Catering::create([
            'title' => 'Katering Śniadaniowy "Poranek Mistrzów"',
            'description' => 'Energetyczne i zróżnicowane śniadanie idealne na rozpoczęcie dnia, spotkania firmowe lub eventy poranne.',
            'type' => 'Katering śniadaniowy',
            'elements' => 'Świeże pieczywo (jasne, ciemne, bułki), Wybór serów żółtych i pleśniowych, Wysokiej jakości wędliny, Jajecznica na maśle ze szczypiorkiem, Parówki z wody, Jogurty naturalne i owocowe z granolą i świeżymi owocami, Croissanty maślane, Kawa świeżo parzona, Herbata (różne rodzaje), Soki (pomarańczowy, grejpfrutowy)',
            'price' => 950,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja'
        ]);

        Catering::create([
            'title' => 'Stół Wiejski "Tradycja Polska"',
            'description' => 'Tradycyjne polskie smaki i wyroby, które uświetnią każde przyjęcie weselne, imprezę firmową lub rodzinne spotkanie.',
            'type' => 'Katering regionalny',
            'elements' => 'Smalec domowy ze skwarkami i jabłkiem, Ogórki kiszone i małosolne własnej roboty, Chleb wiejski na naturalnym zakwasie, Wybór wędlin swojskich (kiełbasa jałowcowa, szynka wędzona, boczek pieczony), Sery regionalne (oscypek, korbacze, bundz), Pasztet domowy z dziczyzny, Marynowane grzybki, Żurawina',
            'price' => 1900,
            'photo' => '',
            'allergens' => 'gluten, gorczyca, jaja'
        ]);

        Catering::create([
            'title' => 'Domówka Premium Mix',
            'description' => 'Zestaw przekąsek i dań idealny na elegancką domówkę ze znajomymi lub wieczór kawalerski/panieński.',
            'type' => 'Katering impreza',
            'elements' => 'Deska serów i wędlin premium (np. Prosciutto, Salami Milano, Brie, Comté), Mini burgery wołowe z karmelizowaną cebulą i sosem BBQ, Krewetki w tempurze z sosem sweet chili, Sałatka z rukolą, gruszką, orzechami włoskimi i serem pleśniowym, Patatas bravas z aioli, Mini ptysie z kremem waniliowym i owocami',
            'price' => 2200,
            'photo' => '',
            'allergens' => 'gluten, laktoza, skorupiaki, orzechy, gorczyca, jaja, soja'
        ]);

        Catering::create([
            'title' => 'Wieczór Filmowy - Zestaw Przekąsek',
            'description' => 'Idealny zestaw na maraton filmowy lub wieczór z grami planszowymi w gronie przyjaciół.',
            'type' => 'Katering impreza',
            'elements' => 'Nachosy z sosem serowym i guacamole, Popcorn maślany, Mini hot-dogi, Paluszki grissini z dipem ziołowym, Mix słonych przekąsek (orzeszki, precelki), Czekoladowe brownie',
            'price' => 700,
            'photo' => '',
            'allergens' => 'gluten, laktoza, orzechy, soja, jaja'
        ]);

        Catering::create([
            'title' => 'Piknik Rodzinny w Koszu',
            'description' => 'Kompletny zestaw piknikowy dla całej rodziny, gotowy do zabrania w plener na słoneczne popołudnie.',
            'type' => 'Katering plenerowy',
            'elements' => 'Świeże bagietki, Kanapki z pastą jajeczną i szczypiorkiem oraz z hummusem i pieczoną papryką, Roladki z tortilli z grillowanym kurczakiem i warzywami, Sałatka owocowa sezonowa (np. arbuz, melon, winogrona), Mini quiche lorraine, Domowa lemoniada cytrynowo-miętowa, Ciasteczka owsiane z żurawiną',
            'price' => 900,
            'photo' => '',
            'allergens' => 'gluten, jaja, laktoza, gorczyca'
        ]);

        Catering::create([
            'title' => 'Ognisko Integracyjne',
            'description' => 'Zestaw idealny na firmowe lub prywatne ognisko, z tradycyjnymi polskimi smakami.',
            'type' => 'Katering plenerowy',
            'elements' => 'Kiełbaski do pieczenia na ogniu (różne rodzaje), Karkówka marynowana do grillowania na ruszcie, Chleb wiejski, Musztarda, Ketchup, Ogórki kiszone, Smalec ze skwarkami, Pieczone ziemniaki w folii z masłem czosnkowym, Napoje (woda, soki)',
            'price' => 1300,
            'photo' => '',
            'allergens' => 'gluten, gorczyca, laktoza'
        ]);

        Catering::create([
            'title' => 'Przerwa Kawowa Premium dla VIP',
            'description' => 'Elegancka i bogata przerwa kawowa z wykwintnymi przekąskami na spotkania biznesowe, konferencje i szkolenia na najwyższym poziomie.',
            'type' => 'Katering biznesowy',
            'elements' => 'Wybór kaw speciality (espresso, americano, cappuccino, latte) parzonych przez baristę, Herbaty liściaste premium (czarna, zielona, owocowa, ziołowa), Świeżo wyciskane soki (pomarańczowy, grejpfrutowy, jabłkowy), Mini croissanty z nadzieniem migdałowym i czekoladowym, Financierki, Makaroniki (różne smaki), Panna cotta z musem malinowym w małych słoiczkach, Owoce filetowane (melon, ananas, winogrona, kiwi), Woda z cytryną, miętą i imbirem',
            'price' => 1100, 
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, orzechy'
        ]);

        Catering::create([
            'title' => 'Lunch Roboczy "Express"',
            'description' => 'Szybki, ale pożywny i smaczny lunch dla zapracowanych zespołów podczas intensywnych dni projektowych lub warsztatów.',
            'type' => 'Katering biznesowy',
            'elements' => 'Mix kanapek na pieczywie pełnoziarnistym i jasnym (z rostbefem i rukolą, z serem kozim i figą, z tuńczykiem), Sałatka grecka w indywidualnych porcjach, Jogurt z musli i owocami, Baton energetyczny, Woda mineralna',
            'price' => 850,
            'photo' => '',
            'allergens' => 'gluten, laktoza, ryby, orzechy, jaja'
        ]);

        Catering::create([
            'title' => 'Skarby Małego Odkrywcy',
            'description' => 'Zestaw pełen smacznych niespodzianek dla małych poszukiwaczy przygód, idealny na urodziny i zabawy tematyczne.',
            'type' => 'Katering dla dzieci',
            'elements' => 'Mini-hot dogi "Wesołe Gąsieniczki", Szaszłyki owocowe "Kolorowe Klejnoty" (z winogron, truskawek, borówek, ananasa), Kanapki w kształcie gwiazdek i serduszek z szynką, serem i świeżym ogórkiem, Chrupiące słupki marchewki i papryki "Złote Sztabki" z dipem jogurtowo-ziołowym, Galaretki owocowe "Tęczowe Kryształy" w pucharkach, Babeczki "Wulkan Czekolady" z kolorową posypką i żelkami, Sok jabłkowy i pomarańczowy "Eliksir Mocy"',
            'price' => 980,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, gorczyca, soja '
        ]);

        Catering::create([
            'title' => 'Studencka Integracja',
            'description' => 'Budżetowy, ale sycący zestaw na imprezę studencką lub spotkanie integracyjne na około 10-15 osób.',
            'type' => 'Katering impreza',
            'elements' => 'Duże misy chipsów i chrupek, Paluszki słone i sezamowe, Koreczki serowo-wędliniarskie z oliwkami, Mini zapiekanki z pieczarkami i serem na bagietce, Prosta sałatka jarzynowa tradycyjna, Napoje gazowane (cola, oranżada, woda).',
            'price' => 550,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, gorczyca, seler, sezam'
        ]);

        Catering::create([
            'title' => 'Spotkanie Autorskie - Poczęstunek',
            'description' => 'Lekki i elegancki poczęstunek na spotkanie autorskie, promocję książki lub inne kameralne wydarzenie kulturalne.',
            'type' => 'Katering kulturalny',
            'elements' => 'Mini kanapki bankietowe (3 rodzaje: z pastą łososiową, z hummusem i suszonym pomidorem, z serem pleśniowym i winogronem), Kruche ciasteczka (np. cytrynowe, maślane), Wybór herbat czarnych, zielonych i owocowych, Kawa parzona, Woda mineralna z cytryną i miętą, Małe patery owoców sezonowych (np. winogrona, mandarynki, truskawki).',
            'price' => 600,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, ryby, orzechy (w niektórych ciastkach), soja (w pastach)'
        ]);

        Catering::create([
            'title' => 'Romantyczna Kolacja We Dwoje - Dostawa',
            'description' => 'Elegancki zestaw kolacyjny dla dwojga, idealny na rocznicę lub specjalną okazję, dostarczany prosto do domu. Zestaw zawiera przystawkę, danie główne i deser.',
            'type' => 'Katering specjalny',
            'elements' => 'Przystawka: Krem z białych warzyw z chipsem z parmezanu i oliwą truflową. Danie główne (do wyboru jedno): Polędwiczki wieprzowe w sosie kurkowym z puree ziemniaczanym i blanszowanymi szparagami LUB Łosoś pieczony w ziołach z czarnym ryżem i sałatką z rukoli, pomidorków cherry i winegret. Deser: Mus czekoladowy z musem malinowym i świeżymi owocami.',
            'price' => 750,
            'photo' => '',
            'allergens' => 'laktoza, gluten (np. grzanki, panierka), ryby (opcja), seler, gorczyca (w winegret)'
        ]);

        Catering::create([
            'title' => 'Garden Party u Sąsiadów',
            'description' => 'Lekki i świeży katering idealny na letnie, nieformalne spotkanie w ogrodzie z sąsiadami i przyjaciółmi (dla ok. 10-12 osób).',
            'type' => 'Katering plenerowy',
            'elements' => 'Grillowane szaszłyki drobiowe z papryką i cebulą, Duża sałatka grecka z serem feta i oliwkami, Bruschetta z dojrzałymi pomidorami, czosnkiem i bazylią, Domowa lemoniada arbuzowo-miętowa, Mini tarty z owocami sezonowymi (np. truskawki, maliny, borówki), Pieczywo czosnkowe z grilla.',
            'price' => 1150,
            'photo' => '',
            'allergens' => 'gluten, laktoza, gorczyca (w marynacie do szaszłyków)'
        ]);

        Catering::create([
            'title' => 'Warsztaty Kreatywne - Przerwa Lunchowa',
            'description' => 'Zdrowy i energetyzujący lunch dla uczestników warsztatów lub szkoleń (ok. 15 osób), wspierający koncentrację i dobre samopoczucie.',
            'type' => 'Katering biznesowy',
            'elements' => 'Wybór wrapów pełnoziarnistych (z hummusem, pieczonymi warzywami i rukolą; z indykiem, awokado i świeżym szpinakiem), Zupa krem z sezonowych warzyw (np. dyniowa jesienią, chłodnik litewski latem) serwowana w kubeczkach, Duża miska sałaty z grillowanym halloumi, pomarańczą i orzechami włoskimi z dressingiem miodowo-musztardowym, Świeże owoce (jabłka, banany, gruszki), Woda z cytryną, miętą i imbirem, Ciasteczka owsiane z bakaliami.',
            'price' => 1350,
            'photo' => '',
            'allergens' => 'gluten, laktoza (halloumi), seler (w zupie), orzechy włoskie, gorczyca, sezam (w hummusie)'
        ]);

        Catering::create([
            'title' => 'Urodziny Nastolatka - Gaming Night',
            'description' => 'Zestaw przekąsek i napojów idealny na urodzinową noc z grami komputerowymi dla grupy nastolatków (ok. 8-10 osób).',
            'type' => 'Katering dla młodzieży',
            'elements' => 'Duża pizza (np. Pepperoni, Margherita, Hawajska - 3 sztuki), Skrzydełka z kurczaka w glazurze BBQ, Frytki belgijskie z keczupem i sosem czosnkowym, Mini tortille z kurczakiem, serem cheddar i salsą pomidorową, Mix napojów gazowanych i soków, Babeczki czekoladowe z kolorową posypką.',
            'price' => 1650,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, soja (w sosie BBQ, salsie), gorczyca'
        ]);

        Catering::create([
            'title' => 'Chrzciny - Obiad Rodzinny',
            'description' => 'Tradycyjny, uroczysty obiad rodzinny z okazji chrzcin, serwowany w formie bufetu dla około 20 osób.',
            'type' => 'Katering rodzinny',
            'elements' => 'Zupa: Rosół z domowym makaronem. Dania główne: Zrazy wołowe w sosie własnym, Pieczony kurczak w ziołach, Devolay z masłem. Dodatki: Ziemniaki gotowane z koperkiem, Kluski śląskie, Kasza gryczana. Surówki: Surówka z białej kapusty, Buraczki zasmażane, Mizeria. Deser: Sernik domowy na kruchym spodzie, Szarlotka z kruszonką. Napoje: Kompot owocowy, Woda z cytryną.',
            'price' => 2450,
            'photo' => '',
            'allergens' => 'gluten, jaja, laktoza, seler, gorczyca'
        ]);

        Catering::create([
            'title' => 'Eleganckie Przyjęcie Zaręczynowe',
            'description' => 'Wykwintny katering na kameralne przyjęcie zaręczynowe (ok. 15-20 osób), który zachwyci gości smakiem i elegancką prezentacją.',
            'type' => 'Katering okolicznościowy',
            'elements' => 'Przystawki (serwowane na paterach): Łosoś wędzony na blinach gryczanych z koperkowym serkiem, Roladki z szynki parmeńskiej z melonem, rukolą i octem balsamicznym, Vol-au-vents z musem grzybowym i tymiankiem, Mini sałatki Caprese na patyczkach. Danie główne (bufet): Pierś z kaczki w sosie wiśniowym z kluseczkami gnocchi i czerwoną kapustą z żurawiną, Dorsz atlantycki pieczony na puree z zielonego groszku z sosem szafranowym i grillowanymi warzywami. Deser (bufet): Mini torciki (np. red velvet, czekoladowy z malinami), Panna cotta z musem mango, Wybór świeżych owoców sezonowych.',
            'price' => 2950,
            'photo' => '',
            'allergens' => 'gluten, laktoza, jaja, ryby, orzechy, seler, gorczyca, soja'
        ]);
    }
}

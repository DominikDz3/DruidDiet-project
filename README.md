Zespół opracowujący projekt
Jakub Czesnak,  
Dominik Dziadosz, 
Julia Chmura, 
Bartłomiej Dobranowski 

Link do repozytorium:

https://github.com/DominikDz3/DruidDiet-project

# Temat Projektu 

Zamawianie cateringu dietetycznego (dieta pudełkowa).
### Opis Projektu

DruidDiet to aplikacja internetowa stworzona w celu ułatwienia użytkownikom wyboru i zamawiania spersonalizowanych diet oraz zestawów cateringowych, inspirowanych naturalnym i zdrowym stylem życia. System umożliwia przeglądanie oferty, dostosowywanie posiłków pod kątem preferencji i potrzeb kalorycznych, a także zarządzanie zamówieniami i punktami lojalnościowymi. Aplikacja skierowana jest do osób poszukujących zdrowych, gotowych posiłków dostarczanych do domu lub biura, jak również do tych, którzy chcą świadomie planować swoje żywienie.

### Użytkownik w aplikacji może:

- Przeglądać ofertę diet i cateringów.
- Filtrować i sortować produkty według różnych kryteriów (np. typ, kaloryczność, cena).
- Dodawać wybrane produkty do koszyka.
- Modyfikować zawartość koszyka (zmiana ilości, usuwanie produktów).
- Stosować kody rabatowe.
- Składać zamówienia, z opcją wykorzystania zgromadzonych punktów lojalnościowych.
- Zarządzać swoim kontem, w tym danymi osobowymi i historią zamówień.
- Przeglądać saldo punktów lojalnościowych.
- Dodawać komentarze/oceny do cateringów.
- Korzystać z kalkulatorów (np. zapotrzebowania na wodę, BMI - jeśli są zaimplementowane publicznie).

Administrator systemu ma dostęp do panelu zarządzania, gdzie może:

- Zarządzać zamówieniami klientów (przeglądanie, zmiana statusu, usuwanie).
- Zarządzać użytkownikami.
- Zarządzać ofertą cateringową i dietetyczną (CRUD).
- Zarządzać kodami rabatowymi.
- Przeglądać i zarządzać komentarzami.

### **Dostępne funkcjonalności:**

- Rejestracja i logowanie użytkowników (w tym z weryfikacją dwuetapową TOTP).
- Przeglądanie, filtrowanie i sortowanie oferty cateringów i diet.
- System koszyka opartego na sesji.
- Składanie zamówień.
- Panel użytkownika z historią zamówień i zarządzaniem kontem.
- Panel administratora do zarządzania kluczowymi zasobami aplikacji (zamówienia, użytkownicy, cateringi, diety, kupony, komentarze).
- System punktów lojalnościowych (zdobywanie punktów za zamówienia, możliwość płacenia punktami).
- System kodów rabatowych.
- System "Polecanych na Dzisiaj" cateringów i diet na stronie głównej, zmieniających się dynamicznie.
- Możliwość dodawania komentarzy do cateringów.
- Kalkulatory (BMI, zapotrzebowania na wodę).
- System obsługi niestandardowych stron błędów HTTP.

### Narzędzia i technologie

- **Backend:**
    - PHP 
    - Laravel Framework 
    - Composer (do zarządzania zależnościami PHP)
- **Frontend:**
    - HTML5, CSS3
    - JavaScript
    - Bootstrap 
    - Bootstrap Icons 
- **Baza Danych:**
    - PostgreSQL
- **Serwer WWW:**
    - Nginx 
- **Środowisko:**
    - Docker & Docker Compose 
- **Kontrola Wersji:**
    - Git

# Uruchomienie aplikacji

Do uruchomienia aplikacji lokalnie niezbędne jest posiadanie zainstalowanego i skonfigurowanego środowiska Docker oraz Docker Compose.



**Kroki do uruchomienia:**
1. Sklonuj repozytorium projektu.
2. Przejdź do głównego katalogu projektu w terminalu.
3.Uruchom skrypt `start.bat` (dla systemów Windows) lub wykonaj manualnie komendy zawarte w skrypcie.
   
   
   1 Sklonuj repozytorium projektu.
   2 Przejdź do głównego katalogu projektu w terminalu.
   3 Uruchom skrypt `start.bat` (dla systemów Windows) lub wykonaj manualnie komendy zawarte w skrypcie.
   4 W terminalu wpisz komendę `docker-compose up --build` i przejdź pod `localhost:8000`
   
   
   Skrypt startowy poza opisem czynności składa się z poleceń: 
- **Kopiowanie pliku `.env`:** `copy "website\.env.example" "website\.env"`
    
- **Uruchamianie kontenerów Docker:** `docker-compose up -d --build`
    
- **Sprawdzanie statusu kontenerów Docker:** `docker-compose ps`
    
- **Oczekiwanie na inicjalizację serwisów:** `timeout /t 15 /nobreak >nul`
    
- **Sprawdzanie istnienia katalogu `vendor` (wewnątrz kontenera `app`):** `docker-compose exec app test -d vendor`
    
- **Instalowanie zależności Composer (wewnątrz kontenera `app`):** `docker-compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader`
    
- **Sprawdzanie i generowanie klucza aplikacji Laravel (wewnątrz kontenera `app`):** `docker-compose exec app php artisan key:generate --check` `docker-compose exec app php artisan key:generate`
    
- **Czyszczenie bazy danych i uruchamianie migracji z seedami (wewnątrz kontenera `app`):** `docker-compose exec app php artisan migrate:fresh --seed`
   
   
# Baza danych

Schemat bazy danych został zaprojektowany w celu efektywnego przechowywania informacji o użytkownikach, ich zamówieniach, dostępnych cateringach, dietach, kuponach, komentarzach oraz wynikach BMI. Poniżej znajduje się diagram ERD ilustrujący kluczowe tabele i relacje między nimi.
   

![diagram-export-3 06 2025-19_51_26](https://github.com/user-attachments/assets/dfd93300-ea81-4e00-93bc-fbcd2cfe8a8d)

   
   
# Opis Widoków


## Strona główna



![main](https://github.com/user-attachments/assets/3f6b3c44-4eaa-4040-bee5-dcadad7af799)


### **Opis Strony Głównej (`main`)**

Strona główna aplikacji DruidDiet pełni rolę wizytówki oraz dynamicznego centrum prezentacji oferty. Została zaprojektowana w sposób spójny i nowoczesny, aby zachęcić użytkowników do zapoznania się z produktami i filozofią marki.

**Struktura i Kluczowe Sekcje:**

1. **Nagłówek (`Header`):**
    
    - Zawiera logo aplikacji, główne menu nawigacyjne (`O nas`, `Diety`, `Katering`, `Kontakt`) oraz dynamiczny panel użytkownika.
    - Panel użytkownika wyświetla przycisk "Zaloguj się" dla gości lub menu rozwijane z imieniem zalogowanego użytkownika, linkami do jego konta, historii zamówień oraz opcją wylogowania.
    - Zintegrowany jest z nim **dynamiczny koszyk**, który pokazuje liczbę produktów dodanych przez użytkownika.
2. **Sekcja "Hero":**
    
    - Jest to główny element wizualny przy pierwszym kontakcie ze stroną.
    - Zawiera hasło przewodnie "Odkryj Moc Natury z DruidDiet" oraz krótki, inspirujący opis.
    - Centralny przycisk "Sprawdź nasze cateringi" kieruje użytkowników bezpośrednio do oferty.
3. **Sekcja "Nasza Filozofia":**
    
    - Przedstawia misję i wartości marki.
    - Podkreśla trzy kluczowe filary: "Naturalne Składniki", "Zrównoważony Rozwój" oraz "Energia Słońca i Ziemi".
    - Każdy filar jest wizualnie reprezentowany przez pasujący do tematyki symbol (liść, drzewo, słońce).
4. **Sekcja "Polecane na Dzisiaj" (Cateringi):**
    
    - **Dynamiczna promocja:** Ta sekcja codziennie automatycznie wyróżnia inny typ cateringu (np. "Katering biznesowy", "Katering impreza").
    - Wyświetla do czterech losowych cateringów pasujących do "typu dnia".
    - Każda karta produktu zawiera zdjęcie, nazwę, typ, krótki opis, cenę oraz przycisk "Dodaj do koszyka", co umożliwia szybkie zakupy bezpośrednio ze strony głównej.
5. **Sekcja "Polecana Dieta Dnia":**
    
    - Działa analogicznie do sekcji z cateringami, codziennie promując inny typ diety (np. "dieta wegetariańska", "dieta białkowa").
    - Wyświetla do czterech diet danego typu wraz z kluczowymi informacjami (nazwa, typ, kaloryczność, cena) i możliwością dodania do koszyka.
6. **Sekcje zapoznawcze ("Nasze Diety" i "DruidDiet Katering"):**
    
    - Są to statyczne sekcje, które w skrócie prezentują ogólne kategorie oferty.
    - Zawierają przyciski kierujące do pełnych list diet i cateringów, zachęcając użytkownika do dalszej eksploracji.
7. **Stopka (`Footer`):**
    
    - Zawiera informację o prawach autorskich i jest spójna na wszystkich podstronach.

**Technicznie, widok `main.blade.php`:**

- Rozszerza główny layout aplikacji (`layouts.app.blade.php`), dzięki czemu dziedziczy spójny nagłówek i stopkę.
- Dynamicznie pobiera i wyświetla dane przekazane z `HomeController`, takie jak promowane diety i cateringi.
- Jest w pełni responsywny dzięki zastosowaniu siatki (grid system) Bootstrapa.
  
  
  
  
  
  
  
  
# Sekcja Diety


![diets](https://github.com/user-attachments/assets/2c83f73f-44e8-4f5c-8f53-1538561f45d2)

### **Opis Widoku Listy Diet (`diets.index`)**

Widok `diets.index` jest kluczową stroną publiczną aplikacji, która prezentuje użytkownikom pełną ofertę dostępnych diet. Został zaprojektowany w celu zapewnienia intuicyjnego i efektywnego przeglądania oraz filtrowania produktów, co ułatwia klientom znalezienie diety idealnie dopasowanej do ich potrzeb.

**Struktura i Kluczowe Elementy:**

1. **Nagłówek (`Header`):**
    
    - Strona korzysta ze wspólnego, spójnego nagłówka aplikacji, który zawiera logo, główne menu nawigacyjne oraz panel użytkownika z opcjami logowania/rejestracji lub zarządzania kontem.
    - Zintegrowana jest z nim **dynamiczna ikona koszyka**, która na bieżąco informuje o liczbie produktów dodanych do zamówienia.
2. **Zaawansowane Filtrowanie i Sortowanie:**
    
    - Użytkownik ma do dyspozycji rozbudowany, ale intuicyjny panel filtrowania i sortowania.
    - Panel jest domyślnie zwinięty i można go rozwinąć za pomocą przycisków "Filtry" i "Sortowanie".
    - **Filtry** pozwalają na zawężenie listy diet według:
        - **Zakresu kalorii:** Za pomocą podwójnych suwaków użytkownik może precyzyjnie określić minimalną i maksymalną wartość kaloryczną.
        - **Zakresu cenowego:** Podwójne suwaki umożliwiają wybór diet w określonym przedziale cenowym.
        - **Typu diety:** Rozwijana lista pozwala wybrać konkretny typ diety (np. wegetariańska, białkowa).
    - **Sortowanie** umożliwia uporządkowanie wyników według:
        - Nazwy (alfabetycznie A-Z lub Z-A).
        - Ceny (rosnąco lub malejąco).
        - Kaloryczności (rosnąco lub malejąco).
    - Przycisk "Zastosuj" przesyła wybrane kryteria i odświeża listę produktów.
3. **Lista Produktów (Diet):**
    
    - Diety prezentowane są w formie estetycznych kart produktowych, ułożonych w responsywnej siatce (grid).
    - Każda karta diety zawiera:
        - Zdjęcie produktu (lub placeholder).
        - Tytuł, typ i kaloryczność.
        - Krótki opis, skład oraz listę alergenów.
        - Wyraźnie widoczną cenę.
    - **Formularz "Dodaj do koszyka":** Pod każdą dietą znajduje się interaktywny formularz umożliwiający dodanie produktu do koszyka, wraz z opcją wyboru ilości.
4. **Paginacja:**
    
    - Na dole strony znajduje się system paginacji, który dzieli długie listy diet na mniejsze, łatwiejsze do przeglądania strony.
    - Paginacja jest zintegrowana z filtrami, co oznacza, że przechodzenie między stronami zachowuje aktywne kryteria wyszukiwania.

**Technicznie, widok `diets.blade.php`:**

- Rozszerza główny layout `layouts.app.blade.php`, zapewniając spójność z resztą aplikacji.
- Wykorzystuje dyrektywy `@push` do dołączania specyficznych dla tej strony stylów CSS oraz skryptów JavaScript.
- JavaScript na stronie odpowiada za interaktywną obsługę podwójnych suwaków (range sliders) oraz dynamiczne pokazywanie/ukrywanie panelu filtrów i sortowania.
- Formularze dodawania do koszyka wysyłają żądania POST do `CartController`, przekazując ID produktu oraz jego typ ('diet').
  

 
#  Cateringi
  ![caterings](https://github.com/user-attachments/assets/3b54f877-4dca-480d-9b99-f65e10aabfb8)

  
### **Opis Widoku Listy Cateringów (`caterings.index`)**

Widok `caterings.index` to strona prezentująca pełną ofertę cateringową aplikacji DruidDiet. Podobnie jak w przypadku listy diet, strona ta została zaprojektowana z myślą o zapewnieniu użytkownikom łatwego i intuicyjnego sposobu na przeglądanie, filtrowanie i sortowanie dostępnych opcji, co ułatwia znalezienie idealnego cateringu na każdą okazję.

**Struktura i Kluczowe Elementy:**

1. **Spójny Nagłówek (`Header`):**
    
    - Strona wykorzystuje wspólny dla całej aplikacji nagłówek, co zapewnia jednolity wygląd i nawigację.
    - W nagłówku znajduje się główne menu, panel logowania/rejestracji lub menu zalogowanego użytkownika oraz **dynamiczna ikona koszyka**, która na bieżąco pokazuje liczbę dodanych produktów.
2. **Panel Filtrowania i Sortowania:**
    
    - Użytkownik może skorzystać z rozbudowanego panelu do personalizacji wyświetlanej listy cateringów, który jest domyślnie zwinięty.
    - **Filtry** pozwalają na zawężenie wyników na podstawie:
        - **Zakresu cenowego:** Umożliwia wybór cateringów w określonym przedziale cenowym za pomocą interaktywnych suwaków.
        - **Typu cateringu:** Rozwijana lista pozwala na wybór konkretnego typu cateringu (np. "Katering biznesowy", "Katering impreza").
    - **Sortowanie** pozwala na uporządkowanie wyników według:
        - **Nazwy** (alfabetycznie A-Z lub Z-A).
        - **Ceny** (rosnąco lub malejąco).
3. **Lista Produktów (Cateringi):**
    
    - Cateringi są prezentowane w formie estetycznych kart produktowych, ułożonych w responsywnej siatce (grid), co zapewnia czytelność na różnych urządzeniach.
    - Każda karta cateringu zawiera:
        - Zdjęcie produktu.
        - Tytuł oraz typ (kategorię).
        - Krótki opis, listę składników ("Skład") oraz informacje o alergenach.
        - Wyraźnie widoczną cenę.
    - **Formularz "Dodaj do koszyka":** Każdy catering posiada przycisk oraz pole do wpisania ilości, umożliwiając szybkie dodanie produktu do koszyka bezpośrednio z poziomu listy.
4. **Paginacja:**
    
    - W przypadku dużej liczby produktów, na dole strony aktywowany jest system paginacji, który dzieli listę na strony.
    - Paginacja jest zintegrowana z filtrami i sortowaniem, co oznacza, że przechodzenie między stronami zachowuje aktywne kryteria wyszukiwania.

**Technicznie, widok `caterings/index.blade.php`:**

- Rozszerza główny layout aplikacji (`layouts.app.blade.php`), dziedzicząc spójny nagłówek i stopkę.
- Używa dyrektyw `@push` do dołączania specyficznych dla tej strony stylów CSS oraz skryptów JavaScript.
- Logika JavaScript na stronie obsługuje interaktywne suwaki (range sliders) oraz dynamiczne pokazywanie i ukrywanie panelu filtrów/sortowania.
- Formularze dodawania do koszyka wysyłają żądanie POST do `CartController`, przekazując `product_id` cateringu oraz `product_type` ustawiony na `'catering'`.
  
  
  # Strefa Dostaw 
  
  
![delivery_cart](https://github.com/user-attachments/assets/59ed0f4a-1c87-414e-ae62-c77c1539fe6d)

  
  Strona Strefy dostaw składa się z mapy strefy dostaw. 
  
  
  
  
  # Koszyk
  

![cart](https://github.com/user-attachments/assets/4b615e32-b03f-4a98-85ac-1b373c0227b3)


### **Opis Widoku Koszyka (`cart.index`)**

Widok koszyka jest kluczowym elementem procesu zakupowego w aplikacji DruidDiet. Został zaprojektowany tak, aby zapewnić użytkownikom pełną kontrolę nad wybranymi produktami, umożliwić łatwe modyfikacje oraz zastosowanie dostępnych benefitów, takich jak kody rabatowe czy punkty lojalnościowe.

**Struktura i Kluczowe Elementy:**

1. **Nagłówek i Komunikaty Systemowe:**
    
    - Strona wykorzystuje spójny nagłówek i stopkę aplikacji, zachowując ciągłość nawigacji.
    - W górnej części widoku dynamicznie wyświetlane są komunikaty systemowe (tzw. "flash messages"), informujące użytkownika o pomyślnie wykonanych akcjach (np. "Produkt usunięty z koszyka") lub o ewentualnych błędach. Alerty te można zamknąć1.
        
2. **Lista Produktów w Koszyku:**
    
    - Główną część strony stanowi responsywna tabela z listą dodanych produktów. Jeśli koszyk jest pusty, w jej miejscu pojawia się stosowny komunikat2.
        
    - Każdy wiersz tabeli reprezentuje jedną pozycję w koszyku i zawiera:
        - **Zdjęcie i Nazwę produktu:** Umożliwia szybką identyfikację pozycji w koszyku3.
            
        - **Cenę jednostkową** produktu.
        - **Formularz do zmiany ilości:** Interaktywny element składający się z pola numerycznego i przycisku do aktualizacji, co pozwala na łatwą zmianę liczby zamawianych sztuk4.
            
        - **Sumę częściową:** Obliczana na bieżąco wartość dla danej pozycji (cena × ilość).
        - **Przycisk usunięcia** (`ikona kosza na śmieci`), pozwalający na usunięcie pojedynczej pozycji z zamówienia5.
            
3. **Sekcja Podsumowania i Finalizacji:**
    
    - Umieszczona po prawej stronie, grupuje wszystkie informacje finansowe i kluczowe akcje.
    - **Formularz Kodu Rabatowego:** Użytkownik może wpisać posiadany kod rabatowy i zatwierdzić go przyciskiem "Zastosuj"6.
        
    - **Podsumowanie kwot:** Czytelnie przedstawia sumę częściową, kwotę przyznanego rabatu (jeśli kupon został zastosowany) oraz ostateczną kwotę "Łącznie do zapłaty"7.
        
    - **Opcja płatności punktami:** Dla zalogowanych użytkowników z odpowiednią liczbą punktów lojalnościowych, widoczny jest "suwak" (przełącznik), który pozwala na opłacenie całego zamówienia zgromadzonymi punktami8. Użytkownik jest również informowany o swoim aktualnym saldzie punktów.
        
    - **Przycisk "Złóż zamówienie":** Główny przycisk akcji, który inicjuje proces finalizacji zakupu. Jest on dostępny tylko dla zalogowanych użytkowników9.
        
    - **Dodatkowe Akcje:** Użytkownik ma również do dyspozycji przyciski "Kontynuuj zakupy" oraz "Wyczyść Koszyk"10.
        

**Technicznie, widok `cart.index.blade.php`:**

- Jest zintegrowany z głównym layoutem aplikacji, co zapewnia spójny interfejs.
- Dynamicznie renderuje zawartość na podstawie danych o koszyku przechowywanych w sesji.
- Interakcje modyfikujące koszyk (zmiana ilości, usuwanie, stosowanie kuponu) są obsługiwane przez formularze wysyłające żądania do `CartController`.
- Finalizacja zamówienia jest obsługiwana przez formularz kierujący do `CheckoutController`.
  
  
  
  # Panel użytkownika


![user_main](https://github.com/user-attachments/assets/46664ed8-2b59-4b8b-9653-2e8970fce23c)

  

  
  
### **Opis Widoku Głównego Panelu Użytkownika (`user.dashboard`)**

Główny widok panelu użytkownika (`user.dashboard`) jest centralnym punktem zarządzania kontem klienta po zalogowaniu. Został zaprojektowany w sposób minimalistyczny i funkcjonalny, aby zapewnić łatwy dostęp do kluczowych informacji i akcji związanych z kontem.

**Struktura i Kluczowe Elementy:**

1. **Nawigacja Boczna (`Sidebar`):**
    
    - Po lewej stronie znajduje się pionowe menu nawigacyjne, które jest głównym sposobem poruszania się po panelu.
    - Zawiera czytelne linki do poszczególnych sekcji:
        - **Twój profil:** Główny widok panelu, podsumowujący aktywność.
        - **Zamówienia:** Link do historii wszystkich złożonych zamówień.
	    -  Kody Rabatowe: Kody rabatowe przypisane do danego uzytkownika
        - **Wyloguj się:** Bezpieczne zakończenie sesji użytkownika.
		- **Kalkulatory: Dostępne narzędzie dla użytkownika, liczenie zapotrzebowania wody, kalkulator BMI
		- **Uwierzytelnianie 2FA: Możliwość dodania do konta weryfikacji TOTP
		  
        
    - Aktywna sekcja (w tym przypadku "Konto") jest wizualnie wyróżniona, co ułatwia orientację.
2. **Główny Obszar Treści:**
    
    - Po prawej stronie, w głównym obszarze, wyświetlane są informacje kontekstowe dla wybranej sekcji z menu bocznego.
    - **Szybkie Linki/Akcje:** W tej sekcji znajdują się również przyciski lub linki do najważniejszych działań, takich jak przeglądanie ostatniego zamówienia czy edycja profilu, co ułatwia szybki dostęp do najczęściej używanych funkcji.


  
  
  
  
# Panel Administartora

![admin_main](https://github.com/user-attachments/assets/1399409a-9961-41df-a4d5-d5bdf28189dd)

### **Opis Panelu Administratora**

Panel administratora aplikacji DruidDiet to kompleksowe centrum zarządzania, które umożliwia pełną kontrolę nad kluczowymi aspektami działania serwisu. Interfejs został zaprojektowany w sposób czysty i funkcjonalny, aby zapewnić efektywną pracę i szybki dostęp do wszystkich modułów.

**Struktura i Główne Komponenty:**

1. **Nawigacja Boczna (`Sidebar`):**
    
    - Stanowi główne menu panelu, zapewniając stały i łatwy dostęp do wszystkich sekcji zarządzania.
    - Zawiera linki do modułów takich jak: Dashboard, Użytkownicy, Kody Rabatowe, Diety, Kateringi, Komentarze i Oceny oraz Zamówienia.
    - Na dole menu znajduje się przycisk "Wyloguj".
2. **Górny Pasek (`Topbar`):**
    
    - Wyświetla tytuł panelu oraz informacje o aktualnie zalogowanym administratorze.

**Opis Poszczególnych Widoków:**

- **Dashboard (Panel Główny)** 
    
    - Jest to strona startowa panelu, prezentująca kluczowe wskaźniki i statystyki dotyczące działalności serwisu.
    - **Karty Statystyk:** W górnej części widoczne są karty z szybkimi podsumowaniami, takimi jak łączne przychody, liczba zamówień oraz ich łączna wartość w ciągu ostatnich 30 dni.
    - **Wykresy:** Panel zawiera dwa główne wykresy, które wizualizują trendy w czasie:
        - **Dzienne Przychody:** Wykres liniowy pokazujący przychody z każdego dnia w okresie ostatnich 30 dni.
        - **Dzienna Liczba Zamówień:** Wykres liniowy ilustrujący liczbę składanych zamówień każdego dnia w tym samym okresie.
- **Zarządzanie Użytkownikami (`/admin/users`)** 
    
    - **Lista Użytkowników:** Główny widok tej sekcji to tabela z listą wszystkich zarejestrowanych użytkowników. 3 Zawiera kluczowe informacje, takie jak ID, imię i nazwisko, adres e-mail, przypisana rola (np. "User" lub "Admin"), aktualna liczba punktów lojalnościowych oraz zadeklarowane alergeny. 4 Administrator ma możliwość wyszukiwania użytkowników po emailu, imieniu i nazwisku. 
        
    - **Akcje:** Przy każdym użytkowniku znajdują się przyciski umożliwiające jego edycję lub usunięcie. 
        
    - **Dodawanie Użytkownika:** Przycisk "+ Dodaj Nowego Użytkownika" przenosi do formularza tworzenia nowego konta. 
        
    - **Edycja Użytkownika:** Formularz edycji pozwala na modyfikację wszystkich kluczowych danych użytkownika, w tym imienia, nazwiska, adresu e-mail, hasła, roli oraz liczby punktów lojalnościowych. 
        
- **Zarządzanie Kodami Rabatowymi (`/admin/coupons`)** 
    
    - Widok ten prezentuje listę wszystkich wygenerowanych kodów rabatowych w systemie.
    - Tabela zawiera informacje o kodzie, wartości rabatu (np. 10%, 30%), ewentualnym przypisaniu do konkretnego użytkownika, statusie wykorzystania ("Tak"/"Nie") oraz dacie utworzenia. 
        
    - Administrator ma do dyspozycji przyciski do edycji i usuwania kuponów. 
        
    - Przyciski "Wygeneruj Nowy Kod" i "Losuj Kupon dla Użytkownika" umożliwiają tworzenie nowych kuponów rabatowych.
        
- **Zarządzanie Kateringami (`/admin/caterings`)** 
    
    - Sekcja ta umożliwia pełne zarządzanie ofertą cateringową (CRUD).
    - Główny widok to tabela z listą wszystkich dostępnych cateringów, która pokazuje ich tytuł, typ, cenę, fragment opisu oraz listę alergenów.
    - Administrator może wyszukiwać cateringi po tytule i typie. 
        
    - Standardowe akcje CRUD (dodawanie, edycja, usuwanie) są dostępne dla każdej pozycji. 
        
- **Zarządzanie Zamówieniami (`/admin/orders`)** 16
    
    - Widok ten przedstawia tabelaryczną listę wszystkich zamówień złożonych w systemie.
    - Kolumny zawierają kluczowe dane: ID zamówienia, dane klienta (imię, nazwisko, e-mail), datę zamówienia, jego status (np. "w przygotowaniu", wizualizowany za pomocą kolorowej etykiety) oraz sumę. 
        
    - Administrator ma możliwość filtrowania zamówień po statusie oraz wyszukiwania ich po ID, emailu lub imieniu klienta. 
        
    - Dla każdego zamówienia dostępne są akcje: "Podgląd" (przejście do szczegółów), "Edytuj" (np. zmiana statusu) oraz "Usuń". 
        
- **Zarządzanie Komentarzami i Ocenami (`/admin/comments`)** 
    
    - Sekcja ta służy do moderacji opinii wystawianych przez użytkowników.
    - Lista pokazuje treść komentarza, jego autora, ocenę (w postaci gwiazdek), datę dodania oraz produkt (dietę lub catering), którego dotyczy. 
        
    - Administrator może wyszukiwać komentarze po ich treści. 
        
    - Główną akcją jest możliwość usunięcia nieodpowiedniego komentarza. 
  
# Prezentacja działania trybu Dark-Mode na przykładzie strony cateringów
  

  ![darkmode_presentation](https://github.com/user-attachments/assets/f19e56b6-25fa-4049-9862-6f329283faf1)

  
  

Zespół opracowujący projekt
- Dawid Bar  
- Dominik Dziadosz
- Julia Chmura

Link do repozytorium:

https://github.com/DominikDz3/DruidDiet-project

# Temat Projektu 

Aplikacja wspomagająca samorozwój, organizację czasu i monitorowanie samopoczucia
### Opis Projektu

SelfDev App to aplikacja internetowa stworzona z myślą o osobwach dążących do rozwoju osobistego. System łączy w sobie narzędzia do edukacji (moduł lekcji), zarządzania czasem (Pomodoro) oraz dbania o zdrowie psychiczne (zaawansowany dziennik nastroju). Aplikacja umożliwia uzytkownikom śledznie swoich emocji, analizowanie statystyk samopoczucia oraz efektywną pracę w cyklach skupienia.

### Użytkownik w aplikacji może:

- Zarządzać kontem: Rejestracja, logowanie, reset hasła, edycja danych profilowych (w tym awatar) oraz zmiana hasła.
- Dbać o bezpieczeństwo: Włączyć dwuetapową weryfikację (2FA) przy użyciu aplikacji typu Google Authenticator (generowanie kodów QR).
- Dodawać wpis do dziennika (ocena nastroju 1-5, wybór emocji, notatka).
- Przeglądać historię wpisów w formie tabeli.
- Edytować i usuwać wpisy.
- Przeglądać wykresy trendu nastroju (tydzień/miesiąc/rok).
- Analizować najczęściej występujące emocje (wykres słupkowy).
- Generować miesięczne raporty podsumowujące (średnia nastroju, najlepszy/najgorszy dzień).
- Korzystać z wbudowanego narzędzia Pomodoro (automatyczne cykle 25 min pracy / 5 min przerwy z powiadomieniami dźwiękowymi).
- Przeglądać dostępne lekcje i materiały edukacyjne (moduł Lekcje).
 
Administrator systemu ma dostęp do panelu zarządzania, gdzie może:
- Zarządzać użytkownikami (blokowanie, edycja).
- Zarządzać treściami lekcji (CRUD).
- Przeglądać statystyki globalne aplikacji.


### **Dostępne funkcjonalności:**

- Logowanie i Rejestracja.
- 2FA (Two-Factor Authentication): Implementacja TOTP z wykorzystaniem biblioteki OTPHP i kodów QR.
- Mechanizm przypominania i resetowania hasła.
- Dynamiczny widok kafelkowy (Lekcje, Pomodoro, Profil).
- Personalizowane powitanie.
- Moduł Profilu i Dziennika:
- Zakładki: Moje dane, Hasło, Bezpieczeństwo, Dziennik, Statystyki.
- Interaktywne wykresy (biblioteka Recharts).
- Upload i obsługa awatarów użytkownika.
- Timer odliczający czas pracy i przerwy.
- Płynne przejścia między trybami.
- Sygnalizacja dźwiękowa.
- RESTful API oparte o Laravel.
- Zabezpieczenie tras za pomocą Laravel Sanctum.

### Narzędzia i technologie

- **Backend:**
    - PHP 
    - Laravel Framework 
    - Composer
    - Biblioteki: spomky-labs/otphp (2FA)
- **Frontend:**
    - React.js (biblioteka UI)
    - Vite (bundler)
    - CSS3 (Custom CSS + animacje) 
    - Biblioteki: recharts (wykresy), qrcode.react (kody QR), react-router-dom (nawigacja), axios 
- **Baza Danych:**
    - PostgreSQL
- **Serwer WWW:**
    - Nginx 
- **Środowisko:**
    - Docker & Docker Compose 
- **Kontrola Wersji:**
    - Git

# Uruchomienie aplikacji

Wymagane środowisko: Docker oraz Docker Compose.


**Kroki do uruchomienia:**
1. Sklonuj repozytorium projektu.
2. Przejdź do katalogu głównego.
3. Utwórz plik .env na podstawie .env.example (zarówno w backendzie jak i frontendzie, jeśli wymagane).
4. W terminalu uruchom komendę: docker-compose up --build
5. Aplikacja frontendowa dostępna będzie pod adresem: http://localhost:5173 (lub port z vite.config).
6. API backendowe dostępne pod adresem: http://localhost:8000.

7. Ewentualnie uruchom plik start.bat
   
# Baza danych

Schemat bazy danych przechowuje informacje o użytkownikach, ich nastrojach, emocjach oraz lekcjach.
   
   
# Opis Widoków

## Strona główna


### **Strona główna**

Strona główna pełni dwie funkcje w zależności od statusu użytkownika
- Dla niezalogowanych (Hero Page): Wyświetla nagłówek "Zmień swoje życie na lepsze", zachętę do rejestracji oraz przycisk "Dołącz do nas". Navbar zawiera przycisk "Lekcje" (dostępne publicznie) oraz "Zaloguj się".
- Dla zalogowanych (Dashboard):
Personalizowane powitanie ("Witaj, [Login]!").
System kafelków: Centralna nawigacja prowadząca do trzech głównych modułów:
Lekcje, Pomodoro

Mój Profil / Panel Admina  (zależnie od roli).

Navbar jest uproszczony, zawiera tylko powitanie i przycisk "Wyloguj".
  
### **Logowanie i rejestracja**

Estetyczne formularze z nowoczesnym tłem (gradient) i efektem "glassmorphism" (półprzezroczyste karty).
- Logowanie: Obsługuje walidację, wyświetlanie błędów oraz obsługę 2FA. Jeśli użytkownik ma włączone 2FA, po podaniu poprawnego hasła pojawia się dodatkowe pole na kod 6-cyfrowy.
- Rejestracja: Walidacja unikalności e-maila/loginu oraz potwierdzenia hasła.


### **Profil użytkownika i dziennik nastroju**

Rozbudowany panel zarządzania podzielony na zakładki (Sidebar po lewej stronie):

- Moje dane: Edycja loginu, e-maila oraz upload zdjęcia profilowego (awatar).
- Zmień hasło: Formularz zmiany hasła.
- Bezpieczeństwo: Generowanie kodu QR dla Google Authenticator, włączanie/wyłączanie 2FA.
	
Dziennik:

- Formularz dodawania nastroju (Data, Suwak oceny 1-5, Wybór emocji jako "tabletki", Notatka).
- Tabela z historią wpisów (data, ocena, emocje, akcje edycji/usuwania).
	
Statystyki:

- Wykres liniowy trendu samopoczucia.
- Wykres słupkowy najczęstszych emocji.
- Generator raportów miesięcznych (podsumowanie najlepszego/najgorszego dnia)

### **Moduł Pomodoro**

Narzędzie typu "Focus Timer".
- Design: Duży, czytelny zegar na środku ekranu.
Funkcjonalność:
- Automatyczne przełączanie między trybem "Praca" (25 min - fioletowy motyw) a "Przerwa" (5 min - zielony motyw).
- Pasek postępu wizualizujący upływ czasu.
- Sygnalizacja dźwiękowa (dzwonek) po zakończeniu cyklu.
- Przycisk Start/Pauza oraz Reset.


### **Przeglądarka Lekcji**

Lista dostępnych materiałów edukacyjnych z możliwością paginacji i wejścia w szczegóły lekcji. Dostępna (w ograniczonym lub pełnym zakresie) z poziomu strony głównej.


  
  

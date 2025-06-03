@echo off
SETLOCAL

ECHO === DruidDiet - Uruchamianie Aplikacji ===
ECHO.

ECHO 1. Sprawdzanie pliku .env...
IF NOT EXIST "website\.env" (
    ECHO Plik .env nie istnieje. Kopiowanie z website\.env.example...
    IF EXIST "website\.env.example" (
        copy "website\.env.example" "website\.env"
        ECHO Plik .env utworzony. Skonfiguruj go jesli to konieczne.
    ) ELSE (
        ECHO OSTRZEZENIE: Plik website\.env.example nie znaleziony! Nie mozna utworzyc .env.
        ECHO Utworz i skonfiguruj plik 'website\.env' manualnie.
        GOTO EndScript
    )
) ELSE (
    ECHO Plik .env juz istnieje.
)
ECHO.

ECHO 2. Uruchamianie kontenerow Docker...
docker-compose up -d --build
IF %ERRORLEVEL% NEQ 0 (
    ECHO BLAD: Nie udalo sie uruchomic kontenerow Docker. Sprawdz konfiguracje.
    GOTO EndScript
)
ECHO Kontenery Docker uruchomione.
ECHO.
ECHO Status kontenerow:
docker-compose ps
ECHO.

ECHO 3. Oczekiwanie na inicjalizacje serwisow (np. bazy danych)...
timeout /t 15 /nobreak >nul
ECHO.

ECHO 4. Instalowanie zaleznosci Composer (jesli potrzebne)...
docker-compose exec app test -d vendor || (
    ECHO Katalog vendor nie istnieje. Instalowanie zaleznosci Composer...
    docker-compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader
    IF %ERRORLEVEL% NEQ 0 (
        ECHO BLAD: Instalacja Composer nie powiodla sie.
        GOTO EndScript
    ) ELSE (
        ECHO Zaleznosci Composer zainstalowane.
    )
)
ECHO Zaleznosci Composer sa aktualne lub zostaly zainstalowane.
ECHO.

ECHO 5. Generowanie klucza aplikacji Laravel (jesli potrzebne)...
docker-compose exec app php artisan key:generate --check >nul 2>&1
IF %ERRORLEVEL% EQU 1 (
    ECHO Klucz aplikacji nie jest ustawiony. Generowanie klucza...
    docker-compose exec app php artisan key:generate
    ECHO Klucz aplikacji wygenerowany.
) ELSE (
    ECHO Klucz aplikacji jest juz ustawiony.
)
ECHO.

ECHO 6. Czyszczenie bazy danych i uruchamianie migracji (migrate:fresh)...
docker-compose exec app php artisan migrate:fresh --seed
IF %ERRORLEVEL% NEQ 0 (
    ECHO OSTRZEZENIE: Komenda 'migrate:fresh --seed' mogla sie nie powiesc.
    ECHO Sprawdz logi kontenerow: 'docker-compose logs app' oraz logi bazy danych.
) ELSE (
    ECHO Baza danych wyczyszczona, migracje i seedery wykonane.
)
ECHO.

ECHO ==================================================
ECHO Podstawowa konfiguracja aplikacji zakonczona! Aby uruchomić aplikację wpisz w terminalu polecenie docker-compose up --build
ECHO.


:EndScript
ENDLOCAL
pause

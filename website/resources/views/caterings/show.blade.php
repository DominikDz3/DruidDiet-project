@extends('layouts.app')

@section('title', htmlspecialchars($catering->title ?? 'Szczegóły Kateringu'))

@push('head_styles')
<style>
    .rating-stars span {
        font-size: 1.5rem; /* Rozmiar gwiazdek */
        color: #ccc; /* Kolor nieaktywnej gwiazdki */
        cursor: pointer;
        transition: color 0.2s;
    }
    .rating-stars span.filled,
    .rating-stars span:hover,
    .rating-stars span:hover ~ span { /* Efekt hover dla gwiazdek */
        color: #ffc107; /* Kolor aktywnej/hoverowanej gwiazdki (żółty Bootstrapa) */
    }
    .rating-stars input[type="radio"] {
        display: none; /* Ukryj oryginalne radio buttons */
    }
    .comment-card {
        border: 1px solid var(--card-border);
        background-color: var(--card-bg);
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.25rem;
    }
    .comment-author {
        font-weight: bold;
        color: var(--heading-color);
    }
    .comment-date {
        font-size: 0.85rem;
        color: var(--text-color-p);
    }
    .comment-body {
        margin-top: 0.5rem;
        color: var(--text-color);
    }
    .average-rating-stars span {
        font-size: 1.2rem;
        color: #ffc107;
    }
    .average-rating-stars span.empty {
        color: #ccc;
    }
</style>
@endpush

@section('content')
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="catering-detail-container">
                <div class="row">
                    {{-- ... (istniejąca treść szczegółów cateringu: obrazek, opis, cena, formularz do koszyka) ... --}}
                    <div class="col-md-7">
                        <div class="catering-image-wrapper">
                            @if ($catering->photo)
                                <img src="{{ asset( $catering->photo) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @else
                                <img src="https://via.placeholder.com/800x400.png?text={{ urlencode(htmlspecialchars($catering->title ?? 'Katering')) }}" class="img-fluid" alt="{{ htmlspecialchars($catering->title ?? 'Katering') }}">
                            @endif
                        </div>

                        <div class="catering-main-info mt-4">
                            <h2 class="card-title-detail">{{ ($catering->title ?? 'Brak tytułu') }}</h2>
                            <p class="text-muted"><small><strong>Typ:</strong> {{ htmlspecialchars($catering->type ?? 'N/A') }}</small></p>

                            <h5 class="mt-4">Opis:</h5>
                            <p class="card-text fs-5">{{ nl2br(htmlspecialchars($catering->description ?? 'Brak opisu.')) }}</p>

                            @if (!empty($catering->elements))
                                <h5 class="mt-4">Skład:</h5>
                                <p class="card-text">{{ ($catering->elements) }}</p>
                            @endif

                            @if (!empty($catering->allergens))
                                <h5 class="mt-4 text-danger">Alergeny:</h5>
                                <p class="card-text text-danger fw-bold">{{ htmlspecialchars($catering->allergens) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="catering-sidebar">
                            <h3 class="mb-4">Informacje o kateringu</h3>
                            <p class="price-tag-detail">{{ htmlspecialchars(number_format($catering->price ?? 0, 2, ',', ' ')) }} zł</p>

                            <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $catering->catering_id }}">
                                <input type="hidden" name="product_type" value="catering">
                                <div class="d-flex align-items-center mb-3">
                                    <label for="quantity" class="form-label mb-0 me-2">Ilość:</label>
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-quantity" aria-label="Ilość" id="quantity">
                                </div>
                                <button type="submit" class="btn button">
                                    <i class="bi bi-cart-plus"></i> Dodaj do koszyka
                                </button>
                            </form>

                            <hr class="themed-hr">

                            <div class="mt-3">
                                <p class="mb-1"><small>Dostępność: <span class="text-success fw-bold">Dostępny</span></small></p>
                                <p><small>Dostawa: <span class="fw-bold">24-48h</span></small></p>
                            </div>
                        </div>
                    </div>
                </div>{{-- Koniec .row dla obrazka i sidebara --}}

                {{-- SEKCJA OCEN I KOMENTARZY --}}
                <hr class="themed-hr my-4">
                <section class="comments-section mt-4">
                    <h4 class="mb-3">Opinie i Oceny ({{ $totalRatings ?? 0 }})</h4>

                    @if(isset($averageRating) && $averageRating > 0)
                        <div class="mb-3">
                            <strong>Średnia ocena: {{ number_format($averageRating, 1) }} / 5</strong>
                            <div class="average-rating-stars d-inline-block ms-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="bi {{ $i <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }} {{ $i <= round($averageRating) ? '' : 'empty' }}"></span>
                                @endfor
                            </div>
                        </div>
                    @else
                         <p>Brak jeszcze ocen dla tego cateringu.</p>
                    @endif


                    @auth
                        <div class="add-comment-form mb-4 p-3 comment-card">
                            <h5>Dodaj swoją opinię:</h5>
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('comments.store', $catering->catering_id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Twoja ocena:</label>
                                    <div class="rating-stars" id="ratingStars">
                                        <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == 5 ? 'checked' : '' }} required><label for="star5" title="5 gwiazdek"><span class="bi bi-star"></span></label>
                                        <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }}><label for="star4" title="4 gwiazdki"><span class="bi bi-star"></span></label>
                                        <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }}><label for="star3" title="3 gwiazdki"><span class="bi bi-star"></span></label>
                                        <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }}><label for="star2" title="2 gwiazdki"><span class="bi bi-star"></span></label>
                                        <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }}><label for="star1" title="1 gwiazdka"><span class="bi bi-star"></span></label>
                                    </div>
                                    @error('rating') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="comment_text" class="form-label">Komentarz:</label>
                                    <textarea class="form-control" id="comment_text" name="comment_text" rows="3" required minlength="10" maxlength="1000">{{ old('comment_text') }}</textarea>
                                    @error('comment_text') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <button type="submit" class="btn button">Dodaj opinię</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}" class="alert-link">Zaloguj się</a>, aby dodać opinię.
                        </div>
                    @endauth

                    <div class="existing-comments mt-4">
                        @forelse ($catering->comments as $comment)
                            <div class="comment-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="comment-author">{{ $comment->user->name ?? 'Anonim' }} {{ $comment->user->surname ?? '' }}</span>
                                    <span class="comment-date">{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y') }}</span>
                                </div>
                                <div class="rating-stars d-block my-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="bi {{ $i <= $comment->rating ? 'bi-star-fill filled' : 'bi-star' }}"></span>
                                    @endfor
                                </div>
                                <p class="comment-body">{{ nl2br(e($comment->comment_text)) }}</p>
                            </div>
                        @empty
                            @if(!(isset($averageRating) && $averageRating > 0)) {{-- Pokaż tylko jeśli nie ma też średniej oceny --}}
                            <p>Brak komentarzy dla tego kateringu. Bądź pierwszy!</p>
                            @endif
                        @endforelse
                    </div>
                </section>
                {{-- KONIEC SEKCJI OCEN I KOMENTARZY --}}

                <div class="mt-4 text-center text-md-start"> {{-- Ten link był na końcu, więc zostawiam --}}
                    <a href="{{ route('caterings.index') }}" class="back-link">
                        <i class="bi bi-arrow-left"></i> Powrót do listy kateringów
                    </a>
                </div>

            </div> {{-- Koniec .catering-detail-container --}}
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ratingStarsContainer = document.getElementById('ratingStars');
    if (ratingStarsContainer) {
        const stars = ratingStarsContainer.querySelectorAll('label span.bi-star, label span.bi-star-fill');
        const radios = ratingStarsContainer.querySelectorAll('input[type="radio"]');

        // Funkcja do aktualizacji wyglądu gwiazdek na podstawie zaznaczonego radio
        function updateStarsAppearance() {
            let selectedRating = 0;
            radios.forEach(radio => {
                if (radio.checked) {
                    selectedRating = parseInt(radio.value);
                }
            });
            stars.forEach((star, index) => {
                // Gwiazdki są w odwrotnej kolejności w HTML (5 do 1)
                const starValue = 5 - index;
                if (starValue <= selectedRating) {
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill', 'filled');
                } else {
                    star.classList.remove('bi-star-fill', 'filled');
                    star.classList.add('bi-star');
                }
            });
        }

        // Inicjalizacja wyglądu gwiazdek
        updateStarsAppearance();

        // Dodanie event listenerów do radio buttons
        radios.forEach(radio => {
            radio.addEventListener('change', updateStarsAppearance);
        });

        // Dodanie event listenerów do labeli (dla efektu hover i kliknięcia)
        // To jest bardziej skomplikowane, bo labele są po inputach.
        // Dla uproszczenia, można użyć JS do odwrócenia kolejności lub stylować hover na label.
        // Prostszy efekt hover:
        const labels = ratingStarsContainer.querySelectorAll('label');
        labels.forEach(label => {
            const starSpan = label.querySelector('span');
            label.addEventListener('mouseover', function() {
                const ratingValue = parseInt(this.getAttribute('for').replace('star', ''));
                stars.forEach((s, index) => {
                    const starVal = 5 - index;
                    if (starVal <= ratingValue) {
                        s.style.color = '#ffc107'; // Kolor hover
                    }
                });
            });
            label.addEventListener('mouseout', function() {
                stars.forEach(s => {
                    if (!s.classList.contains('filled')) { // Resetuj tylko jeśli nie jest 'filled' przez radio
                         s.style.color = ''; // Wróć do domyślnego (z CSS)
                    }
                });
                // Przywróć stan zaznaczonych
                updateStarsAppearance();
            });
        });
    }
});
</script>
@endpush
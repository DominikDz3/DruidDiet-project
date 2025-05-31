@extends('layouts.admin')

@section('title', 'Zarządzanie Komentarzami')

@push('styles')
<style>
    .comment-text-cell {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .rating-display span {
        color: #ffc107; /* Kolor gwiazdek */
    }
    .rating-display span.empty {
        color: #e0e0e0; /* Kolor pustych gwiazdek */
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Komentarze i Oceny</h5>
            {{-- Możesz tu dodać formularz wyszukiwania --}}
            <form method="GET" action="{{ route('admin.comments.index') }}" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Szukaj w komentarzach..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary">Szukaj</button>
            </form>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Autor</th>
                            <th>Treść</th>
                            <th>Ocena</th>
                            <th>Dotyczy</th>
                            <th>Data dodania</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                        <tr>
                            <td>{{ $comment->comment_id }}</td>
                            <td>
                                @if($comment->user)
                                    <a href="{{ route('admin.users.show', $comment->user->user_id) }}">{{ $comment->user->name }} {{ $comment->user->surname }}</a>
                                    <br><small class="text-muted">{{ $comment->user->email }}</small>
                                @else
                                    <span class="text-muted">Użytkownik usunięty</span>
                                @endif
                            </td>
                            <td class="comment-text-cell" title="{{ $comment->comment_text }}">
                                {{ Str::limit($comment->comment_text, 70) }}
                            </td>
                            <td class="rating-display">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="fas fa-star{{ $i <= $comment->rating ? '' : '-empty empty' }}"></span>
                                @endfor
                                ({{ $comment->rating }}/5)
                            </td>
                            <td>
                                @if($comment->catering)
                                    Katering: <a href="{{ route('caterings.show', $comment->catering->catering_id) }}" target="_blank">{{ Str::limit($comment->catering->title, 30) }}</a>
                                @elseif($comment->diet)
                                    Dieta: <a href="#" target="_blank">{{ Str::limit($comment->diet->title, 30) }}</a> {{-- TODO: Zaktualizuj link do diety, jeśli istnieje --}}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i') }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCommentModal-{{ $comment->comment_id }}">
                                    <i class="fas fa-trash-alt"></i> Usuń
                                </button>

                                <div class="modal fade" id="deleteCommentModal-{{ $comment->comment_id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $comment->comment_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel-{{ $comment->comment_id }}">Potwierdź usunięcie</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Czy na pewno chcesz usunąć ten komentarz? <br>
                                                <small class="text-muted">"{{ Str::limit($comment->comment_text, 100) }}"</small>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                                                <form action="{{ route('admin.comments.destroy', $comment->comment_id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Usuń</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Brak komentarzy do wyświetlenia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $comments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Opcjonalny skrypt
</script>
@endpush
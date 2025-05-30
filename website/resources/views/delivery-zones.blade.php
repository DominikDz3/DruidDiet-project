@extends('layouts.app')

@section('title', 'Strefy Dostaw - DruidDiet')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Strefy Dostaw</h1>
            <p class="lead">Poniżej znajduje się mapa z naszymi strefami dostaw. Sprawdź, czy dowozimy do Twojej lokalizacji!</p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                        
                    <div style="width: 100%; height: 450px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d82044.39162808233!2d21.894016011021503!3d50.01346869087415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x473cfae3cc14d449%3A0xd2240d31b33eb2ed!2zUnplc3rDs3c!5e0!3m2!1spl!2spl!4v1748610673206!5m2!1spl!2spl"                
                                width="100%"
                                height="450"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.obs')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="section-race">
                    <p>
                       @if(is_numeric($race->nummer))
                            {{ $race->nummer }}. {{ $race->rennBezeichnung }}
                       @else
                            {{ $race->nummer }} / {{ $race->rennBezeichnung }}
                       @endif
                    </p>
                    <p>
                        {{-- Uhrzeit im deutschen Format anzeigen --}}
                        @if(!empty($race->startzeit))
                            @php
                                use Carbon\Carbon;
                                $startzeit = Carbon::parse($race->startzeit)->format('H:i \U\h\r');
                            @endphp
                            Startzeit: {{ $startzeit }}
                        @endif
                    </p>
                </div>
            </div>
        </section>
    </main>

@endsection

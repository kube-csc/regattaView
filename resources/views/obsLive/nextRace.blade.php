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
                </div>
            </div>
        </section>
    </main>

@endsection

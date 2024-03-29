@if($boards->count() > 0 )
    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
        <div class="container">

            <div class="section-title" data-aos="fade-in" data-aos-delay="100">
                <h2>{{ $sportSectionTeamName }}</h2>
                @php /*
                 ToDo: Text muss noch bearbeitet werden
                 */
                @endphp
                <p>
                </p>
            </div>

            <div class="row">
                @php
                    $delay=50;
                    $delayname ="data-aos-delay=\"".$delay."\"";
                @endphp
                @foreach($boards as $board)
                    <div class="col-lg-3 col-md-6" >
                        @if($delay == 50)
                            <div class="member" data-aos="fade-up">
                                @else
                                    <div class="member" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                                        @endif
                                        <div class="pic">
                                            @if(isset($board->postenPortraet))
                                                <img src="{{ env('VEREIN_URL') }}/storage/boardPortrait/{{ $board->postenPortraet }}" class="img-fluid"
                                                     alt="{{ $board->geschlecht=='m' ? $board->postenMaenlich : $board->postenWeiblich }}">
                                            @else
                                                <img src="{{ env('VEREIN_URL') }}/asset/img/postenLeer.jpg" class="img-fluid">
                                            @endif
                                        </div>
                                        <div class="member-info">
                                            <h4>{{ $board->vorname }} {{ $board->nachname }}</h4>
                                            <span>
                            @if($board->nummer>0)
                                                    {{ $board->nummer }}.
                                                @endif
                                                @if($board->geschlecht=='m')
                                                    {{ $board->postenMaenlich }}
                                                @else
                                                    {{ $board->postenWeiblich }}
                                                @endif
                        </span>
                                            <div class="social">
                                                @php
                                                    /*
                                                    ToDo: Socialmedia für Teams
                                                    <a href=""><i class="icofont-twitter"></i></a>
                                                    <a href=""><i class="icofont-facebook"></i></a>
                                                    <a href=""><i class="icofont-instagram"></i></a>
                                                    <a href=""><i class="icofont-linkedin"></i></a>
                                                    */
                                                @endphp
                                                @php
                                                    /*
                                                    @if(isset($board->vorstandsemail))
                                                     <a href=""><i class="icofont-mail"></i>{{ $board->vorstandsemail }}</a>
                                                    @else
                                                      @if(isset($board->email) | $board->email != str_replace("@domain.de", "" , $board->email))
                                                        <a href=""><i class="icofont-mail"></i>{{ $board->email }}</a>
                                                      @endif
                                                    @endif
                                                    */
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            @php
                                $delay=$delay+50;
                            @endphp
                            @endforeach
                    </div>
            </div>
    </section><!-- End Team Section -->
@endif

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    @php
        $vereinsname = str_replace('_', ' ', env('VEREIN_DOMAIN'));
    @endphp
    <title> @yield( 'title' , '$vereinsname' ) </title>
    @php
        // ToDo: Meta Conten bearbeiten
        $description = str_replace('_', ' ', env('VEREIN_KEYWORDS'));
        $keywords    = str_replace('_', ' ', env('VEREIN_DESCRIPTION'));
    @endphp

    <!-- Favicons -->
    <link href="/favicon.ico" rel="icon">
    <link href="/apple-touch-icon.png" rel="apple-touch-icon">

    <?php /*
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    */ ?>

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/venobox/venobox.css" rel="stylesheet">
    <link href="/assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/assets/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Squadfree - v2.2.0
    * Template URL: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top header-transparent">
    <div class="container d-flex align-items-center">

        <div class="logo mr-auto">
            <h1 class="text-light"><a href="{{env('APP_URL')}}"><span>{{ str_replace('_' , ' ' , env('VIEWREGATTA_DOMAIN')) }}</span></a></h1>
            <!-- Uncomment below if you prefer to use an image logo -->
            <!-- <a href="index.html"><img src="/assets/img/logo.png" alt="" class="img-fluid"></a> -->
        </div>

        <nav class="nav-menu d-none d-lg-block">
            <ul>
                <!-- <li class="active"><a href="index.html">Home</a></li> -->
                <li class="active"><a href="/#about">Home</a></li>
                <li><a href="/#portfolio">Aktueller Stand</a></li>
                <li><a href="/#team">Team</a></li>
                <li class="drop-down"><a href="">Programm</a>
                    <ul>
                        <li><a href="/Programm">alle Rennen</a></li>
                        <li><a href="/Programm/geplante">geplante Rennen</a></li>
                        <li><a href="/Ergebnisse">gewertete Rennen</a></li>
                        <li><a href="/Tabellen">Tabellen</a></li>
                        <li><a href="{{ route('program.selectTeamFilter') }}">Mannschaft filtern</a></li>
                    </ul>
                </li>
                <li><a href="/Dokumente">Dokumente</a></li>
                <li><a href="/#contact">Kontakt</a></li>
            </ul>
        </nav><!-- .nav-menu -->

    </div>
</header><!-- End Header -->

@yield('content')

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="footer-info" data-aos="fade-up" data-aos-delay="50">
                        <h3>{{ str_replace('_', ' ', env('VEREIN_NAME')) }}</h3>
                        <?php
                        /*
                          <p class="pb-3"><em>Qui repudiandae et eum dolores alias sed ea. Qui suscipit veniam excepturi quod.</em></p>
                          // QUESTION: : Warum em
                        */
                        ?>
                        <p>
                            {{ str_replace('_', ' ', env('VEREIN_NAME')) }}<br>
                            {{ str_replace('_', ' ', env('VEREIN_STRASSE')) }}<br>
                            {{ str_replace('_', ' ', env('VEREIN_PLZ')) }} {{ str_replace('_', ' ', env('VEREIN_ORT')) }}<br>
                            @if(env('VEREIN_TELEFON=')<>"")
                                <i class="icofont-telephone"></i>{{ str_replace('_' , ' ' , env('VEREIN_TELEFON')) }}<br>
                            @endif
                            @if(env('VEREIN_FAX')<>"")
                                <i class="icofont-fax"></i>{{ str_replace('_' , ' ' , env('VEREIN_FAX')) }}<br>
                            @endif
                            <i class="icofont-email"></i>
                            <a href="mailto:{{ str_replace('_' , ' ' , env('VEREIN_EMAIL')) }}">{{ str_replace('_' , ' ' , env('VEREIN_EMAIL')) }}</a>
                        </p>

                        <div class="social-links mt-3">
                            <a href="https://www.facebook.com/KELDatteln" class="facebook" target="_blank"><i class="bx bxl-facebook"></i></a>
                            <?php /*
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                */ ?>
                        </div>

                    </div>
                </div>

                <!--<div class="col-lg-2 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="150">-->
                <div class="col-lg-4 col-md-6 footer-newsletter" data-aos="fade-up" data-aos-delay="100">
                    @include('textimport.footer')
                </div>

                <?php /*
                        <div class="col-lg-2 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="150">
                          <h4>Useful Links</h4>
                          <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Services</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Terms of service</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li>
                          </ul>
                        </div>
                */ ?>
                <?php /*
        <div class="col-lg-2 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="250">
          <h4>Our Services</h4>
          <ul>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
            <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
          </ul>
        </div>
*/ ?>
                <div class="col-lg-4 col-md-6 footer-newsletter" data-aos="fade-up" data-aos-delay="150">
                    @php
                        /* ToD@o: Netsletter
                        <h4>Dein Newsletter</h4>
                        <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
                        <form action="" method="post">
                          <input type="email" name="email"><input type="submit" value="Subscribe">
                        </form>
                        <br>
                        */
                    @endphp
                        @php
                            $abteilungDomains  = DB::table('sport_sections')
                            ->where('status' , '>' , '1')
                            ->where('domain' , '!=' , '')
                            ->orderby('abteilung')
                            ->get();
                            $count=$abteilungDomains->count();
                        @endphp
                        @if($count>0)
                            <h4>Webseiten {{ env('MENUE_ABTEILUNG') }}</h4>
                            <ul>
                                @foreach($abteilungDomains as $abteilungDomain)
                                    <li><a href="http://{{$abteilungDomain->domain}}" target="_blank" class="bx bx-link-external"> {{$abteilungDomain->abteilung}}</a></li>
                                @endforeach
                            </ul>
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong><span>{{ env('VEREIN_NAME') }}</span></strong><br>
            All Rights Reserved
        </div>
        <div class="credits">
            <?php /* // NOTE:
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/squadfree-free-bootstrap-template-creative/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      <br><br>
      */?>
            <a href="/Information/Datenschutzerklärung">Datenschutzerklärung</a> | <a href="/Impressum">Impressum</a>
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/venobox/venobox.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/aos/aos.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('/assets/js/main.js') }}"></script>

</body>
</html>

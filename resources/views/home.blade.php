@extends('z')

@section('htmlheader_title')
Bu Ana Catering
@endsection

@push('html-header')
<meta name="description" content=""/>
<meta name="keywords" content=""/>
@endpush

@push('content-header')
<style>
    /* Custom Colors */
    .bg-red {
        background-color: #a00000;
    }
    .text-gold {
        color: #ffd700;
    }

    /* Hero Section Styling */
    .hero {
        background-size: cover;
        background-position: center;
        color: #fff;
        padding: 120px 0;
        text-align: center;
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../image/bg-1.jpeg');
        height: 100vh;
        color: white;
    }

    .hero h1 {
        font-size: 3rem;
        font-weight: bold;
    }

    .hero h2 {
        font-size: 2rem;
        font-weight: bold;
    }

    /* Section Styling */
    .section-title {
        font-size: 2.5rem;
        color: #a00000;
        margin-bottom: 1rem;
    }

    #services {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../image/bg-2.png');
        background-size: cover;
        background-position: center;
        color: #fff;
        padding: 120px 0;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="d-none d-lg-block">
                <h1 class="text-white" style="padding-top:20vh">Selamat Datang di Bu Ana Catering</h1>
                <p class="text-gold">Kepuasan Pelanggan adalah Motto Kami</p>
                <a href="#services" class="btn btn-light mt-4">MELAYANI</a>
            </div>
            <div class="d-lg-none d-md-block">
                <h2 class="text-white">Selamat Datang di Bu Ana Catering</h2>
                <p class="text-gold">Kepuasan Pelanggan adalah Motto Kami</p>
                <a href="#services" class="btn btn-light mt-4">MELAYANI</a>
            </div>
            
        </div>
    </div>

    <!-- About Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <p>Bu Ana Catering has been serving exceptional meals with a passion for flavor and quality. Whether it’s a wedding, corporate event, or private gathering, we ensure every dish leaves a lasting impression.</p>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="bg-light py-3">
        <div class="container">
            <h2 class="section-title text-center">Our Services</h2>
            <div class="row mt-2">
                <div class="col-md-4 text-center mt-2">
                    <div class="card border-0">
                        <img src="https://example.com/service1.jpg" class="card-img-top" alt="Wedding Catering">
                        <div class="card-body">
                            <h5 class="card-title">Wedding Catering</h5>
                            <p class="card-text">Make your big day memorable with a custom menu tailored to your taste and style.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mt-2">
                    <div class="card border-0">
                        <img src="https://example.com/service2.jpg" class="card-img-top" alt="Corporate Events">
                        <div class="card-body">
                            <h5 class="card-title">Corporate Events</h5>
                            <p class="card-text">From small meetings to large conferences, our catering will impress your guests.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mt-2">
                    <div class="card border-0">
                        <img src="https://example.com/service3.jpg" class="card-img-top" alt="Private Parties">
                        <div class="card-body">
                            <h5 class="card-title">Private Parties</h5>
                            <p class="card-text">Celebrate any occasion with our delicious and beautifully presented dishes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 text-center">
        <div class="container">
            <h2 class="section-title">Get in Touch</h2>
            <p>Ready to plan your next event? Contact us to learn more about our menu and services.</p>
            <a href="#" class="btn btn-red text-white">Contact Us</a>
        </div>
    </section>

@push('content-footer')
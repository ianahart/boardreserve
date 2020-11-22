@extends('layouts.layout')

@section('content')
<div class="home-container">
    <h1 class="welcome-message">{{ session('welcome') }}</h1>
    <div class="home-header-container">
        <header>
            <div>
                <h1>Buy Affordable New and Used Snowboards</h1>
                <h2>A Collection of Your Favorite Brands</h2>
            </div>
            <a href="/register/create">Sign up</a>
        </header>
        <div class="extra-information">
            <div>
                <h2>Trying To Bring The Snow Community Together</h2>
                <p>Make connections with other snowboarders</p>
                <i class="fas fa-handshake"></i>
            </div>
        </div>
    </div>
    <section class="home-main">
        <div class='gallery-text'>
            <h2>Buy a Board Today</h2>
            <h4>And get a free lift ticket to <span>Misty Mountain</span></h4>
            <div>
                <img src="/img/siren.png" />
                <p>Limited Time Offer!</p>
            </div>
        </div>
        <div class="home-gallery-container">
            <img src="/img/home-gallery-1.jpg" alt="mountains" />
            <img class="hidden" src="/img/home-gallery-2.jpg" alt="mountains" />
            <img class="hidden" src="/img/home-gallery-3.jpg" alt="mountains" />
            <div class="gallery-overlay"></div>
        </div>
    </section>
    <div class="home-bottom-divider"></div>
    <section class="social-information">
        <div id="contact-information">
            <h2>Join Our Community</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla dolorum labore repellendus, porro ut dicta eius maiores eveniet voluptates aut doloremque sed unde reprehenderit molestias. Aspernatur, quasi molestiae accusantium non vel eveniet earum mollitia officiis ea adipisci, necessitatibus molestias a.</p>
        </div>
        <div class="social-icons">
            <p>Follow us <i class="fas fa-arrow-right"></i></p>
            <a href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
            <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
        </div>
    </section>
</div>
<script src="/js/home.js"></script>
@endsection
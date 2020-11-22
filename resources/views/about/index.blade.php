@extends('layouts.layout')

@section('content')
<div class="about-banner">
  <div class="about-overlay">
    <div class="heading">
      <h1>About The Reserve</h1>
      <img class="about-heading-image" src="/img/about-mountain.png" alt="mountains" />
      <h3><i class="fas fa-flag"></i> Established in 2020</h3>
    </div>
  </div>
</div>
<div class="company-quote">
  <h2>“All our dreams can come true, if we have the courage to pursue them.”</h2>
</div>
<div class="about-content-container">
  <div class="about-content">
    <div class="about-company hidden">
      <h3>About BoardReserve</h3>
      <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Qui suscipit corrupti laudantium eos, soluta architecto laboriosam quibusdam! Veritatis explicabo rem, adipisci tempore odio nisi! Doloribus obcaecati ipsam commodi adipisci! Accusamus delectus eos fuga aliquam neque eligendi, corrupti unde facilis veritatis ea eaque. Nisi perferendis reprehenderit voluptates facere, inventore praesentium necessitatibus? Nostrum hic quas ab iusto soluta dolorum molestias aperiam ad aliquam, ea necessitatibus amet cum nam? Provident fugit et beatae.</p>
    </div>
    <div class="about-me hidden">
      <div class="me-image-container">
        <img src="/img/ian.png" alt="a headshot of ian" />
      </div>
      <h3>About Ian</h3>
      <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima natus unde vitae eius reiciendis! Exercitationem natus nemo consequuntur nam dolorum, pariatur temporibus, magnam quo nulla nobis suscipit consectetur repellat nihil sequi quaerat perspiciatis excepturi tempora optio et facilis esse laudantium atque at. Libero velit tempore enim quia necessitatibus asperiores, quam nulla dolore reiciendis mollitia culpa doloremque. Molestiae rem vel cupiditate!</p>
    </div>
  </div>
</div>
<div class="about-divider">
</div>
<div class="contact-questions">
  <div class="contact-img-container">
    <img src="/img/question_mark.png" alt="question mark" />
  </div>
  <h3><span>Please </span>feel free to reach out if you have any questions</h3>
  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt, ducimus! Officiis animi perspiciatis consequatur? Ipsa vel eius quis enim quia corrupti magni perspiciatis nisi atque inventore, neque itaque. Molestias, illum laborum. Neque eius repellat vel eligendi quos quas impedit nisi?</p>
  <p><span>-Ian</span></p>
</div>
<script src="/js/about.js"></script>
@endsection
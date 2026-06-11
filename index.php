<?php
/* index.php — Home / Hero page */

$pageTitle = 'The Amazing World of Gumball Fan Site';
$bodyClass = 'page-home';
require_once 'includes/header.php';
?>

<!-- HERO -->
<main class="hero">

    <div class="hero-overlay"></div>

    <div class="hero-content">

        <img src="/assets/images/tawog-logo.png"
             alt="The Amazing World of Gumball"
             class="hero-logo">

        <h1 class="hero-welcome">
            WELCOME TO,
            <span>THE AMAZING WORLD OF GUMBALL!</span>
        </h1>

        <div class="hero-desc">
            <p>
                Selamat datang di fan site tidak resmi
                <strong>The Amazing World of Gumball</strong> —
                serial animasi Cartoon Network karya Ben Bocquelet
                yang mengikuti petualangan Gumball Watterson,
                seekor kucing biru yang tinggal di kota Elmore
                bersama keluarga dan teman-temannya yang unik.
            </p>
            <p>
                Jelajahi ensiklopedi karakter, daftar episode,
                dan koleksi quote favoritmu!
            </p>
        </div>

    </div>

</main>
<!-- /HERO -->

<?php require_once 'includes/footer.php'; ?>

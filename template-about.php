<?php
/**
 * Template Name: About Us Page
 *
 * This is the template that displays the custom About Us page.
 * It relies on assets enqueued in functions.php to style correctly.
 *
 * @package PriyanshTours
 */

get_header(); // This will include your theme's header file.
?>

<div class="about-us-page-wrapper">
	<main>
		<!-- HERO SECTION -->
		<section class="hero-section" style="background-image: url('https://placehold.co/1600x800/334155/f1f5f9?text=Our+Adventures');">
			<div class="container">
				<h1>Crafting Your Unforgettable Journeys</h1>
				<p>Discover the world with those who know it best. Welcome to Priyansh Tours.</p>
			</div>
		</section>

		<!-- OUR STORY SECTION -->
		<section class="our-story-section animate-on-scroll">
			<div class="container">
				<div class="flex-container">
					<div class="image-container">
						<img src="https://placehold.co/600x400/F57A45/ffffff?text=Our+Beginning" alt="A map and a compass" class="story-image">
					</div>
					<div class="text-container">
						<h2>Our Story</h2>
						<p>
							Priyansh Tours began with a simple idea: to share the magic of authentic travel. It started not in a boardroom, but on a dusty trail in the Himalayas, with our founder, Gourav Giri. His passion for exploration and genuine connection grew into a mission to offer the same life-changing experiences to others.
						</p>
						<p>
							From a one-man operation fueled by passion, we've grown into a company known for crafting unique, personal adventures across the globe.
						</p>
					</div>
				</div>
			</div>
		</section>

		<!-- WHY CHOOSE US SECTION -->
		<section class="why-choose-section animate-on-scroll">
			<div class="container">
				<h2>Why Travel With Us?</h2>
				<div class="cards-grid">
					<div class="about-card animate-on-scroll animate-delay-100">
						<div class="icon-container bg-green-100">
							<i class='bx bx-map-alt text-green-600'></i>
						</div>
						<h3>Authentic Experiences</h3>
						<p>We craft journeys that immerse you in local culture, away from the tourist traps.</p>
					</div>
					<div class="about-card animate-on-scroll animate-delay-200">
						<div class="icon-container bg-orange-100">
							<i class='bx bx-star text-orange-500'></i>
						</div>
						<h3>Expert-Led Journeys</h3>
						<p>Our guides are passionate locals who bring destinations to life with their knowledge and stories.</p>
					</div>
					<div class="about-card animate-on-scroll animate-delay-300">
						<div class="icon-container bg-green-100">
							<i class='bx bx-leaf text-green-600'></i>
						</div>
						<h3>Sustainable Travel</h3>
						<p>We are committed to responsible tourism that supports local communities and protects the environment.</p>
					</div>
				</div>
			</div>
		</section>

		<!-- MEET THE FOUNDER SECTION -->
		<section class="founder-section animate-on-scroll">
			<div class="container">
				<div class="text-center">
					<h2>Meet the Founder</h2>
				</div>
				<div class="founder-container">
					<div class="founder-profile">
						<img class="founder-image" src="https://placehold.co/200x200/F57A45/ffffff?text=GG" alt="Founder Gourav Giri">
						<h3>Gourav Giri</h3>
						<p class="founder-title">Founder & Lead Explorer</p>
					</div>
					<div class="founder-bio">
						<p class="founder-quote">
						   "Travel is more than seeing sights; it's about the feeling of a place, the taste of its food, the warmth of its people. That's the magic I want to share. Every journey we design is an invitation to connect, to learn, and to return home with more than just memories. You return with a story."
						</p>
					</div>
				</div>
			</div>
		</section>
		
		<!-- PHOTO GALLERY SECTION -->
		<section class="gallery-section animate-on-scroll">
			<div class="container">
				<h2>Glimpses From Our Adventures</h2>
				<div class="gallery-grid">
					<img src="https://placehold.co/400x300/F59E0B/ffffff?text=Destination+1" alt="Travel destination" class="animate-on-scroll animate-delay-100">
					<img src="https://placehold.co/400x300/10B981/ffffff?text=Destination+2" alt="Travel destination" class="animate-on-scroll animate-delay-200">
					<img src="https://placehold.co/400x300/3B82F6/ffffff?text=Destination+3" alt="Travel destination" class="animate-on-scroll animate-delay-300">
					<img src="https://placehold.co/400x300/8B5CF6/ffffff?text=Destination+4" alt="Travel destination" class="animate-on-scroll animate-delay-100">
					<img src="https://placehold.co/400x300/EC4899/ffffff?text=Destination+5" alt="Travel destination" class="animate-on-scroll animate-delay-200">
					<img src="https://placehold.co/400x300/F97316/ffffff?text=Destination+6" alt="Travel destination" class="animate-on-scroll animate-delay-300">
					<img src="https://placehold.co/400x300/6366F1/ffffff?text=Destination+7" alt="Travel destination" class="animate-on-scroll animate-delay-100">
					<img src="https://placehold.co/400x300/14B8A6/ffffff?text=Destination+8" alt="Travel destination" class="animate-on-scroll animate-delay-200">
				</div>
			</div>
		</section>

		<!-- CTA SECTION -->
		<section class="cta-section animate-on-scroll" style="background-color: #F57A45; color: white; text-align: center; padding: 60px 0;">
			<div class="container">
				<h2 style="color: white; margin-bottom: 20px;">Ready to Start Your Adventure?</h2>
				<p style="margin-bottom: 30px; font-size: 1.2rem;">Explore our packages and discover your next unforgettable journey.</p>
				<a href="/tour-packages" class="cta-button" style="background-color: white; color: #F57A45; padding: 12px 30px; border-radius: 30px; font-weight: bold; text-decoration: none; display: inline-block; transition: all 0.3s ease;">View Our Tours</a>
			</div>
		</section>
	</main>
</div>

<script>
// This small inline script ensures the animations script can find elements
// even if the external animation script loads with delay
document.addEventListener('DOMContentLoaded', function() {
    // Add the necessary classes to the body
    document.body.classList.add('has-animations');
});
</script>

<?php
get_footer(); // This will include your theme's footer file.
?>

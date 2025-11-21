<template>
  <div class="home-container">
    <!-- Background Elements -->
    <div class="sky-gradient-bg"></div>
  
    

    <!-- Main Content -->
    <main class="main-content">
      <!-- Hero Section -->
      <div class="hero-section">
        <!-- Decorative Clouds -->
        <i class="fas fa-cloud cloud-icon cloud-1"></i>
        <i class="fas fa-cloud cloud-icon cloud-2"></i>

        <h2 class="hero-subtitle">fly</h2>
        
        <div class="hero-price-wrapper">
          <h1 class="hero-price-bg">0 THB</h1>
          <h1 class="hero-price-fg">0 THB</h1>
        </div>
        
        <p class="hero-badge">
          🔥 Starting Price / One-way (Excl. Tax)
        </p>
      </div>

      <!-- Auto-Scrolling Promo Banners -->
      <div class="marquee-wrapper">
        <div class="marquee-container">
          <div class="marquee-content">
            <!-- Set A -->
            <div v-for="(promo, index) in promos" :key="'a'+index" class="promo-card card-yellow">
              <div class="card-header">
                <div>
                  <div class="card-label">Flight Deal</div>
                  <div class="card-route">{{ promo.route }}</div>
                </div>
                <i class="fas fa-plane card-icon-yellow"></i>
              </div>
              <div class="card-body">
                <div class="card-price-label">Starting from</div>
                <div class="card-price">{{ promo.price }} <span>THB</span></div>
              </div>
              <div class="card-tag-red">Limited</div>
            </div>

            <!-- Set B (Duplicate for loop) -->
            <div v-for="(promo, index) in promos" :key="'b'+index" class="promo-card card-red">
              <div class="card-header">
                <div>
                  <div class="card-label">Hot Route</div>
                  <div class="card-route">{{ promo.route }}</div>
                </div>
                <i class="fas fa-plane card-icon-red"></i>
              </div>
              <div class="card-body">
                <div class="card-price-label">Starting from</div>
                <div class="card-price">{{ promo.price }} <span>THB</span></div>
              </div>
              <div class="card-tag-yellow">Book Now</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Search Bar (Visual) -->
      <div class="search-bar-container">
        <div class="glass-search-bar">
          <div class="search-input-group">
            <i class="fas fa-map-marker-alt map-icon"></i>
            <div class="search-text">
              <span class="label">Where to?</span>
              <span class="value">Bangkok (BKK) <i class="fas fa-arrow-right arrow-icon"></i> Phuket (HKT)</span>
            </div>
          </div>
          <div class="divider"></div>
          <div class="search-date-group">
            <span class="label">Date</span>
            <span class="value">21 Nov 2021</span>
          </div>
          <button class="btn-search">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </main>

    <!-- Foreground Fade -->
    <div class="foreground-fade"></div>
  </div>
</template>

<script>
export default {
  name: 'HomeView',
  // เปลี่ยนจาก setup() ที่ใช้ import มาเป็น data() แบบ Options API เพื่อแก้ปัญหา import error
  data() {
    return {
      promos: [
        { route: 'Bangkok - Chiang Mai', price: '450' },
        { route: 'Bangkok - Phuket', price: '10' },
        { route: 'Phuket - Chiang Rai', price: '0' },
        { route: 'Bangkok - Osaka', price: '2,990' },
        { route: 'Krabi - Singapore', price: '1,200' },
        { route: 'Bangkok - Da Nang', price: '990' }
      ]
    }
  }
}
</script>

<style scoped>
/* Import Fonts & Icons */
@import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,600;0,800;1,800&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.home-container {
  font-family: 'Kanit', sans-serif;
  min-height: 100vh;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  color: white;
}

/* Background Animation */
.sky-gradient-bg {
  position: absolute;
  inset: 0;
  z-index: -1;
  background: radial-gradient(circle at 50% 120%, #ffcd00 0%, #ff8a65 20%, #4fc3f7 60%, #0288d1 100%);
  background-size: 200% 200%;
  animation: sky-shift 20s infinite alternate;
}

@keyframes sky-shift {
  0% { background-position: 50% 0%; }
  100% { background-position: 50% 100%; }
}

/* Header Styles */
.header {
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 50;
}

.brand {
  font-size: 2.25rem;
  font-weight: 900;
  font-style: italic;
  letter-spacing: -0.05em;
  text-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.brand-text-red {
  color: #D6001C;
  -webkit-text-stroke: 1px white;
}

.nav-menu {
  display: none;
}

@media (min-width: 768px) {
  .nav-menu {
    display: flex;
    gap: 1.5rem;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
  }
  .nav-item {
    color: white;
    text-decoration: none;
    transition: color 0.3s;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
  }
  .nav-item:hover {
    color: #fde047;
  }
}

.btn-join {
  background-color: #facc15;
  color: #b91c1c;
  padding: 0.5rem 1.5rem;
  border-radius: 9999px;
  font-weight: 900;
  border: none;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: transform 0.2s, background-color 0.2s;
}

.btn-join:hover {
  background-color: #fde047;
  transform: scale(1.05);
}

/* Main Content */
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  z-index: 10;
  padding: 0 1rem;
}

/* Hero Section */
.hero-section {
  text-align: center;
  margin-bottom: 3rem;
  position: relative;
}

.hero-subtitle {
  font-size: 2rem;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 0.5rem;
  color: #fde047;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.hero-price-wrapper {
  position: relative;
  display: inline-block;
  transform: rotate(-2deg);
}

.hero-price-bg {
  font-size: 6rem;
  line-height: 1;
  font-weight: 900;
  font-style: italic;
  position: absolute;
  inset: 0;
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
  background-image: linear-gradient(to bottom, #fde047, #ca8a04);
  filter: blur(4px);
  margin: 0;
}

.hero-price-fg {
  font-size: 6rem;
  line-height: 1;
  font-weight: 900;
  font-style: italic;
  position: relative;
  color: transparent;
  background-clip: text;
  -webkit-background-clip: text;
  background-image: linear-gradient(to bottom, #fde047, #eab308);
  -webkit-text-stroke: 2px #D6001C;
  filter: drop-shadow(0 25px 25px rgba(0, 0, 0, 0.15));
  margin: 0;
}

@media (min-width: 768px) {
  .hero-price-bg, .hero-price-fg {
    font-size: 9rem;
  }
  .hero-subtitle {
    font-size: 3rem;
  }
}

.hero-badge {
  display: inline-block;
  font-size: 1.125rem;
  font-weight: 500;
  margin-top: 1rem;
  padding: 0.5rem 2rem;
  border-radius: 9999px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Animations */
.cloud-icon {
  position: absolute;
  color: white;
  opacity: 0.2;
}
.cloud-1 { top: -40px; left: 10%; font-size: 6rem; animation: float 6s ease-in-out infinite; }
.cloud-2 { top: 20px; right: 15%; font-size: 4rem; animation: float 4s ease-in-out infinite; animation-delay: 1s; }

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .8; }
}

/* Marquee / Cards */
.marquee-wrapper {
  width: 100%;
  max-width: 72rem;
  margin: 0 auto 2rem;
  overflow: hidden;
}

.marquee-container {
  mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  padding: 1rem 0;
}

.marquee-content {
  display: inline-flex;
  gap: 1.5rem;
  animation: scroll 25s linear infinite;
}

.marquee-content:hover {
  animation-play-state: paused;
}

@keyframes scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.promo-card {
  width: 18rem;
  height: 10rem;
  border-radius: 1rem;
  padding: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  cursor: pointer;
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  position: relative;
  flex-shrink: 0;
}

.promo-card:hover {
  transform: scale(1.05) translateY(-5px);
  z-index: 10;
}

.card-yellow {
  background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%);
  color: #1f2937;
  border-left: 8px solid #facc15;
}

.card-red {
  background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%);
  color: #1f2937;
  border-left: 8px solid #ef4444;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.card-label {
  font-size: 0.75rem;
  font-weight: bold;
  color: #9ca3af;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.card-route {
  font-size: 1.125rem;
  font-weight: 900;
  color: #dc2626;
  line-height: 1.25;
  margin-top: 0.25rem;
}

.card-icon-yellow { font-size: 1.5rem; color: #facc15; transform: rotate(-45deg); }
.card-icon-red { font-size: 1.5rem; color: #ef4444; transform: rotate(-45deg); }

.card-price-label { font-size: 0.75rem; color: #6b7280; }
.card-price { font-size: 1.875rem; font-weight: 900; color: #111827; }
.card-price span { font-size: 0.75rem; font-weight: normal; }

.card-tag-red {
  position: absolute;
  bottom: -0.5rem;
  right: -0.5rem;
  background-color: #dc2626;
  color: white;
  font-size: 0.75rem;
  padding: 0.25rem 0.75rem;
  border-top-left-radius: 0.5rem;
  font-weight: bold;
}

.card-tag-yellow {
  position: absolute;
  bottom: -0.5rem;
  right: -0.5rem;
  background-color: #facc15;
  color: #991b1b;
  font-size: 0.75rem;
  padding: 0.25rem 0.75rem;
  border-top-left-radius: 0.5rem;
  font-weight: bold;
}

/* Search Bar */
.search-bar-container {
  margin-top: auto;
  margin-bottom: 2.5rem;
  width: 100%;
  max-width: 56rem;
  margin-left: auto;
  margin-right: auto;
}

.glass-search-bar {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.4);
  border-radius: 9999px;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 5rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  animation: float 6s ease-in-out infinite;
}

.search-input-group {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex: 1;
  padding-left: 2rem;
  color: rgba(255, 255, 255, 0.9);
}

.map-icon { font-size: 1.25rem; color: #facc15; }

.search-text { display: flex; flex-direction: column; }
.search-text .label { font-size: 0.75rem; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.7; }
.search-text .value { font-size: 1.125rem; font-weight: bold; }

.arrow-icon { margin: 0 0.5rem; font-size: 0.875rem; }

.divider { height: 2.5rem; width: 1px; background-color: rgba(255, 255, 255, 0.2); margin: 0 1rem; }

.search-date-group {
  display: none;
  flex-direction: column;
  margin-right: 2rem;
  color: rgba(255, 255, 255, 0.9);
}

@media (min-width: 768px) {
  .search-date-group { display: flex; }
}

.search-date-group .label { font-size: 0.75rem; font-weight: bold; text-transform: uppercase; opacity: 0.7; }
.search-date-group .value { font-size: 1.125rem; font-weight: bold; }

.btn-search {
  background-image: linear-gradient(to right, #dc2626, #b91c1c);
  color: white;
  border-radius: 9999px;
  height: 4rem;
  width: 8rem;
  font-weight: bold;
  font-size: 1.25rem;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.btn-search:hover {
  transform: scale(1.05);
}

/* Foreground */
.foreground-fade {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 8rem;
  background-image: linear-gradient(to top, rgba(255,255,255,0.2), transparent);
  pointer-events: none;
  z-index: 0;
}
</style>
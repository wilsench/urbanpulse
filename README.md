# URBANPULSE — "Data-driven Sustainable City Assistant"

[![Infinitera 2.0 Project](https://img.shields.io/badge/Competition-Infinitera%202.0-10b981.svg)](https://infinitera.id)
[![Laravel 12](https://img.shields.io/badge/Framework-Laravel%2012-ff2d20.svg)](https://laravel.com)
[![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777bb4.svg)](https://php.net)
[![Primary SDG 11](https://img.shields.io/badge/Primary%20SDG-SDG%2011%20Sustainable%20Cities-06b6d4.svg)](https://sdgs.un.org/goals/goal11)
[![Secondary SDG 13](https://img.shields.io/badge/Secondary%20SDG-SDG%2013%20Climate%20Action-10b981.svg)](https://sdgs.un.org/goals/goal13)

---

## 🌟 Executive Summary
UrbanPulse is a competition-grade web application built for **Infinitera 2.0** under the theme **"Bridging Innovation and Sustainability to Create Meaningful Impact for Future Generations"**.

UrbanPulse transforms real-world environmental, weather, geographic, and location data into actionable recommendations for sustainable daily decisions.

> **Core Value Proposition:**
> *"UrbanPulse transforms urban and environmental data into actionable recommendations for sustainable daily decisions."*

---

## 🏙️ Multi-City Architecture
UrbanPulse supports multiple cities dynamically (e.g. **Kota Bogor**, **Jakarta Selatan**, **Kota Bandung**), utilizing verified spatial boundaries, real-time BMKG weather forecasts, and Open Air Quality API data per active city.

---

## 🤖 AI Specification & Configuration System
The UrbanPulse AI Assistant ("Tanya UrbanPulse") is driven by a single version-controlled source of truth file:

`config/urbanpulse_ai.json`

This file controls the AI identity, role, language, communication style, tone of voice, allowed/forbidden emojis, data trust policies, limitations, and formatting rules.

When modifying `config/urbanpulse_ai.json`, run:
```bash
php artisan config:clear
php artisan config:cache
```

---

## 🚀 Key Modules
1. **Landing Page**: Competition hero, interactive questionnaire, live city intelligence snippet, SDG 11 & 13 alignment.
2. **City Dashboard**: Real-time indicators for active city (Sustainability Score, Air Quality, Temp, Humidity, Rain Probability, Crowd Level, Green Score, Accessibility).
3. **Interactive City Map**: Leaflet + OpenStreetMap displaying real locations with category filtering and popups.
4. **Smart Recommendation Wizard**: 4-step interactive wizard (Activity, Crowd, Time, Transport) producing top 3 matches with transparent scoring breakdown.
5. **AI Sustainability Assistant ("Tanya UrbanPulse")**: Grounded Gemini 1.5 API assistant driven by `config/urbanpulse_ai.json`.
6. **Eco Actions & Personal Impact**: Track cycling, walking, and transit activities with calculated CO2 avoided & Eco Points.
7. **Transparent Data Sources Page**: Source transparency, endpoints, last updated sync status, and methodology docs.
8. **Admin Command Center**: KPIs, API sync health monitors, manual sync triggers, spatial location CRUD, user impact analytics.

---

## 🛠️ Technology Stack
* **Backend**: Laravel Monolith (PHP 8.4+)
* **Frontend**: Blade, Tailwind CSS, Alpine.js
* **Maps**: Leaflet + OpenStreetMap
* **Charts**: Chart.js
* **AI Integration**: Gemini API (v1beta) driven by `config/urbanpulse_ai.json`
* **Database**: MySQL / MariaDB / SQLite
* **Caching & Scheduler**: Laravel Cache & Laravel Scheduler

---

## 💻 Installation & Setup

1. **Clone & Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Migration & Seeding**
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Config & Asset Build**
   ```bash
   php artisan config:clear
   npm run build
   php artisan serve
   ```

5. **Default Credentials**
   * **Demo User**: `user@urbanpulse.id` / `password123`
   * **Admin User**: `admin@urbanpulse.id` / `password123`

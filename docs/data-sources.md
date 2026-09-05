# UrbanPulse Data Sources & Transparency Documentation

## Core Principle
UrbanPulse operates under a strict data integrity rule: **Never fabricate real-time environmental data**.
All environmental, meteorological, and geographic data displayed across the platform is fetched from public open data APIs or clearly labeled as UrbanPulse-derived calculated models.

---

## 1. OpenStreetMap (OSM)
* **Purpose**: Provides spatial boundaries, coordinates, roads, park locations, green spaces, public transport hubs, and waste recycling facilities for Bogor.
* **Endpoint**: `https://overpass-api.de/api/interpreter`
* **Data Fields Extracted**: `node["leisure"="park"]`, `latitude`, `longitude`, `tags.name`, `OSM identifier`.
* **Caching Strategy**: Persisted in PostgreSQL/MySQL `locations` table and cached for 24 hours.

---

## 2. BMKG Open Data / Open-Meteo Meteorological API
* **Purpose**: Real-time meteorological parameters for Bogor (-6.5971, 106.7949).
* **Endpoint**: `https://api.open-meteo.com/v1/forecast`
* **Parameters**: Temperature (°C), Relative Humidity (%), Weather Condition Code, Rainfall (mm), Precipitation Probability (%).
* **Caching Strategy**: Cached in Laravel Cache for 30 minutes to comply with rate limits.
* **Fallback**: Uses latest verified database timestamp if API is temporarily unreachable.

---

## 3. Air Quality Open Data API (Open-Meteo Air Quality / WAQI)
* **Purpose**: Provides US Air Quality Index (AQI), PM2.5 concentration, and atmospheric health categorization.
* **Endpoint**: `https://air-quality-api.open-meteo.com/v1/air-quality`
* **Parameters**: `us_aqi`, `pm2_5`, `pm10`.
* **AQI Status Categorization**:
  * `0 - 50`: BAIK (GOOD)
  * `51 - 100`: SEDANG (MODERATE)
  * `101 - 150`: TIDAK SEHAT SENSITIF (UNHEALTHY FOR SENSITIVE)
  * `151 - 200`: TIDAK SEHAT (UNHEALTHY)
  * `201+`: SANGAT TIDAK SEHAT / BERBAHAYA
* **Caching Strategy**: Cached for 30 minutes in Laravel Cache.

---

## 4. UrbanPulse Derived Scoring & Crowd Estimation Engine
* **Purpose**: Calculates composite match recommendation scores, crowd level estimates, and CO2 avoided metrics.
* **Labeling**: Explicitly tagged in UI as `Calculated by UrbanPulse` or `Estimated by UrbanPulse`.

# UrbanPulse Recommendation & Scoring Methodology

## Overview
UrbanPulse calculates location recommendation match scores (0% - 100%) using a multi-factor weighted formula designed for sustainable urban decision-making.

---

## Weight Formula Breakdown

$$\text{Match Score} = W_{\text{AQI}} + W_{\text{Crowd}} + W_{\text{Green}} + W_{\text{Access}} + W_{\text{Weather}}$$

1. **Air Quality Index Score ($W_{\text{AQI}}$) — 30%**
   * Raw Formula: $S_{\text{AQI}} = \max(0, \min(100, 100 - (\text{AQI} \times 0.6)))$
   * Earned Points: $(S_{\text{AQI}} / 100) \times 30$

2. **Crowd Level Score ($W_{\text{Crowd}}$) — 25%**
   * Based on user preferred crowd setting vs estimated crowd level (LOW, MEDIUM, HIGH).
   * Match LOW preference with LOW crowd = 100% (25 pts).

3. **Green Space Index ($W_{\text{Green}}$) — 20%**
   * Earned Points: $(\text{Green Score} / 100) \times 20$

4. **Mobility & Accessibility ($W_{\text{Access}}$) — 15%**
   * Evaluates bike-friendliness and pedestrian infrastructure accessibility.
   * Bonus points added for bicycle mode on bike-friendly paths.

5. **Weather Conditions ($W_{\text{Weather}}$) — 10%**
   * Evaluates rain probability from BMKG forecast.
   * Formula: $((100 - \text{Rain Prob \%}) / 100) \times 10$

---

## CO2 Avoided Calculation Methodology
* **Baseline**: Average solo passenger vehicle emits $\sim 0.210\text{ kg CO}_2/\text{km}$.
* **Cycling & Walking**: Saves $100\%$ emissions ($0.210\text{ kg CO}_2/\text{km}$ avoided).
* **Public Transport**: Saves $\sim 66\%$ emissions ($0.140\text{ kg CO}_2/\text{km}$ avoided).
* **Carpooling**: Saves $\sim 50\%$ emissions ($0.105\text{ kg CO}_2/\text{km}$ avoided).

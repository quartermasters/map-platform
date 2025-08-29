# MAP Terminal - Local Development Setup

## Quick Start (3 Methods)

### Method 1: PHP Built-in Server (Recommended)

```bash
# Navigate to project directory
cd "/mnt/d/MAP market"

# Start PHP development server
php -S localhost:8000

# Open browser to: http://localhost:8000
```

### Method 2: Python HTTP Server

```bash
# Navigate to project directory
cd "/mnt/d/MAP market"

# Python 3
python -m http.server 8000

# Python 2
python -m SimpleHTTPServer 8000

# Open browser to: http://localhost:8000
# Note: PHP features won't work with Python server
```

### Method 3: Node.js Live Server

```bash
# Install globally (one time)
npm install -g live-server

# Navigate to project directory
cd "/mnt/d/MAP market"

# Start live server with auto-reload
live-server --port=8000

# Opens browser automatically
```

## File Structure

```
MAP market/
├── index.php                 # Main application
├── assets/
│   ├── css/
│   │   └── bloomberg.css     # Bloomberg terminal styling
│   └── js/
│       └── terminal.js       # Interactive functionality
└── README.md                 # This file
```

## Features to Test

### 🎯 Core Functionality

- [x] Navigation between sections (Overview, Markets, Species, etc.)
- [x] Live clock in header
- [x] Market status indicator (pulsing green dot)
- [x] Animated metric cards with counter animations
- [x] Sortable data tables (click column headers)
- [x] Hover effects on table rows

### 🚀 Interactive Elements

- [x] Keyboard shortcuts: Alt + 1-6 for section navigation
- [x] Panel controls (10Y, 5Y, 1Y buttons)
- [x] Progress bars and growth indicators
- [x] Tooltip system (hover over elements)
- [x] Search functionality in header

### 📱 Responsive Design

- [x] Desktop layout (1920px+)
- [x] Tablet layout (768px-1024px)
- [x] Mobile layout (320px-768px)

## Browser Compatibility

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

## Development Notes

### Color Palette

- Primary Background: #0d1117 (Dark)
- Secondary Background: #161b22 (Panels)
- Orange Accent: #ff6b35 (Bloomberg signature)
- Text Primary: #e6edf3 (Light text)
- Success Green: #28a745
- Warning Red: #dc3545

### Fonts Used

- **Primary**: Inter (body text, headers)
- **Monospace**: Source Code Pro (data, numbers, code)

### Key CSS Classes

- `.terminal-body` - Main body styling
- `.metric-card` - Dashboard metric cards
- `.data-table` - Bloomberg-style tables
- `.panel-container` - Content panels
- `.nav-item` - Navigation items

### JavaScript Features

- Real-time clock updates
- Counter animations on scroll
- Table sorting functionality
- Keyboard navigation
- Search highlighting
- Responsive tooltip system

## Troubleshooting

### PHP Not Working?

- Ensure PHP is installed: `php --version`
- Check if port 8000 is available
- Try different port: `php -S localhost:8080`

### Fonts Not Loading?

- Check internet connection (Google Fonts CDN)
- Fonts will fallback to system fonts if unavailable

### CSS Not Applied?

- Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
- Check browser console for errors
- Ensure file paths are correct

### Performance Issues?

- Disable animations in CSS (comment out @keyframes)
- Reduce animation frequency in terminal.js
- Use simpler data sets for testing

## Next Steps for Production

1. **Optimize Images**: Add favicon and any chart images
2. **Minify Assets**: Compress CSS/JS for faster loading
3. **Add Caching**: Implement PHP caching for data
4. **Database Integration**: Connect to MySQL for dynamic data

# 🌿 MAP Terminal: Medicinal & Aromatic Plants Intelligence Platform

> Developed by **Quartermasters FZC**  
> Team Lead: Haroon Haider  
> Contact: hello@quartermasters.me

---

## 🚀 Live Demo

- **Local:** [http://localhost:8000](http://localhost:8000) or [http://localhost:8001](http://localhost:8001)
- **Production:** [https://github.com/quartermasters/map-platform](https://github.com/quartermasters/map-platform)

---

## 🧭 Quick Start

### 1. PHP Server (Recommended)

```bash
cd "/mnt/d/MAP market"
php -S localhost:8000
```

### 2. Node.js Live Server

```bash
npm install -g live-server
cd "/mnt/d/MAP market"
live-server --port=8000
```

### 3. Python HTTP Server

```bash
cd "/mnt/d/MAP market"
python -m http.server 8000
```

---

## 🗂️ Project Structure

```
MAP market/
├── index.php                 # Main PHP app
├── index.html                # Static HTML demo
├── assets/
│   ├── css/
│   │   └── bloomberg.css     # Bloomberg-style UI
│   └── js/
│       └── terminal.js       # Interactive dashboard logic
├── Medicinal Plant Farming Market Analysis.md # Market research
├── README.md                 # This file
├── start-dev-server.bat/.sh  # Dev server scripts
└── package.json              # Node.js config
```

---

## ✨ Features

- **Bloomberg-style UI:** Modern, dark-themed dashboard
- **Live Clock & Market Status:** Real-time updates, pulsing indicators
- **Animated Metrics:** Counter animations, sortable tables
- **Keyboard Shortcuts:** Alt+1-6 for instant navigation
- **Responsive Design:** Desktop, tablet, mobile layouts
- **Interactive Charts:** Market growth, regional data, segment analysis
- **Tooltip System:** Hover for insights
- **Search & Filter:** Fast header search
- **Panel Controls:** 10Y/5Y/1Y data views

---

## 📊 Data & Analytics

- **Market Data:** Dynamic PHP arrays for market size, CAGR, regional share
- **Species Profiles:** Ginseng, Echinacea, Turmeric, Aloe Vera, Licorice Root
- **Growth Projections:** 2024-2034, with interactive charts (Chart.js)
- **Segment Analysis:** Raw materials, extracts, essential oils, finished products

---

## 🛠️ Tech Stack

- **PHP 8+**
- **HTML5, CSS3 (Bloomberg.css)**
- **JavaScript (terminal.js)**
- **Chart.js** for analytics
- **Google Fonts:** Inter, Source Code Pro
- **Font Awesome** for icons

---

## 🧑‍💻 Development

- **Start Server:** See Quick Start above
- **Edit Styles:** `assets/css/bloomberg.css`
- **Edit JS:** `assets/js/terminal.js`
- **Edit PHP:** `index.php`
- **Market Analysis:** `Medicinal Plant Farming Market Analysis.md`

---

## 🧩 Extending & Customizing

- Add new species or market segments in `index.php`
- Customize charts/data in `terminal.js`
- Tweak UI/UX in `bloomberg.css`
- Add new sections or panels in HTML/PHP

---

## 🧠 Interactive Experience

- Try keyboard shortcuts (Alt+1-6)
- Click table headers to sort
- Hover for tooltips
- Use panel controls for timeframes
- Search in header for instant results

---

## 🛡️ Troubleshooting

- **PHP Issues:** `php --version`, try a different port
- **CSS/JS Issues:** Hard refresh (Ctrl+F5), check console
- **Font Issues:** Check Google Fonts CDN
- **Performance:** Disable animations in CSS/JS for speed

---

## 🏆 Next Steps

- Optimize images, add favicon
- Minify CSS/JS for production
- Add PHP caching/database integration
- Implement security headers & SSL
- Expand species/market data

---

## 🤝 Contributing

Pull requests and suggestions welcome!  
Please contact the team lead for major feature proposals.

---

## 📬 Contact & Support

- Email: hello@quartermasters.me
- Team Lead: Haroon Haider

---

## 📝 License

## © 2025 Quartermasters FZC. All rights reserved.

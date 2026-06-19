/**
 * ===================================================================
 *
 *  Live RTP Engine (Version 8.0 - Stateless & Synchronized Patterns)
 *
 *  This version ensures that all pattern, time, and RTP values are
 *  calculated deterministically based on the current UTC time and
 *  the game's unique file ID.
 *
 *  Key Features:
 *  - 100% SYNCHRONIZED: The same game displays the exact same RTP
 *    and patterns on all devices (mobile, desktop, tablet).
 *  - TIME-BASED PRNG: Patterns and base values are locked to 4-hour
 *    UTC blocks, so they update globally and periodically.
 *  - TIME-BASED FLUCTUATIONS: Live RTP fluctuations use an absolute
 *    sine wave based on the current Unix timestamp to stay in sync.
 *  - STATELESS: No localStorage is used for game states, preventing
 *    out-of-sync or cache mismatch issues.
 *
 * ===================================================================
 */

const LiveRTPEngine = {
    config: {
        gameCardSelector: '.rtp-card',
        renderUpdateInterval: 2000, // Recalculate/render every 2 seconds
        minRtp: 40,
        maxRtp: 98,
        lowRtpThreshold: 50,
    },

    gameState: [],

    init() {
        console.log('Live RTP Engine: Initializing with Seeded Deterministic Patterns...');
        this._generateGameState();
        
        if (this.gameState.length === 0) {
            console.warn('Live RTP Engine: No game cards found.');
            return;
        }

        this._cacheDomElements();
        this._renderAllCards();
        this._startTimers();
        console.log(`Live RTP Engine: Initialization complete. Managing ${this.gameState.length} cards.`);
    },

    _generateGameState() {
        this.gameState = [];
        const gameCards = document.querySelectorAll(this.config.gameCardSelector);
        
        gameCards.forEach((card, index) => {
            // Extract a unique identifier from the image src path (e.g. "ais-01" from "images/games/ais-01.png")
            const imgEl = card.querySelector('.rtp-card-img');
            const imgSrc = imgEl ? imgEl.getAttribute('data-src') : '';
            const gameIdentifier = imgSrc ? imgSrc.split('/').pop().split('.')[0] : `game-${index + 1}`;
            
            // Get seed based on gameIdentifier and current 4-hour UTC block
            const seedStr = this._getSeedString(gameIdentifier);
            const seedNum = this._hashString(seedStr);
            
            // Generate stable parameters for the current 4-hour block using the seed
            const r1 = this._seededRandom(seedNum);
            const r2 = this._seededRandom(seedNum + 1);
            const r3 = this._seededRandom(seedNum + 2);
            const r4 = this._seededRandom(seedNum + 3);
            const r5 = this._seededRandom(seedNum + 4);
            const r6 = this._seededRandom(seedNum + 5);

            // Determine pattern set (manual vs auto)
            const patternSet = r1 > 0.5 ? 'manual' : 'auto';
            let pola1HTML, pola2HTML, pola3HTML;

            if (patternSet === 'manual') {
                pola1HTML = `<td>Manual 9</td><td>${this._getDeterministicEmojis(r2)}</td>`;
                pola2HTML = `<td>Manual 7</td><td>${this._getDeterministicEmojis(r3)}</td>`;
                pola3HTML = `<td>Manual 5</td><td>${this._getDeterministicEmojis(r4)}</td>`;
            } else { // 'auto'
                pola1HTML = `<td>Auto 70</td><td>${this._getDeterministicEmojis(r2)}</td>`;
                pola2HTML = `<td>Auto 10</td><td>${this._getDeterministicEmojis(r3)}</td>`;
                pola3HTML = `<td>Auto 30</td><td>${this._getDeterministicEmojis(r4)}</td>`;
            }

            // Determine Jam Gacor hour (0 to 22)
            const startHour = Math.floor(r5 * 23);
            const formattedStart = startHour.toString().padStart(2, '0');
            const jamGacorHTML = `<i class="lni lni-alarm-clock"></i> Jam Gacor: ${formattedStart}:00 - ${formattedStart}:59`;

            // Base RTP (constant for the 4-hour block)
            const baseRtp = 50 + (r6 * 45); // 50% to 95%

            this.gameState.push({
                id: index + 1,
                seedNum: seedNum,
                baseRtp: baseRtp,
                pola1HTML: pola1HTML,
                pola2HTML: pola2HTML,
                pola3HTML: pola3HTML,
                jamGacorHTML: jamGacorHTML,
            });
        });
    },

    _cacheDomElements() {
        this.gameState.forEach(game => {
            game.elements = {
                percentBar: document.getElementById(`percent-bar-${game.id}`),
                percentTxt: document.getElementById(`percent-txt-${game.id}`),
                polaSlot1: document.getElementById(`pola-slot-1-${game.id}`),
                polaSlot2: document.getElementById(`pola-slot-2-${game.id}`),
                polaSlot3: document.getElementById(`pola-slot-3-${game.id}`),
                jamGacorTxt: document.getElementById(`jam-gacor-${game.id}`),
            };
        });
    },

    _startTimers() {
        // Redraw/recalculate RTP at regular intervals
        setInterval(() => {
            this._renderAllCards();
        }, this.config.renderUpdateInterval);
    },

    _renderAllCards() {
        // Use Date.now() / 1000 to get absolute current Unix seconds (synchronized on all devices)
        const currentSeconds = Math.floor(Date.now() / 1000);
        const period = 120; // 2 minutes for a full oscillation cycle
        const amplitude = 4; // Max +/- 4% fluctuation

        this.gameState.forEach(game => {
            const { elements, baseRtp, seedNum } = game;
            if (!elements) return;

            // Calculate deterministic RTP fluctuation based on the absolute timestamp
            const phaseShift = (seedNum % 360) * (Math.PI / 180);
            const angle = (currentSeconds * (2 * Math.PI / period)) + phaseShift;
            const fluctuation = amplitude * Math.sin(angle);
            const rtp = Math.max(this.config.minRtp, Math.min(this.config.maxRtp, baseRtp + fluctuation));

            // --- 1. Render the RTP Bar and Text ---
            if (elements.percentTxt) elements.percentTxt.textContent = rtp.toFixed(0) + "%";
            if (elements.percentBar) {
                elements.percentBar.style.width = rtp + "%";
                elements.percentBar.className = 'percent-bar ' + this._getColorClass(rtp);
            }

            // --- 2. Render Pola and Jam Gacor based on RTP Threshold ---
            if (rtp < this.config.lowRtpThreshold) {
                if (elements.polaSlot1) elements.polaSlot1.innerHTML = `<td colspan="2" class="pola-warning-text">Pola Tidak Tersedia!!</td>`;
                if (elements.polaSlot2) elements.polaSlot2.innerHTML = '';
                if (elements.polaSlot3) elements.polaSlot3.innerHTML = '';
                if (elements.jamGacorTxt) elements.jamGacorTxt.innerHTML = `<i class="lni lni-warning"></i> Tidak Disarankan Bermain Game Ini`;
            } else {
                if (elements.polaSlot1) elements.polaSlot1.innerHTML = game.pola1HTML;
                if (elements.polaSlot2) elements.polaSlot2.innerHTML = game.pola2HTML;
                if (elements.polaSlot3) elements.polaSlot3.innerHTML = game.pola3HTML;
                if (elements.jamGacorTxt) elements.jamGacorTxt.innerHTML = game.jamGacorHTML;
            }
        });
    },

    _getColorClass(rtp) {
        if (rtp >= 75) return 'great';
        if (rtp >= 50) return 'good';
        return 'bad';
    },

    _getSeedString(gameIdentifier) {
        const now = new Date();
        const year = now.getUTCFullYear();
        const month = now.getUTCMonth();
        const day = now.getUTCDate();
        const hourBlock = Math.floor(now.getUTCHours() / 4); // 4-hour blocks
        return `${gameIdentifier}-${year}-${month}-${day}-${hourBlock}`;
    },

    _hashString(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = (hash << 5) - hash + str.charCodeAt(i);
            hash |= 0; // Convert to 32bit integer
        }
        return Math.abs(hash);
    },

    _seededRandom(seedNum) {
        // Mulberry32 generator
        let t = seedNum + 0x6D2B79F5;
        t = Math.imul(t ^ (t >>> 15), t | 1);
        t ^= t + Math.imul(t ^ (t >>> 7), t | 61);
        return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
    },

    _getDeterministicEmojis(randVal) {
        const val = Math.floor(randVal * 8);
        let emojiString = '';
        for (let i = 0; i < 3; i++) {
            emojiString += ((val >> i) & 1) ? '✅' : '❌';
        }
        return emojiString;
    }
};

$(document).ready(function() {
    LiveRTPEngine.init();
});

// --- Existing Utility Functions (Unchanged) ---
function linkProv(prov) { location.href = "?game=" + prov; }
const scheme = 'dark';
const btnColorScheme = document.getElementById('btn-colorscheme');
const iconColorScheme = document.getElementById('icon-colorscheme');
function darkMode() { if (localStorage.getItem(scheme)) { localStorage.removeItem(scheme); iconColorScheme.classList.remove('lni-night'); iconColorScheme.classList.add('lni-sun'); } else { localStorage.setItem(scheme, 'true'); iconColorScheme.classList.remove('lni-sun'); iconColorScheme.classList.add('lni-night'); } applyTheme(); }
function applyTheme() { if (localStorage.getItem(scheme)) { document.body.classList.add(scheme); } else { document.body.classList.remove(scheme); } }
applyTheme();
let mybutton = document.getElementById("btn-up");
window.onscroll = function() {scrollFunction()};
function scrollFunction() { if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) { mybutton.style.display = "block"; } else { mybutton.style.display = "none"; } }
function goUp() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }
$(function() { $('.lazy').lazy(); });
const swiper = new Swiper('.slider', { loop: true, autoplay: { delay: 3000, }, });
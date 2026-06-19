/**
 * ===================================================================
 *
 *  Live RTP Engine (Version 7.0 - Static & Persistent Patterns)
 *
 *  This is the final production version. It ensures that all pattern
 *  and time information is generated only once and persists across
 *  refreshes, eliminating distracting visual changes.
 *
 *  Key Features:
 *  - STATIC PATTERNS: Pola and Jam Gacor are generated once and saved.
 *    They no longer change on every update, providing a stable UI.
 *  - REAL-TIME RTP: Only the RTP percentage and bar color update live.
 *  - FULL PERSISTENCE: The entire card state is saved and reloaded.
 *
 * ===================================================================
 */

const LiveRTPEngine = {
    config: {
        gameCardSelector: '.rtp-card',
        dataUpdateInterval: 7000,
        renderUpdateInterval: 2500,
        minRtp: 40,
        maxRtp: 98,
        rtpVolatility: 0.5,
        storageKey: 'rtpEngineGameState_v7', // Final version key
        lowRtpThreshold: 50,
    },

    gameState: [],

    init() {
        console.log('Live RTP Engine: Initializing with Static & Persistent Patterns...');
        const successfulLoad = this._loadStateFromStorage();

        if (!successfulLoad) {
            console.log('Live RTP Engine: No valid saved state found. Generating new state.');
            this._generateNewGameState();
            this._saveStateToStorage();
        }
        
        if (this.gameState.length === 0) {
            console.warn('Live RTP Engine: No game cards found.');
            return;
        }

        this._cacheDomElements();
        this._renderAllCards();
        this._startTimers();
        console.log(`Live RTP Engine: Initialization complete. Managing ${this.gameState.length} cards.`);
    },
    
    _loadStateFromStorage() {
        const savedStateJSON = localStorage.getItem(this.config.storageKey);
        if (!savedStateJSON) return false;

        const savedState = JSON.parse(savedStateJSON);
        const currentCardCount = document.querySelectorAll(this.config.gameCardSelector).length;

        // [VALIDATION] Check if the saved state is from this version and matches card count
        if (savedState.length !== currentCardCount || !savedState[0].hasOwnProperty('pola1HTML')) {
            console.warn('Live RTP Engine: State mismatch or outdated version. Discarding saved state.');
            localStorage.removeItem(this.config.storageKey);
            return false;
        }

        this.gameState = savedState;
        console.log('Live RTP Engine: Successfully loaded state from storage.');
        return true;
    },
    
    _saveStateToStorage() {
        // [CHANGE] Now saves the generated HTML for patterns and time
        const stateToSave = this.gameState.map(game => ({
            id: game.id,
            rtp: game.rtp,
            patternSet: game.patternSet,
            pola1HTML: game.pola1HTML,
            pola2HTML: game.pola2HTML,
            pola3HTML: game.pola3HTML,
            jamGacorHTML: game.jamGacorHTML,
        }));
        localStorage.setItem(this.config.storageKey, JSON.stringify(stateToSave));
    },

    _generateNewGameState() {
        this.gameState = [];
        const gameCards = document.querySelectorAll(this.config.gameCardSelector);
        gameCards.forEach((card, index) => {
            const patternSet = Math.random() > 0.5 ? 'manual' : 'auto';
            let pola1HTML, pola2HTML, pola3HTML;

            if (patternSet === 'manual') {
                pola1HTML = `<td>Manual 9</td><td>${this._getRandomEmojiIcons()}</td>`;
                pola2HTML = `<td>Manual 7</td><td>${this._getRandomEmojiIcons()}</td>`;
                pola3HTML = `<td>Manual 5</td><td>${this._getRandomEmojiIcons()}</td>`;
            } else { // 'auto'
                pola1HTML = `<td>Auto 70</td><td>${this._getRandomEmojiIcons()}</td>`;
                pola2HTML = `<td>Auto 10</td><td>${this._getRandomEmojiIcons()}</td>`;
                pola3HTML = `<td>Auto 30</td><td>${this._getRandomEmojiIcons()}</td>`;
            }

            this.gameState.push({
                id: index + 1,
                rtp: this._getRandomInt(this.config.minRtp, this.config.maxRtp),
                patternSet: patternSet,
                // [NEW] Store the generated HTML directly in the state
                pola1HTML: pola1HTML,
                pola2HTML: pola2HTML,
                pola3HTML: pola3HTML,
                jamGacorHTML: this._generateJamGacorHTML(),
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
        // Data timer only updates RTP and saves
        setInterval(() => {
            this._updateAllRtpData();
            this._saveStateToStorage();
        }, this.config.dataUpdateInterval);

        // Render timer just redraws from the existing state
        setInterval(() => {
            this._renderAllCards();
        }, this.config.renderUpdateInterval);
    },

    _updateAllRtpData() {
        this.gameState.forEach(game => {
            const change = (Math.random() * this.config.rtpVolatility * 2) - this.config.rtpVolatility;
            const newRtp = game.rtp + change;
            game.rtp = Math.max(this.config.minRtp, Math.min(this.config.maxRtp, newRtp));
        });
    },

    _renderAllCards() {
        this.gameState.forEach(game => {
            const { elements, rtp } = game;
            if (!elements) return;

            // --- 1. Render the RTP Bar and Text ---
            if (elements.percentTxt) elements.percentTxt.textContent = rtp.toFixed(0) + "%";
            if (elements.percentBar) {
                elements.percentBar.style.width = rtp + "%";
                elements.percentBar.className = 'percent-bar ' + this._getColorClass(rtp);
            }

            // --- 2. Render Pola and Jam Gacor based on RTP Threshold ---
            if (rtp < this.config.lowRtpThreshold) {
                // RENDER LOW RTP WARNINGS
                if (elements.polaSlot1) elements.polaSlot1.innerHTML = `<td colspan="2" class="pola-warning-text">Pola Tidak Tersedia!!</td>`;
                if (elements.polaSlot2) elements.polaSlot2.innerHTML = '';
                if (elements.polaSlot3) elements.polaSlot3.innerHTML = '';
                if (elements.jamGacorTxt) elements.jamGacorTxt.innerHTML = `<i class="lni lni-warning"></i> Tidak Disarankan Bermain Game Ini`;
            } else {
                // [CHANGE] RENDER STATIC, SAVED PATTERNS
                if (elements.polaSlot1) elements.polaSlot1.innerHTML = game.pola1HTML;
                if (elements.polaSlot2) elements.polaSlot2.innerHTML = game.pola2HTML;
                if (elements.polaSlot3) elements.polaSlot3.innerHTML = game.pola3HTML;
                if (elements.jamGacorTxt) elements.jamGacorTxt.innerHTML = game.jamGacorHTML;
            }
        });
    },

    // --- Helper Functions ---
    _getColorClass(rtp) {
        if (rtp >= 75) return 'great';
        if (rtp >= 50) return 'good';
        return 'bad';
    },
    _generateJamGacorHTML() {
        const startHour = this._getRandomInt(0, 22);
        const formattedStart = startHour.toString().padStart(2, '0');
        return `<i class="lni lni-alarm-clock"></i> Jam Gacor: ${formattedStart}:00 - ${formattedStart}:59`;
    },
    _getRandomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    _getRandomEmojiIcons() {
        let emojiString = '';
        for (let i = 0; i < 3; i++) {
            emojiString += (Math.random() > 0.4) ? '✅' : '❌';
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
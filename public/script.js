// Initialize Lucide icons and app state
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    initializeApp();
});

// Global app state
const AppState = {
    selectedLanguages: ['en'],
    currentSection: 'home',
    isGenerating: false,
    settings: {
        voiceType: 'female',
        speed: 1.0,
        pitch: 1.0,
        volume: 100
    },
    history: JSON.parse(localStorage.getItem('tts-history') || '[]')
};

// DOM Elements
const elements = {
    textInput: document.getElementById('textInput'),
    charCount: document.getElementById('charCount'),
    generateBtn: document.getElementById('generateBtn'),
    clearBtn: document.getElementById('clearBtn'),
    historyBtn: document.getElementById('historyBtn'),
    languageGrid: document.getElementById('languageGrid'),
    resultsSection: document.getElementById('resultsSection'),
    resultsGrid: document.getElementById('resultsGrid'),
    loadingOverlay: document.getElementById('loadingOverlay'),
    progressFill: document.getElementById('progressFill'),
    progressText: document.getElementById('progressText'),
    toastContainer: document.getElementById('toastContainer'),
    navLinks: document.querySelectorAll('.nav-link'),
    sections: document.querySelectorAll('.section'),
    historyGrid: document.getElementById('historyGrid'),
    emptyHistory: document.getElementById('emptyHistory'),
    speedSlider: document.getElementById('speedSlider'),
    pitchSlider: document.getElementById('pitchSlider'),
    volumeSlider: document.getElementById('volumeSlider'),
    speedValue: document.getElementById('speedValue'),
    pitchValue: document.getElementById('pitchValue'),
    volumeValue: document.getElementById('volumeValue')
};

// Initialize the application
function initializeApp() {
    setupEventListeners();
    updateCharCount();
    updateGenerateButton();
    loadSettings();
    renderHistory();
    
    // Load voices when available
    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = loadVoices;
    }
    loadVoices();
}

// Setup all event listeners
function setupEventListeners() {
    // Navigation
    elements.navLinks.forEach(link => {
        link.addEventListener('click', handleNavigation);
    });
    
    // Text input
    elements.textInput.addEventListener('input', handleTextInput);
    elements.textInput.addEventListener('paste', handleTextInput);
    
    // Language selection
    elements.languageGrid.addEventListener('click', handleLanguageSelection);
    
    // Action buttons
    elements.generateBtn.addEventListener('click', handleGenerateSpeech);
    elements.clearBtn.addEventListener('click', handleClearText);
    elements.historyBtn.addEventListener('click', () => switchSection('history'));
    
    // Settings
    if (elements.speedSlider) {
        elements.speedSlider.addEventListener('input', handleSpeedChange);
        elements.pitchSlider.addEventListener('input', handlePitchChange);
        elements.volumeSlider.addEventListener('input', handleVolumeChange);
    }
    
    // Voice type radio buttons
    document.querySelectorAll('input[name="voiceType"]').forEach(radio => {
        radio.addEventListener('change', handleVoiceTypeChange);
    });
    
    // History actions
    document.getElementById('clearHistoryBtn')?.addEventListener('click', handleClearHistory);
    document.getElementById('resetSettingsBtn')?.addEventListener('click', handleResetSettings);
    document.getElementById('downloadAllBtn')?.addEventListener('click', handleDownloadAll);
    
    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboardShortcuts);
}

// Handle navigation between sections
function handleNavigation(e) {
    e.preventDefault();
    const targetSection = e.currentTarget.dataset.section;
    switchSection(targetSection);
}

function switchSection(sectionName) {
    // Update navigation
    elements.navLinks.forEach(link => {
        link.classList.toggle('active', link.dataset.section === sectionName);
    });
    
    // Update sections
    elements.sections.forEach(section => {
        section.classList.toggle('active', section.id === sectionName);
    });
    
    AppState.currentSection = sectionName;
    
    // Special handling for history section
    if (sectionName === 'history') {
        renderHistory();
    }
    
    // Update URL hash
    window.history.pushState(null, null, `#${sectionName}`);
}

// Handle text input changes
function handleTextInput() {
    updateCharCount();
    updateGenerateButton();
    
    // Auto-resize textarea
    const textarea = elements.textInput;
    textarea.style.height = 'auto';
    textarea.style.height = Math.max(200, textarea.scrollHeight) + 'px';
}

function updateCharCount() {
    const count = elements.textInput.value.length;
    elements.charCount.textContent = count;
    elements.charCount.style.color = count > 4500 ? '#e74c3c' : 'rgba(255, 255, 255, 0.6)';
}

function updateGenerateButton() {
    const hasText = elements.textInput.value.trim().length > 0;
    const hasLanguages = AppState.selectedLanguages.length > 0;
    const isValid = hasText && hasLanguages && !AppState.isGenerating;
    
    elements.generateBtn.disabled = !isValid;
    
    if (AppState.isGenerating) {
        elements.generateBtn.innerHTML = `
            <div class="btn-content">
                <i data-lucide="loader" class="animate-spin"></i>
                <span>Generating...</span>
            </div>
        `;
    } else {
        elements.generateBtn.innerHTML = `
            <div class="btn-content">
                <i data-lucide="play-circle"></i>
                <span>Generate Speech</span>
            </div>
            <div class="btn-ripple"></div>
        `;
    }
    
    lucide.createIcons();
}

// Handle language selection
function handleLanguageSelection(e) {
    const chip = e.target.closest('.language-chip');
    if (!chip) return;
    
    const lang = chip.dataset.lang;
    const isActive = chip.classList.contains('active');
    
    if (isActive && AppState.selectedLanguages.length === 1) {
        showToast('⚠️ At least one language must be selected', 'warning');
        return;
    }
    
    chip.classList.toggle('active');
    
    if (isActive) {
        AppState.selectedLanguages = AppState.selectedLanguages.filter(l => l !== lang);
    } else {
        AppState.selectedLanguages.push(lang);
    }
    
    updateGenerateButton();
    
    // Add ripple effect
    addRippleEffect(chip, e);
}

// Generate speech
async function handleGenerateSpeech() {
    const text = elements.textInput.value.trim();
    
    if (!text) {
        showToast('⚠️ Please enter some text to convert', 'error');
        return;
    }
    
    if (AppState.selectedLanguages.length === 0) {
        showToast('⚠️ Please select at least one language', 'error');
        return;
    }
    
    AppState.isGenerating = true;
    updateGenerateButton();
    showLoading(true);
    
    try {
        const results = [];
        
        for (let i = 0; i < AppState.selectedLanguages.length; i++) {
            const lang = AppState.selectedLanguages[i];
            updateProgress((i / AppState.selectedLanguages.length) * 100);
            
            // Simulate processing time
            await sleep(1000 + Math.random() * 1000);
            
            const result = await generateSpeechForLanguage(text, lang);
            results.push(result);
        }
        
        updateProgress(100);
        await sleep(500);
        
        displayResults(results);
        saveToHistory(text, results);
        
        showToast('✅ Speech generated successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating speech:', error);
        showToast('⚠️ Couldn\'t generate audio, please try again', 'error');
    } finally {
        AppState.isGenerating = false;
        updateGenerateButton();
        showLoading(false);
    }
}

// Generate speech for a specific language
async function generateSpeechForLanguage(text, lang) {
    const voices = speechSynthesis.getVoices();
    const langInfo = getLanguageInfo(lang);
    
    // Find appropriate voice
    let voice = voices.find(v => 
        v.lang.startsWith(lang) && 
        v.name.toLowerCase().includes(AppState.settings.voiceType)
    );
    
    if (!voice) {
        voice = voices.find(v => v.lang.startsWith(lang));
    }
    
    if (!voice) {
        voice = voices[0];
    }
    
    // Create utterance
    const utterance = new SpeechSynthesisUtterance(text);
    utterance.voice = voice;
    utterance.rate = AppState.settings.speed;
    utterance.pitch = AppState.settings.pitch;
    utterance.volume = AppState.settings.volume / 100;
    utterance.lang = lang;
    
    return {
        id: Date.now() + Math.random(),
        language: lang,
        languageInfo: langInfo,
        text: text,
        utterance: utterance,
        voice: voice,
        timestamp: new Date()
    };
}

// Display results
function displayResults(results) {
    elements.resultsGrid.innerHTML = '';
    
    results.forEach((result, index) => {
        const card = createResultCard(result, index);
        elements.resultsGrid.appendChild(card);
    });
    
    elements.resultsSection.classList.add('active');
    elements.resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Create result card
function createResultCard(result, index) {
    const card = document.createElement('div');
    card.className = 'result-card';
    card.style.animationDelay = `${index * 0.1}s`;
    
    card.innerHTML = `
        <div class="result-header">
            <div class="result-lang">
                <span class="flag">${result.languageInfo.flag}</span>
                <span class="lang-name">${result.languageInfo.name}</span>
            </div>
            <div class="result-actions">
                <button class="btn btn-outline btn-sm play-btn" data-id="${result.id}">
                    <i data-lucide="play"></i>
                </button>
                <button class="btn btn-outline btn-sm download-btn" data-id="${result.id}">
                    <i data-lucide="download"></i>
                </button>
            </div>
        </div>
        <div class="result-content">
            <p class="result-text">${result.text}</p>
        </div>
        <div class="waveform" data-id="${result.id}">
            ${Array(5).fill().map(() => '<div class="wave-bar"></div>').join('')}
        </div>
        <div class="result-info">
            <span class="voice-info">Voice: ${result.voice?.name || 'Default'}</span>
            <span class="settings-info">Speed: ${AppState.settings.speed}x, Pitch: ${AppState.settings.pitch}</span>
        </div>
    `;
    
    // Add event listeners
    const playBtn = card.querySelector('.play-btn');
    const downloadBtn = card.querySelector('.download-btn');
    
    playBtn.addEventListener('click', () => playAudio(result, playBtn));
    downloadBtn.addEventListener('click', () => downloadAudio(result));
    
    lucide.createIcons();
    return card;
}

// Play audio
function playAudio(result, playBtn) {
    // Stop any currently playing speech
    speechSynthesis.cancel();
    
    const waveform = document.querySelector(`[data-id="${result.id}"].waveform`);
    const isPlaying = playBtn.classList.contains('playing');
    
    if (isPlaying) {
        speechSynthesis.cancel();
        playBtn.classList.remove('playing');
        playBtn.innerHTML = '<i data-lucide="play"></i>';
        waveform.classList.remove('active');
        lucide.createIcons();
        return;
    }
    
    // Update UI
    document.querySelectorAll('.play-btn').forEach(btn => {
        btn.classList.remove('playing');
        btn.innerHTML = '<i data-lucide="play"></i>';
    });
    document.querySelectorAll('.waveform').forEach(w => w.classList.remove('active'));
    
    playBtn.classList.add('playing');
    playBtn.innerHTML = '<i data-lucide="square"></i>';
    waveform.classList.add('active');
    
    // Play speech
    result.utterance.onend = () => {
        playBtn.classList.remove('playing');
        playBtn.innerHTML = '<i data-lucide="play"></i>';
        waveform.classList.remove('active');
        lucide.createIcons();
    };
    
    result.utterance.onerror = () => {
        playBtn.classList.remove('playing');
        playBtn.innerHTML = '<i data-lucide="play"></i>';
        waveform.classList.remove('active');
        showToast('⚠️ Error playing audio', 'error');
        lucide.createIcons();
    };
    
    speechSynthesis.speak(result.utterance);
    lucide.createIcons();
}

// Download audio (simulated)
function downloadAudio(result) {
    showToast(`📥 Downloading ${result.languageInfo.name} audio...`, 'info');
    
    // Simulate download process
    setTimeout(() => {
        // In a real app, this would trigger an actual download
        const blob = new Blob([''], { type: 'audio/mp3' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `speech_${result.languageInfo.name.toLowerCase()}_${Date.now()}.mp3`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        showToast(`✅ ${result.languageInfo.name} audio downloaded!`, 'success');
    }, 1500);
}

// Clear text
function handleClearText() {
    elements.textInput.value = '';
    elements.resultsSection.classList.remove('active');
    elements.resultsGrid.innerHTML = '';
    updateCharCount();
    updateGenerateButton();
    showToast('🗑️ Text cleared', 'info');
}

// Settings handlers
function handleSpeedChange(e) {
    AppState.settings.speed = parseFloat(e.target.value);
    elements.speedValue.textContent = `${AppState.settings.speed}x`;
    saveSettings();
}

function handlePitchChange(e) {
    AppState.settings.pitch = parseFloat(e.target.value);
    elements.pitchValue.textContent = AppState.settings.pitch;
    saveSettings();
}

function handleVolumeChange(e) {
    AppState.settings.volume = parseInt(e.target.value);
    elements.volumeValue.textContent = `${AppState.settings.volume}%`;
    saveSettings();
}

function handleVoiceTypeChange(e) {
    AppState.settings.voiceType = e.target.value;
    saveSettings();
}

function handleResetSettings() {
    AppState.settings = {
        voiceType: 'female',
        speed: 1.0,
        pitch: 1.0,
        volume: 100
    };
    
    loadSettings();
    saveSettings();
    showToast('⚙️ Settings reset to default', 'info');
}

// History management
function saveToHistory(text, results) {
    const historyItem = {
        id: Date.now(),
        timestamp: new Date(),
        originalText: text,
        results: results,
        languages: AppState.selectedLanguages.map(lang => getLanguageInfo(lang))
    };
    
    AppState.history.unshift(historyItem);
    AppState.history = AppState.history.slice(0, 100); // Keep last 100 items
    
    localStorage.setItem('tts-history', JSON.stringify(AppState.history));
}

function renderHistory() {
    if (AppState.history.length === 0) {
        elements.historyGrid.style.display = 'none';
        elements.emptyHistory.style.display = 'block';
        return;
    }
    
    elements.historyGrid.style.display = 'grid';
    elements.emptyHistory.style.display = 'none';
    elements.historyGrid.innerHTML = '';
    
    AppState.history.forEach(item => {
        const card = createHistoryCard(item);
        elements.historyGrid.appendChild(card);
    });
}

function createHistoryCard(item) {
    const card = document.createElement('div');
    card.className = 'history-card';
    
    const timeAgo = getTimeAgo(item.timestamp);
    const languages = item.languages.map(lang => `${lang.flag} ${lang.name}`).join(', ');
    
    card.innerHTML = `
        <div class="history-header">
            <div class="history-meta">
                <span class="history-date">${timeAgo}</span>
                <span class="history-langs">${languages}</span>
            </div>
            <div class="history-actions">
                <button class="btn btn-outline btn-sm" onclick="playHistoryItem('${item.id}')">
                    <i data-lucide="play"></i>
                </button>
                <button class="btn btn-outline btn-sm" onclick="downloadHistoryItem('${item.id}')">
                    <i data-lucide="download"></i>
                </button>
                <button class="btn btn-outline btn-sm" onclick="deleteHistoryItem('${item.id}')">
                    <i data-lucide="trash-2"></i>
                </button>
            </div>
        </div>
        <div class="history-content">
            <p class="history-text">${item.originalText}</p>
        </div>
    `;
    
    lucide.createIcons();
    return card;
}

function handleClearHistory() {
    if (confirm('Are you sure you want to clear all history? This action cannot be undone.')) {
        AppState.history = [];
        localStorage.removeItem('tts-history');
        renderHistory();
        showToast('🗑️ History cleared', 'info');
    }
}

// Global functions for history actions
window.playHistoryItem = function(id) {
    const item = AppState.history.find(h => h.id == id);
    if (item && item.results.length > 0) {
        speechSynthesis.cancel();
        speechSynthesis.speak(item.results[0].utterance);
        showToast('▶️ Playing audio...', 'info');
    }
};

window.downloadHistoryItem = function(id) {
    const item = AppState.history.find(h => h.id == id);
    if (item) {
        showToast('📥 Downloading audio...', 'info');
        // Simulate download
        setTimeout(() => {
            showToast('✅ Audio downloaded!', 'success');
        }, 1500);
    }
};

window.deleteHistoryItem = function(id) {
    AppState.history = AppState.history.filter(h => h.id != id);
    localStorage.setItem('tts-history', JSON.stringify(AppState.history));
    renderHistory();
    showToast('🗑️ Item deleted', 'info');
};

// Utility functions
function getLanguageInfo(lang) {
    const languages = {
        'en': { name: 'English', flag: '🇺🇸' },
        'es': { name: 'Spanish', flag: '🇪🇸' },
        'fr': { name: 'French', flag: '🇫🇷' },
        'de': { name: 'German', flag: '🇩🇪' },
        'it': { name: 'Italian', flag: '🇮🇹' },
        'pt': { name: 'Portuguese', flag: '🇵🇹' },
        'ja': { name: 'Japanese', flag: '🇯🇵' },
        'ko': { name: 'Korean', flag: '🇰🇷' }
    };
    
    return languages[lang] || { name: 'Unknown', flag: '🌐' };
}

function getTimeAgo(timestamp) {
    const now = new Date();
    const time = new Date(timestamp);
    const diffInSeconds = Math.floor((now - time) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)} days ago`;
    return time.toLocaleDateString();
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function addRippleEffect(element, event) {
    const ripple = document.createElement('div');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
        z-index: 1;
    `;
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Loading and progress
function showLoading(show) {
    if (show) {
        elements.loadingOverlay.classList.add('active');
        updateProgress(0);
    } else {
        elements.loadingOverlay.classList.remove('active');
    }
}

function updateProgress(percent) {
    elements.progressFill.style.width = `${percent}%`;
    elements.progressText.textContent = `${Math.round(percent)}%`;
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    
    elements.toastContainer.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease forwards';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

// Settings persistence
function saveSettings() {
    localStorage.setItem('tts-settings', JSON.stringify(AppState.settings));
}

function loadSettings() {
    const saved = localStorage.getItem('tts-settings');
    if (saved) {
        AppState.settings = { ...AppState.settings, ...JSON.parse(saved) };
    }
    
    // Update UI
    if (elements.speedSlider) {
        elements.speedSlider.value = AppState.settings.speed;
        elements.speedValue.textContent = `${AppState.settings.speed}x`;
        
        elements.pitchSlider.value = AppState.settings.pitch;
        elements.pitchValue.textContent = AppState.settings.pitch;
        
        elements.volumeSlider.value = AppState.settings.volume;
        elements.volumeValue.textContent = `${AppState.settings.volume}%`;
    }
    
    // Update radio buttons
    const voiceRadio = document.querySelector(`input[name="voiceType"][value="${AppState.settings.voiceType}"]`);
    if (voiceRadio) {
        voiceRadio.checked = true;
    }
}

// Voice loading
function loadVoices() {
    const voices = speechSynthesis.getVoices();
    console.log('Available voices:', voices.length);
}

// Keyboard shortcuts
function handleKeyboardShortcuts(e) {
    if (e.ctrlKey || e.metaKey) {
        switch (e.key) {
            case 'Enter':
                e.preventDefault();
                if (!elements.generateBtn.disabled) {
                    handleGenerateSpeech();
                }
                break;
            case 'k':
                e.preventDefault();
                elements.textInput.focus();
                break;
            case 'l':
                e.preventDefault();
                handleClearText();
                break;
        }
    }
    
    // Escape key
    if (e.key === 'Escape') {
        speechSynthesis.cancel();
        if (AppState.isGenerating) {
            showLoading(false);
            AppState.isGenerating = false;
            updateGenerateButton();
        }
    }
}

// Handle download all
function handleDownloadAll() {
    const resultCards = document.querySelectorAll('.result-card');
    if (resultCards.length === 0) {
        showToast('⚠️ No audio to download', 'warning');
        return;
    }
    
    showToast('📥 Downloading all audio files...', 'info');
    
    // Simulate batch download
    setTimeout(() => {
        showToast('✅ All audio files downloaded!', 'success');
    }, 2000);
}

// Handle URL hash on page load
window.addEventListener('load', () => {
    const hash = window.location.hash.substring(1);
    if (hash && ['home', 'history', 'settings'].includes(hash)) {
        switchSection(hash);
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .waveform {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2px;
        height: 30px;
        margin: 1rem 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .waveform.active {
        opacity: 1;
    }
    
    .wave-bar {
        width: 3px;
        height: 10px;
        background: var(--primary-gradient);
        border-radius: 2px;
        animation: wave 1.5s ease-in-out infinite;
    }
    
    .waveform.active .wave-bar:nth-child(1) { animation-delay: 0s; }
    .waveform.active .wave-bar:nth-child(2) { animation-delay: 0.1s; }
    .waveform.active .wave-bar:nth-child(3) { animation-delay: 0.2s; }
    .waveform.active .wave-bar:nth-child(4) { animation-delay: 0.3s; }
    .waveform.active .wave-bar:nth-child(5) { animation-delay: 0.4s; }
    
    @keyframes wave {
        0%, 100% { height: 10px; }
        50% { height: 25px; }
    }
    
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .result-lang {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(79, 172, 254, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.9rem;
    }
    
    .result-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.5rem;
        min-width: auto;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }
    
    .result-content {
        margin-bottom: 1rem;
    }
    
    .result-text {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .result-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }
    
    .history-meta {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .history-date {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
    }
    
    .history-langs {
        font-size: 0.8rem;
        color: var(--primary-blue);
        background: rgba(79, 172, 254, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        display: inline-block;
    }
    
    .history-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    
    .history-content {
        margin-bottom: 1rem;
    }
    
    .history-text {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .play-btn.playing {
        background: var(--primary-gradient);
        color: white;
        box-shadow: var(--shadow-glow);
    }
    
    @media (max-width: 768px) {
        .result-actions,
        .history-actions {
            flex-direction: column;
        }
        
        .history-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .history-actions {
            flex-direction: row;
            justify-content: center;
        }
        
        .result-info {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
`;
document.head.appendChild(style);
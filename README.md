# AI Voice - Text-to-Speech Application

A modern, futuristic text-to-speech application built with Laravel, featuring a sleek dark UI with glassmorphism effects and advanced voice synthesis capabilities.

## 🚀 Features

- **Modern UI Design**: Dark gradient background with neon blue/purple color scheme
- **Text-to-Speech**: Advanced voice synthesis with multiple language support
- **Voice History**: Save and manage your voice recordings with edit/delete functionality
- **Playback Visualizer**: Animated waveform with word highlighting during playback
- **User Authentication**: Simple login system with localStorage-based sessions
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Multiple Interfaces**: Various UI layouts for different use cases

## 🛠️ Tech Stack

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Styling**: Bootstrap 5, Tailwind CSS
- **Database**: MySQL
- **Build Tools**: Vite, npm
- **HTTP Client**: Axios

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & npm
- MySQL
- XAMPP (recommended for Windows)

## 🔧 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/mohamedahmedkhriji/aivoice.git
   cd aivoice
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Environment setup:**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

5. **Database configuration:**
   - Create MySQL database named `voice`
   - Update `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=voice
   DB_USERNAME=root
   DB_PASSWORD=moha
   ```

6. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

7. **Compile assets:**
   ```bash
   npm run dev
   ```

8. **Start the development server:**
   ```bash
   php artisan serve
   ```

## 🎯 Usage

### Main Interface (`index.html`)
- Full-featured TTS interface with navigation
- Language selection and voice options
- Complete functionality with modern design

### Test Interface (`test.html`)
- Advanced playback visualizer
- Animated waveform display
- Voice history sidebar with management tools

### Login System (`login.html`)
- Modern authentication interface
- Password visibility toggle
- localStorage-based sessions

### Dashboard (`dashboard.html`)
- User-specific TTS interface
- Voice history management
- Personalized experience

## 🎨 UI Components

- **Glassmorphism Effects**: Modern glass-like UI elements
- **Gradient Backgrounds**: Dark themes with neon accents
- **Animated Waveforms**: Real-time audio visualization
- **Interactive Controls**: Intuitive play/pause/stop buttons
- **Responsive Modals**: Confirmation dialogs and popups

## 🔊 Voice Features

- Multiple language support
- Voice speed and pitch control
- Word-by-word highlighting during playback
- Audio visualization with waveform animation
- Voice history with playback capabilities

## 📱 Responsive Design

The application is fully responsive and optimized for:
- Desktop computers (1920px+)
- Tablets (768px - 1024px)
- Mobile devices (320px - 767px)

## 🛡️ Security Features

- Input sanitization
- XSS protection
- CSRF token validation
- Secure authentication flow

## 📁 Project Structure

```
aivoice/
├── app/                    # Laravel application logic
├── config/                 # Configuration files
├── database/              # Database migrations and seeds
├── public/                # Public web files
│   ├── index.html         # Main TTS interface
│   ├── test.html          # Advanced visualizer
│   ├── login.html         # Authentication page
│   ├── dashboard.html     # User dashboard
│   └── modern-tts.html    # Clean standalone interface
├── resources/             # Frontend resources
├── routes/                # Application routes
└── vendor/                # Composer dependencies
```

## 🚀 Development Commands

- `npm run dev` - Start Vite development server
- `npm run build` - Build assets for production
- `php artisan serve` - Start Laravel development server
- `php artisan migrate` - Run database migrations
- `php artisan key:generate` - Generate application key

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Mohamed Ahmed Khriji**
- GitHub: [@mohamedahmedkhriji](https://github.com/mohamedahmedkhriji)

## 🙏 Acknowledgments

- Laravel Framework
- Bootstrap & Tailwind CSS
- Web Speech API
- Modern UI/UX design principles

---

⭐ Star this repository if you find it helpful!
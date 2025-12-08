import React, { useState, useEffect } from 'react';
import InteractiveButton from './InteractiveButton';

export default function HomepageHero({ title, subtitle, ctaText, ctaLink }) {
    const [displayedText, setDisplayedText] = useState('');
    const [isDeleting, setIsDeleting] = useState(false);
    const [textIndex, setTextIndex] = useState(0);

    const texts = [
        "Ne perdez plus de temps à écouter vos vocaux",
        "Automatisez vos messages WhatsApp",
        "Gagnez du temps avec l'intelligence artificielle"
    ];

    useEffect(() => {
        const currentText = texts[textIndex];
        let timeout;

        if (!isDeleting && displayedText.length < currentText.length) {
            timeout = setTimeout(() => {
                setDisplayedText(currentText.slice(0, displayedText.length + 1));
            }, 100);
        } else if (!isDeleting && displayedText.length === currentText.length) {
            timeout = setTimeout(() => setIsDeleting(true), 2000);
        } else if (isDeleting && displayedText.length > 0) {
            timeout = setTimeout(() => {
                setDisplayedText(currentText.slice(0, displayedText.length - 1));
            }, 50);
        } else if (isDeleting && displayedText.length === 0) {
            setIsDeleting(false);
            setTextIndex((prev) => (prev + 1) % texts.length);
        }

        return () => clearTimeout(timeout);
    }, [displayedText, isDeleting, textIndex]);

    return (
        <section className="relative bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-16 sm:py-20 lg:py-32 overflow-hidden min-h-[600px] sm:min-h-[700px] flex items-center">
            {/* Animated Background Particles */}
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
                <canvas id="particles-canvas" className="absolute inset-0 w-full h-full"></canvas>
            </div>

            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                <div className="text-center">
                    <h1 className="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-4 sm:mb-6">
                        <span className="text-primary-600 block sm:inline">
                            {displayedText}
                            <span className="animate-pulse">|</span>
                        </span>
                    </h1>
                    <p className="text-base sm:text-lg md:text-xl text-gray-600 mb-6 sm:mb-8 max-w-3xl mx-auto px-4">
                        {subtitle}
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <InteractiveButton
                            variant="primary"
                            size="lg"
                            href={ctaLink}
                            icon="fas fa-rocket"
                            className="w-full sm:w-auto"
                        >
                            {ctaText}
                        </InteractiveButton>
                        <InteractiveButton
                            variant="outline"
                            size="lg"
                            href="#features"
                            icon="fas fa-info-circle"
                            className="w-full sm:w-auto"
                        >
                            En savoir plus
                        </InteractiveButton>
                    </div>
                </div>
            </div>
        </section>
    );
}


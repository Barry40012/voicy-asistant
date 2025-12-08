import React, { useState, useEffect } from 'react';

export default function AnimatedCard({ 
    title, 
    value, 
    icon, 
    color = 'primary',
    animation = 'fadeInUp',
    delay = 0 
}) {
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => setIsVisible(true), delay);
        return () => clearTimeout(timer);
    }, [delay]);

    const colorClasses = {
        primary: 'from-primary-500 to-primary-600',
        secondary: 'from-secondary-500 to-secondary-600',
        green: 'from-green-500 to-green-600',
        purple: 'from-purple-500 to-purple-600',
    };

    return (
        <div
            className={`bg-gradient-to-br ${colorClasses[color]} rounded-2xl p-6 sm:p-8 text-white transform transition-all duration-700 ${
                isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
            } hover:scale-105 hover:shadow-2xl`}
            style={{ animationDelay: `${delay}ms` }}
        >
            <div className="flex items-center justify-between mb-4">
                <div className="bg-white/20 rounded-xl p-3 backdrop-blur-sm animate-pulse">
                    <i className={`${icon} text-white text-3xl`}></i>
                </div>
            </div>
            <div>
                <p className="text-white/80 text-sm font-medium mb-2">{title}</p>
                <p className="text-3xl font-bold">{value}</p>
            </div>
        </div>
    );
}


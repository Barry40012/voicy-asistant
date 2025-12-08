import React, { useState } from 'react';

export default function FeatureCard({ 
    icon, 
    title, 
    description, 
    color = 'primary',
    delay = 0 
}) {
    const [isHovered, setIsHovered] = useState(false);

    const colorClasses = {
        primary: {
            bg: 'from-primary-50 to-primary-100',
            border: 'border-primary-200',
            icon: 'bg-primary-600',
            iconSvg: 'text-white'
        },
        secondary: {
            bg: 'from-secondary-50 to-secondary-100',
            border: 'border-secondary-200',
            icon: 'bg-secondary-600',
            iconSvg: 'text-white'
        },
        green: {
            bg: 'from-green-50 to-green-100',
            border: 'border-green-200',
            icon: 'bg-green-600',
            iconSvg: 'text-white'
        }
    };

    const colors = colorClasses[color] || colorClasses.primary;

    return (
        <div
            className={`bg-gradient-to-br ${colors.bg} rounded-xl sm:rounded-2xl p-6 sm:p-8 border ${colors.border} transform transition-all duration-500 ${
                isHovered ? 'scale-105 shadow-2xl -translate-y-2' : 'shadow-md'
            }`}
            onMouseEnter={() => setIsHovered(true)}
            onMouseLeave={() => setIsHovered(false)}
            style={{ animationDelay: `${delay}ms` }}
            data-aos="fade-up"
            data-aos-delay={delay}
        >
            <div className={`${colors.icon} rounded-lg w-12 h-12 sm:w-14 sm:h-14 flex items-center justify-center mb-4 sm:mb-6 transform transition-transform duration-300 ${
                isHovered ? 'scale-110 rotate-6' : ''
            }`}>
                <i className={`${icon} ${colors.iconSvg} text-xl sm:text-2xl`}></i>
            </div>
            <h3 className="text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-3">{title}</h3>
            <p className="text-sm sm:text-base text-gray-600 leading-relaxed">{description}</p>
        </div>
    );
}


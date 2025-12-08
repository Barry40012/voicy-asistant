import React, { useState } from 'react';

export default function InteractiveButton({ 
    children, 
    onClick, 
    href,
    variant = 'primary',
    size = 'md',
    icon,
    loading = false,
    className = ''
}) {
    const [isHovered, setIsHovered] = useState(false);
    const [isPressed, setIsPressed] = useState(false);

    const baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-300 transform';
    
    const variantClasses = {
        primary: 'bg-primary-600 text-white hover:bg-primary-700 hover:shadow-xl',
        secondary: 'bg-secondary-600 text-white hover:bg-secondary-700 hover:shadow-xl',
        success: 'bg-green-600 text-white hover:bg-green-700 hover:shadow-xl',
        outline: 'border-2 border-primary-600 text-primary-600 hover:bg-primary-50',
    };

    const sizeClasses = {
        sm: 'px-4 py-2 text-sm',
        md: 'px-6 py-3 text-base',
        lg: 'px-8 py-4 text-lg',
    };

    const buttonClasses = `${baseClasses} ${variantClasses[variant]} ${sizeClasses[size]} ${className} ${
        isHovered ? 'scale-105' : ''
    } ${isPressed ? 'scale-95' : ''} ${loading ? 'opacity-75 cursor-not-allowed' : ''}`;

    const content = (
        <>
            {loading && (
                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            )}
            {icon && <i className={`${icon} mr-2`}></i>}
            {children}
        </>
    );

    if (href) {
        return (
            <a
                href={href}
                className={buttonClasses}
                onMouseEnter={() => setIsHovered(true)}
                onMouseLeave={() => setIsHovered(false)}
                onMouseDown={() => setIsPressed(true)}
                onMouseUp={() => setIsPressed(false)}
            >
                {content}
            </a>
        );
    }

    return (
        <button
            onClick={onClick}
            disabled={loading}
            className={buttonClasses}
            onMouseEnter={() => setIsHovered(true)}
            onMouseLeave={() => setIsHovered(false)}
            onMouseDown={() => setIsPressed(true)}
            onMouseUp={() => setIsPressed(false)}
        >
            {content}
        </button>
    );
}


import React, { useState } from 'react';
import InteractiveButton from './InteractiveButton';

export default function SubscriptionCard({ 
    plan, 
    isCurrent = false,
    onSubscribe,
    features = []
}) {
    const [isHovered, setIsHovered] = useState(false);

    return (
        <div
            className={`relative bg-white rounded-2xl p-8 border-2 transition-all duration-300 transform ${
                isCurrent 
                    ? 'border-primary-500 shadow-2xl scale-105' 
                    : 'border-gray-200 hover:border-primary-300 hover:shadow-xl'
            } ${isHovered ? 'scale-105' : ''}`}
            onMouseEnter={() => setIsHovered(true)}
            onMouseLeave={() => setIsHovered(false)}
        >
            {isCurrent && (
                <div className="absolute top-4 right-4 bg-primary-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    Actuel
                </div>
            )}
            
            <div className="text-center mb-6">
                <h3 className="text-2xl font-bold text-gray-900 mb-2">{plan.name}</h3>
                <div className="flex items-baseline justify-center">
                    <span className="text-4xl font-bold text-primary-600">{plan.price}</span>
                    <span className="text-gray-600 ml-2">/mois</span>
                </div>
                <p className="text-gray-500 text-sm mt-2">{plan.description}</p>
            </div>

            <ul className="space-y-3 mb-6">
                {features.map((feature, index) => (
                    <li key={index} className="flex items-center text-gray-700">
                        <i className="fas fa-check-circle text-green-500 mr-3"></i>
                        <span>{feature}</span>
                    </li>
                ))}
            </ul>

            <InteractiveButton
                variant={isCurrent ? 'outline' : 'primary'}
                size="lg"
                onClick={onSubscribe}
                className="w-full"
                icon="fas fa-crown"
            >
                {isCurrent ? 'Plan Actuel' : 'Choisir ce plan'}
            </InteractiveButton>
        </div>
    );
}


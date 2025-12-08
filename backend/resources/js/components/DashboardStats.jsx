import React, { useState, useEffect } from 'react';
import AnimatedCard from './AnimatedCard';

export default function DashboardStats({ stats = [] }) {
    const [animatedStats, setAnimatedStats] = useState([]);

    useEffect(() => {
        // Animer les statistiques une par une
        stats.forEach((stat, index) => {
            setTimeout(() => {
                setAnimatedStats(prev => [...prev, stat]);
            }, index * 200);
        });
    }, [stats]);

    return (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {animatedStats.map((stat, index) => (
                <AnimatedCard
                    key={index}
                    title={stat.title}
                    value={stat.value}
                    icon={stat.icon}
                    color={stat.color || 'primary'}
                    delay={index * 200}
                />
            ))}
        </div>
    );
}


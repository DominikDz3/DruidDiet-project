document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.chartData === 'undefined' || !window.chartData) {
        console.error('Dane wykresu (window.chartData) nie zostały znalezione. Upewnij się, że są zdefiniowane w szablonie Blade przed załadowaniem tego skryptu.');
        return;
    }

    const chartLabels = window.chartData.labels;
    const dailyRevenueValues = window.chartData.dailyRevenue;
    const dailyOrdersValues = window.chartData.dailyOrders;

    const revenueCanvas = document.getElementById('dailyRevenueChart');
    const ordersCanvas = document.getElementById('dailyOrdersChart');

    if (!chartLabels || !dailyRevenueValues || !dailyOrdersValues) {
        console.error('Jedna lub więcej tablic danych dla wykresów jest pusta lub niezdefiniowana.');
        if (revenueCanvas) revenueCanvas.style.display = 'none';
        if (ordersCanvas) ordersCanvas.style.display = 'none';
        return;
    }

    if (revenueCanvas) {
        const ctxRevenue = revenueCanvas.getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Przychód (zł)',
                    data: dailyRevenueValues,
                    borderColor: 'rgba(74, 107, 90, 1)',
                    backgroundColor: 'rgba(74, 107, 90, 0.1)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' zł';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('Element canvas dla wykresu przychodów ("dailyRevenueChart") nie został znaleziony.');
    }

    if (ordersCanvas) {
        const ctxOrders = ordersCanvas.getContext('2d');
        new Chart(ctxOrders, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Liczba Zamówień',
                    data: dailyOrdersValues,
                    borderColor: 'rgba(92, 184, 92, 1)',
                    backgroundColor: 'rgba(92, 184, 92, 0.1)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    } else {
        console.error('Element canvas dla wykresu zamówień ("dailyOrdersChart") nie został znaleziony.');
    }
});
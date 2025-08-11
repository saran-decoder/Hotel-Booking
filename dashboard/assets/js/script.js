const ctx = document.getElementById("revenueChart");
new Chart(ctx, {
    type: "line",
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [
            {
                label: "Revenue",
                data: [3000, 2500, 4600, 2100, 1800, 2500, 3100, 3500, 4000, 4200, 4600, 5800],
                borderColor: "#3B82F6",
                backgroundColor: "rgba(59, 130, 246, 0.3)",
                fill: true,
                tension: 0.4,
            },
        ],
    },
});

const pieCtx = document.getElementById("bookingPieChart");
new Chart(pieCtx, {
    type: "pie",
    data: {
        labels: ["Confirmed", "Pending", "Cancelled"],
        datasets: [
            {
                data: [60, 25, 15],
                backgroundColor: ["#3B82F6", "#FBBF24", "#EF4444"],
            },
        ],
    },
});
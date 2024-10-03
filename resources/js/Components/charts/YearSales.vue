
<template>
    <div class="bg-white py-2 px-4 rounded">
        <h4 class="text-lg font-bold text-gray-700">Ventas semanales</h4>
        <Chart type="bar" :data="chartData" :options="chartOptions" class="h-[30rem]"  />
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { AxiosResponse } from "axios";
import { ref, onMounted } from "vue";

onMounted(() => {
    loadChartsData();
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref();

const loadChartsData = () => {
    axios.get(route('api.charts.week-sales'))
    .then((response: AxiosResponse) => {
        chartData.value = response.data
    })
}

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}
</script>


<template>
    <div class="card flex justify-center">
        <Chart type="doughnut" :data="chartData" :options="chartOptions" class="w-full md:w-[30rem]" />
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, onMounted } from "vue";

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const chartData = ref();
const chartOptions = ref();

const setChartData = () => {
    // axios.get(route('api.charts.category-sales'))
    // .then()

    return {
        labels: ['A', 'B', 'C'],
        datasets: [
            {
                backgroundColor: ['#EF64AA', '#41D477', '#F37524'],
                hoverBackgroundColor: ['#DF3280', '#1EAC53', '#ED6013'],
                data: [540, 325, 702],
            }
        ]
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        plugins: {
            legend: {
                labels: {
                    cutout: '60%',
                    color: textColor
                }
            }
        }
    };
};
</script>

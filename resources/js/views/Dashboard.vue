<template>
    <section id="dashboard">
        <n-card title="DASHBOARD">
            <n-grid cols="1" class="chart-container">
                <n-gi>
                    <n-card title="Daily income expense">
                        <n-skeleton height="200px" v-if="dailyInExLoading" />
                        <Bar :chart-data="dailyInExData" :chart-options="barChartOptions" :height="160" v-else />
                    </n-card>
                </n-gi>
            </n-grid>
            <n-grid x-gap="24" cols="1 m:2" responsive="screen" class="chart-container">
                <n-gi>
                    <n-card title="Top 5 largest expenses">
                        <n-skeleton height="250px" v-if="largestExLoading" />
                        <Pie :chart-data="largestExData" :chart-options="pieChartOptions" :height="50" v-else />
                    </n-card>
                </n-gi>
                <n-gi>
                    <n-card title="Group expenses">
                        <n-skeleton height="250px" v-if="groupExLoading" />
                        <Pie :chart-data="groupExData" :chart-options="pieChartOptions" :height="80" v-else />
                    </n-card>
                </n-gi>
            </n-grid>
            <n-grid cols="1" class="chart-container">
                <n-gi>
                    <n-card title="Monthly income expense">
                        <n-skeleton height="200px" v-if="monthlyInExLoading" />
                        <Bar :chart-data="monthlyInExData" :chart-options="barChartOptions" :height="160" v-else />
                    </n-card>
                </n-gi>
            </n-grid>
        </n-card>
    </section>
</template>

<script>
import { ref } from "vue";
import { useMessage } from "naive-ui";
import { Bar, Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, ArcElement, CategoryScale, LinearScale);


export default {
    components: {
        Bar,
        Pie,
    },
    setup() {
        const message = useMessage();

        const dailyInExLoading = ref(true);
        const largestExLoading = ref(true);
        const groupExLoading = ref(true);
        const monthlyInExLoading = ref(true);

        const dailyInExData = ref();
        const largestExData = ref();
        const groupExData = ref();
        const monthlyInExData = ref();

        const barChartOptions = ref({
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 20,
                    }
                }
            }
        });
        const pieChartOptions = ref({
            plugins: {
                legend: {
                    position: "bottom",
                    labels: {
                        padding: 20,
                    }
                }
            }
        });

        const getDailyInExData = () => {
            axios.get('/api/dashboard/daily-income-expense').then(res => {
                if (res.data.response == "success") {
                    dailyInExData.value = res.data.data;
                    dailyInExLoading.value = false;
                }
                else{
                    message.error("Error fetching data. Please reload!");
                }
            });
        };
        getDailyInExData();
        const getLargestExData = () => {
            axios.get('/api/dashboard/largest-expense').then(res => {
                if (res.data.response == "success") {
                    largestExData.value = res.data.data;
                    largestExLoading.value = false;
                }
                else{
                    message.error("Error fetching data. Please reload!");
                }
            });
        };
        getLargestExData();
        const getGroupExData = () => {
            axios.get('/api/dashboard/group-expense').then(res => {
                if (res.data.response == "success") {
                    groupExData.value = res.data.data;
                    groupExLoading.value = false;
                }
                else{
                    message.error("Error fetching data. Please reload!");
                }
            });
        };
        getGroupExData();
        const getMonthlyInExData = () => {
            axios.get('/api/dashboard/monthly-income-expense').then(res => {
                if (res.data.response == "success") {
                    monthlyInExData.value = res.data.data;
                    monthlyInExLoading.value = false;
                }
                else{
                    message.error("Error fetching data. Please reload!");
                }
            });
        };
        getMonthlyInExData();

        return {
            dailyInExLoading,
            monthlyInExLoading,
            largestExLoading,
            groupExLoading,

            dailyInExData,
            largestExData,
            groupExData,
            monthlyInExData,

            barChartOptions,
            pieChartOptions,

            getDailyInExData,
            getLargestExData,
            getGroupExData,
            getMonthlyInExData,
        };
    },
};
</script>
<style lang="scss" scoped>
.chart-container {
    margin-bottom: 24px;
}
</style>

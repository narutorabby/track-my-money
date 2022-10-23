<template>
    <section id="dashboard">
        <n-card title="DASHBOARD">
            <template #header-extra>
                #header-extra
            </template>
            Card Content
        </n-card>
    </section>
</template>

<script>
import { ref, h } from "vue";
import { useStore } from "vuex";
import { useRouter, RouterLink } from "vue-router";
import { NIcon } from "naive-ui";
import { Home, MoneyBill, } from "@vicons/fa";

export default {
    setup() {
        const store = useStore();
        const router = useRouter();

        return {
            //
        };
    },
};
</script>
<style lang="scss" scoped>
</style>

<template>
    <section id="dashboard">
        <n-space vertical>
        <n-layout has-sider>
            <n-layout-sider
                collapse-mode="width"
                :collapsed-width="64"
                :collapsed="collapsed"
                show-trigger
                @collapse="collapsed = true"
                @expand="collapsed = false"
            >
                <img class="logo" src="../assets/images/logo.png" alt="Logo">
                <n-menu
                    v-model:value="activeKey"
                    :collapsed="collapsed"
                    :collapsed-width="64"
                    :collapsed-icon-size="32"
                    :options="menuOptions"
                    :accordion="true"
                />
            </n-layout-sider>
            <n-layout>
                <n-layout-header>Yiheyuan Road</n-layout-header>
                <n-layout-content content-style="padding: 24px;">
                Pingshan Road
                </n-layout-content>
                <n-layout-footer>Chengfu Road</n-layout-footer>
            </n-layout>
        </n-layout>
        </n-space>
    </section>
</template>

<script>
import { h, ref } from "vue";
import { NIcon } from "naive-ui";
import {
    MoneyBill,
} from "@vicons/fa";

export default {
    setup() {
        const activeKey = ref('dashboard');
        const collapsed = ref(false);

        const renderIcon = (icon) => {
            return () => h(NIcon, null, { default: () => h(icon) });
        }

        const menuOptions = ref([
            {
                label: "Dashboard",
                key: "dashboard",
                icon: renderIcon(MoneyBill),
            },
            {
                label: "Personal",
                key: "personal",
                icon: renderIcon(MoneyBill),
            },
            {
                label: "Group",
                key: "group",
                icon: renderIcon(MoneyBill),
            },
            {
                label: "Account",
                key: "account",
                icon: renderIcon(MoneyBill),
            },
        ]);

        return {
            activeKey,
            collapsed,
            menuOptions,

            renderIcon,
        };
    },
};
</script>
<style scoped>
.n-layout-header,
.n-layout-footer {
    padding: 24px;
}
.logo {
    height: 48px;
    width: auto;
    margin-bottom: 20px;
}
</style>

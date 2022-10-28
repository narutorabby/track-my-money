<template>
    <n-grid :cols="1">
        <n-gi>
            <n-layout has-sider>
                <n-layout-sider
                    collapse-mode="width"
                    :collapsed-width="64"
                    :collapsed="collapsed"
                    show-trigger
                    @collapse="collapsed = true"
                    @expand="collapsed = false"
                >
                    <div class="brand-container" @click="home">
                        <img class="logo" src="../assets/images/logo.png" alt="Logo">
                        <span class="name" v-if="!collapsed">Track My money</span>
                    </div>
                    <n-menu
                        v-model:value="activeKey"
                        :collapsed="collapsed"
                        :collapsed-width="64"
                        :collapsed-icon-size="28"
                        :options="menuOptions"
                        :accordion="true"
                    />
                </n-layout-sider>
                <n-layout class="container-main">
                    <router-view />
                </n-layout>
            </n-layout>
            <n-divider />
            <div class="footer">
                <n-space justify="center">
                    {{ new Date().getFullYear() }} — <strong>Track My Money</strong>
                </n-space>
            </div>
        </n-gi>
    </n-grid>
</template>
<script>
import { ref, computed, h } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute, RouterLink } from "vue-router";
import { NIcon } from "naive-ui";
import { HouseUser, UserTie, Users, Envelope, UserCog } from "@vicons/fa";

export default {
    setup() {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();

        const userData = computed(() => {
            return store.getters.userData || null;
        });

        const currentRoute = computed(() => {
            return route.name;
        });

        const activeKey = ref(currentRoute);
        const collapsed = ref(false);

        const renderIcon = (icon) => {
            return () => h(NIcon, null, { default: () => h(icon) });
        }

        const menuOptions = ref([
            {
                label: () => h(
                    RouterLink,
                    { to: { name: 'Dashboard' } },
                    { default: () => "Dashboard" }
                ),
                key: "Dashboard",
                icon: renderIcon(HouseUser),
            },
            {
                label: () => h(
                    RouterLink,
                    { to: { name: 'Personal' } },
                    { default: () => "Personal" }
                ),
                key: "Personal",
                icon: renderIcon(UserTie),
            },
            {
                label: () => h(
                    RouterLink,
                    { to: { name: 'Group' } },
                    { default: () => "Group" }
                ),
                key: "Group",
                icon: renderIcon(Users),
            },
            {
                label: () => h(
                    RouterLink,
                    { to: { name: 'Invitation' } },
                    { default: () => "Invitation" }
                ),
                key: "Invitation",
                icon: renderIcon(Envelope),
            },
            {
                label: () => h(
                    RouterLink,
                    { to: { name: 'Profile' } },
                    { default: () => userData.value ? userData.value.name : "User" }
                ),
                key: "Profile",
                icon: renderIcon(UserCog),
            }
        ]);

        const home = () => {
            router.push('/');
        }

        return {
            userData,
            currentRoute,
            activeKey,
            collapsed,
            menuOptions,

            renderIcon,
            home,
        };
    },
};
</script>
<style lang="scss" scoped>
.n-layout-header, .n-layout-footer {
    padding: 24px;
}
.brand-container {
    height: 36px;
    position: relative;
    margin: 10px;
    cursor: pointer;

    .logo {
        height: 36px;
        width: auto;
    }
    .name {
        font-size: 16px;
        text-transform: uppercase;
        top: 5px;
        position: absolute;
        margin-left: 10px;
    }
}
.footer {
    margin-bottom: 20px;
}
</style>

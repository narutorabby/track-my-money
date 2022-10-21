<template>
    <n-grid :cols="1">
        <n-gi>
            <div class="main-container">
                <router-view></router-view>
            </div>
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
import { ref } from "vue";
export default {
    setup() {
        const label = ref("Button");

        return {
            label,
        };
    },
};
</script>
<style lang="scss" scoped>
.footer {
    margin-bottom: 20px;
}
</style>

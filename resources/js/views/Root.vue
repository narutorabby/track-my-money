<template>
    <n-grid :cols="1">
        <n-gi>
            <div class="main-container">
                <router-view></router-view>
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

<template>
    <section id="personal">
        <n-card title="PERSONAL">
            <template #header-extra>
                <n-button>
                    <template #icon>
                        <n-icon>
                        <plus />
                        </n-icon>
                    </template>
                    Create New
                </n-button>
            </template>
            <n-table striped>
                <thead>
                <tr>
                    <th>Abandon</th>
                    <th>Abormal</th>
                    <th>Abolish</th>
                    <th>...</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>放弃</td>
                    <td>反常的</td>
                    <td>彻底废除</td>
                    <td>...</td>
                </tr>
                <tr>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                    <td>...</td>
                </tr>
                </tbody>
            </n-table>
        </n-card>
    </section>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter, RouterLink } from "vue-router";
import { Plus } from "@vicons/fa";

export default {
    components: {
        Plus
    },
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

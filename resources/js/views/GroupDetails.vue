<template>
    <section id="group-details">
        <n-card title="GROUP DETAILS">
            <template #header-extra>
                <n-button @click="openModal()">
                    <template #icon>
                        <n-icon>
                            <plus />
                        </n-icon>
                    </template>
                    Create New
                </n-button>
            </template>
            <n-space class="data-container" vertical>
                <n-card>
                    <n-skeleton height="120px" v-if="pageLoading" />
                    <template v-else>
                        <n-descriptions label-placement="top" :column="4">
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Name</strong>
                                </template>
                                {{ group.name }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Slug</strong>
                                </template>
                                {{ group.slug }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Admin</strong>
                                </template>
                                {{ group.admin.name }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Total members</strong>
                                </template>
                                {{ group.members_count }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Total records</strong>
                                </template>
                                {{ group.records_count }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Created At</strong>
                                </template>
                                {{ formatDate(group.created_at) }}
                            </n-descriptions-item>
                            <n-descriptions-item>
                                <template #label>
                                    <strong>Last Edited At</strong>
                                </template>
                                {{ formatDate(group.updated_at) }}
                            </n-descriptions-item>
                        </n-descriptions>
                    </template>
                </n-card>
            </n-space>
        </n-card>
    </section>
</template>
<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { useRouter } from "vue-router";
import { useMessage } from "naive-ui";
import { Plus, EyeRegular, EditRegular } from "@vicons/fa";
import moment from 'moment';

export default {
    components: {
        Plus,
        EyeRegular,
        EditRegular,
    },
    setup() {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const userData = computed(() => {
            return store.getters.userData || null;
        });

        const pageLoading = ref(true);
        const group = ref(null);

        const groupDetails = () => {
            pageLoading.value = true;
            axios.get('/api/group/show/' + route.params.slug).then(res => {
                if (res.data.response == "success") {
                    group.value = res.data.data;
                    console.log(group.value);
                }
                else{
                    message.error("Error fetching details. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        groupDetails();

        const openModal = () => {
        };

        const formatDate = (rawDate) => {
            return moment(rawDate).format("DD-MM-YYYY");
        };

        return {
            userData,
            pageLoading,
            group,

            groupDetails,
            openModal,
            formatDate,
        }
    },
}
</script>

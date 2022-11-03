<template>
    <section id="group">
        <n-card title="GROUPS">
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
                <template v-if="pageLoading">
                    <n-skeleton height="50px" />
                    <n-skeleton v-for="i in 10" :key="i" height="50px" />
                </template>
                <template v-else-if="groups && groups.length > 0">
                    <n-table striped>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Admin</th>
                                <th>Total Members</th>
                                <th>Total Records</th>
                                <th>Created At</th>
                                <th>Last Edited At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(group, index) in groups" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ group.name }}</td>
                            <td>{{ group.admin.name }}</td>
                            <td>{{ group.members_count }}</td>
                            <td>{{ group.records_count }}</td>
                            <td>{{ formatDate(group.created_at) }}</td>
                            <td>{{ formatDate(group.updated_at) }}</td>
                            <td style="text-align: end;">
                                <n-button-group>
                                    <n-button round @click="editGroup(index, 'edit')">
                                        <template #icon>
                                            <n-icon>
                                                <edit-regular />
                                            </n-icon>
                                        </template>
                                        Edit
                                    </n-button>
                                    <n-button round @click="groupMembers(group.slug)">
                                        <template #icon>
                                            <n-icon>
                                                <eye-regular />
                                            </n-icon>
                                        </template>
                                        Members
                                    </n-button>
                                    <n-button round @click="groupRecords(group.slug)">
                                        <template #icon>
                                            <n-icon>
                                                <eye-regular />
                                            </n-icon>
                                        </template>
                                        Records
                                    </n-button>
                                </n-button-group>
                            </td>
                        </tr>
                        </tbody>
                    </n-table>
                </template>
                <template v-else>
                    <n-empty class="empty" size="huge" description="No group found">
                        <template #extra>
                            <n-button @click="openModal()">
                                <template #icon>
                                    <n-icon>
                                    <plus />
                                    </n-icon>
                                </template>
                                Create New
                            </n-button>
                        </template>
                    </n-empty>
                </template>
            </n-space>
        </n-card>
        <n-modal v-model:show="showModalRef" :mask-closable="false" :auto-focus="false">
            <n-card
                style="width: 600px"
                :name="editFlag ? 'Edit group' : 'Create group'"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <n-space vertical>
                    <n-form ref="formRef" :model="group" :rules="rules">
                        <n-grid cols="1">
                            <n-gi>
                                <n-form-item label="Title" path="name">
                                    <n-input
                                        v-model:value="group.name"
                                        placeholder="Input Title"
                                        maxlength="60"
                                        show-count
                                    />
                                </n-form-item>
                            </n-gi>
                        </n-grid>
                        <n-space justify="end">
                            <n-button @click="closeModal">Cancel</n-button>
                            <n-button type="primary" @click="submitForm" :loading="formSubmitLoading">
                                <template #icon>
                                    <n-icon>
                                        <check />
                                    </n-icon>
                                </template>
                                {{ editFlag ? 'Save' : 'Create' }}
                            </n-button>
                        </n-space>
                    </n-form>
                </n-space>
            </n-card>
        </n-modal>
    </section>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import { useMessage } from "naive-ui";
import { Plus, EyeRegular, EditRegular, Check } from "@vicons/fa";
import moment from 'moment';

export default {
    components: {
        Plus,
        EyeRegular,
        EditRegular,
        Check,
    },
    setup: () => {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const pageLoading = ref(true);
        const formSubmitLoading = ref(false);
        const groups = ref([]);
        const group = ref({
            id: 0,
            name: '',
        });
        const editFlag = ref(false);

        const showModalRef = ref(false);
        const formRef = ref(null);

        const rules = ref({
            name: {
                required: true,
                message: 'Please enter name',
                trigger: ["input", "blur"]
            }
        });

        const getGroups = () => {
            pageLoading.value = true;
            axios.get('/api/group/list').then(res => {
                if (res.data.response == "success") {
                    groups.value = res.data.data;
                }
                else{
                    message.error("Error fetching list. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        getGroups();

        const openModal = () => {
            showModalRef.value = true;
        };
        const submitForm = (event) => {
            event.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    formSubmitLoading.value = true;
                    let url = "";
                    let formData = {};
                    if(editFlag.value) {
                        formData = { ...group.value };
                        url = "/api/group/update/" + group.value.id;
                    }
                    else {
                        formData = { ...group.value };
                        url = "/api/group/create";
                    }
                    axios.post(url, formData)
                    .then((res) => {
                        if (res.data.response == "success") {
                            message.success(res.data.message);
                            getGroups();
                            closeModal();
                        } else {
                            message.error(res.data.message);
                        }
                        formSubmitLoading.value = false;
                    })
                    .catch(() => {
                        formSubmitLoading.value = false;
                        message.error(`Could not ${editFlag.value ? 'edit' : 'create'} group!`);
                    });
                } else {
                    console.log(errors);
                }
            });
        };
        const closeModal = () => {
            showModalRef.value = false;
            editFlag.value = false;
            group.value = {
                id: 0,
                name: '',
            };
        };
        const editGroup = (index, flag) => {
            if(flag == "edit") {
                editFlag.value = true;
            }
            let selectedGroup = groups.value[index];
            group.value = {
                id: selectedGroup.id,
                name: selectedGroup.name,
            };
            showModalRef.value = true;
        };

        const groupMembers = (slug) => {
            router.push({ name: 'GroupMembers', params: { slug: slug } });
        };

        const groupRecords = (slug) => {
            router.push({ name: 'GroupRecords', params: { slug: slug } });
        };

        const formatDate = (rawDate) => {
            return moment(rawDate).format("DD-MM-YYYY");
        };

        return {
            pageLoading,
            formSubmitLoading,
            groups,
            group,
            editFlag,
            showModalRef,
            formRef,
            rules,

            getGroups,

            openModal,
            submitForm,
            closeModal,
            editGroup,
            groupMembers,
            groupRecords,

            formatDate,
        };
    },
};
</script>
<style lang="scss" scoped>
.data-container {
    min-height: 80vh;

    .empty {
        margin: 25vh 0;
    }
}
</style>

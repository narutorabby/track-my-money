<template>
    <section id="group-members">
        <n-card title="GROUP MEMBERS">
            <template #header-extra>
            <n-button @click="openModal()">
                <template #icon>
                    <n-icon>
                        <envelope-regular />
                    </n-icon>
                </template>
                Invite New Member
            </n-button>
        </template>
            <n-space class="data-container" vertical>
                <template v-if="pageLoading">
                    <n-skeleton height="50px" />
                    <n-skeleton v-for="i in 10" :key="i" height="50px" />
                </template>
                <template v-else-if="members && members.length > 0">
                    <n-table striped>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Total Contribution</th>
                                <th>Total Bill</th>
                                <th>Joined At</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(member, index) in members" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ member.user.name }}</td>
                            <td>{{ member.user.email }}</td>
                            <td>{{ member.user.mobile || '-' }}</td>
                            <td>{{ member.total_contribution.toLocaleString('en-BD') }}</td>
                            <td>{{ member.total_bill.toLocaleString('en-BD') }}</td>
                            <td>{{ formatDate(member.joined_at) }}</td>
                        </tr>
                        </tbody>
                    </n-table>
                </template>
                <template v-else>
                    <n-empty class="empty" size="huge" description="No group members found">
                    </n-empty>
                </template>
            </n-space>
        </n-card>
        <n-modal v-model:show="showModalRef" :mask-closable="false" :auto-focus="false">
            <n-card
                style="width: 600px"
                name="Invite new member"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <n-space vertical>
                    <n-form ref="formRef" :model="member" :rules="rules">
                        <n-grid cols="1">
                            <n-gi>
                                <n-form-item label="Valid Email" path="email">
                                    <n-input
                                        v-model:value="member.email"
                                        placeholder="Enter member email"
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
                                Send Invitation
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
import { useRoute } from "vue-router";
import { useMessage } from "naive-ui";
import moment from 'moment';
import { EnvelopeRegular, Check } from "@vicons/fa";

export default {
    components: {
        EnvelopeRegular,
        Check,
    },
    setup() {
        const route = useRoute();
        const message = useMessage();

        const pageLoading = ref(true);
        const members = ref();
        const member = ref({
            email: ''
        });

        const showModalRef = ref(false);
        const formSubmitLoading = ref(false);
        const formRef = ref(null);

        const rules = ref({
            email: {
                required: true,
                message: 'Please enter email',
                trigger: ["input", "blur"]
            }
        });

        const groupMembers = () => {
            pageLoading.value = true;
            axios.get('/api/member/list', {
                params: {
                    slug: route.params.slug
                }
            }).then(res => {
                if (res.data.response == "success") {
                    members.value = res.data.data;
                }
                else{
                    message.error("Error fetching group members. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        groupMembers();

        const openModal = () => {
            showModalRef.value = true;
        };
        const submitForm = (event) => {
            event.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    formSubmitLoading.value = true;
                    axios.post("/api/invitation/invite", {
                        group: route.params.slug,
                        email: member.value.email,
                    })
                    .then((res) => {
                        if (res.data.response == "success") {
                            message.success(res.data.message);
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
            member.value = {
                email: '',
            };
        };

        const formatDate = (rawDate) => {
            return moment(rawDate).format("DD-MM-YYYY");
        };

        return {
            pageLoading,
            formSubmitLoading,
            members,
            member,
            showModalRef,
            formRef,
            rules,

            groupMembers,
            openModal,
            submitForm,
            closeModal,
            formatDate,
        }
    },
}
</script>

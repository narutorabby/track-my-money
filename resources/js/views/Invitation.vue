<template>
    <section id="invitations">
        <n-card title="INVITATIONS">
            <n-space class="data-container" vertical>
                <n-card name="List filters" hoverable>
                    <n-grid x-gap="24" y-gap="12" cols="1 m:4" responsive="screen">
                        <n-gi>
                            <n-select placeholder="Select invitation type" size="large" v-model:value="invitationType" :options="invitationTypes" clearable />
                        </n-gi>
                        <n-gi>
                            <n-button @click="getFilteredInvitations">Submit</n-button>
                        </n-gi>
                    </n-grid>
                </n-card>
                <template v-if="pageLoading">
                    <n-skeleton height="50px" />
                    <n-skeleton v-for="i in 10" :key="i" height="50px" />
                </template>
                <template v-else-if="invitations && invitations.length > 0">
                    <n-table striped>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Group</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(invitation, index) in invitations" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ invitation.user.name }}</td>
                            <td>{{ invitation.user.email }}</td>
                            <td>{{ invitation.group.name }}</td>
                            <td>
                                <n-tag :type="
                                    invitation.status == 'Accepted' ? 'success'
                                        : invitation.status == 'Declined' ? 'warning'
                                            : invitation.status == 'Canceled' ? 'error' : 'info'">
                                    {{ invitation.status }}
                                </n-tag>
                            </td>
                            <td style="text-align: end;">
                                <n-button-group v-if="invitation.status == 'Pending'">
                                    <template v-if="invitation.user_id == userData.id">
                                        <n-button round @click="invitationAction(invitation.id, 'Accept')">
                                            <template #icon>
                                                <n-icon>
                                                    <check />
                                                </n-icon>
                                            </template>
                                            Accept
                                        </n-button>
                                        <n-button round @click="invitationAction(invitation.id, 'Decline')">
                                            <template #icon>
                                                <n-icon>
                                                    <times />
                                                </n-icon>
                                            </template>
                                            Decline
                                        </n-button>
                                    </template>
                                    <template v-else>
                                        <n-button round @click="invitationAction(invitation.id, 'Cancel')">
                                            <template #icon>
                                                <n-icon>
                                                    <times />
                                                </n-icon>
                                            </template>
                                            Cancel
                                        </n-button>
                                    </template>
                                </n-button-group>
                            </td>
                        </tr>
                        </tbody>
                    </n-table>
                </template>
                <template v-else>
                    <n-empty class="empty" size="huge" description="No invitations found">
                    </n-empty>
                </template>
            </n-space>
        </n-card>
    </section>
</template>
<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import { useMessage, useDialog } from "naive-ui";
import { Check, Times } from "@vicons/fa";
import moment from 'moment';

export default {
    components: {
        Check,
        Times,
    },
    setup() {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();
        const dialog = useDialog();

        const userData = computed(() => {
            return store.getters.userData || null;
        });

        const pageLoading = ref(true);
        const invitations = ref([]);
        const invitation = ref();

        const invitationTypes = ref([
            {
                label: "Sent",
                value: "sent"
            },
            {
                label: "Received",
                value: "received"
            },
        ]);
        const invitationType = ref(null);

        const getInvitations = () => {
            pageLoading.value = true;
            axios.get('/api/invitation/list', {
                params: {
                    type: invitationType.value,
                }
            }).then(res => {
                if (res.data.response == "success") {
                    invitations.value = res.data.data;
                }
                else{
                    message.error("Error fetching list. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        const getActiveFilters = () => {
            if(route.query.type != null){
                invitationType.value = route.query.type;
            }
            getInvitations();
        };
        getActiveFilters();

        const getFilteredInvitations = () => {
            pageLoading.value = true;

            let queryParams = {};
            if(invitationType.value) {
                queryParams.type = invitationType.value;
            }

            router.replace({ name: 'Invitation', query: { ...queryParams } });
            getInvitations();
        };

        const invitationAction = (id, status) => {
            const d = dialog.success({
                title: status + " invitation",
                content: status + " this invitation! Are you sure?",
                negativeText: "No",
                positiveText: "Yes",
                onPositiveClick: () => {
                    d.loading = true;
                    return new Promise((resolve) => {
                        axios.post("/api/invitation/invitation-action/" + id, {
                            status: status == "Accept" ? "Accepted" : status == "Decline" ? "Declined" : status == "Cancel" ? "Canceled" : ""
                        })
                        .then((res) => {
                            if (res.data.response == "success") {
                                message.success(res.data.message);
                                getInvitations();
                            } else {
                                message.error(res.data.message);
                            }
                        })
                        .catch(() => {
                            message.error("Could not " + status.toLowerCase() + " invitation!");
                        })
                        .then(resolve);
                    });
                }
            });
        };

        return {
            userData,
            pageLoading,
            invitations,
            invitation,
            invitationTypes,
            invitationType,

            getActiveFilters,
            invitationAction,
            getFilteredInvitations,
        }
    },
}
</script>

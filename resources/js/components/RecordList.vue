<template>
    <n-card :title="isGroup ? 'GROUP RECORDS' : 'PERSONAL RECORDS'">
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
            <n-card title="List filters" hoverable>
                <n-grid x-gap="24" y-gap="12" cols="1 m:4" responsive="screen">
                    <n-gi>
                        <n-select placeholder="Select record type" size="large" v-model:value="recordType" :options="isGroup ? groupRecordTypes : personalRecordTypes" clearable />
                    </n-gi>
                    <n-gi>
                        <n-date-picker placeholder="Select date from" size="large" v-model:formatted-value="dateFrom" value-format="dd-MM-yyyy" type="date" clearable />
                    </n-gi>
                    <n-gi>
                        <n-date-picker placeholder="Select date to" size="large" v-model:formatted-value="dateTo" value-format="dd-MM-yyyy" type="date" clearable />
                    </n-gi>
                    <n-gi>
                        <n-button @click="getFilteredRecords">Submit</n-button>
                    </n-gi>
                </n-grid>
            </n-card>
            <template v-if="pageLoading">
                <n-skeleton height="50px" />
                <n-skeleton v-for="i in 10" :key="i" height="50px" />
            </template>
            <template v-else-if="records && records.total > 0">
                <n-table striped>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(record, index) in records.data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ formatDate(record.date) }}</td>
                        <td>{{ record.type }}</td>
                        <td>{{ record.amount.toLocaleString('en-BD') }}</td>
                        <td>{{ record.title }}</td>
                        <td style="text-align: end;">
                            <n-button-group>
                                <n-button round @click="viewEditRecord(index, 'edit')">
                                    <template #icon>
                                        <n-icon>
                                            <edit-regular />
                                        </n-icon>
                                    </template>
                                    Edit
                                </n-button>
                                <n-button round @click="viewEditRecord(index, 'view')">
                                    <template #icon>
                                        <n-icon>
                                            <eye-regular />
                                        </n-icon>
                                    </template>
                                    View
                                </n-button>
                            </n-button-group>
                        </td>
                    </tr>
                    </tbody>
                </n-table>
                <n-space justify="end">
                    <n-pagination
                        :item-count="records.total"
                        v-model:page="currentPage"
                        v-model:page-size="currentPageSize"
                        :page-sizes="[10, 20, 50, 100]"
                        size="large"
                        show-size-picker
                        :on-update:page="(page) => { pageChanged(page)}"
                        :on-update:page-size="(pageSize) => { currentPageSizeChanged(pageSize)}"
                    />
                </n-space>
            </template>
            <template v-else>
                <n-empty class="empty" size="huge" description="No records found">
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
        <n-modal v-model:show="showModalRef" :mask-closable="false" :auto-focus="false">
            <create-edit-record
                :record="record"
                :editFlag="editFlag"
                :viewFlag="viewFlag"
                :groupId="isGroup ? group.id : null"
                :members="isGroup ? group.members : []"
                @close="closeModal"
            />
        </n-modal>
    </n-card>
</template>

<script>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useMessage } from "naive-ui";
import { Plus, EyeRegular, EditRegular, TrashAltRegular } from "@vicons/fa";
import moment from 'moment';
import CreateEditRecord from './CreateEditRecord.vue';

export default {
    components: {
        Plus,
        EyeRegular,
        EditRegular,
        TrashAltRegular,
        CreateEditRecord,
    },
    props: {
        isGroup: {
            type: Boolean,
            default: false,
        },
    },
    setup: (props) => {
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const isGroup = ref(props.isGroup);

        const pageLoading = ref(true);
        const formSubmitLoading = ref(false);
        const group = ref();

        const records = ref([]);
        const record = ref({
            id: 0,
            title: '',
            amount: 0,
            date: null,
            type: '',
            description: "",
            members: [],
        });
        const viewFlag = ref(false);
        const editFlag = ref(false);

        const showModalRef = ref(false);

        // List filters
        const currentPage = ref(1);
        const currentPageSize = ref(10);

        const personalRecordTypes = ref([
            {
                label: "Income",
                value: "Income"
            },
            {
                label: "Expense",
                value: "Expense"
            },
        ]);

        const groupRecordTypes = ref([
            {
                label: "Contribution",
                value: "Contribution"
            },
            {
                label: "Bill",
                value: "Bill"
            },
        ]);
        const recordType = ref(null);
        const dateFrom = ref(null);
        const dateTo = ref(null);

        const groupDetails = () => {
            pageLoading.value = true;
            axios.get('/api/group/show/' + route.params.slug).then(res => {
                if (res.data.response == "success") {
                    group.value = res.data.data;
                    getActiveFilters();
                }
                else{
                    message.error("Error fetching details. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        const getRecords = () => {
            pageLoading.value = true;
            let url = isGroup.value ? "/api/record/group" : "/api/record/personal";
            axios.get(url, {
                params: {
                    group_id: isGroup.value ? group.value.id : null,
                    type: recordType.value,
                    date_from: dateFrom.value,
                    date_to: dateTo.value,
                    page_size: currentPageSize.value,
                    page: currentPage.value,
                }
            }).then(res => {
                if (res.data.response == "success") {
                    records.value = res.data.data;
                    if(isGroup.value) {
                        records.value.data.forEach(record => {
                            let members = [];
                            if(record.type == 'Contribution') {
                                members = record.shares[0].id;
                            }
                            else {
                                record.shares.forEach(member => {
                                    members.push(member.id);
                                });
                            }
                            record.members = members;
                        });
                    }
                }
                else{
                    message.error("Error fetching list. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        const getActiveFilters = () => {
            if(route.query.page != null){
                currentPage.value = parseInt(route.query.page);
            }
            if(route.query.page_size != null){
                currentPageSize.value = parseInt(route.query.page_size);
            }
            if(route.query.type != null){
                recordType.value = route.query.type;
            }
            if(route.query.date_from != null){
                dateFrom.value = route.query.date_from;
            }
            if(route.query.date_to != null){
                dateTo.value = route.query.date_to;
            }
            getRecords();
        };

        const initAction = () => {
            if(route.name == "GroupRecords") {
                groupDetails();
            }
            else {
                getActiveFilters();
            }
        };
        initAction();

        const getFilteredRecords = () => {
            pageLoading.value = true;

            let queryParams = {};
            if(recordType.value) {
                queryParams.type = recordType.value;
            }
            if(dateFrom.value) {
                queryParams.date_from = dateFrom.value;
            }
            if(dateTo.value) {
                queryParams.date_to = dateTo.value;
            }
            currentPage.value = 1;
            currentPageSize.value = 10;

            router.replace({ name: 'GroupRecords', query: { ...queryParams } });
            getRecords();
        };

        const pageChanged = (page) => {
            currentPage.value = page;
            getPaginatedRecords();
        };
        const currentPageSizeChanged = (pageSize) => {
            currentPageSize.value = pageSize;
            getPaginatedRecords();
        };
        const getPaginatedRecords = () => {
            router.replace({ name: 'GroupRecords', query: Object.assign({}, route.query, { page: currentPage.value, page_size: currentPageSize.value }) });
            getRecords();
        };

        const openModal = () => {
            showModalRef.value = true;
        };
        const closeModal = (result) => {
            if(result) {
                initAction();
            }
            showModalRef.value = false;
            viewFlag.value = false;
            editFlag.value = false;
            record.value = {
                id: 0,
                title: '',
                amount: 0,
                date: null,
                type: '',
                description: "",
                members: []
            };
        };
        const viewEditRecord = (index, flag) => {
            if(flag == "edit") {
                editFlag.value = true;
            }
            else {
                viewFlag.value = true;
            }
            let selectedRecord = records.value.data[index];
            record.value = {
                id: selectedRecord.id,
                title: selectedRecord.title,
                amount: selectedRecord.amount,
                date: selectedRecord.date,
                type: selectedRecord.type,
                description: selectedRecord.description,
                members: selectedRecord.members,
            };
            showModalRef.value = true;
        };

        const formatDate = (rawDate) => {
            return moment(rawDate).format("DD-MM-YYYY");
        };

        return {
            isGroup,
            pageLoading,
            formSubmitLoading,
            group,
            records,
            record,
            viewFlag,
            editFlag,
            showModalRef,

            currentPage,
            currentPageSize,
            personalRecordTypes,
            groupRecordTypes,
            recordType,
            dateFrom,
            dateTo,

            initAction,
            groupDetails,
            getRecords,
            getActiveFilters,
            getFilteredRecords,
            pageChanged,
            currentPageSizeChanged,
            getPaginatedRecords,

            openModal,
            viewEditRecord,
            closeModal,

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

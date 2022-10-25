<template>
    <section id="personal">
        <n-card title="PERSONAL">
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
                            <n-select placeholder="Select record type" size="large" v-model:value="recordType" :options="recordTypes" clearable />
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
                    <n-skeleton v-for="i in 8" :key="i" height="36px" />
                </template>
                <template v-else-if="records && records.total > 0">
                    <n-table size="large" striped>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Title</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(record, index) in records.data" :key="index">
                            <td>{{ record.date }}</td>
                            <td>{{ record.type }}</td>
                            <td>{{ record.amount }}</td>
                            <td>{{ record.title }}</td>
                            <td>...</td>
                        </tr>
                        </tbody>
                    </n-table>
                    <n-space justify="end">
                        <n-pagination
                            :item-count="records.total"
                            v-model:page="currentPage"
                            v-model:page-size="currentPageSize"
                            :page-sizes="[10, 20, 30, 40]"
                            size="large"
                            show-size-picker
                            :on-update:page="(page) => { pageChanged(page)}"
                            :on-update:page-size="(pageSize) => { currentPageSizeChanged(pageSize)}"
                        />
                    </n-space>
                </template>
                <template v-else>
                    <n-empty class="empty" size="huge" description="No personal records found">
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
        <n-modal v-model:show="showModalRef" :mask-closable="false">
            <n-card
                style="width: 600px"
                title="Create record"
                size="huge"
                role="dialog"
                aria-modal="true"
            >
                <n-space vertical>
                    <n-form ref="formRef" :model="record" :rules="rules">
                        <n-grid cols="1">
                            <n-gi>
                                <n-form-item label="Title" path="title">
                                    <n-input
                                        v-model:value="record.title"
                                        placeholder="Input Title"
                                        maxlength="60"
                                        show-count
                                    />
                                </n-form-item>
                            </n-gi>
                        </n-grid>
                        <n-grid cols="3" x-gap="12">
                            <n-gi>
                                <n-form-item label="Amount" path="amount">
                                    <n-input-number
                                        v-model:value="record.amount"
                                        placeholder="Input Amount"
                                        :min="0"
                                        :precision="2"
                                    />
                                </n-form-item>
                            </n-gi>
                            <n-gi>
                                <n-form-item label="Date" path="date">
                                    <n-date-picker
                                        v-model:formatted-value="record.date"
                                        placeholder="Select a date"
                                        value-format="dd-MM-yyyy"
                                        type="date"
                                    />
                                </n-form-item>
                            </n-gi>
                            <n-gi>
                                <n-form-item label="Type" path="type">
                                    <n-select
                                        v-model:value="record.type"
                                        placeholder="Select record type"
                                        :options="recordTypes"
                                    />
                                </n-form-item>
                            </n-gi>
                        </n-grid>
                        <n-grid cols="1">
                            <n-gi>
                                <n-form-item label="Description" path="description">
                                    <n-input
                                        v-model:value="record.description"
                                        placeholder="Write a description..."
                                        type="textarea"
                                        maxlength="500"
                                        show-count
                                    />
                                </n-form-item>
                            </n-gi>
                        </n-grid>
                        <n-space justify="end">
                            <n-button @click="closeModal">Cancel</n-button>
                            <n-button type="primary" @click="submitForm" :loading="formSubmitLoading">Submit</n-button>
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
import { Plus } from "@vicons/fa";

export default {
    components: {
        Plus
    },
    setup: () => {
        const store = useStore();
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const pageLoading = ref(true);
        const formSubmitLoading = ref(false);
        const records = ref([]);
        const record = ref({
            title: '',
            amount: 0,
            date: null,
            type: '',
            description: "",
        });
        const rules = ref({
            title: {
                required: true,
                message: 'Please enter title',
                trigger: ["input", "blur"]
            },
            amount: {
                required: true,
                type: "number",
                message: 'Please enter amount',
                validator(rule, value) {
                    if (value <= 0) {
                        return new Error("Please enter amount");
                    }
                    return true;
                },
                trigger: ["input", "blur"]
            },
            date: {
                required: true,
                message: 'Please select date',
                trigger: ["input", "blur"]
            },
            type: {
                required: true,
                message: 'Please select type',
                trigger: ["input", "blur"]
            },
            description: {
                required: false,
                validator(rule, value) {
                    if (value.length > 500) {
                        return new Error("Description should be smaller than 500 characters");
                    }
                    return true;
                },
                trigger: ["input", "blur"]
            },
        });

        // List filters
        const currentPage = ref(1);
        const currentPageSize = ref(10);

        const recordTypes = ref([
            {
                label: "Income",
                value: "Income"
            },
            {
                label: "Expense",
                value: "Expense"
            },
        ]);
        const recordType = ref(null);
        const dateFrom = ref(null);
        const dateTo = ref(null);

        const getRecords = () => {
            pageLoading.value = true;
            axios.get('/api/record/personal', {
                params: {
                    type: recordType.value,
                    date_from: dateFrom.value,
                    date_to: dateTo.value,
                    page_size: currentPageSize.value,
                    page: currentPage.value,
                }
            }).then(res => {
                if (res.data.response == "success") {
                    records.value = res.data.data;
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
        getActiveFilters();

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

            router.replace({ name: 'Personal', query: { ...queryParams } });
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
            router.replace({ name: 'Personal', query: Object.assign({}, route.query, { page: currentPage.value, page_size: currentPageSize.value }) });
            getRecords();
        };

        const showModalRef = ref(false);
        const formRef = ref(null);
        const openModal = () => {
            showModalRef.value = true;
        };
        const submitForm = (event) => {
            event.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    formSubmitLoading.value = true;
                    axios.post("/api/record/create", record.value)
                    .then((res) => {
                        if (res.data.response == "success") {
                            message.success(res.data.message);
                            getRecords();
                            closeModal();
                        } else {
                            message.error(res.data.message);
                        }
                        formSubmitLoading.value = false;
                    })
                    .catch(() => {
                        formSubmitLoading.value = false;
                        message.error("Could not create record!");
                    });
                } else {
                    console.log(errors);
                }
            });
        };
        const closeModal = () => {
            showModalRef.value = false;
            record.value = {
                title: '',
                amount: 0,
                date: null,
                type: '',
                description: "",
            };
        };
        const viewDetails = (record) => {};

        return {
            pageLoading,
            formSubmitLoading,
            records,
            formRef,
            record,
            rules,

            currentPage,
            currentPageSize,

            recordTypes,
            recordType,
            dateFrom,
            dateTo,

            showModalRef,

            getRecords,
            getActiveFilters,
            getFilteredRecords,
            pageChanged,
            currentPageSizeChanged,
            getPaginatedRecords,

            openModal,
            submitForm,
            closeModal,

            viewDetails,
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

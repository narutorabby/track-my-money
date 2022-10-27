<template>
    <section id="group-records">
        <record-list :groupRecords="true" />
    </section>
</template>

<script>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useMessage } from "naive-ui";
import { Plus, EyeRegular, EditRegular, TrashAltRegular } from "@vicons/fa";
import moment from 'moment';
import CreateEditRecord from '../../components/CreateEditRecord.vue';
import RecordList from '../../components/RecordList.vue';

export default {
    components: {
        Plus,
        EyeRegular,
        EditRegular,
        TrashAltRegular,
        CreateEditRecord,
        RecordList,
    },
    setup: () => {
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const pageLoading = ref(true);
        const formSubmitLoading = ref(false);
        const group = ref();


        const groupDetails = () => {
            axios.get('/api/group/show/' + route.params.slug).then(res => {
                if (res.data.response == "success") {
                    group.value = res.data.data;
                    group.value.members.forEach(element => {
                        element.label = element.name + ' (' + element.email + ')'
                    });
                    getActiveFilters();
                }
                else{
                    message.error("Error fetching details. Please reload!");
                }
                pageLoading.value = false;
            })
        };
        groupDetails();

        const records = ref([]);
        const record = ref({
            id: 0,
            title: '',
            amount: 0,
            date: null,
            type: '',
            description: "",
        });
        const viewFlag = ref(false);
        const editFlag = ref(false);

        const showModalRef = ref(false);

        // List filters
        const currentPage = ref(1);
        const currentPageSize = ref(10);

        const recordTypes = ref([
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

        const getRecords = () => {
            pageLoading.value = true;
            axios.get('/api/record/group', {
                params: {
                    group_id: group.value.id,
                    type: recordType.value,
                    date_from: dateFrom.value,
                    date_to: dateTo.value,
                    page_size: currentPageSize.value,
                    page: currentPage.value,
                }
            }).then(res => {
                if (res.data.response == "success") {
                    records.value = res.data.data;
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
                getRecords();
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
            recordTypes,
            recordType,
            dateFrom,
            dateTo,

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

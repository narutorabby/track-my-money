<template>
    <n-card
        style="width: 600px"
        :title="viewFlag ? 'View record' : editFlag ? 'Edit record' : 'Create record'"
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
                                :readonly="viewFlag"
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
                                :readonly="viewFlag"
                            />
                        </n-form-item>
                    </n-gi>
                    <n-gi>
                        <n-form-item label="Date" path="date">
                            <n-date-picker
                                v-model:formatted-value="record.date"
                                placeholder="Select a date"
                                type="date"
                                :disabled="viewFlag"
                            />
                        </n-form-item>
                    </n-gi>
                    <n-gi>
                        <n-form-item label="Type" path="type">
                            <n-select
                                v-model:value="record.type"
                                placeholder="Select record type"
                                :options="groupId ? groupRecordTypes : personalRecordTypes"
                                :disabled="viewFlag"
                            />
                        </n-form-item>
                    </n-gi>
                </n-grid>
                <n-grid cols="1" v-if="groupId">
                    <n-gi>
                        <n-form-item label="Member(s)" path="members">
                            <n-select
                                v-model:value="record.members"
                                placeholder="Select members(s)"
                                :options="members"
                                value-field="id"
                                label-field="label"
                                :disabled="viewFlag || (groupId && !record.type)"
                                :multiple="record.type == 'Bill'"
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
                                :readonly="viewFlag"
                            />
                        </n-form-item>
                    </n-gi>
                </n-grid>
                <n-space justify="end">
                    <n-button @click="close">Cancel</n-button>
                    <n-button type="primary" @click="submitForm" :loading="formSubmitLoading" v-if="!viewFlag">
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
</template>
<script>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useMessage } from "naive-ui";
import { Check } from "@vicons/fa";
export default {
    components: {
        Check,
    },
    props: {
        record: {
            type: Object,
            default: {
                id: 0,
                title: '',
                amount: 0,
                date: null,
                type: '',
                description: "",
                members: [],
            }
        },
        editFlag: {
            type: Boolean,
            default: false
        },
        viewFlag: {
            type: Boolean,
            default: false
        },
        groupId: {
            type: Number,
            default: null
        },
        members: {
            type: Array,
            default: []
        },
    },
    setup(props, { emit }) {
        const router = useRouter();
        const route = useRoute();
        const message = useMessage();

        const formSubmitLoading = ref(false);
        const formRef = ref(null);

        const record = ref(props.record);
        const viewFlag = ref(props.viewFlag);
        const editFlag = ref(props.editFlag);
        const groupId = ref(props.groupId);
        const members = ref(props.members);

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
                    if (value && value.length > 500) {
                        return new Error("Description should be smaller than 500 characters");
                    }
                    return true;
                },
                trigger: ["input", "blur"]
            },
        });
        const submitForm = (event) => {
            event.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    formSubmitLoading.value = true;
                    let url = "";
                    let formData = {};
                    if(editFlag.value) {
                        formData = { ...record.value, _method: "PUT"};
                        url = "/api/record/update/" + record.value.id;
                    }
                    else {
                        formData = { ...record.value };
                        url = "/api/record/create";
                    }
                    if(groupId.value) {
                        formData.group_id = groupId.value;
                    }
                    axios.post(url, formData)
                    .then((res) => {
                        if (res.data.response == "success") {
                            message.success(res.data.message);
                            emit("close", true);
                        } else {
                            message.error(res.data.message);
                        }
                        formSubmitLoading.value = false;
                    })
                    .catch((err) => {
                        console.log(err);
                        formSubmitLoading.value = false;
                        message.error(`Could not ${editFlag.value ? 'edit' : 'create'} record!`);
                    });
                } else {
                    message.error("Please enter proper information");
                }
            });
        };

        const close = () => {
            emit("close", false);
        }

        return {
            formSubmitLoading,
            formRef,
            record,
            viewFlag,
            editFlag,
            groupId,
            members,
            personalRecordTypes,
            groupRecordTypes,
            rules,

            submitForm,
            close,
        }
    },
}
</script>

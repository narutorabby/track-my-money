<template>
    <n-form ref="formRef" :model="record" :rules="rules">
        <n-grid cols="1">
            <n-gi>
                <n-form-item label="Title" path="title">
                    <n-input
                        v-model:value="record.title"
                        placeholder="Input Title"
                    />
                </n-form-item>
            </n-gi>
        </n-grid>
        <n-grid cols="2" x-gap="24">
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
                        placeholder="Select a date"
                        v-model:formatted-value="record.date"
                        value-format="dd-MM-yyyy"
                        type="date"
                    />
                </n-form-item>
            </n-gi>
        </n-grid>
        <n-grid cols="1">
            <n-gi>
                <n-form-item label="Description" path="description">
                    <n-input
                        v-model:value="record.description"
                        type="textarea"
                        placeholder="Write a description..."
                    />
                </n-form-item>
            </n-gi>
        </n-grid>
        <n-space justify="end">
            <n-button @click="handleValidateClick">Cancel</n-button>
            <n-button type="primary" @click="handleValidateClick">Submit</n-button>
        </n-space>
    </n-form>
</template>

<script>
import { ref } from "vue";
import { useMessage } from "naive-ui";

export default {
    setup: () => {
        const message = useMessage();
        const formRef = ref(null);
        const record = ref({
            title: "",
            amount: 0,
            date: null,
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
        return {
            formRef,
            record,
            rules,
            handleValidateClick(e) {
                e.preventDefault();
                formRef.value?.validate((errors) => {
                    if (!errors) {
                        message.success("Valid");
                    } else {
                        console.log(errors);
                        message.error("Invalid");
                    }
                });
            },
        };
    },
};
</script>

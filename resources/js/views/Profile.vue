<template>
    <section id="profile">
        <n-card title="PROFILE">
            <template #header-extra>
                <n-space>
                    <n-button @click="signout" type="error">
                        <template #icon>
                            <n-icon>
                                <sign-out-alt />
                            </n-icon>
                        </template>
                        Signout
                    </n-button>
                    <n-button @click="submitForm" :loading="formSubmitLoading">
                        <template #icon>
                            <n-icon>
                                <check />
                            </n-icon>
                        </template>
                        Save Profile
                    </n-button>
                </n-space>
            </template>
            <n-grid cols="1 m:2" x-gap="24" responsive="screen">
                <n-grid-item>
                    <n-card>
                        <template v-if="pageLoading">
                            <n-skeleton height="220px" />
                        </template>
                        <n-space vertical v-else>
                            <n-form ref="formRef" :model="profile" :rules="rules">
                                <n-grid cols="1">
                                    <n-gi>
                                        <n-form-item label="Email Address">
                                            <n-input
                                                v-model:value="profile.email"
                                                placeholder="Enter your email"
                                                disabled
                                            />
                                        </n-form-item>
                                    </n-gi>
                                </n-grid>
                                <n-grid cols="1">
                                    <n-gi>
                                        <n-form-item label="Name" path="name">
                                            <n-input
                                                v-model:value="profile.name"
                                                placeholder="Enter your name"
                                                maxlength="40"
                                                show-count
                                            />
                                        </n-form-item>
                                    </n-gi>
                                </n-grid>
                                <n-grid cols="1">
                                    <n-gi>
                                        <n-form-item label="Mobile No.">
                                            <n-input
                                                v-model:value="profile.mobile"
                                                placeholder="Enter your mobile"
                                                :input-props="{ type: 'number' }"
                                            />
                                        </n-form-item>
                                    </n-gi>
                                </n-grid>
                            </n-form>
                        </n-space>
                    </n-card>
                </n-grid-item>
                <n-grid-item>
                    <n-card>
                        <template v-if="pageLoading">
                            <n-skeleton height="320px" />
                        </template>
                        <n-space align="flex-end" v-else>
                            <n-avatar
                                class="avatar"
                                v-for="(avatar, index) in avatars"
                                :key="index"
                                :size="64"
                                :round="avatar.value == profile.avatar"
                                :src="'http://track-my-money.xyz/avatar/' + avatar.src"
                                @click="changeAvatar(avatar)"
                            />
                        </n-space>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-card>
    </section>
</template>
<script>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useStore } from "vuex";
import { useMessage, useDialog } from "naive-ui";
import { Check, SignOutAlt } from "@vicons/fa";
export default {
    components: {
        Check,
        SignOutAlt,
    },
    setup() {
        const router = useRouter();
        const route = useRoute();
        const store = useStore();
        const message = useMessage();
        const dialog = useDialog();

        const pageLoading = ref(true);
        const formSubmitLoading = ref(false);
        const formRef = ref(null);

        const profile = ref({
            email: "",
            name: "",
            mobile: "",
            avatar: ""
        });

        const avatars = ref([
            { value: "first", src: "first.png" },
            { value: "football", src: "football.png" },
            { value: "dragon", src: "dragon.png" },
            { value: "smart", src: "smart.png" },

            { value: "pizza", src: "pizza.png" },
            { value: "ice-cream", src: "ice-cream.png" },
            { value: "male", src: "male.png" },
            { value: "female", src: "female.png" },

            { value: "male-2", src: "male-2.png" },
            { value: "female-2", src: "female-2.png" },
            { value: "penguin", src: "penguin.png" },
            { value: "jaguar", src: "jaguar.png" },

            { value: "viking", src: "viking.png" },
            { value: "ninja", src: "ninja.png" },
            { value: "anonymous", src: "anonymous.png" },
            { value: "scream", src: "scream.png" },

            { value: "iron-man", src: "iron-man.png" },
            { value: "simpson", src: "simpson.png" },
            { value: "mario", src: "mario.png" },
            { value: "walter-white", src: "walter-white.png" },

            { value: "male-3", src: "male-3.png" },
            { value: "female-3", src: "female-3.png" },
            { value: "male-4", src: "male-4.png" },
            { value: "female-4", src: "female-4.png" },

            { value: "sun", src: "sun.png" },
            { value: "fire", src: "fire.png" },
            { value: "wave", src: "wave.png" },
            { value: "soil", src: "soil.png" },
        ]);

        const rules = ref({
            name: {
                required: true,
                message: 'Please enter name',
                trigger: ["input", "blur"]
            }
        });

        const getProfile = () => {
            axios.get("/api/user/me")
            .then((res) => {
                if (res.data.response == "success") {
                    profile.value.email = res.data.data.email;
                    profile.value.name = res.data.data.name;
                    profile.value.mobile = res.data.data.mobile.substring(4);
                    profile.value.avatar = res.data.data.avatar;
                } else {
                    message.error(res.data.message);
                }
                pageLoading.value = false;
            })
            .catch((err) => {
                pageLoading.value = false;
                message.error("Could not fetch profile information");
            });
        };
        getProfile();

        const changeAvatar = (avatar) => {
            profile.value.avatar = avatar.value;
        };

        const submitForm = (event) => {
            event.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    formSubmitLoading.value = true;
                    axios.post("/api/user/update", {
                        name: profile.value.name,
                        mobile: 'mob:' + profile.value.mobile,
                        avatar: profile.value.avatar,
                        _method: "PUT"
                    })
                    .then((res) => {
                        if (res.data.response == "success") {
                            message.success(res.data.message);
                        } else {
                            message.error(res.data.message);
                        }
                        formSubmitLoading.value = false;
                    })
                    .catch((err) => {
                        formSubmitLoading.value = false;
                        message.error("Could not save profile information");
                    });
                } else {
                    message.error("Please enter proper information");
                }
            });
        };

        const signout = () => {
            dialog.error({
                title: "Signout",
                content: "Do you want to signout from your current session?",
                negativeText: "No",
                positiveText: "Yes",
                onPositiveClick: () => {
                    store.dispatch("removeSession");
                    router.replace('/home');
                }
            });
        };

        return {
            pageLoading,
            formSubmitLoading,
            formRef,
            profile,
            avatars,
            rules,

            getProfile,
            changeAvatar,
            submitForm,
            signout,
        }
    },
}
</script>
<style lang="scss" scoped>
.avatar {
    margin: 5px;
    cursor: pointer;
    border: 1px solid #0004;
}
</style>

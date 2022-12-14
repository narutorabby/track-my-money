<template>
    <section id="landing">
        <div class="landing-top">
            <n-grid cols="1 m:2" item-responsive responsive="screen">
                <n-grid-item span="0 m:1"></n-grid-item>
                <n-grid-item>
                    <div class="centered-element-container">
                        <div class="centered-element">
                            <n-h2>Keep track of your money over the internet!</n-h2>
                            <n-h2>Access it from anywhere in the world!</n-h2>
                        </div>
                    </div>
                </n-grid-item>
            </n-grid>
        </div>
        <div class="landing-middle">
            <n-grid cols="1 m:2" item-responsive responsive="screen">
                <n-grid-item>
                    <div class="centered-element-container">
                        <div class="centered-element">
                            <n-h2>Where is your money going? To whom?</n-h2>
                            <n-h2>Never loose track of your money!</n-h2>
                        </div>
                    </div>
                </n-grid-item>
            </n-grid>
        </div>
        <div class="get-started" v-if="session == null">
            <n-space justify="center">
                <n-card hoverable>
                    <div class="text">Get started now!</div>
                    <div class="google-button-container">
                        <img
                            class="google-button"
                            src="../assets/images/btn_google_signin_dark.png"
                            alt="Google button"
                            @click="googleSignin"
                        >
                    </div>
                    <n-divider>OR</n-divider>
                    <div class="download-button-container">
                        <n-button type="info" size="large" @click="downloadApp">
                            <template #icon>
                                <n-icon>
                                    <google-play />
                                </n-icon>
                            </template>
                            Download from PlayStore
                        </n-button>
                    </div>
                </n-card>
            </n-space>
        </div>
        <n-divider />
        <div class="landing-bottom">
            <n-grid x-gap="24" cols="1 m:2" responsive="screen">
                <n-gi>
                    <n-card class="user-feedback" title="USER FEEDBACK">
                        <p>Have a cool suggestion? Let us know!</p>
                        <n-space vertical>
                            <n-form
                                ref="formRef"
                                :model="suggestion"
                                :rules="rules"
                            >
                                <n-form-item label="Name" path="suggestion.name">
                                    <n-input v-model:value="suggestion.name" type="text" placeholder="Your name" />
                                </n-form-item>
                                <n-form-item label="Email" path="suggestion.email">
                                    <n-input v-model:value="suggestion.email" type="email" placeholder="Your email" />
                                </n-form-item>
                                <n-form-item label="Suggestion" path="suggestion.suggestion">
                                    <n-input
                                        v-model:value="suggestion.suggestion"
                                        type="textarea"
                                        placeholder="Your thoughts or suggestions..."
                                    />
                                </n-form-item>
                                <n-form-item>
                                    <n-button @click="submitSuggestion">Submit</n-button>
                                </n-form-item>
                            </n-form>
                        </n-space>
                    </n-card>
                </n-gi>
                <n-gi>
                    <n-descriptions label-placement="top" :column="1">
                        <template #header>
                            <div>ABOUT - Track My Money</div>
                            <img class="logo" src="../assets/images/logo.png" alt="Logo">
                        </template>
                        <n-descriptions-item>
                            <template #label>
                                <strong>Track My Money</strong>
                            </template>
                            Track My Money is an easy-to-use daily income, expanse and debt tracker mobile app for everyone. It can be used for both individual and/or a group of people. And it's totally free of cost.
                        </n-descriptions-item>
                        <n-descriptions-item>
                            <template #label>
                                <strong>Individual</strong>
                            </template>
                            Individual ??? With this application you can keep track of your all individual incomes, expenses and debts.
                        </n-descriptions-item>
                        <n-descriptions-item>
                            <template #label>
                                <strong>Group</strong>
                            </template>
                            With this application you can keep track of all the contributions and bills for a group of people.
                        </n-descriptions-item>
                    </n-descriptions>
                    <n-divider />
                    <n-descriptions label-placement="top" :column="1" title="WORD FROM DEVELOPER">
                        <n-descriptions-item label="Don't like ads?" :span="2">
                            So does the developer. That's why this application is ad-free. So no advertisement, no annoying pop-ups.
                            <br/>
                            <img class="developer" src="../assets/images/developer.png" alt="Logo">
                        </n-descriptions-item>
                    </n-descriptions>
                    <n-divider />
                    <n-descriptions label-placement="top" :column="1">
                        <n-descriptions-item>
                            <template #label>
                                <strong>Useful links</strong>
                            </template>
                            <a class="useful-links" href="/privacy-policy">Privacy Policy</a>
                            <a class="useful-links" href="/terms-of-service">Terms Of Service</a>
                        </n-descriptions-item>
                    </n-descriptions>
                </n-gi>
            </n-grid>
        </div>
        <n-divider />
    </section>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { useMessage } from "naive-ui";
import { Google, GooglePlay } from '@vicons/fa'
import { googleTokenLogin } from "vue3-google-login"

export default {
    components: {
        Google,
        GooglePlay
    },
    setup() {
        const store = useStore();
        const router = useRouter();
        const message = useMessage();

        const session = computed(() => {
            return store.getters.session || null;
        });

        const googleSigninLoading = ref(false);
        const formRef = ref(null);
        const suggestion = ref({
            name: '',
            email: '',
            suggestion: '',
        });

        const rules = ref({
            suggestion: {
                name: {
                    required: true,
                    message: 'Please input your name',
                    trigger: 'blur'
                },
                email: {
                    required: true,
                    message: 'Please input your email',
                    trigger: 'blur'
                },
                suggestion: {
                    required: true,
                    message: 'Please write some suggestions',
                    trigger: 'blur'
                },
            },
        });

        const googleSignin = () => {
            googleSigninLoading.value = true;
            googleTokenLogin().then((response) => {
                if(response && response.access_token) {
                    axios.post("/api/authenticate", {
                        token: response.access_token
                    })
                    .then((res) => {
                        if (res.data.response == "success") {
                            store.dispatch("setSession", res.data.data.token);
                            window.axios.defaults.headers.common['Authorization'] = "Bearer " + res.data.data.token;
                            window.axios.defaults.headers.common['Content-Type'] = 'application/json';
                            router.replace('/');
                        } else {
                            message.error(res.data.message);
                        }
                        googleSigninLoading.value = false;
                    })
                    .catch((err) => {
                        console.log("err", err);
                        googleSigninLoading.value = false;
                        message.error("Could not signin with Google. Try again!");
                    });
                }
            })
        };

        const downloadApp = () => {
            window.open('https://play.google.com/store/apps/details?id=xyz.trackmymoney.trackmymoney', '_blank');
        };

        const submitSuggestion = (e) => {
            e.preventDefault();
            formRef.value.validate((errors) => {
                if (!errors) {
                    message.success("Thanks for your valuable opinion!");
                } else {
                    console.log(errors);
                }
            });
        };

        return {
            session,
            googleSigninLoading,
            formRef,
            suggestion,
            rules,

            googleSignin,
            downloadApp,
            submitSuggestion,
        }
    },
}
</script>
<style lang="scss" scoped>
.landing-top {
    height: 60vh;
    background-image: url("../assets/images/landing-bg-1.jpg");
    background-size: cover;
    background-position: bottom;
}
.landing-middle {
    height: 60vh;
    background-image: url("../assets/images/landing-bg-2.jpg");
    background-size: cover;
    background-position: top;
}
.centered-element-container {
    height: 60vh;
    position: relative;

    .centered-element {
        margin: 0;
        padding: 30px;
        position: absolute;
        top: 20%;
        transform: translateY(-20%);
    }
}
.get-started {
    width: 100%;
    top: calc(60vh - 60px);
    position: absolute;

    .text {
        text-align: center;
        font-size: 18px;
        margin-bottom: 10px;
    }
}
.google-button-container {
    text-align: center;
    margin-bottom: 5px;
}
.google-button {
    cursor: pointer;
    width: 80%;
}
.download-button-container {
    text-align: center;
    margin-bottom: 5px;
}
.landing-bottom {
    padding: 0 20px 30px 20px;

    .user-feedback {
        margin-bottom: 40px;
    }

    .logo {
        height: 80px;
        width: auto;
        margin-top: 40px;
    }

    .developer {
        height: 80px;
        width: auto;
        margin-top: 20px;
    }

    .useful-links {
        margin-right: 10px;
        margin-bottom: 10px;
    }
}
</style>

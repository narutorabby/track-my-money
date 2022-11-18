<template>
    <div class="centered-element-container">
        <div class="centered-element">
            <n-result
                status="404"
                title="404 Not Found"
                description="You know life is always ridiculous."
                class="result-element"
            />
        </div>
    </div>
</template>
<style lang="scss" scoped>
.centered-element-container {
    height: 100vh;
    position: relative;

    .centered-element {
        margin: 0;
        width: 100%;
        position: absolute;
        top: 40%;
        transform: translateY(-20%);
    }
}
</style>

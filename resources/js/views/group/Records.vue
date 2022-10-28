<template>
    <section id="group-records">
        <record-list :isGroup="true" />
    </section>
</template>

<script>
import { ref } from "vue";
import RecordList from '../../components/RecordList.vue';

export default {
    components: {
        RecordList,
    },
    setup: () => {
        //

        return {
            //
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

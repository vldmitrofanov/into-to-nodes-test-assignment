<template>
    <div>
        <b-table-simple small>
            <b-tr>
                <b-th>Name</b-th>
                <b-th>Date</b-th>
                <b-th>Time From</b-th>
                <b-th>Time To</b-th>
            </b-tr>
            <b-tr v-for="(item, i) in availabilities" :key="i">
                <b-td :class="{ 'hide-td': sameName(i) }"
                    ><span>{{ item.expert.name }}</span></b-td
                >
                <b-td>{{ prettyDate(item.date) }}</b-td>
                <b-td>{{ prettyTime(item.time_start) }}</b-td>
                <b-td>{{ prettyTime(item.time_end) }}</b-td>
            </b-tr>
        </b-table-simple>
    </div>
</template>
<script>
import myMixin from "../mixin.js";
export default {
    mixins: [myMixin],
    props: {
        availabilities: {
            type: Array,
            required: true
        }
    },
    data() {
        return {};
    },
    methods: {
        sameName(index) {
            if (index > 0) {
                if (
                    this.availabilities[index].expert.name ==
                    this.availabilities[index - 1].expert.name
                )
                    return true;
            }
            return false;
        }
    }
};
</script>

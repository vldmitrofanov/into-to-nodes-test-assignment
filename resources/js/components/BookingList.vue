<template>
    <div>
        <b-table-simple small>
            <b-tr>
                <b-th>Date</b-th>
                <b-th>Description</b-th>
                <b-th>Time From</b-th>
                <b-th>Time To</b-th>
                <b-th>Expert</b-th>
            </b-tr>
            <b-tr v-for="(item, i) in client.bookings" :key="i">
                <b-td>{{ prettyDate(item.date) }}</b-td>
                <b-td>{{ item.description }}</b-td>
                <b-td>{{ prettyTime(item.time_start) }}</b-td>
                <b-td>{{ prettyTime(item.time_end) }}</b-td>
                <b-td>{{ item.expert != null ? item.expert.name : "--" }}</b-td>
            </b-tr>
        </b-table-simple>
        <b-button
            type="submit"
            variant="primary"
            @click.stop.prevent="setSchedules"
        >
            Auto set schedules
        </b-button>
        <b-button
            type="submit"
            variant="danger"
            @click.stop.prevent="unsetSchedules"
        >
            Rollback schedules
        </b-button>
    </div>
</template>
<script>
import myMixin from "../mixin.js";
export default {
    mixins: [myMixin],
    props: {
        client: {
            type: Object,
            required: true
        }
    },
    data() {
        return {};
    },
    methods: {
        async setSchedules() {
            const url = `/api/bookings/auto-set/${this.client.id}`;
            const response = await axios.post(url, {
                //TODO: start date, end date
            });
            if (response.data.status == "OK") {
                this.$emit("updated");
            }
        },
        async unsetSchedules() {
            const url = `/api/bookings/unset-all/${this.client.id}`;
            const response = await axios.post(url, {
                //TODO: start date, end date
            });
            if (response.data.status == "OK") {
                this.$emit("updated");
            }
        }
    }
};
</script>

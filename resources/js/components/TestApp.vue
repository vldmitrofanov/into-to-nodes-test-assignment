<template>
    <div class="container">
        <!--<div class="mt-5">
            <h3>Add expert:</h3>
            <experts-form @updated="fetchExperts"></experts-form>
        </div>

        <experts-schedule-form @updated="fetchExperts"></experts-schedule-form>-->
        <div class="mt-5">
            <h3>
                <a href="#" @click.stop.prevent="showExperts = !showExperts"
                    >Experts Availability</a
                >
            </h3>
            <experts-list
                v-show="showExperts"
                class="mt-5"
                :availabilities="availabilities"
                @updated="fetchAvailabilities"
            ></experts-list>
        </div>

        <div class="mt-5 mb-5" v-if="defaultClient">
            <h3>Client's booking:</h3>
            <p>
                &lsaquo;Here could be added some client selection
                section&rsaquo;
            </p>
            <booking-list
                :client="defaultClient"
                @updated="fetchClients"
            ></booking-list>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            expertsSrc: [],
            availabilitiesSrc: [],
            clients: [],
            showExperts: true
        };
    },
    computed: {
        experts() {
            return this.experts || [];
        },
        availabilities() {
            return this.availabilitiesSrc || [];
        },
        defaultClient() {
            return this.clients[0] || null;
        }
    },
    mounted() {
        this.fetchAll();
    },
    methods: {
        fetchAll() {
            this.fetchExperts();
            this.fetchClients();
            this.fetchAvailabilities();
        },
        async fetchExperts() {
            const url = `/api/experts`;
            const response = await axios.get(url);
            this.expertsSrc = response.data.experts;
        },
        async fetchClients() {
            this.clients = [];
            const url = `/api/clients`;
            const response = await axios.get(url);
            this.clients = response.data.clients;
        },
        async fetchAvailabilities() {
            const url = `/api/availabilities`;
            const response = await axios.get(url);
            this.availabilitiesSrc = response.data.availabilities;
        }
    }
};
</script>

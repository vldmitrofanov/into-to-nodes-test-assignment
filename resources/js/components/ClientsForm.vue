<template>
    <div class="custom-inline-form">
        <b-form-group label="Name:" description="">
            <b-form-input
                v-model="name"
                type="text"
                placeholder="Enter client's name"
                required
            ></b-form-input>
        </b-form-group>
        <b-button
            type="submit"
            variant="primary"
            @click.stop.prevent="submitForm"
        >
            Add Client
        </b-button>
    </div>
</template>
<script>
export default {
    data() {
        return { name: "" };
    },
    methods: {
        async submitForm() {
            if (this.name == "") {
                return;
            }
            const url = `/api/clients`;
            const response = await axios.post(url, {
                name: this.name
            });
            this.name = "";
            this.$emit("updated");
        }
    }
};
</script>

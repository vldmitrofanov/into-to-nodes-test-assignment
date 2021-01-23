<template>
    <div>
        leeee
        <b-form-group
            id="input-group-1"
            label="Name:"
            label-for="input-1"
            description=""
        >
            <b-form-input
                id="input-1"
                v-model="name"
                type="text"
                placeholder="Enter expert's name"
                required
            ></b-form-input>
        </b-form-group>
        <b-button
            type="submit"
            variant="primary"
            @click.stop.prevent="submitForm"
            >Add expert</b-button
        >
    </div>
</template>
<script>
export default {
    data() {
        return { name: "" };
    },
    methods: {
        async submitForm() {
            const url = `/api/v1/experts`;
            const response = await axios.posr(url, {
                name
            });
            this.name = "";
            this.$emit("updated");
        }
    }
};
</script>

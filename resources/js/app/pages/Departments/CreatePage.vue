<template>
    <v-card>
        <v-card-title>Create Department</v-card-title>
        <v-card-text>
            <v-text-field
            label="Name"
            v-model="department.name"
            required
            />
            <v-select
            label="Parent Department"
            :items="parentDepartments"
            v-model="department.parent_id"
            />
        </v-card-text>
        <v-card-actions>
            <v-btn color="primary" variant="outlined" @click="createDepartment">Save</v-btn>
            <v-btn color="error" variant="outlined" @click="$router.back()">Cancel</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import ApiService from '../../services/ApiService';

export default {
    data() {
        return {
            department: {
                id: '',
                name: '',
                parent_id: ''
            },
            parentDepartments: []
        };
    },
    async created() {
        this.parentDepartments = (await ApiService.fetchDepartments({ page: 1, pageSize: 100})).items.map((item) => ({
            title: item.name,
            value: item.id
        }));
        this.parentDepartments = [{value: null, title: 'None'}, ...this.parentDepartments]
    },
    methods: {
        async createDepartment() {
            try {
                this.department = await ApiService.create({
                    name: this.department.name,
                    parent_id: this.department.parent_id,
                });
                this.$router.push(`/departments/${this.department.id}`);
            } catch (error) {
                alert(error);
            }
        },
    }
}
</script>
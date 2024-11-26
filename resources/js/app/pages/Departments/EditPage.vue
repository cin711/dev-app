<template>
    <div>
        <v-card>
            <v-card-title>Edit Department</v-card-title>
            <v-card-actions>
                <v-btn color="success" variant="outlined" @click="approveDepartment" :disabled="department.is_approved">
                    {{ department.is_approved ? 'Approved' : 'Approve' }}
                </v-btn>
                <v-btn color="error" variant="outlined" @click="deleteDepartment" :disabled="department.is_deleted">
                    {{ department.is_deleted ? 'Deleted' : 'Delete' }}
                </v-btn>
                <v-btn color="primary" variant="outlined" @click="toggleActive">
                    {{ department.is_active ? 'Deactivate' : 'Activate' }}
                </v-btn>
            </v-card-actions>
            <v-card-text>
                <v-text-field
                label="ID"
                v-model="department.id"
                disabled
                />
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
                <v-btn color="primary" variant="outlined" @click="updateDepartment">Save</v-btn>
                <v-btn color="error" variant="outlined" @click="$router.push('/departments')">Cancel</v-btn>
            </v-card-actions>
        </v-card>
        <br>
        <v-card variant="outlined">
            <v-card-item>
                Hierarchy
            </v-card-item>
            <v-card-item>
                <v-treeview 
                lines="one"
                item-title="name"
                item-value="id"
                item-children="children"
                :items="hierarchy"
                open-all="true"
                />
            </v-card-item>
        </v-card>
    </div>
</template>

<script>
import ApiService from '../../services/ApiService';
import { VTreeview } from 'vuetify/labs/components';

export default {
    components: {
        VTreeview,
    },
    data() {
        return {
            department: {
                id: '',
                name: '',
                parent_id: ''
            },
            parentDepartments: [],
            hierarchy: [],
        };
    },
    async created() {
        try {
            this.department = await ApiService.fetchDepartment(this.$route.params.id);
            this.parentDepartments = (await ApiService.fetchDepartments({ page: 1, pageSize: 100})).items.map((item) => ({
                title: item.name,
                value: item.id
            }));
            this.parentDepartments = [{value: null, title: 'None'}, ...this.parentDepartments]
        } catch (error) {
            alert(error);
            this.$router.back();
        }

        this.hierarchy = [(await ApiService.fetchDepartmentHierarchy(this.department.name))];
    },
    methods: {
        async updateDepartment() {
            try {
                this.department = await ApiService.updateDepartment(this.$route.params.id, {
                    name: this.department.name,
                    parent_id: this.department.parent_id,
                });
            } catch (error) {
                alert(error);
            }
        },
        async approveDepartment() {
            try {
                this.department = await ApiService.approveDepartment(this.department.id);
            } catch (error) {
                alert(error);
            }
        },
        async deleteDepartment() {
            try {
                if (! confirm('Deleting this department will delete all of its children. Are you sure?')) {
                    return;
                }
                await ApiService.deleteDepartment(this.department.id);
                this.$router.push('/departments');
            } catch (error) {
                alert(error);
            }       
        },
        async toggleActive() {
            if (this.department.is_active) {
                try {
                    if (! confirm('Deactivating this department will deactive all of its children. Are you sure?')) {
                        return;
                    }
                    this.department = await ApiService.deactivateDepartment(this.department.id);
                } catch (error) {
                    alert(error);
                }
            } else {
                try {
                    this.department = await ApiService.activateDepartment(this.department.id);
                } catch (error) {
                    alert(error);
                }
            }
        }
    }
}
</script>
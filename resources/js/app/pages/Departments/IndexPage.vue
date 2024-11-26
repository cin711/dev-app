<template>
  <v-card>
    <v-card-actions>
      <v-card-title>Departments</v-card-title>
      <v-btn color="primary" variant="outlined" size="small" @click="$router.push('/departments/create')">+</v-btn>
    </v-card-actions>
    <v-card-item>
      <v-data-table-server
      :items="departments"
      :loading="loading"
      :items-length="totalItems"
      loading-text="Loading..."
      @update:options="fetchData"
      >
      <template #item="{ item }">
        <tr>
          <td>
            <v-btn color="primary" variant="outlined" size="small" @click="$router.push(`/departments/${item.id}`)">
              {{ item.id }}
            </v-btn>
          </td>
          <td>{{ item.name }}</td>
          <td :class="item.is_active? 'text-green' : 'text-red'">{{ item.is_active ? 'yes' : 'no' }}</td>
          <td :class="item.is_approved? 'text-green' : 'text-red'">{{ item.is_approved ? 'yes' : 'no' }}</td>
          <td :class="item.is_deleted? 'text-green' : 'text-red'">{{ item.is_deleted ? 'yes' : 'no' }}</td>
          <td>{{ item.created_at }}</td>
          <td>{{ item.updated_at }}</td>
        </tr>
      </template>
    </v-data-table-server>
  </v-card-item>
</v-card>
</template>
<script>
import ApiService from '../../services/ApiService';

export default {
  data() {
    return {
      departments: [],
      loading: false,
      currentPage: 1,
      perPage: 10,
      totalItems: 0,
    };
  },
  created() {
    this.fetchData({
      page: this.currentPage,
      itemsPerPage: this.perPage
    });
  },
  methods: {
    async fetchData({page, itemsPerPage}) {
      this.loading = true;
      const response = await ApiService.fetchDepartments({
        page: page,
        pageSize: itemsPerPage
      });
      this.departments = response.items;
      this.totalItems = response.total_records;
      this.loading = false;
    }
  }
}
</script>
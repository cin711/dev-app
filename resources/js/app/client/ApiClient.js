import HttpClient from "./HttpClient";

export default class ApiClient {
  static client = new HttpClient(import.meta.env.VITE_API_URL, localStorage.getItem('api_token'));

  static async login({email, password}) {
    const response = await this.client.post('/login', {
      email: email,
      password: password
    });
    
    localStorage.setItem('api_token', response.token);
    this.client.changeToken(response.token);

    return response;
  }

  static async logout() {    
    localStorage.clear();
    this.client.changeToken();
  }

  static async register({name, email, password}) {
    const response = await this.client.post('/register', {
      name: name,
      email: email,
      password: password
    });
    
    localStorage.setItem('api_token', response.token);
    this.client.changeToken(response.token);

    return response;
  }

  static async fetchDepartments({page, pageSize}) {
    return await this.client.get(`/departments?page=${page ?? 1}&page_size=${pageSize ?? 50}`);    
  }

  static async fetchDepartment(id) {
    return await this.client.get(`/departments/${id}`);    
  }

  
  static async fetchDepartmentHierarchy(name) {
    return await this.client.get(`/departments/${name}/hierachy`);
  }

  static async create({name, parent_id}) {
    return await this.client.post(`/departments`, {
      name: name,
      parent_id: parent_id
    });
  }

  static async updateDepartment(id, {name, parent_id}) {
    return await this.client.put(`/departments/${id}`, {
      id: id,
      name: name,
      parent_id: parent_id
    });
    return response;
  }

  static async activateDepartment(id) {
    return await this.client.patch(`/departments/${id}/activate`);
  }

  static async deactivateDepartment(id) {
    return await this.client.patch(`/departments/${id}/deactivate`);
  }

  static async approveDepartment(id) {
    return await this.client.patch(`/departments/${id}/approve`);
  }

  static async deleteDepartment(id) {
    return await this.client.delete(`/departments/${id}`);
  }
  
}
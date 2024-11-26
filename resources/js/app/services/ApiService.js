import ApiClient from '../client/ApiClient';

export default class ApiService {
  static async login({email, password}) {
    return await ApiClient.login({
      email: email,
      password: password
    });
  }

  static async logout() {    
    ApiClient.logout();
  }

  static async register({name, email, password}) {
    return await ApiClient.register({
      name: name,
      email: email,
      password: password
    });
  }

  static async fetchDepartments({page, pageSize}) {
    return await ApiClient.fetchDepartments({page, pageSize});    
  }

  static async fetchDepartment(id) {
    const response = await ApiClient.fetchDepartment(id);    
    return response.resource;
  }

  
  static async fetchDepartmentHierarchy(name) {
    const response = await ApiClient.fetchDepartmentHierarchy(name);
    return response.resource;
  }

  static async create({name, parent_id}) {
    const response = await ApiClient.create({
      name: name,
      parent_id: parent_id
    });
    return response.resource;
  }


  static async updateDepartment(id, {name, parent_id}) {
    const response = await ApiClient.updateDepartment(id, {
      name: name,
      parent_id: parent_id
    });
    return response.resource;
  }

  static async activateDepartment(id) {
    const response = await ApiClient.activateDepartment(id);
    return response.resource;
  }

  static async deactivateDepartment(id) {
    const response = await ApiClient.deactivateDepartment(id);
    return response.resource;
  }

  static async approveDepartment(id) {
    const response = await ApiClient.approveDepartment(id);
    return response.resource;
  }

  static async deleteDepartment(id) {
    const response = await ApiClient.deleteDepartment(id);
    return response.resource;
  }

}
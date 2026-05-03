import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class ContactService {

  private api = 'http://localhost/contact-manager-web-app';

  constructor(private http: HttpClient) {}

  getContacts() {
    return this.http.get(this.api + '/api_get_contacts.php');
  }

  getCategories() {
    return this.http.get(this.api + '/api_get_categories.php');
  }

  addContact(data: FormData) {
    return this.http.post(this.api + '/api_add_contact.php', data);
  }

  updateContact(data: any) {
    return this.http.post(this.api + '/api_update_contact.php', data);
  }

  deleteContact(id: number) {
    return this.http.get(this.api + '/api_delete_contact.php?id=' + id);
  }

  login(data: any) {
    return this.http.post(this.api + '/login.php', data);
  }
}
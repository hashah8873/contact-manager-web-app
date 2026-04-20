import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ContactService } from './services/contact';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './app.html'
})
export class AppComponent implements OnInit {

  contacts: any[] = [];
  categories: any[] = [];

  searchText: string = '';

  newContact = {
    name: '',
    email: '',
    phone: '',
    category_id: ''
  };

  selectedFile: any = null;
  editingId: number | null = null;

  constructor(private service: ContactService) {}

  ngOnInit() {
    this.loadContacts();
    this.loadCategories();
  }

  loadContacts() {
    this.service.getContacts().subscribe((res: any) => {
      this.contacts = res;
    });
  }

  loadCategories() {
    this.service.getCategories().subscribe((res: any) => {
      this.categories = res;
    });
  }

  filteredContacts() {
    return this.contacts.filter((c: any) => {
      const s = this.searchText.toLowerCase();
      return (
        c.name?.toLowerCase().includes(s) ||
        c.email?.toLowerCase().includes(s) ||
        c.phone?.toLowerCase().includes(s)
      );
    });
  }

  onFileChange(event: any) {
    this.selectedFile = event.target.files[0];
  }

  addContact() {

    if (this.editingId) {
      this.updateContact();
      return;
    }

    if (!this.newContact.name || !this.newContact.email || !this.newContact.phone) {
      alert("Fill all fields ❌");
      return;
    }

    const formData = new FormData();

    formData.append('name', this.newContact.name);
    formData.append('email', this.newContact.email);
    formData.append('phone', this.newContact.phone);
    formData.append('category_id', this.newContact.category_id);

    if (this.selectedFile) {
      formData.append('image', this.selectedFile);
    }

    this.service.addContact(formData).subscribe(() => {
      alert("Added ✅");
      this.loadContacts();
      this.resetForm();
    });
  }

  editContact(c: any) {
    this.newContact = {
      name: c.name,
      email: c.email,
      phone: c.phone,
      category_id: c.category_id
    };
    this.editingId = c.id;
  }

  updateContact() {

    const data = {
      id: this.editingId,
      ...this.newContact
    };

    this.service.updateContact(data).subscribe(() => {
      alert("Updated ✅");
      this.loadContacts();
      this.resetForm();
    });
  }

  deleteContact(id: number) {
    this.service.deleteContact(id).subscribe(() => {
      this.loadContacts();
    });
  }

  resetForm() {
    this.newContact = { name:'', email:'', phone:'', category_id:'' };
    this.selectedFile = null;
    this.editingId = null;
  }
}
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
  selectedCategory: string = '';
  message: string = '';

  isLoggedIn: boolean = false;

  loginData = {
    email: '',
    password: ''
  };

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
    this.isLoggedIn = !!localStorage.getItem('user');

    if (this.isLoggedIn) {
      this.loadContacts();
      this.loadCategories();
    }
  }

  // 🔐 LOGIN
  login() {
    this.service.login(this.loginData).subscribe((res: any) => {

      if (res.status === 'success') {
        this.isLoggedIn = true;
        localStorage.setItem('user', 'logged');

        this.loadContacts();
        this.loadCategories();
      } else {
        this.showMessage("Wrong email or password ❌");
      }

    });
  }

  logout() {
    localStorage.removeItem('user');
    this.isLoggedIn = false;
  }

  // DATA
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

  // SEARCH + FILTER
  filteredContacts() {
    return this.contacts.filter((c: any) => {

      const s = this.searchText.toLowerCase();

      const matchesSearch =
        c.name?.toLowerCase().includes(s) ||
        c.email?.toLowerCase().includes(s) ||
        c.phone?.toLowerCase().includes(s);

      const matchesCategory =
        !this.selectedCategory ||
        c.category_name?.trim() === this.selectedCategory;

      return matchesSearch && matchesCategory;
    });
  }

  // FILE
  onFileChange(event: any) {
    this.selectedFile = event.target.files[0];
  }

  // ADD
  addContact() {

    if (this.editingId) {
      this.updateContact();
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
      this.showMessage("Added ✅");
      this.loadContacts();
      this.resetForm();
    });
  }

  // EDIT
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
      this.showMessage("Updated ✅");
      this.loadContacts();
      this.resetForm();
    });
  }

  deleteContact(id: number) {
    if (!confirm("Delete contact?")) return;

    this.service.deleteContact(id).subscribe(() => {
      this.showMessage("Deleted 🗑️");
      this.loadContacts();
    });
  }

  // UI
  showMessage(msg: string) {
    this.message = msg;
    setTimeout(() => this.message = '', 3000);
  }

  resetForm() {
    this.newContact = { name:'', email:'', phone:'', category_id:'' };
    this.selectedFile = null;
    this.editingId = null;
  }

  // CSV
  exportCSV() {
    window.open('http://localhost/contact-manager-web-app/export_contacts.php');
  }

}
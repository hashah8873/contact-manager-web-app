import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ContactService } from './services/contact';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [FormsModule, CommonModule],
  templateUrl: './app.html',
  styleUrls: ['./app.css']
})
export class AppComponent implements OnInit {

  contacts: any[] = [];
  categories: any[] = [];

  searchText: string = '';
  selectedCategory: string = '';

  message: string = '';

  loading = false;

  isLoggedIn = false;

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

  /* ================= LOGIN ================= */

  login() {

    if (!this.loginData.email || !this.loginData.password) {
      this.showMessage('Please fill login fields ❌');
      return;
    }

    this.service.login(this.loginData).subscribe((res: any) => {

      if (res.status === 'success') {

        localStorage.setItem('user', 'logged');
        this.isLoggedIn = true;

        this.loadContacts();
        this.loadCategories();

        this.showMessage('Login successful ✅');

      } else {

        this.showMessage('Invalid email or password ❌');

      }

    });

  }

  logout() {

    localStorage.removeItem('user');
    location.reload();

  }

  /* ================= LOAD CONTACTS ================= */

  loadContacts() {

    this.loading = true;

    this.service.getContacts().subscribe((res: any) => {

      this.contacts = res;

      this.loading = false;

    }, () => {

      this.loading = false;

    });

  }

  /* ================= LOAD CATEGORIES ================= */

  loadCategories() {

    this.service.getCategories().subscribe((res: any) => {
      this.categories = res;
    });

  }

  /* ================= FILTER ================= */

  filteredContacts() {

    return this.contacts.filter((c: any) => {

      const search = this.searchText.toLowerCase();

      const matchesSearch =

        c.name?.toLowerCase().includes(search) ||
        c.email?.toLowerCase().includes(search) ||
        c.phone?.toLowerCase().includes(search);

      const matchesCategory =

        this.selectedCategory === '' ||
        c.category_name === this.selectedCategory;

      return matchesSearch && matchesCategory;

    });

  }

  /* ================= FILE ================= */

  onFileChange(event: any) {

    this.selectedFile = event.target.files[0];

  }

  /* ================= VALIDATION ================= */

  validateForm(): boolean {

    if (
      !this.newContact.name.trim() ||
      !this.newContact.email.trim() ||
      !this.newContact.phone.trim() ||
      !this.newContact.category_id
    ) {

      this.showMessage('All fields are required ❌');
      return false;

    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(this.newContact.email)) {

      this.showMessage('Invalid email format ❌');
      return false;

    }

    const phoneRegex = /^[0-9]+$/;

    if (!phoneRegex.test(this.newContact.phone)) {

      this.showMessage('Phone must contain numbers only ❌');
      return false;

    }

    const nameRegex = /^[a-zA-Z\s]+$/;

    if (!nameRegex.test(this.newContact.name)) {

      this.showMessage('Name must contain letters only ❌');
      return false;

    }

    return true;

  }

  /* ================= ADD CONTACT ================= */

  addContact() {

    if (this.editingId) {
      this.updateContact();
      return;
    }

    if (!this.validateForm()) {
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

      this.showMessage('Contact added successfully ✅');

      this.loadContacts();

      this.resetForm();

    });

  }

  /* ================= EDIT ================= */

  editContact(c: any) {

    this.newContact = {
      name: c.name,
      email: c.email,
      phone: c.phone,
      category_id: c.category_id
    };

    this.editingId = c.id;

    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });

  }

  /* ================= UPDATE ================= */

  updateContact() {

    if (!this.validateForm()) {
      return;
    }

    const data = {
      id: this.editingId,
      ...this.newContact
    };

    this.service.updateContact(data).subscribe(() => {

      this.showMessage('Contact updated successfully ✅');

      this.loadContacts();

      this.resetForm();

    });

  }

  /* ================= DELETE ================= */

  deleteContact(id: number) {

    const confirmDelete = confirm(
      'Are you sure you want to delete this contact?'
    );

    if (!confirmDelete) {
      return;
    }

    this.service.deleteContact(id).subscribe(() => {

      this.showMessage('Contact deleted successfully ✅');

      this.loadContacts();

    });

  }

  /* ================= EXPORT CSV ================= */

  exportCSV() {

    window.open(
      'http://localhost/contact-manager-web-app/export_contacts.php',
      '_blank'
    );

  }

  /* ================= RESET ================= */

  resetForm() {

    this.newContact = {
      name: '',
      email: '',
      phone: '',
      category_id: ''
    };

    this.selectedFile = null;

    this.editingId = null;

  }

  /* ================= TOAST ================= */

  showMessage(msg: string) {

    this.message = msg;

    setTimeout(() => {
      this.message = '';
    }, 3000);

  }

}
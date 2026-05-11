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

  // ================= LOGIN =================

  login() {

    if (!this.loginData.email || !this.loginData.password) {
      this.showMessage("Fill login fields ❌");
      return;
    }

    this.service.login(this.loginData).subscribe((res: any) => {

      if (res.status === 'success') {

        this.isLoggedIn = true;
        localStorage.setItem('user', 'logged');

        this.loadContacts();
        this.loadCategories();

        this.showMessage("Login Successful ✅");

      } else {
        this.showMessage("Wrong email or password ❌");
      }

    });

  }

  logout() {
    localStorage.removeItem('user');
    this.isLoggedIn = false;
  }

  // ================= LOAD DATA =================

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

  // ================= SEARCH + FILTER =================

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

  // ================= FILE =================

  onFileChange(event: any) {
    this.selectedFile = event.target.files[0];
  }

  // ================= VALIDATION =================

  validateForm(): boolean {

    // Empty fields
    if (
      !this.newContact.name ||
      !this.newContact.email ||
      !this.newContact.phone ||
      !this.newContact.category_id
    ) {

      this.showMessage("All fields are required ❌");
      return false;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(this.newContact.email)) {
      this.showMessage("Invalid email format ❌");
      return false;
    }

    // Phone validation
    const phonePattern = /^[0-9]+$/;

    if (!phonePattern.test(this.newContact.phone)) {
      this.showMessage("Phone must contain numbers only ❌");
      return false;
    }

    return true;
  }

  // ================= ADD CONTACT =================

  addContact() {

    if (!this.validateForm()) return;

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

      this.showMessage("Contact Added Successfully ✅");

      this.loadContacts();
      this.resetForm();

    });

  }

  // ================= EDIT =================

  editContact(c: any) {

    this.newContact = {
      name: c.name,
      email: c.email,
      phone: c.phone,
      category_id: c.category_id
    };

    this.editingId = c.id;

    this.showMessage("Editing Contact ✏️");
  }

  // ================= UPDATE =================

  updateContact() {

    if (!this.validateForm()) return;

    const data = {
      id: this.editingId,
      ...this.newContact
    };

    this.service.updateContact(data).subscribe(() => {

      this.showMessage("Contact Updated Successfully ✅");

      this.loadContacts();
      this.resetForm();

    });

  }

  // ================= DELETE =================

  deleteContact(id: number) {

    if (!confirm("Are you sure you want to delete this contact?")) {
      return;
    }

    this.service.deleteContact(id).subscribe(() => {

      this.showMessage("Contact Deleted 🗑️");

      this.loadContacts();

    });

  }

  // ================= EXPORT CSV =================

  exportCSV() {

    if (this.contacts.length === 0) {
      this.showMessage("No data to export ❌");
      return;
    }

    const headers = ['ID', 'Name', 'Email', 'Phone', 'Category'];

    const rows = this.contacts.map((c: any) => [
      c.id,
      c.name,
      c.email,
      c.phone,
      c.category_name
    ]);

    let csvContent = '';

    csvContent += headers.join(',') + '\n';

    rows.forEach(row => {
      csvContent += row.join(',') + '\n';
    });

    const blob = new Blob([csvContent], {
      type: 'text/csv;charset=utf-8;'
    });

    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');

    a.href = url;
    a.download = 'contacts.csv';

    a.click();

    window.URL.revokeObjectURL(url);

    this.showMessage("CSV Exported Successfully ✅");
  }

  // ================= UI =================

  showMessage(msg: string) {

    this.message = msg;

    setTimeout(() => {
      this.message = '';
    }, 3000);

  }

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

}
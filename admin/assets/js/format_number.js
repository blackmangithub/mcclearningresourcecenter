function formatPhoneNumber(value) {
     if (!value) return value;
     const phoneNumber = value.replace(/[^\d]/g, '');
     const phoneNumberLength = phoneNumber.length;
     if (phoneNumberLength < 4) return phoneNumber;
     if (phoneNumberLength < 11) {
          return `(+${phoneNumber.slice(0, 2)}) ${phoneNumber.slice(2)}`;
     }
     return `(+${phoneNumber.slice(0, 2)}) ${phoneNumber.slice(2, 6)}-${phoneNumber.slice(6, 9)}-${phoneNumber.slice(9, 11)}`;
}

function phoneFormatNumber() {

     const phoneFormatNumber = document.querySelector('.format_number');
     const formattedPhoneNumber = formatPhoneNumber(phoneFormatNumber.value);
     phoneFormatNumber.value = formattedPhoneNumber;
}

function phoneFormatNumbers() {

     const phoneFormatNumbers = document.querySelector('.format_numbers');
     const formattedPhoneNumbers = formatPhoneNumber(phoneFormatNumbers.value);
     phoneFormatNumbers.value = formattedPhoneNumbers;
}



// Edit Student - Phone Number Format
// function studentFormatEdit() {

//      const studentEditNumber = document.querySelector('.student_number');
//      const formattedStudentNumber = formatPhoneNumber(studentEditNumber.value);
//      studentEditNumber.value = formattedStudentNumber;
// }
// function ContactFormatEdit() {

//      const ContactEditNumber = document.querySelector('.contact_person_number');
//      const formattedContactNumber = formatPhoneNumber(ContactEditNumber.value);
//      ContactEditNumber.value = formattedContactNumber;
// }
// Add Student- Phone Number Format
// function StudentFormatAdd() {

//      const studentAddNumber = document.querySelector('.student_add');
//      const formattedStudentAddNumber = formatPhoneNumber(studentAddNumber.value);
//      studentAddNumber.value = formattedStudentAddNumber;
// }
// function contactFormatAdd() {

//      const contactAddNumber = document.querySelector('.contact_person_number');
//      const formattedContactAddNumber = formatPhoneNumber(contactAddNumber.value);
//      contactAddNumber.value = formattedContactAddNumber;
// }


// Add Book - Call Number Format
// function bookCallNumberAdd() {

//      const bookCallNumber = document.getElementById('book_call_number');
//      const formattedbookCallNumber = formatPhoneNumber(bookCallNumber.value);
//      bookCallNumber.value = formattedbookCallNumber;
// }
// // Edit Book - Call Number Format
// function bookCallNumberEdit() {

//      const bookCallNumberEdit = document.getElementById('book_call_number_edit');
//      const formattedbookCallNumberEdit = formatPhoneNumber(bookCallNumberEdit.value);
//      bookCallNumberEdit.value = formattedbookCallNumberEdit;
// }


// Add admin - Phone Number Format
// function adminPhoneNumberAdd() {

//      const adminPhoneNumber = document.querySelector('.adminadd_contact_number');
//      const formattedPhoneNumber = formatPhoneNumber(adminPhoneNumber.value);
//      adminPhoneNumber.value = formattedPhoneNumber;
// }
// // Edit admin - Phone Nmber Format
// function adminPhoneNumberEdit() {

//      const adminPhoneNumberEdit = document.getElementById('admin_format_edit_number');
//      const formattedPhoneNumberEdit = formatPhoneNumber(adminPhoneNumberEdit.value);
//      adminPhoneNumberEdit.value = formattedPhoneNumberEdit;
// }





















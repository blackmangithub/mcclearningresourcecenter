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
// function phoneNumberFormatter() {
//      const inputField = document.querySelector('.phone-number');
//      const formattedInputValue = formatPhoneNumber(inputField.value);
//      inputField.value = formattedInputValue;

// }

// function phoneNumberFormatter1() {

//      const inputFieldContact = document.querySelector('.phone-number1');
//      const formattedInputValueContact = formatPhoneNumber(inputFieldContact.value);
//      inputFieldContact.value = formattedInputValueContact;
// }

// function studentFormatEdit() {

//      const studentEditNumber = document.querySelector('.student_number');
//      const formattedStudentNumber = formatPhoneNumber(studentEditNumber.value);
//      studentEditNumber.value = formattedStudentNumber;
// }
class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.uploadButton = document.getElementById("uploadButton");
        this.uploadError = document.getElementById("uploadError");
        this.attachEventListeners();
    }

    // Attach event listener for form submission
    attachEventListeners()
    {
        this.form.addEventListener("submit", (event) => this.validateForm(event));
    }

    // Validate Profile Picture (Format: JPG, PNG | Size: Max 2MB)
    validateProfilePicture() {
        if (this.uploadButton && this.uploadButton.files.length > 0) {
            const file = this.uploadButton.files[0];
            const allowedTypes = ["image/jpeg", "image/png"];
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes

            if (!allowedTypes.includes(file.type)) {
                this.uploadError.innerText = "Only JPG and PNG images are allowed.";
                return false;
            }

            if (file.size > maxSize) {
                this.uploadError.innerText = "File size must be less than 2MB.";
                return false;
            }
        }
        return true;
    }

    // Reset error messages
    resetErrors() {
        if (this.uploadError) this.uploadError.innerText = "";
    }

    // Main validation function
    validateForm(event) {
        this.resetErrors();
        let isValid = true;

        if (this.uploadButton && !this.validateProfilePicture()) {
            isValid = false;
        }
        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    }
}

// Initialize the validator when DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("signupForm")) {
        new FormValidator("signupForm");
    }
});

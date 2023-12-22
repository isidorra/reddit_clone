document.addEventListener('DOMContentLoaded', function () {

    function validateSubjectLength(subject) {
        return subject.length >= 5;
    }


    function updateErrorMessage(elementId, errorMessage) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = errorMessage;
    }


    function handleFormSubmission(event) {
        const subject = document.getElementById('subject').value;
        
        updateErrorMessage('subject_error', '');

        if (!validateSubjectLength(subject)) {
            updateErrorMessage('subject_error', 'Subject must be at least 5 characters long');
            event.preventDefault();
        }

    }

    const form = document.getElementById('create_discussion_form');
    form.addEventListener('submit', handleFormSubmission);
});

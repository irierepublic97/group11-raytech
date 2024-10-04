document.addEventListener('DOMContentLoaded', function() {
    const rescheduleBtns = document.querySelectorAll('.reschedule-btn');
    rescheduleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const dateInput = this.closest('form').querySelector('input[type="date"]');
            dateInput.showPicker();
        });
    });

    const dateInputs = document.querySelectorAll('.hidden-date-input');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (confirm('Are you sure you want to reschedule this booking?')) {
                this.closest('form').submit();
            }
        });
    });
});


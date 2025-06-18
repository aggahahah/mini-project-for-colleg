document.addEventListener('DOMContentLoaded', function() {
    // Dynamic course loading for feedback form
    if (document.getElementById('feedbackForm')) {
        loadCourses();
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let valid = true;
            const inputs = form.querySelectorAll('[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = 'red';
                } else {
                    input.style.borderColor = '#ddd';
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });
});

function loadCourses() {
    fetch('get_courses.php')
        .then(response => response.json())
        .then(courses => {
            const select = document.getElementById('course_id');
            select.innerHTML = '<option value="">-- Select Course --</option>';
            
            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.course_code} - ${course.course_name} (${course.instructor_name})`;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading courses:', error));
}
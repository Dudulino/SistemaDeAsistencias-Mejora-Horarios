document.addEventListener('DOMContentLoaded', function () {
    const notificationsCountContainer = document.getElementById('notificationsCountBirthdayBContainer');

fetch('/cumpleaneros')
    .then(response => response.json())
    .then(data => {
        if (data.count > 0) {
            notificationsCountContainer.removeAttribute('hidden');
            notificationsCountContainer.innerText = data.count;
        } else {
            notificationsCountContainer.setAttribute('hidden', 'true');
        }
    })
    .catch(error => console.error('Error:', error));

});

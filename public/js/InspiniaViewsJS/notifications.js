let notificationsContainer = document.getElementById('notificationsContainer');
        let notificationsCountContainer = document.getElementById('notificationsCountContainer');
        let notificationRoute = document.getElementById('notificationRoute').innerText;


        // const UserToken = <?php echo json_encode(session('api_token')); ?>;
        const UserToken = document.getElementById('userToken').innerText.replace(/"/g, '');

        // console.log(UserToken);
        const notificationCard = (icon, message, url) => {
            let card = `<li>
                            <a href="${url}" class="dropdown-item">
                                <div class="text-wrap">
                                    <i class="${icon}"></i> ${message}
                                </div>
                            </a>
                        </li>`;
            notificationsContainer.innerHTML += card;
        };
        const nothingCard = () => {
            let card = `<li>
                            <div class="dropdown-item">
                                <div class="text-wrap">
                                    <i class="fa fa-question-circle"></i> No hay notificaciones pendientes para hoy.
                                </div>
                            </div>
                        </li>`;
            notificationsContainer.innerHTML += card;
        };

        let data = null;
        fetch(notificationRoute, {
            headers: {
                'Authorization': 'Bearer '+UserToken
            }
        })
            .then(response => response.json())
            .then(responseData => {
                data = responseData;
                // console.log(data);

                data.notifications.map(notification => {
                    notificationCard(notification.icon, notification.message, notification.url);
                });
                if (data.notifications.length === 0) {
                nothingCard()
                }
                notificationsCountContainer.removeAttribute('hidden');
                notificationsCountContainer.innerText = data.notifications.length;
            })
            .catch(error => console.error('Error:', error));


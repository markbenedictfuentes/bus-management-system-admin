const notificationButton = document.getElementById('notificationButton');
const notificationBadge = document.getElementById('notificationBadge');
const notificationPopup = document.getElementById('notificationPopup');
const notificationList = document.getElementById('notificationList');

// Notifications array (fake notifications)
const notifications = [
    "You have a new message!",
    "New comment on your post.",
    "Your order has been shipped.",
    "Reminder: Meeting at 3 PM.",
    "Update available for your app.",
];

// Function to create a random notification
function createNotification() {
    const randomIndex = Math.floor(Math.random() * notifications.length);
    const notificationText = notifications[randomIndex];

    // Create a new list item for the notification
    const newNotification = document.createElement('li');
    newNotification.classList.add('py-2', 'border-b', 'border-gray-200', 'text-gray-800');
    newNotification.textContent = notificationText;

    // Add the new notification to the notification list
    notificationList.appendChild(newNotification);

    // Show the notification badge
    notificationBadge.classList.remove('hidden');

    // Increment the badge number
    const currentBadgeCount = parseInt(notificationBadge.textContent, 10) || 0;
    notificationBadge.textContent = currentBadgeCount + 1;
}

// Function to randomly trigger a notification
function randomNotification() {
    // Random time between 3 to 10 seconds
    const randomTime = Math.random() * (10000 - 3000) + 3000;

    setTimeout(() => {
        createNotification();
        randomNotification(); // Repeat the process
    }, randomTime);
}

// Function to toggle the notification popup
notificationButton.addEventListener('click', () => {
    notificationPopup.classList.toggle('hidden');
    // Hide badge when popup is open
    if (!notificationPopup.classList.contains('hidden')) {
        notificationBadge.classList.add('hidden');
        notificationBadge.textContent = '1';
    }
});

// Start generating random notifications
randomNotification();

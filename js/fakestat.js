const totalVisitors = Math.floor(Math.random() * 500) + 100;
const repeatVisitors = Math.floor(totalVisitors * (Math.random() * 0.5));
const purposes = ["Meeting", "Delivery", "Tour", "Business", "Maintenance", "Consultation"];
const commonPurpose = purposes[Math.floor(Math.random() * purposes.length)];
document.getElementById('totalVisitors').textContent = totalVisitors;
document.getElementById('repeatVisitors').textContent = repeatVisitors;
document.getElementById('commonPurpose').textContent = commonPurpose;
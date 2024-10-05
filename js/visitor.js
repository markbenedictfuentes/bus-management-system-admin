const visitorForm = document.getElementById('visitorForm');
const visitorList = document.getElementById('visitorList');

visitorForm.addEventListener('submit', function (e) {
    e.preventDefault(); 
    const name = document.getElementById('name').value;
    const contact = document.getElementById('contact').value;
    const purpose = document.getElementById('purpose').value;


    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${contact}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${purpose}</td>
    `;


    visitorList.appendChild(newRow);

    visitorForm.reset();
});
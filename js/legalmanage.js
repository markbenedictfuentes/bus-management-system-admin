const legalForm = document.getElementById('legalForm');
const documentList = document.getElementById('documentList');
const legalNotices = document.getElementById('legalNotices');

legalForm.addEventListener('submit', function (e) {
    e.preventDefault(); 
    const documentName = document.getElementById('documentName').value;
    const documentType = document.getElementById('documentType').value;
    const expirationDate = document.getElementById('expirationDate').value;
    const currentDate = new Date().toISOString().split("T")[0]; 


    let status;
    if (expirationDate < currentDate) {
        status = `<span class="text-red-500">Expired</span>`;
        addLegalNotice(`${documentName} (${documentType}) has expired!`);
    } else {
        status = `<span class="text-green-500">Valid</span>`;
    }


    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${documentName}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${documentType}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${expirationDate}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm">${status}</td>
    `;


    documentList.appendChild(newRow);


    legalForm.reset();
});

function addLegalNotice(notice) {
    const listItem = document.createElement('li');
    listItem.textContent = notice;
    legalNotices.appendChild(listItem);
}
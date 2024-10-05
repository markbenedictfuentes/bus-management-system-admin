
       const dropdownButton = document.getElementById('dropdownButton');
       const dropdownMenu = document.getElementById('dropdownMenu');

       dropdownButton.addEventListener('click', function (event) {
           event.stopPropagation();
           dropdownMenu.classList.toggle('hidden');
       });


       document.addEventListener('click', function () {
           if (!dropdownMenu.classList.contains('hidden')) {
               dropdownMenu.classList.add('hidden');
           }
       });
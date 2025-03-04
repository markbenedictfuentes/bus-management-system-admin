<!-- topbar.php -->
<div class="flex flex-col w-full h-screen overflow-y-scroll">
  <nav class="md:sticky block md:w-auto top-4 z-10 bg-white-10/50 backdrop-blur-sm border border-gray-300 rounded-md m-4">
    <div class="flex w-full">
      <div class="-me-2 flex items-center sm:hidden">
        <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
          <!-- Mobile menu button (kung kailangan mo) -->
        </button>
      </div>
      <div class="relative flex justify-between w-full">
        <div class="mx-4 my-4 font-medium text-3xl text-[#004369]"></div>
        <button id="dropdownButton" type="button" class="inline-flex items-center px-3 py-2 font-medium transition ease-in-out duration-150">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="currentColor"
               class="icon icon-tabler icons-tabler-filled icon-tabler-caret-down">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M18 9c.852 0 1.297.986.783 1.623l-.076.084l-6 6a1 1 0 0 1 -1.32.083l-.094-.083l-6-6l-.083-.094l-.054-.077l-.054-.096l-.017-.036l-.027-.067l-.032-.108l-.01-.053l-.01-.06l-.004-.057v-.118l.005-.058l.009-.06l.01-.052l.032-.108l.027-.067l.07-.132l.065-.09l.073-.081l.094-.083l.077-.054l.096-.054l.036-.017l.067-.027l.108-.032l.053-.01l.06-.01l.057-.004l12.059-.002z"/>
          </svg>
        </button>
        <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
          <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="dropdownButton">
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
            <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Logout</a>
            <script src="js/dropdown.js"></script>
          </div>
        </div>
      </div>
    </div>
  </nav>

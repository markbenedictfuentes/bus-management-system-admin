<?php

if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit();
}

$role = $_SESSION['role']; // Halimbawa: admin, staff, employee, visitor
?>

<aside class="bg-[#F3F8FF] w-80 hidden md:block border border-gray-300 m-4 mr-0 rounded-lg overflow-hidden">
  <p class="m-4 mb-12 font-bold text-2xl text-center text-[#00446b]">NextFleet Dynamics</p>
  
  <!-- Lahat ng user ay may access sa Dashboard -->
  <div class="flex flex-col mr-4">
    <a href="dashboard.php" class="flex relative my-1 w-full">
      <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369]"></span>
      <p class="ml-8 flex w-full p-2 rounded-xl font-semibold text-white bg-[#004369]">
        <span class="flex items-center gap-2 text-lg">
          <!-- Icon para sa Dashboard -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon icon-tabler icons-tabler-chart-bar">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M4 20h14" />
          </svg>
          Dashboard
        </span>
      </p>
    </a>
  </div>
  
  <!-- Additional admin-only links -->
  <?php if ($role === 'admin'): ?>
    <div class="flex relative mr-4">
      <a href="registration.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
        <span class="flex items-center gap-2">
          <!-- Icon para sa Registration -->
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon icon-tabler icon-tabler-user-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <circle cx="9" cy="7" r="4" />
            <path d="M3 21v-2a4 4 0 0 1 4 -4h4" />
            <line x1="16" y1="11" x2="21" y2="11" />
            <line x1="19.5" y1="8.5" x2="19.5" y2="13.5" />
          </svg>
          <p>User Account</p>
        </span>
      </a>
    </div>
  <?php endif; ?>
  
  <!-- Visitor Management: para sa admin, staff, at visitor -->
  <?php if ($role === 'admin' || $role === 'staff' || $role === 'visitor'): ?>
    <div class="flex relative mr-4">
      <a href="visitor.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
        <span class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon icon-tabler icon-tabler-user-plus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <circle cx="9" cy="7" r="4" />
            <line x1="16" y1="11" x2="21" y2="11" />
            <line x1="19.5" y1="8.5" x2="19.5" y2="13.5" />
          </svg>
          <p>Visitor Management</p>
        </span>
      </a>
    </div>
  <?php endif; ?>
  
  <!-- Legal Management: para sa admin at staff lamang -->
  <?php if ($role === 'admin' || $role === 'staff'): ?>
  <div class="flex relative mr-4">
    <a href="/private/admin/document.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
      <span class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
          <path d="M9 17h6"/>
          <path d="M9 13h6"/>
        </svg>
        <p>Document Management</p>
      </span>
    </a>
  </div>
<?php endif; ?>


  <?php if ($role === 'admin' || $role === 'staff'): ?>
  <div class="flex relative mr-4">
    <a href="/private/admin/all_documents.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
      <span class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
          <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
        </svg>
        <p>Document List</p>
      </span>
    </a>
  </div>
<?php endif; ?>


  <!-- Document Management: para sa admin, staff, at employee -->
  <?php if ($role === 'admin' || $role === 'staff' || $role === 'employee'): ?>
    <div class="flex relative mr-4">
      <a href="/private/admin/announcement.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
        <span class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon icon-tabler icon-tabler-briefcase">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/>
            <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2"/>
            <path d="M12 12l0 .01"/>
            <path d="M3 13a20 20 0 0 0 18 0"/>
          </svg>
          <p>Annoucement</p>
        </span>
      </a>
    </div>
  <?php endif; ?>

  <!-- Rent Reservation: para sa admin, staff, at employee -->
  <?php if ($role === 'admin' || $role === 'staff' || $role === 'employee'): ?>
    <div class="flex relative mr-4">
      <a href="rent.php" class="ml-8 flex p-1 w-full rounded-xl text-[#004369] my-1">
        <span class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
               class="icon icon-tabler icon-tabler-bus">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
            <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
            <path d="M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8"/>
            <path d="M16 5l1.5 7l4.5 0"/>
            <path d="M2 10l15 0"/>
            <path d="M7 5l0 5"/>
            <path d="M12 5l0 5"/>
          </svg>
          <p>Rent Reservation</p>
        </span>
      </a>
    </div>
  <?php endif; ?>

</aside>

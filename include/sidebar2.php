<?php
if (!isset($_SESSION['role'])) {
    header("Location: /index.php");
    exit();
}

$role = $_SESSION['role']; 
?>

<aside class="bg-[#F3F8FF] w-80 hidden md:block border border-gray-300 m-4 mr-0 rounded-lg overflow-hidden shadow-md">
  <p class="m-4 mb-12 font-bold text-2xl text-center text-[#00446b]">NextFleet Dynamics</p>
  
  <?php if($role === 'employee'): ?>
    <!-- Sidebar para sa Employee (unchanged) -->
    <div class="flex flex-col mr-4">
      <!-- Dashboard -->
      <a href="dashboard.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] transition-colors">
        <p class="ml-8 flex w-full p-2 rounded-xl font-semibold text-white bg-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-chart-bar">
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

    <a href="compliance.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] rounded-r-xl transition-colors">
        <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369] opacity-0 group-hover:opacity-100"></span>
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Icon para sa Compliance Officer -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shield-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 3l8 4v5c0 5-4 9-8 12c-4 -3 -8 -7 -8 -12v-5l8 -4" />
              <path d="M9 12l2 2l4 -4" />
            </svg>
            Compliance Officer
          </span>
        </p>
      </a>

      <a href="safety.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] rounded-r-xl transition-colors">
        <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369] opacity-0 group-hover:opacity-100"></span>
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Icon para sa Safety Manager -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M10.29 3.86l-9 16a1 1 0 0 0 .86 1.5h18a1 1 0 0 0 .86 -1.5l-9 -16a1 1 0 0 0 -1.72 0z"/>
              <path d="M12 9v4"/>
              <path d="M12 17h.01"/>
            </svg>
            Safety Manager
          </span>
        </p>
      </a>
      
      <a href="/private/employee/legal_staff.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] rounded-r-xl transition-colors">
      <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369] opacity-0 group-hover:opacity-100"></span>
      <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
        <span class="flex items-center gap-2 text-lg">
          <!-- Icon para sa Legal Staff (Gavel Icon) -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-gavel" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M2 12l2 2l4 -4l-2 -2z" />
            <path d="M16 8l4 4l-8 8l-4 -4z" />
          </svg>
          Legal Staff
        </span>
      </p>
    </a>

      <a href="/private/employee/accident_report.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] rounded-r-xl transition-colors">
        <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369] opacity-0 group-hover:opacity-100"></span>
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Icon para sa Accident Report (Report Icon) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <rect x="4" y="4" width="16" height="16" rx="2" />
              <line x1="9" y1="8" x2="15" y2="8" />
              <line x1="9" y1="12" x2="15" y2="12" />
              <line x1="9" y1="16" x2="15" y2="16" />
            </svg>
            Accident Report
          </span>
        </p>
      </a>
      
      <a href="Bus_List.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] transition-colors">
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Bus Icon (similar to your Rent Reservation icon) -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-bus">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <circle cx="17" cy="17" r="2" />
              <circle cx="7" cy="17" r="2" />
              <path d="M5 17v-11h14a5 7 0 0 1 5 7v5h-2m-4 0h-8"/>
              <path d="M16 5l1.5 7l4.5 0"/>
              <path d="M2 10l15 0"/>
              <path d="M7 5v5"/>
              <path d="M12 5v5"/>
            </svg>
            Bus List
          </span>
        </p>
      </a>
    
  <?php elseif($role === 'visitor'): ?>

    <a href="dashboard.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] transition-colors">
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Using the briefcase icon as in your original code -->
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
            Annoucement
          </span>
        </p>
      </a>
      
      <!-- Complaint -->
      <a href="complaint.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] transition-colors">
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Complaint Icon changed to Message Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-message-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M7 8h10" />
              <path d="M7 12h7" />
              <path d="M21 12c0 3.866 -3.582 7 -8 7a8.263 8.263 0 0 1 -4.085 -.949L3 20l1.949 -4.915A7.963 7.963 0 0 1 3 12c0 -3.866 3.582 -7 8 -7s8 3.134 8 7z" />
            </svg>
            Complaint
          </span>
        </p>
      </a>
      
      <!-- Bus List -->
      <a href="bus_list.php" class="flex relative my-1 w-full hover:bg-[#E6F0FF] transition-colors">
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-[#004369]">
          <span class="flex items-center gap-2 text-lg">
            <!-- Bus Icon (similar to your Rent Reservation icon) -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-bus">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <circle cx="17" cy="17" r="2" />
              <circle cx="7" cy="17" r="2" />
              <path d="M5 17v-11h14a5 7 0 0 1 5 7v5h-2m-4 0h-8"/>
              <path d="M16 5l1.5 7l4.5 0"/>
              <path d="M2 10l15 0"/>
              <path d="M7 5v5"/>
              <path d="M12 5v5"/>
            </svg>
            Bus List
          </span>
        </p>
      </a>
    
  <?php else: ?>
    <!-- Sidebar para sa Admin at Staff (Original code) -->
    <div class="flex flex-col mr-4">
      <a href="/dashboard.php" class="flex relative my-1 w-full">
        <span class="w-4 rounded-xl absolute -left-2 h-full bg-[#004369]"></span>
        <p class="ml-8 flex w-full p-3 rounded-xl font-semibold text-white bg-[#004369]">
          <span class="flex items-center gap-2 text-lg">
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
      
    <?php if ($role === 'admin'): ?>
      <div class="flex relative mr-4">
        <a href="/registration.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
          <span class="flex items-center gap-2">
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
            <p class="whitespace-nowrap">User Account</p>
          </span>
        </a>
      </div>
    <?php endif; ?>
      
    <?php if ($role === 'admin' || $role === 'staff' || $role === 'visitor'): ?>
      <div class="flex relative mr-4">
        <a href="/private/admin/legal.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
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
            <p class="whitespace-nowrap">Legal Management</p>
          </span>
        </a>
      </div>
    <?php endif; ?>
      
    <?php if ($role === 'admin' || $role === 'staff'): ?>
      <div class="flex relative mr-4">
        <a href="document.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
          <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-text" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
              <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
              <path d="M9 17h6"/>
              <path d="M9 13h6"/>
            </svg>
            <p class="whitespace-nowrap">Document Management</p>
          </span>
        </a>
      </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'staff'): ?>
      <div class="flex relative mr-4">
        <a href="all_documents.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
          <span class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
              <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
            </svg>
            <p class="whitespace-nowrap">Document List</p>
          </span>
        </a>
      </div>
    <?php endif; ?>
      
    <?php if ($role === 'admin' || $role === 'staff' || $role === 'employee'): ?>
      <div class="flex relative mr-4">
        <a href="announcement.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
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
            <p class="whitespace-nowrap">Announcement</p>
          </span>
        </a>
      </div>
    <?php endif; ?>
      
    <?php if ($role === 'admin' || $role === 'staff' || $role === 'employee'): ?>
      <div class="flex relative mr-4">
        <a href="complaints_sol.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
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
            <p class="whitespace-nowrap">Complaint List</p>
          </span>
        </a>
      </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'staff' || $role === 'employee'): ?>
  <div class="flex relative mr-4">
    <a href="/private/admin/classifications.php" class="ml-8 flex p-2 w-full rounded-xl text-[#004369] my-1 hover:bg-[#E6F0FF] transition-colors">
      <span class="flex items-center gap-2">
        <!-- New List Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <p class="whitespace-nowrap">Classification List</p>
      </span>
    </a>
  </div>
<?php endif; ?>
  <?php endif; ?>
</aside>


<aside id="sidebar" class="sidebar" id="v-pills-tab" role="tablist">
     <?php  $page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/")+ 1); ?>
     <ul class="sidebar-nav" id="sidebar-nav">
          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == '.' ? 'active': '' ?>" href=".">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
               </a>
          </li>

          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'books' || $page == 'book_add' || $page == 'book_view' || $page == 'book_edit'  ? 'active': '' ?>"
                    href="books">
                    <i class="bi bi-book"></i><span>Book Collection</span>
               </a>
          </li>

          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'users' || $page == 'user_student' || $page == 'user_student_add' || $page == 'user_student_view' || $page == 'user_student_edit' || $page == 'user_faculty' || $page == 'user_student_approval'  ? 'active': '' ?>"
                    href="users">
                    <i class="bi bi-people"></i><span>Users</span>
               </a>
          </li>

          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'attendance' ? 'active': '' ?>"
                    href="attendance">
                    <i class="bi bi-card-checklist"></i><span>Attendance</span>
               </a>
          </li>
          
          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'circulation' || $page == 'circulation_borrow' || $page == 'circulation_borrowing' || $page == 'circulation_return' || $page == 'circulation_returning' || $page == 'acknowledgement_receipt' ? 'active': '' ?>"
                    href="circulation">
                    <i class="bi bi-journal-album"></i><span>Circulation</span>
               </a>
          </li>

          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'ms_account' ? 'active': '' ?>"
                    href="ms_account">
                    <i class="bi bi-cloud"></i><span>MS 365 Account</span>
               </a>
          </li>

          <li class="nav-item">
               <a class="nav-link collapsed<?=$page == 'report' || $page == 'report_penalty' || $page == 'report_faculty' ? 'active': '' ?>"
                    href="report">
                    <i class="bi bi-file-earmark"></i><span>Report</span>
               </a>
          </li>
     </ul>
</aside>

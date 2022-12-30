<!DOCTYPE html>
<html lang="en">
<x-title />

<body>
  <style>
    /* width */
    ::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      box-shadow: inset 0 0 5px grey;
      border-radius: 5px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: rgb(183, 207, 222);
      border-radius: 5px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #b4c5d2;
    }
  </style>
  <div class="div">
    <div class="div">
      <!-- Navbar -->
      <x-nav-bar-dash />
    </div>
    <div style="display: flex ">
      <x-projects-list />
      <x-nav-bar-tasks />
    </div>
    <!-- Modal -->
    <x-task-modal />
    <x-project-Modal />
    <x-share-proj />
    <x-filter-modal />
    <x-status-create-modal />
    <x-confirmmation-modal />

  </div>
</body>
<script type="text/javascript" src="{{ URL::asset('js/helpers.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/dashboard.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<script type="text/javascript">
  $(document).ready(function () {
    setHeaders();
    reqPreFilter();
    setLocalStorage();
    getUserInfo();
    getProjects();

    $(document).on("click", ".show-more-tasks", showMoreTasksPg);
    $(document).on("click", ".project-item", showProjectTasks);
});   
    
</script>
<script src="{{ asset('js/mdb.min.js') }}">
</script>

</html>